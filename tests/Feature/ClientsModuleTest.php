<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Client;
use Laravel\Passport\Passport;

class ClientsModuleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_create_client()
    {
        Passport::actingAs(
            factory(User::class)->make([
                'id'            => '1',
                'enterprise_id' => '5'
            ]));
        
        $response = $this->post('api/clients',[
            'name'  => 'CREATED BY TEST',
            'rut'   => random_int(1000000,99999999).'-'.random_int(1,9)
        ]);
    
        if ( $response->assertStatus(201) ){
            echo PHP_EOL ;
            echo 'test_create_client OK';

        }

    }


    public function test_edit_client()
    {
        Passport::actingAs(
            factory(User::class)->make([
                'id'            => '1',
                'enterprise_id' => '5'
            ]));

        $client = Client::orderBy('id','desc')
                        ->limit('1')
                        ->first()
                        ->id;
    
        $response = $this->put('api/clients/'.$client,[
            'name'  => 'TEST',
            'rut'   => random_int(1000000,99999999).'-'.random_int(1,9)
        ]);
        
    
        if ( $response->assertStatus(200) ){
            echo PHP_EOL ;
            echo 'test_edit_client OK';

        }

    }

    public function test_get_client()
    {
        Passport::actingAs(
            factory(User::class)->make([
                'id'            => '1',
                'enterprise_id' => '5'
            ]));                        
            
        $response = $this->get('api/clients',[
            'length'  => '1',
        ]);
            
    
        if ( $response->assertStatus(200) ){
            echo PHP_EOL ;
            echo 'test_get_client OK';

        }
        

    }

    

    public function test_delete_client()
    {
        Passport::actingAs(
            factory(User::class)->make([
                'id'            => '1',
                'enterprise_id' => '5'
            ]));
    
        $client = Client::orderBy('id','desc')
                        ->limit('1')
                        ->first()
                        ->id;
        $response = $this->delete('api/clients/'.$client);
    
        if ( $response->assertStatus(200) ){
            echo PHP_EOL ;
            echo 'test_delete_client OK';

        }

    }
}
