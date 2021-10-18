<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	Schema::defaultStringLength(191);

        Validator::extend('rut', function($attribute, $value, $parameters, $validator) {
			
			$fields  = $validator->getData();
			$default = true;
			
			if (array_key_exists('tipo_nacionalidad',$fields))
			{
				if ($fields['tipo_nacionalidad'] == 2)
				{
					//internacional, solo valida número
					return preg_match("/^[1-9][0-9]*$/",$value);
				}
			}
			
			
			$M=0;
			$S=1;
				
			if (!preg_match("/^([0-9]{1,3}.[0-9]{3}.[0-9]{3}-[0-9kK]{1}|[0-9]{1,3}.[0-9]{3}-[0-9kK]{1}|[0-9]{1,3}-[0-9kK]{1})/",$value))
			{
				return false;
			}
				
			$value = str_replace(".","",$value);
			if ( !preg_match("/^[0-9]+-[0-9kK]{1}/",$value)) return false;
				
			$part = explode('-', $value);
				
			$T = $part[0];
				
			for(;$T;$T=floor($T/10))
			{
			   $S=($S+$T%10*(9-$M++%6))%11;	
			}
				
			$res = $S?$S-1:'k';
				
			return $part[1] == $res;
			
				
		});
		
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
