<?php


namespace App\Console\Commands;

use App\SpeedTestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendCommand extends Command
{
    public $name = 'levnet:send';

    protected $signature = 'levnet:send';

    protected $description = 'Take Send the speed test';

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
        try
        {
            $this->service->runAndSaveResults();
            Log::info("Stats sent to api");
        }
        catch (\Exception $e)
        {
            Log::info(sprintf("error sending info %s", $e->getMessage()));
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

