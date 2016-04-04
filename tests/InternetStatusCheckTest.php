<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Mockery as m;

class InternetStatusCheckTest extends TestCase
{
     
    use DatabaseTransactions;
    
    /**
     * @test
     */
    public function run_for_real()
    {
        $this->markTestSkipped("Just for trying out real results");
        
        $service = App::make(\App\SpeedTestService::class);

        $run = $service->run();

        dd($run);

    }

    /**
     * @test
     */
    public function can_get_and_save_results_from_test()
    {
        $results_before = \App\Result::all()->count();

        $repo = App::make(\App\SpeedTestResultsRepository::class);

        $client = App::make(\App\Interfaces\APIClientInterface::class);

        $service = m::mock(\App\SpeedTestService::class, [$repo, $client])->makePartial();

        /**
         * Results of a speedtest using python speedtest.net
         */
        $results = json_decode(File::get(base_path('tests/fixtures/output.json')), true);

        $service->setOutput($results);

        $service->shouldReceive('run')->andReturn($results);

        $service->runAndSaveResults();

        $results_after = \App\Result::all()->count();

        $this->assertGreaterThan($results_before, $results_after);

        $result = \App\Result::latest()->first();

        $result = $result->toArray();

        $this->assertNotNull($result['results']);

        $this->assertEquals("1", $result['sent']);
        
    }
    
    

    /**
     * @test
     */
    public function will_fail_and_save_results()
    {
        $results_before = \App\Result::all()->count();

        $repo = App::make(\App\SpeedTestResultsRepository::class);

        $client = App::make(\App\Interfaces\APIClientInterface::class);

        $service = m::mock(\App\SpeedTestService::class, [$repo, $client])->makePartial();

        $results = json_decode(File::get(base_path('tests/fixtures/output.json')), true);

        $results['fail'] = true;

        $service->setOutput($results);

        $service->shouldReceive('run')->andReturn($results);

        $service->runAndSaveResults();

        $results_after = \App\Result::all()->count();

        $this->assertGreaterThan($results_before, $results_after);

        $result = \App\Result::latest()->first();

        $result = $result->toArray();

        $this->assertEquals("0", $result['sent']);
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
