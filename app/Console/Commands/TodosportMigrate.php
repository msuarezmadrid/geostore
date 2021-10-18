<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class TodosportMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todosport:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza la migración de datos desde todosport antiguo a nuevo.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        // SECCION  USUARIO
        echo "Loading users...".PHP_EOL;
        $users = DB::connection('todosport_dummy')->table('users')->find('9');
        $id = $users->id;
        DB::table('users')->insert([
            'id'    => $id,
            'name' => $users->name,
            'email' => $users->email,
            'password' => bcrypt('todosport@2019'),
            'enterprise_id' => 1,
            'role_id' => $users->role_id,
            'admin' => $users->admin
        ]);
        echo "users done!".PHP_EOL;


        //SECCION BRANDS
        echo "Loading brands...".PHP_EOL;
        $brands = DB::connection('todosport_dummy')->table('brands')->get();
        foreach ($brands as $val){
            if($val->id >= '37'){
                DB::table('brands')->insert([
                    'id' => $val->id,
                    'name' => $val->name,
                    'enterprise_id' => 1,
                    'created_by' => $id,
                    'updated_by' => $id,
                    'created_at' => $val->created_at,
                    'updated_at' => $val->updated_at,
                ]);
            }
        }
        echo "brands done!".PHP_EOL;

        //SECCION CATEGORIES
        echo "Loading categories...".PHP_EOL;
        $categories = DB::connection('todosport_dummy')->table('categories')->get();
        foreach ($categories as $val){
            DB::table('categories')->insert([
                'id' => $val->id,
                'name' => $val->name,
                'category_id' => $val->category_id,
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "categories done!".PHP_EOL;

        //SECCION ITEMS
        echo "Loading items...".PHP_EOL;
        $items = DB::connection('todosport_dummy')->table('items')->get();
        foreach ($items as $val){
            DB::table('items')->insert([
                'id' => $val->id,
                'custom_sku' => $val->custom_sku,
                'manufacturer_sku' => $val->manufacturer_sku,
                'ean' => $val->ean,
                'upc' => $val->upc,
                'name' => $val->name,
                'category_id' => $val->category_id,
                'unit_of_measure_id' => $val->unit_of_measure_id,
                'reorder_point' => $val->reorder_point,
                'order_up_to' => $val->order_up_to,
                'brand_id' => $val->brand_id,
                'description' => $val->description,
                'is_bom' => $val->is_bom,
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
                'deleted_at' => $val->deleted_at,
                'item_type_id' => $val->item_type_id,
                'item_id' => $val->item_id,

           ]);
        }
        echo "items done!".PHP_EOL;
        

        //SECCION ADJUSTMENTS
        echo "Loading adjustments...".PHP_EOL;
        $adjustments = DB::connection('todosport_dummy')->table('adjustments')->get();
        foreach ($adjustments as $val){
            DB::table('adjustments')->insert([
                'id' => $val->id,
                'code' => $val->code,
                'date' => $val->date,
                'reason' => $val->reason,
                'location_id' => 1,
                'movement_status_id' => $val->movement_status_id,
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "adjustments done!".PHP_EOL;


        //SECCION ADJUSTMENT_ITEMS
        echo "Loading adjustment_items...".PHP_EOL;
        $adjustment_items = DB::connection('todosport_dummy')->table('adjustment_items')->get();
        foreach ($adjustment_items as $val){
            DB::table('adjustment_items')->insert([
                'id' => $val->id,
                'item_id' => $val->item_id,
                'adjustment_id' => $val->adjustment_id,
                'quantity' => $val->quantity,
                'unitary_price' => round($val->unitary_price*1.19),
                'unit_of_measure_id' => $val->unit_of_measure_id,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "adjustment_items done!".PHP_EOL;


        //SECCION PRICE_TYPES
        echo "Loading price_types...".PHP_EOL;
        $price_types = DB::connection('todosport_dummy')->table('price_types')->get();
        foreach ($price_types as $val){
            if($val->enterprise_id == '4'){
                DB::table('price_types')->insert([
                    'id' => $val->id,
                    'name' => $val->name,
                    'description' => $val->description,
                    'enterprise_id' => 1,
                    'created_by' => $id,
                    'updated_by' => $id,
                    'created_at' => $val->created_at,
                    'updated_at' => $val->updated_at,
                ]);
            }
        }
        echo "price_types done!".PHP_EOL;


        //SECCION STOCK_ITEMS
        echo "Loading stock_items...".PHP_EOL;
        $stock_items = DB::connection('todosport_dummy')->table('stock_items')->get();
        foreach ($stock_items as $val){
            DB::table('stock_items')->insert([
                'id' => $val->id,
                'item_id' => $val->item_id,
                'adjustment_item_id' => $val->adjustment_item_id,
                'price' => round($val->price*1.19),
                'location_id' => 1,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
                'deleted_at' => $val->deleted_at,
                'order_cart_user_id' => $val->order_cart_user_id,
            ]);
        }
        echo "stock_items done!".PHP_EOL;


        //SECCION FILES
        echo "Loading files...".PHP_EOL;
        $files = DB::connection('todosport_dummy')->table('files')->get();
        foreach ($files as $val){
            DB::table('files')->insert([
                'id' => $val->id,
                'name' => $val->name,
                'description' => $val->description,
                'object_id' => $val->object_id,
                'object_type' => $val->object_type,
                'route' => $val->route,
                'filename' => $val->filename,
                'type' => $val->type,
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,

            ]);
        }
        echo "files done!".PHP_EOL;


        // //SECCION EMPLOYEE_TYPES
        // echo "Loading employee_types...".PHP_EOL;
        // $employee_types = DB::connection('todosport_dummy')->table('employee_types')->get();
        // foreach ($employee_types as $val){
        //     DB::table('employee_types')->insert([
        //         'id' => $val->id,
        //         'name' => $val->name,
        //         'description' => $val->description,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "employee_types done!".PHP_EOL;


        //SECCION EMPLOYEES
        echo "Loading employees...".PHP_EOL;
        $employees = DB::connection('todosport_dummy')->table('employees')->get();
        foreach ($employees as $val){
            DB::table('sellers')->insert([
                'id' => $val->id,
                'code' => $val->id,
                'name' => $val->name,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "employees done!".PHP_EOL;


        //SECCION DISCOUNTS
        echo "Loading discounts...".PHP_EOL;
        $discounts = DB::connection('todosport_dummy')->table('discounts')->get();
        foreach ($discounts as $val){
            if($val->id >= 3){
                DB::table('discounts')->insert([
                    'id' => $val->id,
                    'name' => $val->name,
                    'percent' => $val->percent,
                    'created_at' => $val->created_at,
                    'updated_at' => $val->updated_at,
                ]);
            }
        }
        echo "discounts done!".PHP_EOL;


        //SECCION SUPPLIERS
        echo "Loading suppliers...".PHP_EOL;
        $suppliers = DB::connection('todosport_dummy')->table('suppliers')->get();
        foreach ($suppliers as $val){
            DB::table('suppliers')->insert([
                'id' => $val->id,
                'razon_social' => $val->name,
                'rut'       => '66666666',
                'rut_dv'    => '6',
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "suppliers done!".PHP_EOL;


        // //SECCION CONTACTS
        // echo "Loading contacts...".PHP_EOL;
        // $contacts = DB::connection('todosport_dummy')->table('contacts')->get();
        // foreach ($contacts as $val){
        //     DB::table('contacts')->insert([
        //         'id' => $val->id,
        //         'name' => $val->name,
        //         'lastname' => $val->lastname,
        //         'phone' => $val->phone,
        //         'email' => $val->email,
        //         'type' => $val->type,
        //         'enterprise_id' => 1,
        //         'created_by' => $id,
        //         'updated_by' => $id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "contacts done!".PHP_EOL;


        // //SECCION SUPPLIER_CONTACTS
        // echo "Loading supplier_contacts...".PHP_EOL;
        // $supplier_contacts = DB::connection('todosport_dummy')->table('supplier_contacts')->get();
        // foreach ($supplier_contacts as $val){
        //     DB::table('supplier_contacts')->insert([
        //         'id' => $val->id,
        //         'supplier_id' => $val->supplier_id,
        //         'contact_id' => $val->contact_id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "supplier_contacts done!".PHP_EOL;


        // //SECCION SII_DOCUMENT_TYPES
        // echo "Loading sii_document_types...".PHP_EOL;
        // $sii_document_types = DB::connection('todosport_dummy')->table('sii_document_types')->get();
        // foreach ($sii_document_types as $val){
        //     DB::table('sii_document_types')->insert([
        //         'id' => $val->id,
        //         'document_id' => $val->document_id,
        //         'description' => $val->description,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "sii_document_types done!".PHP_EOL;


        // //SECCION SII_TRANSFER_TYPES
        // echo "Loading sii_transfer_types...".PHP_EOL;
        // $sii_transfer_types = DB::connection('todosport_dummy')->table('sii_transfer_types')->get();
        // foreach ($sii_transfer_types as $val){
        //     DB::table('sii_transfer_types')->insert([
        //         'id' => $val->id,
        //         'transfer_id' => $val->transfer_id,
        //         'name' => $val->name,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "sii_transfer_types done!".PHP_EOL;


        // //SECCION CART_ORDERS
        // echo "Loading cart_orders...".PHP_EOL;
        // $cart_orders = DB::connection('todosport_dummy')->table('cart_orders')->get();
        // foreach ($cart_orders as $val){
        //     DB::table('cart_orders')->insert([
        //         'id' => $val->id,
        //         'created_by' => $id,
        //         'updated_by' => $id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "cart_orders done!".PHP_EOL;


        //SECCION ITEM_PRICES
        echo "Loading item_prices...".PHP_EOL;
        $item_prices = DB::connection('todosport_dummy')->table('item_prices')->get();
        foreach ($item_prices as $val){
            $price_type_id = $val->price_type_id;
            if ($val->price_type_id == 6) {
                $price_type_id = 4;
            }
            if ( $val->price_type_id == 7){
                $price_type_id = 5;
            }
            if ( $val->price_type_id == 8){
                $price_type_id = 3;
            }
            DB::table('item_prices')->insert([
                'id' => $val->id,
                'item_id' => $val->item_id,
                'price' => round($val->price*1.19),
                'price_type_id' => $price_type_id,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
                'item_active' => $val->item_active,
            ]);
        }
        echo "item_prices done!".PHP_EOL;


         //SECCION PURCHASE_ORDERS
         echo "Loading purchase_orders...".PHP_EOL;
         $purchase_orders = DB::connection('todosport_dummy')->table('purchase_orders')->get();
         foreach ($purchase_orders as $val){
             DB::table('purchase_orders')->insert([
                'id' => $val->id,
                'code' => $val->code,
                'date' => $val->date,
                'supplier_id' => $val->supplier_id,
                'location_id' => 1,
                'sii_document_type_id' => $val->sii_document_type_id,
                'sii_document_id' => $val->sii_document_id,
                'sii_transfer_type_id' => $val->sii_transfer_type_id,
                'movement_status_id' => $val->movement_status_id,
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,                
             ]);
         }
         echo "purchase_orders done!".PHP_EOL;


        //SECCION PURCHASE_ORDER_ITEMS
        echo "Loading purchase_order_items...".PHP_EOL;
        $purchase_order_items = DB::connection('todosport_dummy')->table('purchase_order_items')->get();
        foreach ($purchase_order_items as $val){
            DB::table('purchase_order_items')->insert([
                'id' => $val->id,
                'item_id' => $val->item_id,
                'purchase_order_id' => $val->purchase_order_id,
                'quantity' => $val->quantity,
                'unit_of_measure_id' => $val->unit_of_measure_id,
                'price_type_id' => $val->price_type_id,
                'price' => round($val->price*1.19),
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "purchase_order_items done!".PHP_EOL;


        // //SECCION CLIENTS
        // echo "Loading clients...".PHP_EOL;
        // $clients = DB::connection('todosport_dummy')->table('clients')->get();
        // foreach ($clients as $val){
        //     DB::table('clients')->insert([
        //         'id' => $val->id,
        //         'name' => $val->name,
        //         'enterprise_id' => 1,
        //         'created_by' => $id,
        //         'updated_by' => $id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "clients done!".PHP_EOL;


        // //SECCION CLIENT_CONTACTS
        // echo "Loading client_contacts...".PHP_EOL;
        // $client_contacts = DB::connection('todosport_dummy')->table('client_contacts')->get();
        // foreach ($client_contacts as $val){
        //     DB::table('client_contacts')->insert([
        //         'id' => $val->id,
        //         'client_id' => 1,
        //         'contact_id' => $val->contact_id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "client_contacts done!".PHP_EOL;


        //SECCION ATTRIBUTES
        echo "Loading attributes...".PHP_EOL;
        $attributes = DB::connection('todosport_dummy')->table('attributes')->get();
        foreach ($attributes as $val){
            if($val->id >= '13'){
                DB::table('attributes')->insert([
                    'id' => $val->id,
                    'name' => $val->name,
                    'type' => $val->type,
                    'enterprise_id' => 1,
                    'created_by' => $id,
                    'updated_by' => $id,
                    'created_at' => $val->created_at,
                    'updated_at' => $val->updated_at,

                ]);
            }
        }
        echo "attributes done!".PHP_EOL;


        // //SECCION SALE_ORDERS
        // echo "Loading sale_orders...".PHP_EOL;
        // $sale_orders = DB::connection('todosport_dummy')->table('sale_orders')->get();
        // foreach ($sale_orders as $val){
        //     DB::table('sale_orders')->insert([
        //         'id' => $val->id,
        //         'code' => $val->code,
        //         'seller_id' => $val->seller_id,
        //         'type' => $val->type,
        //         'doc_id' => $val->doc_id,
        //         'date' => $val->date,
        //         'client_id' => 1,
        //         'afe_client_id' => $val->afe_client_id,
        //         'location_id' => 1,
        //         'movement_status_id' => $val->movement_status_id,
        //         'enterprise_id' => 1,
        //         'created_by' => $id,
        //         'updated_by' => $id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "sale_orders done!".PHP_EOL;


        //  //SECCION SALE_ORDER_DETAILS
        //  echo "Loading sale_order_details...".PHP_EOL;
        //  $sale_order_details = DB::connection('todosport_dummy')->table('sale_order_details')->get();
        //  foreach ($sale_order_details as $val){
        //      DB::table('sale_order_details')->insert([
        //         'id' => $val->id,
        //         'total_discount' => $val->total_discount,
        //         'total_net' => $val->total_net,
        //         'total_tax' => $val->total_tax,
        //         'total' => $val->total,
        //         'sale_order_id' => $val->sale_order_id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,                
        //      ]);
        //  }
        //  echo "sale_order_details done!".PHP_EOL;


        //  //SECCION SALE_ORDER_ITEMS
        // echo "Loading sale_order_items...".PHP_EOL;
        // $sale_order_items = DB::connection('todosport_dummy')->table('sale_order_items')->get();
        // foreach ($sale_order_items as $val){
        //     DB::table('sale_order_items')->insert([
        //         'id' => $val->id,
        //         'item_id' => $val->item_id,
        //         'sale_order_id' => $val->sale_order_id,
        //         'quantity' => $val->quantity,
        //         'unit_of_measure_id' => $val->unit_of_measure_id,
        //         'price_type_id' => $val->price_type_id,
        //         'price' => round($val->price*1.19),
        //         'created_by' => $id,
        //         'updated_by' => $id,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,

        //     ]);
        // }
        // echo "sale_order_items done!".PHP_EOL;


        // //SECCION SALE_BOX_DETAILS
        // echo "Loading sale_box_details...".PHP_EOL;
        // $sale_box_details = DB::connection('todosport_dummy')->table('sale_box_details')->get();
        // foreach ($sale_box_details as $val){
        //     DB::table('sale_box_details')->insert([
        //         'id' => $val->id,
        //         'seller' => $val->seller,
        //         'seller_id' => $val->seller_id,
        //         'sale_box_id' => $val->sale_box_id,
        //         'transact_id' => $val->transact_id,
        //         'type' => $val->type,
        //         'amount' => $val->amount,
        //         'doc_id' => $val->doc_id,
        //         'observations' => $val->observations,
        //         'created_at' => $val->created_at,
        //         'updated_at' => $val->updated_at,
        //     ]);
        // }
        // echo "sale_box_details done!".PHP_EOL;
        

        //SECCION MOVEMENT_HISTORICAL
        echo "Loading movement_historical...".PHP_EOL;
        $movement_historical = DB::connection('todosport_dummy')->table('movement_historical')->get();
        foreach ($movement_historical as $val){
            DB::table('movement_historical')->insert([
                'id' => $val->id,
                'order_id' => $val->order_id,
                'location_id' => 1,
                'movement_status_id' => $val->movement_status_id,
                'movement_type' => $val->movement_type,
                'enterprise_id' => 1,
                'created_by' => $id,
                'updated_by' => $id,
                'created_at' => $val->created_at,
                'updated_at' => $val->updated_at,
            ]);
        }
        echo "movement_historical done!".PHP_EOL;


        
         
    }
}
