<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    /**
     * Test User Registration
     */
    public function test_route_to_registration_form()
    {
        $response = $this->get(route('register'));

        $response->assertSuccessful();
    }
}
