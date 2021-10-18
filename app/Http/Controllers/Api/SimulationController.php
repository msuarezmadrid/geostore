<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Location;
use App\Supplier;
use App\LocationItem;

class SimulationController extends ApiController
{
    private $_method = '';
    private $_id     = '';
    public $cromosome_historical = [];
    public $evaluations = [];
    public $table = [];
    public $table_two = [];
    public $mins = [];
    public $maxs = [];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function optimization(Request $request)
    {
        $data = new \stdClass();

        $data->dcs = $request->get('dcs');
        $data->suppliers = $request->get('suppliers');

        $numberOfDCs = $request->get('total_locations');

        $idDCs = $request->get('locations_ids');


        $initial_inventory = [$request->get('initial_inventory_min'), $request->get('initial_inventory_max')];
        $reorder_point = [$request->get('reorder_point_min'), $request->get('reorder_point_max')];
        $order_up_to_level = [$request->get('order_upto_level_min'), $request->get('order_upto_level_max')];

        $dcs = $request->get('dcs');
        $suppliers = $request->get('suppliers');

        $item_id = 4; //$request->get('item_id')

        $cromosome = $this->initCromosome($dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level);

        $hi = [];
        $Ui = [];
        $ci = [];
        $Oi = [];

        for ($j=0; $j < count($dcs); $j++) { 
            $hi[] = rand($dcs[$j]['average_holding_cost_min'], $dcs[$j]['average_holding_cost_max']);
            $Ui[] = rand($dcs[$j]['fixed_order_cost_min'], $dcs[$j]['fixed_order_cost_max']);
            $ci[] = rand($dcs[$j]['variable_order_cost_min'], $dcs[$j]['variable_order_cost_max']);
            $Oi[] = random_int($dcs[$j]['order_holding_cost_rate_min'], $dcs[$j]['order_holding_cost_rate_max']);
        }



        $c = [];
        $e = [];

        /*First Generation*/
        for ($i=0; $i < 40; $i++) { 
            $cc = $this->initCromosome($dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level);
            $c[] = $cc;
            $e[] = $this->evaluate($cc, $item_id, $dcs, $suppliers, $hi, $Ui, $ci, $Oi);
        }

        foreach ($c as $key => $cromo) {
            $this->table[] = [1, json_encode($cromo), $e[$key]];
        }

        $newCromosomes = $this->geneticAlgorithm($c, $e, $dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level);
        $data->genetic = $newCromosomes;

        $newCromosomesEvaluation = [];
    
        for ($i=0; $i < 20; $i++) { 
            $newCromosomesEvaluation = [];
            foreach ($newCromosomes as $key => $value) {
                $newCromosomesEvaluation[] = $this->evaluate($value, $item_id, $dcs, $suppliers, $hi, $Ui, $ci, $Oi);
            }

            foreach ($newCromosomes as $key => $cromo) {
                $this->table[] = [$i + 2, json_encode($cromo), $newCromosomesEvaluation[$key]];
            }

        
            $newCromosomes = $this->geneticAlgorithm($newCromosomes, $newCromosomesEvaluation, $dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level);
        }






        
        $data->cromosome = $c;
        $data->new_cromosome = $newCromosomes;
        $data->new_cromosome_ev = $newCromosomesEvaluation;

        $data->table_one = $this->table;

        $avg = [];
        $generation = 1;
        $count = 0;
        $promedio = 0;

        foreach ($this->table as $key => $t) {

            $count++;
            $promedio += $t[2];
            if($t[0] != $generation || $key == count($this->table)-1 ) {
                $promedio = $promedio/$count;



                $this->table_two[] = [$generation, $promedio, $this->mins[$generation-1], $this->maxs[$generation-1]];
                $avg[] = $promedio;

                $promedio = 0;
                $count = 0;
                $generation++;


            }

            # code...
        }
        $data->table_two = $this->table_two;
        $data->min_max = [$this->mins, $this->maxs, $avg];
        //$data->historical = $this->cromosome_historical;
        $data->evaluations = $this->evaluations;
        return $this->json($request, $data, 200);
    }

