<?php

namespace App\Console\Commands;

use App\Brand;
use Illuminate\Console\Command;
use Log;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Item;
use App\Transact;
use App\ItemPrice;
use App\PriceType;
use App\UnitOfMeasure;
use App\Category;
use DB;
use Carbon\Carbon;


class AddProductsFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geostore:addproductsfromexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will add products to items table based from an excel file';

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
        $file = storage_path('xlsx/productos.xlsx');
        
        // $rows = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly('Producto');
        // $spreadsheet = $reader->load($file);
        // print_r($spreadsheet);

        $worksheetData = $reader->listWorksheetInfo($file);

        $reader->setLoadSheetsOnly('Producto');
        $spreadsheet = $reader->load($file);
        
        $worksheet = $spreadsheet->getActiveSheet();
        Log::info('Loading Products');
        foreach ($worksheet->toArray() as $key => $product) {
            Log::info("Loading ". $product[1]);
            if ($key != 0) {
                if ($product[5] != null) {
                    Log::info("Loading Category: ". $product[5]);
                    $category = Category::firstOrNew([ 'name' => $product[5]]);
                    $category->name = $product[5];
                    $category->enterprise_id = 1;
                    $category->created_by = 1;
                    $category->save();
                }

                if ($product[4] != null) {
                    Log::info("Loading Brand: ". $product[4]);
                    $brand = Brand::firstOrNew([ 'name' => $product[4]]);
                    $brand->name = $product[4];
                    $brand->enterprise_id = 1;
                    $brand->created_by = 1;
                    $brand->save();
                }

                $category_id = Category::where('name', $product[5])->first();
                $brand_id = Brand::where('name', $product[4])->first();
                Log::info("Creating Item...");
                $item = Item::firstOrNew([ 'manufacturer_sku' => $product[0]]);
                $item->manufacturer_sku = $product[0];
                $item->name = $product[1];
                $item->enterprise_id = 1;
                $item->created_by = 1;
                $item->updated_by = 1;
                $item->item_type_id = 1;
                $item->unit_of_measure_id = UnitOfMeasure::where('abbr', strtolower($product[2]))->first()->id;
                $item->category_id = $category_id != null ? $category_id->id : null;
                $item->brand_id = $brand_id != null ? $brand_id->id : null;
                $item->save();

                Log::info("Creating Transact...");
                $transact = Transact::firstOrNew([ 'object_id' => $item->id], ['object_type' => "items"]);
                $transact->description = 'Se ha creado el producto';
                $transact->object_id = $item->id;
                $transact->object_type = "items";
                $transact->created_by = 1;
                $transact->save();

                Log::info("Creating ItemPrice...");
                $itemPrice = ItemPrice::firstOrNew([ 'item_id' => $item->id]);
                $itemPrice->item_id = $item->id;

                Log::info("Creating PriceType...");
                $price_type = PriceType::where('name','VENTA')
                                        ->where('enterprise_id', 1)->first()->id;
                $itemPrice->price_type_id = $price_type;//COST
                $itemPrice->price = $product[3];
                $itemPrice->created_by = 1;
                $itemPrice->updated_by = 1;
                $itemPrice->item_active = 1;
                $itemPrice->save();
                Log::info("Finish");
        }
            Log::info('Done');
        }
        Log::info('Done');
    }
}
