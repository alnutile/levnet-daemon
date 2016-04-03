<?php


namespace App\Console\Commands;

use App\Result;
use App\SpeedTestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReportOnUnSentCommand extends Command
{
    public $name = 'levnet:report:unsent';

    protected $signature = 'levnet:report:unsent';

    protected $description = 'Report of Unset';

    /**
     * @var SpeedTestService
     */
    protected $service;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $results = Result::select("results", "created_at", "tries", "sent")->where('sent', "LIKE", "0")->get();

        if(empty($results))
        {
            $this->info("No Unsent results");
            return false;
        }

        $this->table(["Results", "Created At", "Tries", "Sent"], $results->toArray());

    }
}