    public function geneticAlgorithm($cromosomes, $evaluation, $dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level){

        $parents = [];
        $childCromosomes = [];
        $parentCromosomes = [];

        $auxCromosomes = $cromosomes;
        $auxEvaluations = $evaluation;
        /*Selects best parent*/

        $max = max($auxEvaluations);
        $key = array_search($max, $auxEvaluations);
        $parents[] = $key;
        unset($auxCromosomes[$key]);
        unset($auxEvaluations[$key]);

        /*Delete worst parent*/

        $min = min($auxEvaluations);
        $key = array_search($min, $auxEvaluations);
        unset($auxCromosomes[$key]);
        unset($auxEvaluations[$key]);


        /*Tournament selection*/
        $count = count($auxCromosomes)/2;
        $aux = [];
        for ($i=0; $i < $count; $i++) { 
            $competitorOne = array_rand($auxCromosomes);
            $keyOne = array_search($cromosomes[$competitorOne], $auxCromosomes);
            unset($auxCromosomes[$keyOne]);

            $competitorTwo = array_rand($auxCromosomes);
            $keyTwo = array_search($cromosomes[$competitorTwo], $auxCromosomes);
            unset($auxCromosomes[$keyTwo]);

            $aux[] = $auxCromosomes;

            if( $evaluation[$keyOne] >  $evaluation[$keyTwo]){
                $parents[] = $competitorOne;
            }else{
                $parents[] = $competitorTwo;
            }
            
        }
       
        /*Childs creation by crossover*/
       

        for ($i=0; $i < count($parents)/2; $i++) { 
            $parentOne = $cromosomes[ $parents[2*$i] ];
            $parentTwo = $cromosomes[ $parents[2*$i +1] ];

            //random cut
            $crossoverValue = rand(0, 1);
            if($crossoverValue <= 0.8){
                $length = count($parentOne);
                $rnd = random_int(0, $length - 1);

                $headOne = array_slice($parentOne, 0, $rnd);
                $tailOne = array_slice($parentOne, $rnd, $length);

                $headTwo = array_slice($parentTwo, 0, $rnd);
                $tailTwo = array_slice($parentTwo, $rnd, $length);

                $childOne = [];
                foreach ($headOne as $key => $value) {
                    $childOne[] = $value;
                }
                foreach ($tailTwo as $key => $value) {
                    $childOne[] = $value;
                }

                $childTwo = [];
                 foreach ($headTwo as $key => $value) {
                    $childTwo[] = $value;
                }
                foreach ($tailOne as $key => $value) {
                    $childTwo[] = $value;
                }
            }else{
                $childOne = $parentOne;
                $childTwo = $parentTwo;
            }
            

            $childCromosomes[] = $childOne;
            $childCromosomes[] = $childTwo;
            $parentCromosomes[] = $parentOne;
            $parentCromosomes[] = $parentTwo;
        }

        /*mutation*/
        foreach ($childCromosomes as $key => $value) {
            $mutationValue = rand(0,1);
            if($mutationValue < 0.05){
                for ($i=2; $i < count($value) -1; $i++) { 
                    $bin = decbin($value[$i]);
                    $bin = str_replace("0", "1", $bin);
                    $value[$i] = bindec($bin);
                }
            }
        }

        $newCromosomes = [];
        foreach ($parentCromosomes as $key => $value) {
            $newCromosomes[] = $value;
            //$newCromosomes[] = $this->initCromosome($dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level);
        }
        foreach ($childCromosomes as $key => $value) {
            $newCromosomes[] = $value;
        }

        $this->evaluations[] = min($evaluation);
        $this->mins[] = min($evaluation);
        $this->maxs[] = max($evaluation);
        return $newCromosomes;
    }

