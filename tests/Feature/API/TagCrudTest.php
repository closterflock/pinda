<?php


namespace Tests\Feature\API;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagCrudTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testGetTagsSuccess()
    {
        $this->stub();
    }

    public function testCreateTagValidationFailure()
    {
        $this->stub();
    }

    public function testCreateTagSuccess()
    {
        $this->stub();
    }

}