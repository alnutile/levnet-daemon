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

        $results = json_decode(File::get(base_path('tests/fixtures/output.json')), true);

        $service->setOutput($results);

        $service->shouldReceive('run')->andReturn($results);

        $service->runAndSaveResults();

        $results_after = \App\Result::all()->count();

        $this->assertGreaterThan($results_before, $results_after);

        $result = \App\Result::latest()->first();

        $result = $result->toArray();
        
        $this->assertEquals("a:4:{i:0;s:14:\"Ping: 9.761 ms\";i:1;s:23:\"Download: 22.65 Mbyte/s\";i:2;s:21:\"Upload: 17.40 Mbyte/s\";i:3;s:0:\"\";}", $result['results']);
        
        $this->assertEquals("0", $result['sent']);
        
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