    public function initCromosome($dcs, $suppliers, $initial_inventory, $reorder_point, $order_up_to_level)
    {
        $cromosome = [];


        /*Seleccion de Suppliers Random para cada DC*/
        $suppliers_ids = [];
        foreach ($suppliers as $key => $supp) {
            $suppliers_ids[] = $supp['id'];
        }
        foreach ($dcs as $key => $dc) {
            $cromosome[] = $suppliers_ids[array_rand($suppliers_ids)];
        }

        /*Configuración inicial de DCs*/        
        foreach ($dcs as $key => $dc) {
            /*DCS - Locations*/
            /*Initial Inventory*/
            $cromosome[] = random_int($initial_inventory[0], $initial_inventory[1]);
            /*Reorden Point*/
            $cromosome[] = random_int($reorder_point[0], $reorder_point[1]);
            /*Order Up To Level*/
            $cromosome[] = random_int($order_up_to_level[0], $order_up_to_level[1]);
        }

        foreach ($suppliers as $key => $value) {
            /*Suppliers*/
            /*Initial Inventory*/
            $cromosome[] = random_int($initial_inventory[0], $initial_inventory[1]);
            /*Reorden Point*/
            $cromosome[] = random_int($reorder_point[0], $reorder_point[1]);
            /*Order Up To Level*/
            $cromosome[] = random_int($order_up_to_level[0], $order_up_to_level[1]);
        }

        return $cromosome;

    }

