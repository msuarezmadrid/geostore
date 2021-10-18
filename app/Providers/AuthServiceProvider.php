<?php

namespace App\Providers;

use Log;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Carbon\Carbon;
use App\Permission;
use App\Item;
use App\File;
use App\User;
use App\Role;
use App\Location;
use App\PurchaseOrder;
use App\SaleOrder;
use App\Transfer;
use App\Adjustment;
use App\WorkOrder;
use App\Client;
use Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $permissions = [];
        if (Schema::hasTable('permissions')) {
            $permissions = Permission::all();
        }
        foreach ($permissions as $permission) {
            Gate::define($permission->resource."_".$permission->action, function ($user) use ($permission) {
            return $user->hasPermission($permission->resource."_".$permission->action);
        });
        }

        Gate::define('items_enterprise', function ($user, $item) {
            
            $i = Item::find($item);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);

            return $res;
        });
        Gate::define('clients_enterprise', function ($user, $client) {
            
            $i = Client::find($client);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
 
            return $res;
        });
        Gate::define('files_enterprise', function ($user, $file) {
            
            $i = File::find($file);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });
        Gate::define('users_enterprise', function ($user, $userr) {
            $i = User::find($userr);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });
        Gate::define('roles_enterprise', function ($user, $role) {
            $i = Role::find($role);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });

        Gate::define('locations_enterprise', function ($user, $location) {
            $i = Location::find($location);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });

        Gate::define('purchases_enterprise', function ($user, $purchaseOrder) {
            $i = PurchaseOrder::find($purchaseOrder);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });
        Gate::define('sales_enterprise', function ($user, $sales) {
            $i = SaleOrder::find($sales);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });
        Gate::define('transfers_enterprise', function ($user, $transfer) {
            $i = Transfer::find($transfer);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });
        Gate::define('adjustments_enterprise', function ($user, $adjustments) {
            $i = Adjustment::find($adjustments);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });

        Gate::define('work_orders_enterprise', function ($user, $work_orders) {
            $i = WorkOrder::find($work_orders);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });

        Gate::define('workorders_enterprise', function ($user, $workorders) {
            $i = WorkOrder::find($workorders);
            if($i == null){
                return false;
            }
            $res = ($user->enterprise->id == $i->enterprise_id);
            return $res;
        });

        Gate::define('system', function ($user) {
            if ($user->type() == 'admin') return true;
            else return false;
        });

        Gate::define('presale', function ($user) {
            switch($user->type()) {
                case 'admin':
                    return true;
                break;
                case 'gseller':
                    return true;
                break;
                default:
                    return false;
                break;
            }
            return false;
        });

        Gate::define('cashier', function ($user) {
            switch($user->type()) {
                case 'admin':
                    return true;
                break;
                case 'cashier':
                    return true;
                break;
                default:
                    return false;
                break;
            }
            return false;
        });



/*
        Gate::define('roles.view', function ($user) {
            return $user->hasPermission('roles.view');
        });
        Gate::define('roles.create', function ($user) {
            return $user->hasPermission('roles.create');
        });
        Gate::define('roles.edit', function ($user) {
            return $user->hasPermission('roles.edit');
        });
        Gate::define('roles.delete', function ($user) {
            return $user->hasPermission('roles.delete');
        });

        Gate::define('items.view', function ($user) {
            return $user->hasPermission('items.view');
        });
        Gate::define('items.create', function ($user) {
            return $user->hasPermission('items.create');
        });
        Gate::define('items.edit', function ($user) {
            return $user->hasPermission('items.edit');
        });
        Gate::define('items.delete', function ($user) {
            return $user->hasPermission('items.delete');
        });
*/

        Passport::routes(function ($router) {
            $router->forAccessTokens();
            //$router->forPersonalAccessTokens();
            //$router->forTransientTokens();
        });

        //Gate::resource('items', 'App\Policies\ItemPolicy');

        Passport::tokensExpireIn(Carbon::now()->addYear(1));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
