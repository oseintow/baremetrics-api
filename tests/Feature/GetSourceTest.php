<?php

namespace Tests\Feature;

use Oseintow\Baremetrics\Baremetrics;
use Tests\TestCase;

class GetSourceTest extends TestCase
{
    protected $baremetrics;

    public function setUp()
    {
        parent::setUp();

        $this->baremetrics = new Baremetrics();
    }
    /** @test */
    public function get_baremetrics_source()
    {
        $response = $this->baremetrics->setApiKey(env("BAREMETRICS_API_KEY"))->get("sources");

        $this->assertArrayHasKey("id", $response["sources"][0]);
        $this->assertArrayHasKey("provider", $response['sources'][0]);
        $this->assertArrayHasKey("provider_id", $response['sources'][0]);
        $this->assertArrayHasKey("per_page", $response["meta"]["pagination"]);

        $this->assertInternalType("string", $this->baremetrics->getHeader("X-RateLimit-Limit"));
    }

}