<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use App\User;
use Laravel\Passport\Passport;

class PresaleModuleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_cart_stock()
    {

        
        
        Passport::actingAs(
            factory(User::class)->make([
                'id'            => '1',
                'enterprise_id' => '5'
            ]));
    
        $response = $this->post('api/cart/stock',[
            'cart' => '[]'
        ]);
    
        if ( $response->assertStatus(200) ){
            echo PHP_EOL ;
            echo 'test_create_cart_stock OK';

        }
    }
}
