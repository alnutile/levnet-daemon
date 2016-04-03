<?php


namespace App;


use App\Interfaces\APIClientInterface;
use App\Providers\APIClientProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class SpeedTestService
{

    public $path = '/usr/local/bin/speedtest-cli';

    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    public $api_results = null;

    public $output = null;

    /**
     * @var \App\Result
     */
    public $results = null;

    /**
     * @var SpeedTestResultsRepository
     */
    private $repository;

    /**
     * @var \App\Client
     */
    private $APIClientInterface;

    public function __construct(SpeedTestResultsRepository $repository, APIClientInterface $APIClientInterface)
    {
        $this->repository = $repository;
        $this->APIClientInterface = $APIClientInterface;
    }


    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
    
    public function runAndSaveResults()
    {
        $this->run();
        
        $this->saveResults();

        $this->sendResults();

        $this->markSuccessSendingResults();
    }

    public function sendResultsAgain()
    {
        $this->sendResults();

        $this->markSuccessSendingResults();
    }
    
    public function run()
    {

        $process = new Process('/usr/local/bin/speedtest-cli --simple --timeout=3 --bytes');

        $process->run();

        $this->output = json_encode(explode("\n", $process->getOutput()), JSON_PRETTY_PRINT);

        return $this->output;

    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput(Result $output)
    {
        $this->output = $output;
        
        return $this;
    }

    private function saveResults()
    {
        $result = new Result();
        $result->results = serialize($this->output);
        $result->sent = 0;
        $result->save();

        $this->results = $result;
        
        return $this;
    }

    private function sendResults()
    {
        try
        {
            $this->setApiResults($this->APIClientInterface->sendUpdate($this->sendResultsToApiTransformer()));
        }
        catch(\Exception $e)
        {
            /**
             * Notify via Slack 
             */
            Log::info(sprintf("Error saving results %s", $e->getMessage()));
            $this->setResultsAsUnsent();
        }
    }

    protected function setResultsAsUnsent()
    {
        $this->results->sent = 0;

        $this->results->tries = $this->results->tries + 1;

        $this->results->save();
    }

    public function getApiResults()
    {
        return $this->api_results;
    }

    public function setApiResults($api_results)
    {
        $this->api_results = $api_results;
    }

    /**
     * If sent great else it stays in the
     * table as not sent for later try
     * via the scheduler
     */
    private function markSuccessSendingResults()
    {
        if($this->api_results && $this->api_results->getStatusCode() == 200)
        {
            $this->results->sent = 1;
            $this->results->save();
        } else {
            //Just save as we count the tries
            $this->results->save();
        }

    }

    private function sendResultsToApiTransformer()
    {
        
        $results = $this->results->toArray();

        $results['machine'] = env('MACHINE_ID', 'MACHINE_ID_NOT_SET');

        $results['tries']   = (isset($results['tries'])) ? $results['tries'] + 1 : 1;

        return $results;
        
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setResults($results)
    {
        $this->results = $results;
        return $this;
    }


}