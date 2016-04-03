<?php


namespace App\Console\Commands;

use App\SpeedTestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckForNonSentResultsConsole extends Command
{
    public $name = 'levnet:checkunsent';

    protected $signature = 'levnet:checkunsent';

    protected $description = 'Send the unsent results';

    /**
     * @var SpeedTestService
     */
    protected $service;

    public function __construct(SpeedTestService $service)
    {
        parent::__construct();
        
        $this->service = $service;
    }
    
    public function handle()
    {
        $results_not_sent = \App\Result::where('sent', "LIKE", "0")->get();

        
        if($results_not_sent)
        {
            foreach($results_not_sent as $result)
            {
                Log::info(sprintf("Sending previous unsent results from %s", $result->created_at));
                $this->getService()->setResults($result)->sendResultsAgain();
            }
        }
    }

    public function getService()
    {
        return $this->service;
    }

    public function setService($service)
    {
        $this->service = $service;
    }
}