    public function evaluate($cromosome, $item_id, $dcs, $suppliers, $hi, $Ui, $ci, $Oi){
        /*
        *   $dcs = dcs a ser evaluados
        */

        $h = []; /*Costo de almacenamiento del item en almacen i*/

        $itemH = LocationItem::where('item_id', $item_id)->get();
        $TSCCin = []; 
        $TSCCjn = [];

        
        $X0 = [];

        /*-----------------------------*/
        /*
        
            Necesito encontrar TSCCn
            Total Supply Chain Cost
            TSCCn = ∑ TSCCin + ∑ TSCCjn 
            
                TSCCin = 
    (ok)            hi*(X^+)in + 
                I{Xin <= si}(Ui + ci(Si - Xin)) + 
                ki*(X^-)in + 
                oim*Oi
                
            hi = average holding cost per unit per unit time    
            (X^+)in = physical average holding unit del periodo
            
            
            I{Xin <= si} inventory level < reorder point
            
            Ui = fixed ordering cost (foreach replenishment order)

            ci = variable ordering cost per unit 
            Si = order up to level
            Xin = existing inventory at DCi at begining of period
            

            ki = lost sales cost per unit 
            (X^-)in = unmet customer order quantity 

            oim = order_processing_time
            Oi = order holding cost rate


                TSCCjn = 
                hj*(X^+)jn + 
                I{Xjn <= sj}(Uj + cj(Sj - Xjn)) + 
                kj*(X^-)jn +
                Tj*Yjn +
                Ajt +
                ojk*Oj

            Tj = satisfied order quantity
            Yjn = transportation cost per unit

            Ajt = fixed transportation cost
            Oj = Order holding rate
            ojk = order processing time
            
        /*-----------------------------*/
        $avg_holding_physical_unit = [];  


        
        /* SIMULATION !!*/

        $total_dcs = count($dcs); // 4
        $total_suppliers = count($suppliers);
        //return $total_suppliers;
        /*otra cosa*/
        $i = 0;
        while ($i < $total_dcs) {
            $X0[] = $cromosome[$total_dcs + $i*3];
            $i++;
        }
        $Xi = 0;

        /*Inicialización de variables*/
        $timeToEvaluateInDays = 150;//365;
        $day = 1;
        $revisionPeriod = 5;

        $clientPetitions = [];
        $supplierOrders = [];
        $dcOrders = [];


        $avg_holding_physical_unit = [];
        $lostSales = [];
        for ($j=0; $j < $total_dcs; $j++) { 
            $lostSales[] = [$j, 0]; 
        }
        $logLostSales = [];

        //
        $TSCCin = [];

        $hiArray = [];
        $UiCiArray = [];
        $kiArray = [];
        $oimArray = [];

        while($day <= $timeToEvaluateInDays){
            $revisionPeriod--;
            //$this->cromosome_historical[]= "DAY: ".$day;
            /*Simulacion*/
            /*  Pasos
            *   1. Chequear ordenes que pueden ser cumplidas y actualizar inventario
            *   2. Realizar ordenes con sus tiempos de procesamiento
            *   PERIODO DE REVISION:
            *   - Contabilizar inventario y aplicar costos
            */


            //CALCULO DE hi*Xin_mas

            $dcsAVG = [];
            for ($i=0; $i < $total_dcs; $i++) { 
                # se agregan physical holding units para promediar
                # [ dc[id], units]
                $aux = [$i, $cromosome[$total_dcs + $i*3] ];
                $dcsAVG[] = $aux;

            }

            $avg_holding_physical_unit[] = $dcsAVG;
            if($revisionPeriod == 0){

                //CALCULO DE hi*XIN_MAS
                $avg = [];

                for ($j=0; $j < $total_dcs; $j++) { 
                    $avg[] = [$j, 0];
                }
                
                for ($j=0; $j < 5; $j++) { //dias de periodo de revision 
                    for ($k=0; $k < $total_dcs; $k++) { 
                        $avg[$k][1] += $avg_holding_physical_unit[$j][$k][1];
                    }
                }
                for ($j=0; $j < $total_dcs; $j++) { 
                    $avg[$j][1] = intval($avg[$j][1]/5); //periodo de revision
                }

                //$this->cromosome_historical[] = "AVG:::" .json_encode($avg);
                //$this->cromosome_historical[] = "hi:::" .json_encode($hi);
                $hi_Xin_mas = 0;


                for ($j=0; $j < $total_dcs; $j++) { 
                    $hi_Xin_mas += $avg[$j][1]*$hi[$j];
                }
               

                $this->cromosome_historical[] = "hi_Xin_mas:::" .json_encode($hi_Xin_mas);
                $avg_holding_physical_unit = [];
            }
            

            if($revisionPeriod == 0){

                //CALCULO DE Ui + ci(Xi-Si)
                //$this->cromosome_historical[] = "dcORDERS: ".json_encode($dcOrders);
                $reps = [];


                for ($j=0; $j < $total_dcs; $j++) { 
                   $reps[] = [$j, 0];
                   //$Ui[] = rand(10, 20);
                   //$ci[] = rand( 5, 10);
                }

                $Ui_ciXin_si = 0;
                
                for ($j=0; $j < $total_dcs; $j++) {
                    //$this->cromosome_historical[] = " cromosome vs: ".json_encode($cromosome[$total_dcs +$j*3])." < ".json_encode($cromosome[$total_dcs + $j*3 +1]);
                    if($cromosome[$total_dcs +$j*3] < $cromosome[$total_dcs + $j*3 +1]){

                        //$this->cromosome_historical[] = " Ui + ci(Xin - Si): ".json_encode($Ui[$j])." + ".json_encode($ci[$j])."*(".json_encode($cromosome[$total_dcs + $j*3 +2])." - ".json_encode($cromosome[$total_dcs + $j*3 +1]).")";
                        $Ui_ciXin_si += $Ui[$j] + $ci[$j]*($cromosome[$total_dcs + $j*3 +2]-$cromosome[$total_dcs + $j*3 +1]);
                    }
                    
                }

                $this->cromosome_historical[] = "Ui + ci*(Xin-Si) = ".$Ui_ciXin_si;

            }


            /*ORDENES DE REABASTECIMIENTO*/
            $supplierOrdersToDelete = [];
            foreach ($supplierOrders as $key => $value) {
                $cromosome_historical[] = json_encode($value);
                if($supplierOrders[$key][2] > 0){
                    $supplierOrders[$key][2]--;
                    if($supplierOrders[$key][2] < 0){/*ajuste decimales*/
                        $supplierOrders[$key][3] -= $supplierOrders[$key][2];
                        $supplierOrders[$key][2] = 0;
                    }
                }elseif($supplierOrders[$key][3] > 0){
                    
                    $supplierOrders[$key][3]--;
                    if($supplierOrders[$key][3] < 0){/*ajuste decimales*/
                        $supplierOrders[$key][4] -= $supplierOrders[$key][3];
                        $supplierOrders[$key][3] = 0;
                    }

                    if($supplierOrders[$key][3] <= 0){/*Actualizar inventario proveedor*/
                        $supId = $cromosome[$supplierOrders[$key][0]];
                        //$this->cromosome_historical[] = "sup_id: ". $supId;
                        foreach ($suppliers as $k => $v) {
                            if($v['id'] == $supId){
                                //$this->cromosome_historical[] = "inicialr: ". $cromosome[$total_dcs+($total_dcs*3)+($k*3)];


                                if($cromosome[$total_dcs+($total_dcs*3)+($k*3)] > $supplierOrders[$key][1]){
                                    $cromosome[$total_dcs+($total_dcs*3)+($k*3)] -= $supplierOrders[$key][1];
                                }else{
                                    
                                    $supplierOrders[$key][1] =  $cromosome[$total_dcs+($total_dcs*3)+($k*3)];
                                    $cromosome[$total_dcs+($total_dcs*3)+($k*3)] = 0;
                                }
                                
                                //$this->cromosome_historical[] = "descuento inventario proveedor: ". $supplierOrders[$key][1];
                                //$this->cromosome_historical[] = "final ". $cromosome[$total_dcs+($total_dcs*3)+($k*3)];
                            }
                        }
                    }
                }else{
                    if($supplierOrders[$key][4]>0){
                        $supplierOrders[$key][4]--;
                    }
                }
                if($supplierOrders[$key][4]<0 || $supplierOrders[$key][4]==0){/*se reabastece inventario dc*/
                    /*PROCESANDO ORDEN DC*/
                    $supplierOrdersToDelete[] = $key;
                    $cromosome[$total_dcs+($supplierOrders[$key][0]*3)] += $supplierOrders[$key][1];

                    //$this->cromosome_historical[] = "cromo_key: ".$x. ", cromo_value: ".$cromosome[$x];

                }
            }
            
            


            /*REVISION PERIOD*/

            if($revisionPeriod == 0){
                //$this->cromosome_historical[]="........revision";
                /*REPOSICION SUPPLIERS*/



                $i = 0;
                while ($i < $total_suppliers) {
                    $supplierInventory = $cromosome[$total_dcs+($total_dcs*3)+($i*3)];
                    $supplierReordePoint = $cromosome[$total_dcs+($total_dcs*3)+($i*3+1)];
                    $supplierOrderUpToLevel = $cromosome[$total_dcs+($total_dcs*3)+($i*3+2)];

                    if($supplierInventory < $supplierReordePoint){
                        $cromosome[$total_dcs+($total_dcs*3)+($i*3)] = $supplierOrderUpToLevel;
                        //$this->cromosome_historical[]= "Rev.Period: Supplier ID:".$suppliers[$i]->id;
                    }
                    $i++;
                }

                /*ORDENES DE REPOSICION DCS*/

                $i = 0;
                
                while ($i < $total_dcs) {
                    $dcInventory = $cromosome[$total_dcs+($i*3)];
                    $dcReordePoint = $cromosome[$total_dcs+($i*3+1)];
                    $dcOrderUpToLevel = $cromosome[$total_dcs+($i*3+2)];

                    if($dcInventory < $dcReordePoint){
                        /*Generar orden de reposición*/
                        $supplierOrderQty = $dcOrderUpToLevel - $dcInventory;
                        foreach ($suppliers as $key => $value) {
                            if($value['id'] == $cromosome[$i]){
                                $supID = $key;
                            }
                        }
                       
                        $supplierProcessingTime = $this->triangular($suppliers[$supID]['processing_time_min'], 
                            $suppliers[$supID]['processing_time_med'], $suppliers[$supID]['processing_time_max']);
                        //$this->triangular(3,5,7);
                        $supplierOrderProcessingTime = rand($suppliers[$supID]['order_processing_time_min'], $suppliers[$supID]['order_processing_time_max']);
                        //rand(2,5);
                        $supplierTransportationTime = rand($suppliers[$supID]['transportation_time_min'], $suppliers[$supID]['transportation_time_max']);
                        //rand(1.25,3);
                        $flag = true;
                        foreach ($supplierOrders as $k => $so) {
                            if($so[0] == $i){
                                $flag = false;
                            }
                        }

                        if($flag){
                            $supplierOrders[] = [$i, $supplierOrderQty, $supplierProcessingTime, 
                                        $supplierOrderProcessingTime, $supplierTransportationTime];
                            //$this->cromosome_historical[]= "----supp_order: ".json_encode([$i, $supplierOrderQty, $supplierProcessingTime, 
                            //            $supplierOrderProcessingTime, $supplierTransportationTime]);
                        

                        }


                    }
                    $i++;
                }


                
                //$this->cromosome_historical[]="..................";

            }

            /*CHECK DC ORDERS*/
            $dcOrdersToDelete = [];
            
            
            foreach ($dcOrders as $key => $value) {
                if($dcOrders[$key][2]>0){
                    $dcOrders[$key][2]--;
                    if($dcOrders[$key][2]<0){/*ajuste decimales*/
                        $dcOrders[$key][3] -= $dcOrders[$key][2];
                        $dcOrders[$key][2] = 0;
                    }
                }else{
                    if($dcOrders[$key][3]>0){
                        $dcOrders[$key][3]--;
                    }
                }
                if($dcOrders[$key][3]<0 || $dcOrders[$key][3]==0){/*cuando se agota el tiempo se descuenta inventario*/
                    /*PROCESANDO ORDEN DC*/
                    $dcOrdersToDelete[] = $key;
                    $x = $total_dcs+($dcOrders[$key][0]*3);

                    //$this->cromosome_historical[] = "cromo_key: ".$x. ", cromo_value: ".$cromosome[$x];


                    /*Control de inventario*/
                    if($cromosome[$total_dcs+($dcOrders[$key][0]*3)] >= $dcOrders[$key][1]){
                        /*Demanda satisfecha (venta realizada*/
                        $cromosome[$total_dcs+($dcOrders[$key][0]*3)] -= $dcOrders[$key][1];
                    }else{
                        /*Demanda parcialmente satisfecha (ventas perdidas)*/
                        $diff = $dcOrders[$key][1] - $cromosome[$total_dcs+($dcOrders[$key][0]*3)];
                        $cromosome[$total_dcs+($dcOrders[$key][0]*3)] = 0;
                        for ($j=0; $j < $total_dcs; $j++) { 
                            if($dcOrders[$key][0]== $j){
                                if($diff > 0){
                                    $lostSales[$j][1] += $diff;
                                    //$this->cromosome_historical[] = " lost ".$j.": ".$lostSales[$j][1]; 
                                }
                            }
                        }
                        
                        

                        $logLostSales[] = $diff;
                        //$this->cromosome_historical[]= "descuento inventario: ".json_encode($cromosome);
                    }
                    //$this->cromosome_historical[] = "cromo_key: ".$x. ", cromo_value: ".$cromosome[$x];

                }
            }
            
            


            

            /*GENERAR ORDENES PARA DCS*/
            foreach ($dcs as $key => $value) {
                $clientPetitions[] = [];
            }
            $dayClientPet = [];

            foreach ($dcs as $key => $value) {
                /*LLEGADA DE CLIENTES*/
                //1 x dia x DC

                /*cantidad ordenada, tiempo de procesamiento de orden*/
                $dcOrderQty = $this->poisson(50);
                $dcProcessingTime = $this->triangular($value['processing_time_min'], $value['processing_time_med'], $value['processing_time_max'] );
                $dcOrderProcessingTime = rand($value['order_processing_time_min'], $value['order_processing_time_max']);

                $dcOrders[] = [$key, $dcOrderQty, $dcProcessingTime, $dcOrderProcessingTime];
                /*$this->cromosome_historical[] = "ORDER_: Qty: ".$dcOrderQty.
                                                " - proc: ".$dcProcessingTime.
                                                ", orderproc: ".$dcOrderProcessingTime;
                */
            }


            //$this->cromosome_historical[]= "Day ".$day.": Pedidos Clientes: ".json_encode($dayClientPet);

            //$this->cromosome_historical[]= "--order: ".json_encode($dcOrders);
            //$this->cromosome_historical[]= "--sups: ".json_encode($supplierOrders);
            //$this->cromosome_historical[]= "--cromosome: ".json_encode($cromosome);


            foreach ($supplierOrdersToDelete as $key => $value) {
                array_splice($supplierOrders, $value, 1);
            }
            foreach ($dcOrdersToDelete as $key => $value) {
                array_splice($dcOrders, $value, 1);
            }

            if($revisionPeriod == 0){
                $revisionPeriod = 5;

                $ki_Xinminus = 0;
                //$this->cromosome_historical[] = " lostSales: ".json_encode($lostSales);
                $ki = [];
                for ($j=0; $j < $total_dcs; $j++) { 
                    $ki [] = rand($dcs[$j]['lost_sales_cost_min'], $dcs[$j]['lost_sales_cost_max'] );
                    //rand(80,100);                    
                }
                for ($j=0; $j < $total_dcs; $j++) { 
                    if($lostSales[$j][1] > 0){
                        $ki_Xinminus += $lostSales[$j][1]*$ki[$j];
                    }
                }

                $this->cromosome_historical[] = "ki_Xinminus: ".json_encode($ki_Xinminus);

                $lostSales = [];
                for ($j=0; $j < $total_dcs; $j++) { 
                   $lostSales[] = [$j, 0];
                }

                $oim_Oi = 0;
                
                 
                foreach ($dcOrders as $key => $value) {
                    
                    $oim_Oi += $value[3]*$Oi[$value[0]];

                }

               
                $this->cromosome_historical[] = "oim_Oi: ".json_encode($oim_Oi);
                $TSCCin[] = $hi_Xin_mas + $Ui_ciXin_si + $ki_Xinminus + $oim_Oi; 

                $this->cromosome_historical[] = "TSCC: ".json_encode( $hi_Xin_mas + $Ui_ciXin_si + $ki_Xinminus + $oim_Oi);
            }
            $day++;
        }
        

        $fitness = 0;
        foreach ($TSCCin as $key => $value) {
            $fitness += $value;
        }

        foreach ($TSCCjn as $key => $value) {
            $fitness += $value;
        }


        return 1/$fitness;
    }

