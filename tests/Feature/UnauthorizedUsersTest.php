<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnauthorizedUsersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_unauthorized_access()
    {
        
        $response  = $this->get('/clients');
        
        if ( $response->assertRedirect('/login') ){
            echo PHP_EOL ;
            echo 'test_unauthorized_access OK';

        }
     
    }
}
