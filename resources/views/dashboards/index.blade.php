@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    
    <link rel="stylesheet" href="{{asset('css/multiple-select.css')}}">
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Indicadores Generales
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Indicadores General</li>
      </ol>
    </section>
	
	<section class="content">

        <div class="row">
            <div class="col-lg-4">
                <label for="stock_location_id">Almacén</label>
                <select name="locations" id="stock_location_id" class="form-control">
                    <option value="0">TODOS LOS ALMACENES</option>
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-xs-12"> 
                <span style="font-size: 20px;">NIVELES DE STOCK</span><br>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;"> 
            <div class="col-xs-12 col-md-6 col-lg-4">

                <div class="small-box bg-green">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="inner">
                        <span id="stock_items_ok" style="font-size: 32px; font-weight: bold;">73</span>
                        <span  style="font-size: 20px; font-weight: bold; margin-left: 5px;">productos</span>
                        <p>Stock Normal</p>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-4">
                <div class="small-box bg-yellow">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-warning"></i></span>
                    <div class="inner">
                        <span id="stock_items_low" style="font-size: 32px; font-weight: bold;">23</span>
                        <span  style="font-size: 20px; font-weight: bold; margin-left: 5px;">productos</span>
                        <p>Stock Bajo</p>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-4">
                <div class="small-box bg-red">
                    <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
                    <div class="inner">
                        <span id="stock_items_no_stock" style="font-size: 32px; font-weight: bold;">12</span>
                        <span  style="font-size: 20px; font-weight: bold; margin-left: 5px;">productos</span>
                        <p>Sin Stock</p>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-xs-12"> 
                <span style="font-size: 20px;">ORDENES</span><br>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;"> 
            <div class="col-xs-12 col-md-6 col-lg-4">

                <div class="small-box bg-green">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="inner">
                        <span id="stock_items_ok" style="font-size: 32px; font-weight: bold;">82</span> 
                        <p>Ordenes aprobadas</p>
                    </div>
                    <a href="#" class="small-box-footer">Ver más<i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-4">
                <div class="small-box bg-yellow">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-exclamation"></i></span>
                    <div class="inner">
                        <span id="stock_items_low" style="font-size: 32px; font-weight: bold;">9</span>  
                        <p>Ordenes pendientes</p>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-4">
                <div class="small-box bg-aqua">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                    <div class="inner">
                        <span id="stock_items_no_stock" style="font-size: 32px; font-weight: bold;">93</span>
                        <p>Total de ordenes</p>
                    </div>
                    <a href="#" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
                </div>
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

    <script>
        
    </script>
@endsection	