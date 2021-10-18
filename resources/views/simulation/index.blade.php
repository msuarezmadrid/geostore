@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    
    <link rel="stylesheet" href="{{asset('css/multiple-select.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.0/chartist.min.css">
@endsection

@section('modals')
        <div id="modal_add_item" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myLargeModalLabel"> Añadir Producto</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              
                <div class="input-group margin">
                      <input type="text" id="item_search" placeholder="Buscar" class="form-control">
                      <span class="input-group-btn">
                        <button id="item_search_btn" class="form-control btn btn-primary"> Filtrar </button>
                      </span>
                      
                  </div>
              
               <div class="col-xs-12">
                        
                      <div class="btn-group btn-toggle pull-right">
                      
                          <button class="btn btn-xs btn-default active" id="normalview_btn">
                              <i class="fa fa-th-list"></i>
                          </button>
                          <button class="btn btn-xs btn-default" id="compactview_btn">
                              <i class="fa fa-align-justify"></i>
                          </button>
                      </div>
                      
                  </div>
                <div class="col-xs-12" id="normal_view">
                  
                    <table id="grid_add_orderitems" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                      <thead>
                      <tr>
                        <th>Imagen</th>
                        <th>Detalles</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                      </tr>
                      </thead>
                    </table>

                </div>

                <div class="col-xs-12" id="compact_view" style="display: none;">
                  
                    <table id="grid_add_orderitems_minimal" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                      <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                      </tr>
                      </thead>
                    </table>

                </div>
            </div>
               
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
           
          </div>
        </div>
      </div>
    </div>

