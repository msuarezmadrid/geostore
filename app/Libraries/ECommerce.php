<?php

namespace App\Libraries;

use Log;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Utils;
use PrestaShopWebservice;

class ECommerce {

    public function getItems($params) {
        
        $webService = new PrestaShopWebservice(config('ecommerce.url'), config('ecommerce.key'), config('ecommerce.debug'));
        $xmlResponse = $webService->get(['resource' => 'products']);
		foreach ($xmlResponse->products->product as $product) {
			$productId = (int) $product['id'];
			$productXmlResponse = $webService->get(['resource' => 'products', 'id' => $productId]);
			$product = $productXmlResponse->product[0];
			echo sprintf('ID: %s, alias: %s' . PHP_EOL, $product->id, $product->name->language[0]);
        }

    }

    public function getItem($params) {
        
        $webService = new PrestaShopWebservice(config('ecommerce.url'), config('ecommerce.key'), config('ecommerce.debug'));
        $productId = (int) $params['id'];
        $xmlResponse = $webService->get(['resource' => 'products', 'id' => $productId]);
        $product = $xmlResponse->product[0];
        return $product;

    }

    public function createItem($params) {
        
        $webService = new PrestaShopWebservice(config('ecommerce.url'), config('ecommerce.key'), config('ecommerce.debug'));
        $xmlResponse = $webService->get(['url' => config('ecommerce.url') . '/api/products?schema=blank']);
        $productXML = $xmlResponse->product[0];
        $productXML->name->language[0]                      = $params['name'];
        $productXML->price                                  = round($params['price']/1.19, 2);
        $productXML->description->language[0]               = $params['description'];
        $productXML->description_short->language[0]         = $params['description'];
        $productXML->id_tax_rules_group                     = 1; //Impuesto por IVA (ID creada en ecommerce);
        $productXML->active                                 = true; //Product activo;
        $productXML->id_category_default                    = 2; //Categoria por defecto producto (2 = Inicio);
        $productXML->associations->categories->category->id = 2; //Categoria Producto (2 = Inicio);
        $productXML->available_for_order                    = true; //Disponible para vender;
        $productXML->indexed                                = true; //Indexada en la pagina ecommerce;
        $productXML->show_price                             = true; //mostrar el precio en la tienda;

        try {
            $addedProductResponse = $webService->add([
                'resource' => 'products',
                'postXml' => $xmlResponse->asXML(),
            ]);
            $productXML = $addedProductResponse->product[0];

            isset($params['stock']) ? $this->updateStock($productXML->id, $params['stock']) : null;

            return $productXML->id;
        } catch (PrestaShopWebserviceException $e) {
            echo $e->getMessage();
        }

    }

    public function updateItem($params) {
        
        $webService = new PrestaShopWebservice(config('ecommerce.url'), config('ecommerce.key'), config('ecommerce.debug'));
        $xmlResponse = $webService->get(['resource' => 'products', 'id' => $params['id']]);
        $productXML = $xmlResponse->product[0];
        $productXML->name->language[0]                                                      = $params['name'];
        $productXML->price                                                                  = round($params['price']/1.19, 2);
        $productXML->description->language[0]                                               = $params['description'];
        $productXML->description_short->language[0]                                         = $params['description'];
        $productXML->id_tax_rules_group                                                     = 1; //Impuesto por IVA (ID creada en ecommerce);
        $productXML->active                                                                 = true; //Product activo;
        $productXML->id_category_default                                                    = 2; //Categoria por defecto producto (2 = Inicio);
        $productXML->associations->categories->category->id                                 = 2; //Categoria Producto (2 = Inicio);
        $productXML->available_for_order                                                    = true; //Disponible para vender;
        $productXML->indexed                                                                = true; //Indexada en la pagina ecommerce;
        $productXML->show_price                                                             = true; //mostrar el precio en la tienda;

        try {
            $addedProductResponse = $webService->edit([
                'resource' => 'products',
                'id' => (int) $productXML->id,
                'putXml' => $xmlResponse->asXML(),
            ]);
            $productXML = $addedProductResponse->product[0];
            return $productXML->id;
        } catch (PrestaShopWebserviceException $e) {
            echo $e->getMessage();
        }

    }

    public function deleteItem($params) {
        
        $webService = new PrestaShopWebservice(config('ecommerce.url'), config('ecommerce.key'), config('ecommerce.debug'));

        try {
            $webService->delete([
                'resource' => 'products',
                'id' => $params['id'],
            ]);
            return "Producto Eliminado";
        } catch (PrestaShopWebserviceException $e) {
            echo $e->getMessage() . ' ' .$e->getTraceAsString();
        }

    }

    public function updateStock($productID, $quantity){
        $webService = new PrestaShopWebservice(config('ecommerce.url'), config('ecommerce.key'), config('ecommerce.debug'));

        try
        {
            $params['id'] = $productID;
            $resources = $this->getItem($params);
            $stockID = $resources->associations->stock_availables->stock_available;
            

            foreach($stockID as $stock){           
                $stockAVailableID = $stock->id;
            }

        }
        catch (PrestaShopWebserviceException $e)
        {
            $trace = $e->getTrace();
            if ($trace[0]['args'][0] == 404) return 'No existe ID de producto en ECommerce';
            else if ($trace[0]['args'][0] == 401) return 'KEY ECommerce erronea';
            else return 'Error <br />'.$e->getMessage();
        }   

        //Second we get all the stockAvailables resources
        try
        {
            $opt = array('resource' => 'stock_availables');
            $opt['id'] = $stockAVailableID;
            $xml = $webService->get($opt);
            $resources = $xml->children()->children();
        }
        catch (PrestaShopWebserviceException $e)
        {
            $trace = $e->getTrace();
            if ($trace[0]['args'][0] == 404) return 'No existe ID de producto en ECommerce';
            else if ($trace[0]['args'][0] == 401) return 'KEY ECommerce erronea';
            else return 'Error <br />'.$e->getMessage();
        }


        //At last we update the quantity with the given value manupulated and all other values original
        foreach ($resources as $nodeKey => $node)
        {
            if($nodeKey == 'quantity'){
                unset($node);
                $node = $quantity;
            }
            $resources->$nodeKey = $node;
        }
        try
        {
            $opt = array('resource' => 'stock_availables');
            $opt['putXml'] = $xml->asXML();
            $opt['id'] = $stockAVailableID;
            $xml = $webService->edit($opt);
            return "Stock Actualiazdo";
        }
        catch (PrestaShopWebserviceException $ex)
        {
            $trace = $ex->getTrace();
            if ($trace[0]['args'][0] == 404) return 'No existe ID de producto en ECommerce';
            else if ($trace[0]['args'][0] == 401) return 'KEY ECommerce erronea';
            else return 'Error <br />'.$e->getMessage();
        }
    }
}