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
    }
    
    public function run()
    {

        $process = new Process('/usr/local/bin/speedtest-cli --simple --timeout=3 --bytes');

        $process->run();

        $this->output = json_encode(explode("\n", $process->getOutput()), JSON_PRETTY_PRINT);

        //File::put(base_path('tests/fixtures/output.json'), $this->output);

        return $this->output;

    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }

    private function saveResults()
    {
        $result = new Result();
        $result->results = serialize($this->output);
        $result->sent = 0;
        $result->save();

        $this->results = $result;
    }

    private function sendResults()
    {
        try
        {
            $this->APIClientInterface->sendUpdate($this->results->toArray());
        }
        catch(\Exception $e)
        {
            Log::info(sprintf("Error saving results %s", $e->getMessage()));
        }
    }


}