@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Simulación
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Simulación</li>
      </ol>
    </section>
	
	<section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat">
                    <div class="box-header">
                        <h3 class="box-title"> 1. Seleccione producto para realizar la simulación</h3>
                        <button class="pull-right btn btn-success"  onclick="showItemsModal();"> Seleccionar</button>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <img id="simulation_item_image" src="http://placehold.it/150x150" alt="">
                                    </div>

                                    <div class="col-xs-8">
                                        <h4 id="simulation_item_title"> Producto </h4>
                                        <div id="simulation_item_body" class="col-xs-12"> Producto </div>
                                    </div>
                                </div>
                                
                            </div>

                           <div class="col-xs-6" id="simulation_item_bom">
                               
                           </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-success pull-right" onclick="showStep2()">Siguiente</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat" id="step_2">
                    <div class="box-header">
                        <h3 class="box-title"> 2. Seleccione proveedores y almacenes</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">

                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4><b> Proveedores</b></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>Proveedor 1</h4>
                                            </div>
                                        </div>
                                        
                                            <div class="col-xs-12">
                                               <b>Costo de transporte por unidad</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_unit_transportation_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_unit_transportation_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo de transporte fijo</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_fixed_transportation_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_fixed_transportation_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <div class="col-xs-3">
                                                            Triangular: 
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                         <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_processing_time_med" class="form-control" placeholder="med"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_order_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_order_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de transporte</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_transportation_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_1_transportation_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                       
                                    </div>
                                    

                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>Proveedor 2</h4>
                                            </div>
                                        </div>
                                        
                                            <div class="col-xs-12">
                                               <b>Costo de transporte por unidad</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_unit_transportation_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_unit_transportation_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo de transporte fijo</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_fixed_transportation_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_fixed_transportation_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <div class="col-xs-3">
                                                            Triangular: 
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                         <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_processing_time_med" class="form-control" placeholder="med"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_order_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_order_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de transporte</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_transportation_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_2_transportation_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                       
                                    </div>


                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>Proveedor 3</h4>
                                            </div>
                                        </div>
                                        
                                            <div class="col-xs-12">
                                               <b>Costo de transporte por unidad</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_unit_transportation_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_unit_transportation_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo de transporte fijo</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_fixed_transportation_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_fixed_transportation_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <div class="col-xs-3">
                                                            Triangular: 
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                         <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_processing_time_med" class="form-control" placeholder="med"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_order_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_order_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de transporte</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_transportation_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="supplier_3_transportation_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                       
                                    </div>
                                </div>

                            </div>

                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4> <b>Almacenes</b></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>Almacen 1</h4>
                                            </div>
                                        </div>
                                        
                                            <div class="col-xs-12">
                                               <b>Costo de venta perdida</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_lost_sales_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_lost_sales_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tasa de costos de mantenimiento de orden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_order_holding_cost_rate_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_order_holding_cost_rate_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo fijo de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_fixed_order_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_fixed_order_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo variable de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_variable_order_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_variable_order_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo promedio de mantenimiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_average_holding_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_average_holding_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <div class="col-xs-3">
                                                            Triangular: 
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="location_1_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                         <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="location_1_processing_time_med" class="form-control" placeholder="med"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="location_1_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_order_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_1_order_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                       
                                    </div>
                                    
                                    
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h4>Almacen 2</h4>
                                            </div>
                                        </div>
                                        
                                            <div class="col-xs-12">
                                               <b>Costo de venta perdida</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_lost_sales_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_lost_sales_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tasa de costos de mantenimiento de orden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_order_holding_cost_rate_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_order_holding_cost_rate_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo fijo de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_fixed_order_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_fixed_order_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo variable de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_variable_order_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_variable_order_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Costo promedio de mantenimiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_average_holding_cost_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_average_holding_cost_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento</b> 
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <div class="col-xs-3">
                                                            Triangular: 
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="location_2_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                         <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="location_2_processing_time_med" class="form-control" placeholder="med"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="row">
                                                                <input type="number" id="location_2_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                               <b>Tiempo de procesamiento de órden</b> 
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="col-xs-4">
                                                            Uniforme: 
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_order_processing_time_min" class="form-control" placeholder="min"> 
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="row">
                                                                <input type="number" id="location_2_order_processing_time_max" class="form-control" placeholder="max">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                       
                                    </div>
                                </div>

                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-success pull-right" onclick="showStep2()">Siguiente</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat" id="step_3">
                    <div class="box-header">
                        <h3 class="box-title"> 3. Ingrese datos de entrada</h3>
                    </div>
                    <div class="box-body">
                        
                         <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4> Datos de entrada</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label for="s_item_initial_inventory">Inventario Inicial</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                Uniforme:
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <input type="number" class="form-control" placeholder="min" id="s_item_initial_inventory_min">
                                                </div>
                                                
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <input type="number" class="form-control" placeholder="max" id="s_item_initial_inventory_max">
                                                </div>
                                                
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label for="s_item_reorder_point">Punto de reorden</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                Uniforme:
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <input type="number" class="form-control" placeholder="min" id="s_item_reorder_point_min">
                                                </div>
                                                
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <input type="number" class="form-control" placeholder="max" id="s_item_reorder_point_max">
                                                </div>
                                                
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label for="s_item_order_upto_level">Nivel ordenar-hasta</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                Uniforme:
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <input type="number" class="form-control" placeholder="min" id="s_item_order_upto_level_min">
                                                </div>
                                                
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <input type="number" class="form-control" placeholder="max" id="s_item_order_upto_level_max">
                                                </div>
                                                
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                
                            </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-success pull-right" onclick="sendSimulation();">Simular</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            4. Resultados
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="nav-tabs-custom flat " id="animal_tabs">
                            <!--tabs-pages-->
                            <ul class="nav nav-tabs">
                                <li class="active"> 
                                    <a href="#tab_1" data-toggle="tab"> Gráfica</a>
                                </li>
                                <li>
                                    <a href="#tab_2" data-toggle="tab">Evaluaciones</a>
                                </li>
                                <li>
                                    <a href="#tab_3" data-toggle="tab">Estadísticas</a>
                                </li>
                            </ul>
                        <!--tabs-contents-->
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-xs-6" align="center">
                                    <div class="ct-chart ct-minor-seventh"></div>
                                    <p> Evaluación de función objetivo</p>
                                </div>
                                </div>
                                
                                

                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <div class="col-xs-12">
                                    <table id="tbl_one" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    </table>
                                </div>
                                </div>
                                
                            </div>
                            <div class="tab-pane" id="tab_3">
                                <div class="row">
                                    <div class="col-xs-12">
                                    <table id="tbl_two" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    </table>
                                </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                            </div>
                        </div>
                        


                        
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-success" type="button" onclick="simulate();">Simular</button>
                <pre id="data"></pre>
            </div>
        </div>
    </section>
