<?php

namespace Tests\Feature;

use Oseintow\Baremetrics\Baremetrics;
use Tests\TestCase;

class CreatePlanTest extends TestCase
{
    protected $baremetrics;

    public function setUp()
    {
        parent::setUp();

        $this->baremetrics = new Baremetrics();
    }

    /** @test */
    public function create_plan()
    {
        $response = $this->baremetrics
            ->setApiKey(env("BAREMETRICS_API_KEY"))
            ->get("sources");

        $sourceId = '';

        foreach($response['sources'] as $source){
            if($source['provider'] == "baremetrics"){
                $sourceId = $source['id'];
            }
        }

        $oid = "Business_Plan_Testing";

        $response = $this->baremetrics
            ->setApiKey(env("BAREMETRICS_API_KEY"))
            ->post("{$sourceId}/plans", [
                "oid"=> $oid,
                'name'=>'Business Plan',
                'currency'=> 'usd',
                'amount'=> '4999',
                'interval'=> 'month',
                'interval_count'=> '1'
            ]);


        if(isset($response['error'])){
            $this->assertEquals("Oid is already taken", $response['error']);
        }else {
            $this->assertArrayHasKey("plan", $response);
            $this->assertEquals($oid, $response["plan"]["oid"]);
        }

        $this->assertInternalType("string", $this->baremetrics->getHeader("X-RateLimit-Limit"));

        $response = $this->baremetrics
            ->setApiKey(env("BAREMETRICS_API_KEY"))
            ->delete("{$sourceId}/plans/{$oid}");

        $this->assertEmpty($response);

    }
}