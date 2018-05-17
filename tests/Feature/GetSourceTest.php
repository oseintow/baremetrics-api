<?php

namespace Tests\Feature;

use Oseintow\Baremetrics\Baremetrics;
use Tests\TestCase;

class GetSourceTest extends TestCase
{
    protected $bareMetrics;

    public function setUp()
    {
        parent::setUp();

        $this->bareMetrics = new Baremetrics();
    }
    /** @test */
    public function get_baremetrics_source()
    {
        $response = $this->bareMetrics->setApiKey(env("BAREMETRICS_API_KEY"))->get("sources");

        $this->assertArrayHasKey("id", $response["sources"][0]);
        $this->assertArrayHasKey("provider", $response['sources'][0]);
        $this->assertArrayHasKey("provider_id", $response['sources'][0]);
        $this->assertArrayHasKey("per_page", $response["meta"]["pagination"]);

        $this->assertInternalType("string", $this->bareMetrics->getHeader("X-RateLimit-Limit"));
    }

}