@endsection



@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{asset('js/multiple-select.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.0/chartist.js"></script>
<script>


$('select').multipleSelect();

$("#getSelectsBtn").click(function() {
    console.log("Selected values: " + $("select").multipleSelect("getSelects"));
    console.log("Selected texts: " + $("select").multipleSelect("getSelects", "text"));
});

tbl_one = null;
tbl_two = null;
$(document).ready(function(){
    initAddItemsTable();
    initDebugForm();
    

});

function initDebugForm(){
    $("#s_item_initial_inventory_min").val(800);
    $("#s_item_initial_inventory_max").val(2000);
    $("#s_item_reorder_point_min").val(100);
    $("#s_item_reorder_point_max").val(800);
    $("#s_item_order_upto_level_min").val(800);
    $("#s_item_order_upto_level_max").val(1500);

    $("#supplier_1_unit_transportation_cost_min").val(0.75);
    $("#supplier_1_unit_transportation_cost_max").val(3.00);
    $("#supplier_1_fixed_transportation_cost_min").val(250);
    $("#supplier_1_fixed_transportation_cost_max").val(275);
    $("#supplier_1_processing_time_min").val(3);
    $("#supplier_1_processing_time_med").val(5);
    $("#supplier_1_processing_time_max").val(7);
    $("#supplier_1_order_processing_time_min").val(2);
    $("#supplier_1_order_processing_time_max").val(5);
    $("#supplier_1_transportation_time_min").val(1.25);
    $("#supplier_1_transportation_time_max").val(3);

    $("#supplier_2_unit_transportation_cost_min").val(0.75);
    $("#supplier_2_unit_transportation_cost_max").val(3.00);
    $("#supplier_2_fixed_transportation_cost_min").val(250);
    $("#supplier_2_fixed_transportation_cost_max").val(275);
    $("#supplier_2_processing_time_min").val(3);
    $("#supplier_2_processing_time_med").val(5);
    $("#supplier_2_processing_time_max").val(7);
    $("#supplier_2_order_processing_time_min").val(2);
    $("#supplier_2_order_processing_time_max").val(5);
    $("#supplier_2_transportation_time_min").val(1.25);
    $("#supplier_2_transportation_time_max").val(3);

    $("#supplier_3_unit_transportation_cost_min").val(0.75);
    $("#supplier_3_unit_transportation_cost_max").val(3.00);
    $("#supplier_3_fixed_transportation_cost_min").val(250);
    $("#supplier_3_fixed_transportation_cost_max").val(275);
    $("#supplier_3_processing_time_min").val(3);
    $("#supplier_3_processing_time_med").val(5);
    $("#supplier_3_processing_time_max").val(7);
    $("#supplier_3_order_processing_time_min").val(2);
    $("#supplier_3_order_processing_time_max").val(5);
    $("#supplier_3_transportation_time_min").val(1.25);
    $("#supplier_3_transportation_time_max").val(3);



    $("#location_1_lost_sales_cost_min").val(80);
    $("#location_1_lost_sales_cost_max").val(100);
    $("#location_1_order_holding_cost_rate_min").val(5);
    $("#location_1_order_holding_cost_rate_max").val(10);
    $("#location_1_fixed_order_cost_min").val(100);
    $("#location_1_fixed_order_cost_max").val(150);
    $("#location_1_variable_order_cost_min").val(50);
    $("#location_1_variable_order_cost_max").val(75);
    $("#location_1_average_holding_cost_min").val(2);
    $("#location_1_average_holding_cost_max").val(5);
    $("#location_1_processing_time_min").val(1);
    $("#location_1_processing_time_med").val(2);
    $("#location_1_processing_time_max").val(3);
    $("#location_1_order_processing_time_min").val(2);
    $("#location_1_order_processing_time_max").val(5);



    $("#location_2_lost_sales_cost_min").val(80);
    $("#location_2_lost_sales_cost_max").val(100);
    $("#location_2_order_holding_cost_rate_min").val(5);
    $("#location_2_order_holding_cost_rate_max").val(10);
    $("#location_2_fixed_order_cost_min").val(100);
    $("#location_2_fixed_order_cost_max").val(150);
    $("#location_2_variable_order_cost_min").val(50);
    $("#location_2_variable_order_cost_max").val(75);
    $("#location_2_average_holding_cost_min").val(2);
    $("#location_2_average_holding_cost_max").val(5);
    $("#location_2_processing_time_min").val(1);
    $("#location_2_processing_time_med").val(2);
    $("#location_2_processing_time_max").val(3);
    $("#location_2_order_processing_time_min").val(2);
    $("#location_2_order_processing_time_max").val(5);
}

function getDCs(){

    dcs = [];

    dc = new Object();
    dc.id = 1;
    dc.lost_sales_cost_min = $("#location_1_lost_sales_cost_min").val();
    dc.lost_sales_cost_max = $("#location_1_lost_sales_cost_max").val();
    dc.order_holding_cost_rate_min = $("#location_1_order_holding_cost_rate_min").val();
    dc.order_holding_cost_rate_max = $("#location_1_order_holding_cost_rate_max").val();
    dc.fixed_order_cost_min = $("#location_1_fixed_order_cost_min").val();
    dc.fixed_order_cost_max = $("#location_1_fixed_order_cost_max").val();
    dc.variable_order_cost_min = $("#location_1_variable_order_cost_min").val();
    dc.variable_order_cost_max = $("#location_1_variable_order_cost_max").val();
    dc.average_holding_cost_min = $("#location_1_average_holding_cost_min").val();
    dc.average_holding_cost_max = $("#location_1_average_holding_cost_max").val();
    dc.processing_time_min = $("#location_1_processing_time_min").val();
    dc.processing_time_med = $("#location_1_processing_time_med").val();
    dc.processing_time_max = $("#location_1_processing_time_max").val();
    dc.order_processing_time_min = $("#location_1_order_processing_time_min").val();
    dc.order_processing_time_max = $("#location_1_order_processing_time_max").val();

    dcs.push(dc);

    dc = new Object();
    dc.id = 2;
    dc.lost_sales_cost_min = $("#location_2_lost_sales_cost_min").val();
    dc.lost_sales_cost_max = $("#location_2_lost_sales_cost_max").val();
    dc.order_holding_cost_rate_min = $("#location_2_order_holding_cost_rate_min").val();
    dc.order_holding_cost_rate_max = $("#location_2_order_holding_cost_rate_max").val();
    dc.fixed_order_cost_min = $("#location_2_fixed_order_cost_min").val();
    dc.fixed_order_cost_max = $("#location_2_fixed_order_cost_max").val();
    dc.variable_order_cost_min = $("#location_2_variable_order_cost_min").val();
    dc.variable_order_cost_max = $("#location_2_variable_order_cost_max").val();
    dc.average_holding_cost_min = $("#location_2_average_holding_cost_min").val();
    dc.average_holding_cost_max = $("#location_2_average_holding_cost_max").val();
    dc.processing_time_min = $("#location_2_processing_time_min").val();
    dc.processing_time_med = $("#location_2_processing_time_med").val();
    dc.processing_time_max = $("#location_2_processing_time_max").val();
    dc.order_processing_time_min = $("#location_2_order_processing_time_min").val();
    dc.order_processing_time_max = $("#location_2_order_processing_time_max").val();

    dcs.push(dc);

    return dcs;

}


function getSuppliers(){
    suppliers = [];

    sup = new Object();
    sup.id = 1;
    sup.unit_transportation_cost_min =  $("#supplier_1_unit_transportation_cost_min").val();
    sup.unit_transportation_cost_max =  $("#supplier_1_unit_transportation_cost_max").val();
    sup.fixed_transportation_cost_min =  $("#supplier_1_fixed_transportation_cost_min").val();
    sup.fixed_transportation_cost_max =  $("#supplier_1_fixed_transportation_cost_max").val();
    sup.processing_time_min =  $("#supplier_1_processing_time_min").val();
    sup.processing_time_med =  $("#supplier_1_processing_time_med").val();
    sup.processing_time_max =  $("#supplier_1_processing_time_max").val();
    sup.order_processing_time_min =  $("#supplier_1_order_processing_time_min").val();
    sup.order_processing_time_max =  $("#supplier_1_order_processing_time_max").val();
    sup.transportation_time_min =  $("#supplier_1_transportation_time_min").val();
    sup.transportation_time_max =  $("#supplier_1_transportation_time_max").val();

    suppliers.push(sup);

    sup = new Object();
    sup.id = 2;
    sup.unit_transportation_cost_min =  $("#supplier_2_unit_transportation_cost_min").val();
    sup.unit_transportation_cost_max =  $("#supplier_2_unit_transportation_cost_max").val();
    sup.fixed_transportation_cost_min =  $("#supplier_2_fixed_transportation_cost_min").val();
    sup.fixed_transportation_cost_max =  $("#supplier_2_fixed_transportation_cost_max").val();
    sup.processing_time_min =  $("#supplier_2_processing_time_min").val();
    sup.processing_time_med =  $("#supplier_2_processing_time_med").val();
    sup.processing_time_max =  $("#supplier_2_processing_time_max").val();
    sup.order_processing_time_min =  $("#supplier_2_order_processing_time_min").val();
    sup.order_processing_time_max =  $("#supplier_2_order_processing_time_max").val();
    sup.transportation_time_min =  $("#supplier_2_transportation_time_min").val();
    sup.transportation_time_max =  $("#supplier_2_transportation_time_max").val();

    suppliers.push(sup);

    sup = new Object();
    sup.id = 3;
    sup.unit_transportation_cost_min =  $("#supplier_3_unit_transportation_cost_min").val();
    sup.unit_transportation_cost_max =  $("#supplier_3_unit_transportation_cost_max").val();
    sup.fixed_transportation_cost_min =  $("#supplier_3_fixed_transportation_cost_min").val();
    sup.fixed_transportation_cost_max =  $("#supplier_3_fixed_transportation_cost_max").val();
    sup.processing_time_min =  $("#supplier_3_processing_time_min").val();
    sup.processing_time_med =  $("#supplier_3_processing_time_med").val();
    sup.processing_time_max =  $("#supplier_3_processing_time_max").val();
    sup.order_processing_time_min =  $("#supplier_3_order_processing_time_min").val();
    sup.order_processing_time_max =  $("#supplier_3_order_processing_time_max").val();
    sup.transportation_time_min =  $("#supplier_3_transportation_time_min").val();
    sup.transportation_time_max =  $("#supplier_3_transportation_time_max").val();

    suppliers.push(sup);

    return suppliers;
}

function sendSimulation(){
    data = new Object();
    data.type = "with_data";
    data.initial_inventory_min = $("#s_item_initial_inventory_min").val();
    data.initial_inventory_max = $("#s_item_initial_inventory_max").val();
    data.reorder_point_min = $("#s_item_reorder_point_min").val();
    data.reorder_point_max = $("#s_item_reorder_point_max").val();
    data.order_upto_level_min = $("#s_item_order_upto_level_min").val();
    data.order_upto_level_max = $("#s_item_order_upto_level_max").val();
    data.dcs = getDCs();
    data.suppliers = getSuppliers();
     

    

    swal({
        title: "Simulación de inventario",
        text: "Esta acción podría tomar varios minutos. ¿Desea continuar?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "SI",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        cancelButtonText: "NO"
    },
    function () {
        AWApi.get('{{ url('api/optimization') }}?'+ $.param(data), function(response){
            $("#data").html("");
            $("#data").append(JSON.stringify({"cromosome": response.data.cromosome}));
            $("#data").append("<br>");
            $("#data").append(JSON.stringify({"evaluation":response.data.evalution}));
            $("#data").append("<br>");
            $("#data").append(JSON.stringify({"historical":response.data.historical}, undefined, 4));
            swal.close();

            /*Graphic Creation*/
            labels = [];
            for (var i = 0; i < response.data.evaluations.length; i++) {
                labels.push(i);
            }
            data = {
                labels: labels,
                series: [response.data.min_max[0], 
                        response.data.min_max[1],
                        response.data.min_max[2]]

            };

            options = {
                fullWidth: true,
            };

            new Chartist.Line('.ct-chart', data, options);
            
            /*Tables*/

            addSimulationTables(response.data);
        });
    });
}   

function addSimulationTables(data){
    if (tbl_one != null){
        tbl_one.destroy();
    }
    tbl_one = $('#tbl_one').DataTable( {
                "responsive": true,
                "processing": true,
                "lengthChange": true,
                "dom": '<"pull-right"l><tr><ip>',
                "data": data.table_one,
                "paging": true,
                "ordering": false,
                "columns": [
                    { "title": "Generación"
                    },     
                    { "title": "Cromosoma",
                    },
                    { "title": "Evaluación",
                    }
                ]
    });

    if (tbl_two != null){
        tbl_two.destroy();
    }
    tbl_two = $('#tbl_two').DataTable( {
                "responsive": true,
                "processing": true,
                "lengthChange": true,
                "dom": '<"pull-right"l><tr><ip>',
                "data": data.table_two,
                "paging": true,
                "ordering": false,
                "columns": [
                    { "title": "Generación"
                    },     
                    { "title": "Promedio",
                    },
                    { "title": "Min",
                    },
                    { "title": "Max",
                    },
                ]
    });
}

function simulate(){
    AWApi.get('{{ url('api/optimization') }}', function(response){
        $("#data").html("");
        $("#data").append(JSON.stringify({"cromosome": response.data.cromosome}));
        $("#data").append("<br>");
        $("#data").append(JSON.stringify({"evaluation":response.data.evalution}));
        $("#data").append("<br>");
        $("#data").append(JSON.stringify({"historical":response.data.historical}, undefined, 4));
    });
}

function showItemsModal(){
    $("#modal_add_item").modal('show');
}

function initAddItemsTable(){

    tbl_add_orderitems = $('#grid_add_orderitems').DataTable( {
        "language": {
            "url": "{{ asset('json/Spanish.json') }}"
        },
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "lengthChange": true,
        "lengthMenu": [ 5, 10, 15, 25 ],
        "dom": '<"pull-right"l><tr><ip>',
        "searching": false,
        "ajax": function (data, callback, settings) {
             var filters = new Object();
            
            filters.all = $("#item_search").val();
            data.filters = filters;
            data.allsearch = 1;
            AWApi.get('{{ url('/api/items' ) }}?' +$.param(data), function (response) {
                callback({
                    recordsTotal: response.data.recordsTotal,
                    recordsFiltered: response.data.recordsFiltered,
                    data: response.data.items
                });
            });
        },
        "paging": true,
        "ordering": true,
        "columnDefs": [
            {
                "width": "72px", "targets": 0
            }
        ],
        "columns": [
             { "data": "id",
                className:'text-center',
                render: function ( data, type, full, meta ) {
                    img_url = "{{ asset('img/defaults/box.png') }}";
                    if(full.image_route != ""){
                        img_url = "{{asset('uploads/items')}}/"+ full.image_route;
                        console.log(img_url);
                    }
                    return '<img src="'+img_url+'" class="rounded" style="height: 70px;width: 70px;">';
                }
            },
            { "data": "id",
                render: function ( data, type, full, meta ) {
                    
                    html = "<strong>" + "Nombre:" + "</strong>";

                    if(full.name){
                        html += "<a class='pull-right'";
                        html += 'href="{{url('items')}}/'+data+'" >';

                        if(full.name.length > 40){
                          name = full.name.substring(0,40) + "...";
                        }else{
                          name = full.name;
                        }
                        html += name + "</a> <br>"
                    }else{
                        html +=  "<span class='pull-right'> - </span> <br> ";
                    }
                    html += "<strong>" + "Categoría:" + "</strong>";
                    if(full.category){
                       html += "<span class='pull-right'>" +full.category+ "</span> <br>";
                    }else{
                      html += "<span class='pull-right'>" +"Ninguna"+ "</span> <br>";
                    }
                    html += "<strong>" + "Marca:" + "</strong>";
                    if(full.brand){
                      html += "<span class='pull-right'>" +full.brand+ "</span> <br>";
                    }else{
                      html += "<span class='pull-right'>" +"Ninguna"+ "</span> <br>";
                    }

                    if(full.custom_sku){
                        html += "<strong>" + "Custom SKU:" + "</strong>";
                        html += "<span class='pull-right'>" +full.custom_sku+ "</span> <br>";
                    }
                    if(full.ean){
                        html += "<strong>" + "EAN:" + "</strong>";
                        html += "<span class='pull-right'>" +full.ean+ "</span> <br>";
                    }
                    if(full.upc){
                        html += "<strong>" + "UPC:" + "</strong>";
                        html += "<span class='pull-right'>" +full.upc+ "</span> <br>";
                    }

                    if(full.attributes.length > 0){
                      for (var i = 0; i < full.attributes.length; i++) {
                        html += "<strong>" + full.attributes[i].name + "</strong>";
                        html += "<span class='pull-right'>" +full.attributes[i].value+ "</span> <br>";
                      }
                    }

                    return html;
                }
            },
            { "data": "id",
                render: function ( data, type, full, meta ) {
                  stock = 0;
                    for (var i = 0; i < full.stock.length; i++) {
                        if(full.stock[i].location == "TOTAL"){
                            stock = full.stock[i].amount;
                        }
                    }
                    html = "<div>";
                    html += '<strong style="color: #3c8dbc; font-size: 20px;padding-right: 3px;">' + stock + '</strong> '+full.uom_plural;
                    html += '</div>'
                    return html;

                }
            },
            {
              "data": "id",
              className: 'text-center',
               render: function ( data, type, full, meta ) {
                html = '<button class="btn btn-primary btn-sm" onclick="addSimulationItem('+data+')"> Agregar Producto</button>'
                return html;
              }
            }
            
        
        ]
    });

    tbl_add_orderitems_minimal = $('#grid_add_orderitems_minimal').DataTable( {
        "language": {
            "url": "{{ asset('json/Spanish.json') }}"
        },
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "lengthChange": true,
        "lengthMenu": [ 5, 10, 15, 25 ],
        "dom": '<"pull-right"l><tr><ip>',
        "searching": false,
        "ajax": function (data, callback, settings) {
            var filters = new Object();
            
            filters.all = $("#item_search").val();
            data.filters = filters;
            data.allsearch = 1;

            AWApi.get('{{ url('/api/items' ) }}?'+$.param(data), function (response) {


                callback({
                    recordsTotal: response.data.recordsTotal,
                    recordsFiltered: response.data.recordsFiltered,
                    data: response.data.items
                });
            });
        },
        "paging": true,
        "ordering": true,
        "columnDefs": [
            {
                "width": "90px", "targets": 2
            }
        ],
        "columns": [
            { "data": "id",
                render: function ( data, type, full, meta ) {
                    html = "";
                    if(full.name){
                        html += "<a ";
                        html += 'href="{{url('items')}}/'+data+'" >';

                        if(full.name.length > 40){
                          name = full.name.substring(0,40) + "...";
                        }else{
                          name = full.name;
                        }
                        html += name + "</a> <br>"
                    }else{
                        html +=  "<span class='pull-right'> - </span> <br> ";
                    }

                    if(full.manufacturer_sku || full.custom_sku || full.ean || full.upc){
                        if(full.manufacturer_sku){
                            html += "[M.SKU: "+ full.manufacturer_sku+"] ";
                        }
                        if(full.custom_sku){
                            html += "[C.SKU: "+ full.custom_sku+"] ";
                        }
                        if(full.ean){
                            html += "[EAN: "+ full.ean+"] ";
                        }
                        if(full.upc){
                            html += "[C.UPC: "+ full.upc+"] ";
                        }
                    }else{
                        html += "[S.ID: "+ full.id+"] ";
                    }
                    
                    
                    //console.log(full);
                    return html;
                }
            },     

            { "data": "id",
                render: function ( data, type, full, meta ) {
                    html = "";
                    if(full.category){
                        html += "<dd>" + full.category + "</dd>";
                    }
                    else{
                        html += "<dd>" + "Ninguna" + "</dd>";
                    }
                    return html;
                }
            },
            { "data": "id",
                render: function ( data, type, full, meta ) {
                  stock = 0;
                    for (var i = 0; i < full.stock.length; i++) {
                        if(full.stock[i].location == "TOTAL"){
                            stock = full.stock[i].amount;
                        }
                    }
                    html = "<div>";
                    html += '<strong style="color: #3c8dbc; font-size: 20px;padding-right: 3px;">' + stock + '</strong> '+full.uom_plural;
                    html += '</div>'
                    return html;

                }
            },
            {
              "data": "id",
              className: 'text-center',
               render: function ( data, type, full, meta ) {
                html = '<button class="btn btn-primary btn-sm" onclick="addSimulationItem('+data+')"> Agregar Producto</button>'
                return html;
              }
            }
           
        ]
    });

    $("#item_search").keyup(function(event) {
       tbl_add_orderitems.ajax.reload();
       tbl_add_orderitems_minimal.ajax.reload();
    });

    $("#normalview_btn").click(function(event) {
        view = 0;

        $("#normal_view").show();
        $("#compact_view").hide();
        tbl_add_orderitems.ajax.reload();

        $("#normalview_btn").addClass('active');
        $("#compactview_btn").removeClass('active');
    });
    $("#compactview_btn").click(function(event) {
        view = 1;
        $("#compact_view").show();
        $("#normal_view").hide();
        tbl_add_orderitems_minimal.ajax.reload();
       
       $("#compactview_btn").addClass('active');
       $("#normalview_btn").removeClass('active');               
       
    });  
}

function addSimulationItem(id){
    $("#step_2").show();
    AWApi.get('{{ url("api/items") }}/'+id, function(response){
        $(".modal").modal('hide');
        console.log(response);
        img_url = "{{asset('uploads/items')}}/"+ response.data.images[0].filename;
        $("#simulation_item_image").attr('src', img_url);
        $("#simulation_item_image").attr('width', '150');
        item_url = "{{ url('items') }}/" +  response.data.item.id;
        $("#simulation_item_title").html('<a href="'+item_url+'">'+ response.data.item.name + '</a>');
        html = "";

        html += "<div class='col-xs-6'>";
        html += "<div class='row'>";
        html += "Punto de reórden:";
        html += "</div>";
        html += "<div class='row'>";
        html += "Ordenar hasta:";
        html += "</div>";
        html += "</div>";
        html += "<div class='col-xs-6'>";
        html += "<div class='row'>";
        html += response.data.item.reorder_point;
        html += "</div>";
        html += "<div class='row'>";
        html += response.data.item.order_up_to;
        html += "</div>";
        html += "</div>";
        $("#simulation_item_body").html(html);
        html = "";
        html += "<div class='row'>";
        html += "<div class='col-xs-6'>";
        html += "Item";
        html += "</div>";
        html += "<div class='col-xs-2'>";
        html += "Cantidad";
        html += "</div>";
        html += "<div class='col-xs-2'>";
        html += "Punto de Reorden";
        html += "</div>";
        html += "<div class='col-xs-2'>";
        html += "Ordenar Hasta";
        html += "</div>";
        html += "</div>";

        for (var i = 0; i < response.data.bom_items.bom.length; i++) {
            html += "<div class='row'>";
            html += "<div class='col-xs-6'>";
            html += '<a href="{{url('items')}}/'+response.data.bom_items.items[i].id+'">';
            html += response.data.bom_items.items[i].name;
            html += '</a>';
            html += "</div>";
            html += "<div class='col-xs-2'>";
            html += response.data.bom_items.bom[i].amount;
            html += "</div>";
            html += "<div class='col-xs-2'>";
            html += response.data.bom_items.items[i].reorder_point;
            html += "</div>";
            html += "<div class='col-xs-2'>";
            html += response.data.bom_items.items[i].order_up_to;
            html += "</div>";
            html += "</div>";
        }


        $("#simulation_item_bom").html(html);
    });
}

function showStep2(){
    $("#step_2").show();

}
</script>
@endsection	