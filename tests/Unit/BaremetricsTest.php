<?php
namespace Tests;

use Oseintow\Baremetrics\Baremetrics;
use Tests\TestCase;

class BaremetricsTest extends TestCase
{
    protected $baremetrics;

    public function setUp()
    {
        parent::setUp();

        $this->baremetrics = new Baremetrics();
    }
    /** @test */
    public function is_live_mode()
    {
        $this->assertEquals("https://api-sandbox.baremetrics.com", $this->baremetrics->getApiUrl());

        $this->baremetrics->isLiveMode(true);
        $this->assertEquals("https://api.baremetrics.com", $this->baremetrics->getApiUrl());
    }
}