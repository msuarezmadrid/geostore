<?php

namespace App\Console\Commands;

use App\User;
use App\Client;
use App\Supplier;
use App\Category;
use App\Item;
use App\Brand;
use App\ItemPrice;
use Illuminate\Console\Command;
use File;
use Log;

class LoadLardaniFromCsv extends Command
{
    protected $signature = 'csv:lardani';
    protected $description = 'Adds data from lardani CSV files to DB';

    public function handle()
    {
        $this->info("Loading Users...");
        $users = fopen(storage_path('csv/Usuarios2.csv'), "r");
        $first = true;
        while (($row = fgetcsv($users)) !== FALSE) { 
            if (!$first) {
                $u = new User();
                $u->id = $this->getDataOrNull($row, 0);
                $u->role_id = $this->getDataOrNull($row, 1);
                $u->name = $this->getDataOrNull($row, 4);
                $u->last_name = $this->getDataOrNull($row, 3);
                $u->email = $this->getDataOrNull($row, 3).$this->getDataOrNull($row, 0)."@lardani.cl";
                $u->password = bcrypt('secret');
                $u->enterprise_id = 1;
                $u->created_by = 1;
                $u->save();
            }
            
            $first = false;
        }


        $this->info("Loading Categories...");
        $categories = [
            [0, "PERNOS"], [1, "AGUAS"], [2, "CONSTRUCCIÓN"], [3, "ELECTRICIDAD"], 
            [4, "HERRAMIENTAS"], [5, "LIQUIDOS"],  [6, "MADERAS"],  [7, "POLVOS"], 
            [8, "OTROS"], [9, "PINTURAS"], [10, "PEQUEÑOS"], [11, "ASEO"], [12, "PVC"],  
            [13, "METALES"], [14, "TERCIADOS Y OTROS"], [15, "BRONCE"], [16, "CANDADOS"], 
            [20, "PESCA"], [50, "HOJALATERÍA"], [60, "BRONCE"], ["", "SIN CATEGORÍA"]
        ];
        foreach ($categories as $cat) {
            $c = new Category();
            $c->original_id = $cat[0];
            $c->name = $cat[1];
            $c->enterprise_id = 1;
            $c->created_by = 1;
            $c->save();
        }

        $this->info("Loading Products...");

        $products = fopen(storage_path('csv/Productos2.csv'), "r");
        $first = true;
        while (($row = fgetcsv($products)) !== FALSE) {
            if (!$first) {
                $category = Category::where('original_id', $this->getDataOrNull($row, 13))->first();
                $brand = Brand::where('name', $this->getDataOrNull($row, 2))->first();

                //$this->info(json_encode($row));
                $i = new Item();
                $i->id = $row[0];
                $i->category_id = $category->id;
                $i->brand_id = $brand !== null ? $brand->id : null;

                $i->name = trim($this->getDataOrNull($row, 6));
                $i->manufacturer_sku = $this->getDataOrNull($row, 7);
                $i->unit_of_measure_id = 1; //Unidad
                $i->item_type_id = 1; //Simple
                $i->enterprise_id = 1; //Default
                $i->created_by = 1; //Default
                $i->save();


  
                //Precio Compra
                $price = $this->getDataOrNull($row, 4);
                $this->info($price);$this->info(".");
                if ( $price!== null && $price != 0 ){
                    
                    $ip = new ItemPrice();
                    $ip->item_id = $i->id;
                    $ip->price = str_replace(",", ".", $price);
                    $ip->price_type_id = 1;
                    $ip->item_active = $this->getDataOrNull($row, 5) === null ? 1 : 0;
                    $ip->created_by = 1;
                    $ip->save();
                }

                //Precio venta

                $price = $this->getDataOrNull($row, 5);
                $this->info($price);$this->info(".");
                if ( $price !== null && $price != 0) {
                    
                    $ip = new ItemPrice();
                    $ip->item_id = $i->id;
                    $ip->price = str_replace(",", ".", $price);


                    $ip->price_type_id = 2;
                    $ip->item_active = 1;
                    $ip->created_by = 1;
                    $ip->save();
                }
            }
            $first = false;
        }
        $this->info("Loading Clients...");
        $clients = fopen(storage_path('csv/Clientes2.csv'), "r");
        $first = true;
        while (($row = fgetcsv($clients)) !== FALSE) {
            if (!$first) {
                $c = new Client();
                $c->id = $row[0];
                $c->name = $this->getDataOrNull($row, 1);
                $c->phone = $this->getDataOrNull($row, 2);
                $c->email = $this->getDataOrNull($row, 3);
                $c->rut = $this->getDataOrNull($row, 4);
                $c->address = $this->getDataOrNull($row, 5);
                $c->rut_dv = $this->getDataOrNull($row, 6);
                $c->enterprise_id = 1;
                $c->created_by = 1;
                $c->save();
            }
            $first = false;
        }
        
        $this->info("Loading Suppliers...");
        $suppliers = fopen(storage_path('csv/Proveedores2.csv'), "r");
        $first = true;
        while (($row = fgetcsv($suppliers)) !== FALSE) {
            if (!$first) {
                $s = new Supplier();
                $s->rut = $this->getDataOrNull($row, 0);
                $s->rut_dv = $this->getDataOrNull($row, 1);
                $s->phone = $this->getDataOrNull($row, 2);
                $s->email = $this->getDataOrNull($row, 3);
                $s->razon_social = $this->getDataOrNull($row, 4);
                $s->address = $this->getDataOrNull($row, 5);
                $s->enterprise_id = 1;
                $s->created_by = 1;
                $s->save();
            }
            $first = false;
        }
      
        $this->info('Finished Correctly');
    }

    public function getDataOrNull($cols, $index){
        if(count($cols) > $index){
            return utf8_encode($cols[$index]);
        }else{
            return null;
        }
    }
}
