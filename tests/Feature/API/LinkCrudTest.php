<?php


namespace Tests\Feature\API;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkCrudTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testGetLinks()
    {
        $this->stub();
    }

    public function testSearchLinksNoSearchTerm()
    {
        $this->stub();
    }

    public function testSearchLinks()
    {
        $this->stub();
    }

    public function testNewLinkValidationFailure()
    {
        $this->stub();
    }

    public function testNewLinkSuccess()
    {
        $this->stub();
    }

    public function testGetLinkNotFound()
    {
        $this->stub();
    }

    public function testGetLinkNotOwnedByUser()
    {
        $this->stub();
    }

    public function testGetLinkSuccess()
    {
        $this->stub();
    }

    public function testDeleteLinkNotFound()
    {
        $this->stub();
    }

    public function testDeleteLinkNotOwnedByUser()
    {
        $this->stub();
    }

    public function testDeleteLinkSuccess()
    {
        $this->stub();
    }

    public function testUpdateLinkNotOwnedByUser()
    {
        $this->stub();
    }

    public function testUpdateLinkSuccess()
    {
        $this->stub();
    }
}