    public function poisson( $lambda=1.0 )
    {
        static $oldLambda ;
        static $g, $sq, $alxm ;

        if ($lambda <= 12.0) {
            //  Use direct method
            if ($lambda <> $oldLambda) {
                $oldLambda = $lambda ;
                $g = exp(-$lambda) ;
            }
            $x = -1 ;
            $t = 1.0 ;
            do {
                ++$x ;
                $t *= $this->random_0_1() ;
            } while ($t > $g) ;
        } else {
            //  Use rejection method
            if ($lambda <> $oldLambda) {
                $oldLambda = $lambda ;
                $sq = sqrt(2.0 * $lambda) ;
                $alxm = log($lambda) ;
                $g = $lambda * $alxm - $this->gammaln($lambda + 1.0) ;
            }
            do {
                do {
                    //  $y is a deviate from a Lorentzian comparison function
                    $y = tan(pi() * $this->random_0_1()) ;
                    $x = $sq * $y + $lambda ;
                //  Reject if close to zero probability
                } while ($x < 0.0) ;
                $x = floor($x) ;
                //  Ratio of the desired distribution to the comparison function
                //  We accept or reject by comparing it to another uniform deviate
                //  The factor 0.9 is used so that $t never exceeds 1
                $t = 0.9 * (1.0 + $y * $y) * exp($x * $alxm - $this->gammaln($x + 1.0) - $g) ;
            } while ($this->random_0_1() > $t) ;
        }

        return $x ;
    }   //  function poisson()

