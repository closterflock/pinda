<?php


namespace Tests\Feature\API;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SyncTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testSyncInvalidTimestamp()
    {
        $this->stub();
    }
    
    public function testSyncNoTimestamp()
    {
        $this->stub();
    }

    public function testSyncWithTimestamp()
    {
        $this->stub();
    }
}