    public function gammaln($in)
    {
        $tmp = $in + 4.5 ;
        $tmp -= ($in - 0.5) * log($tmp) ;

        $ser = 1.000000000190015
                + (76.18009172947146 / $in)
                - (86.50532032941677 / ($in + 1.0))
                + (24.01409824083091 / ($in + 2.0))
                - (1.231739572450155 / ($in + 3.0))
                + (0.1208650973866179e-2 / ($in + 4.0))
                - (0.5395239384953e-5 / ($in + 5.0)) ;

        return (log(2.5066282746310005 * $ser) - $tmp) ;
    }   //  function gammaln()


    public function random_0_1()
    {
        //  returns random number using mt_rand() with a flat distribution from 0 to 1 inclusive
        //
        return (float) mt_rand() / (float) mt_getrandmax() ;
    }   //  random_0_1()

    public function triangular($a,$c,$b){
        //symmetrical_triangular
        $triangularDist = [];
        $triangularDist1 = [];
        $triangularDist2 = [];
        for ($i=0; $i < 100; $i++) { 
            $triangularDist1[] = 1/2*($a + rand(0, $b-$a));
        }
        for ($i=0; $i < 100; $i++) { 
            $triangularDist2[] = 1/2*($a + rand(0, $b-$a));
        }

        for ($i=0; $i < 100 ; $i++) { 
            //$triangularDist[] = ($triangularDist1[$i]+$triangularDist2[$i])/2;
            $triangularDist[] = $triangularDist2[$i] + $triangularDist1[$i];
        }

        return $triangularDist[rand(0,99)];
    }
}
