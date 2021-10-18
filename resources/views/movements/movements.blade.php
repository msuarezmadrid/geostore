@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-bar-chart" style="padding-right: 5px;"></i> 
        Resumen de movimientos
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Resumen</li>
      </ol>
    </section>
    
<section class="content">
<div class="row">
    <div class="col-xs-12">
        
        <div class="box box-solid box-primary flat box-xs">
            <div class="box-header" style="padding-top: 4px; padding-bottom: 4px;">
                <h6 class="box-title" style="padding-top: 6px;"> <i class="fa fa-archive" style="padding-right: 5px;"></i> Ordenes por Almacén</h6>
                <select name="" id="total_orders_by_location" class="form-control pull-right" style="width: 30%;"></select>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class=" col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3 id="approved-orders-num">0</h3>

                          <p><strong><h4>Ordenes Aprobadas</h4></strong></p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-check"></i>
                        </div>
                        <div class="inner" >
                          Últimas Ordenes aprobadas:
                        </div>

                        <div class="inner" id="approved-orders-inner">
                            
                        </div>
                        
      

                      </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-yellow">
                         <div class="inner">
                          <h3 id="pending-orders-num">0</h3>
                          <p><strong><h4>Ordenes Pendientes</h4></strong></p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-exclamation"></i>
                        </div>
                        <div class="inner">
                          Últimas Ordenes pendientes:
                        </div>
                        <div class="inner" id="pending-orders-inner">

                        </div>

                      </div>
                    </div>
                    <!-- ./col -->
                    
                </div>
            </div>
    
        </div>
    </div>

    <div class="col-xs-12">
        
        <div class="box box-solid box-primary flat box-xs">
            <div class="box-header" style="padding-top: 4px; padding-bottom: 4px;">
                <h6 class="box-title" style="padding-top: 6px;"> <i class="fa fa-bookmark" style="padding-right: 5px;"></i> Ordenes por Tipo de Movimiento</h6>
                <select name="" id="total_orders_by_type" class="form-control pull-right" style="width: 30%;"> 
                  <option value="all"> TODAS LAS ORDENES</option>
                  <option value="sales"> ORDENES DE SALIDA</option>
                  <option value="purchases"> ORDENES DE ENTRADA</option>
                  <option value="transfers"> TRANSFERENCIAS</option>
                  <option value="adjustments"> AJUSTES DE INVENTARIO</option>
                </select>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class=" col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3 id="approved-orders-num-type">0</h3>

                          <p><strong><h4>Ordenes Aprobadas</h4></strong></p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-check"></i>
                        </div>
                        <div class="inner">
                          Últimas Ordenes aprobadas:
                        </div>

                        <div class="inner" id="approved-orders-inner-type">
           
                        </div>
                        
      

                      </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-yellow">
                         <div class="inner">
                          <h3 id="pending-orders-num-type">0</h3>
                          <p><strong><h4>Ordenes Pendientes</h4></strong></p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-exclamation"></i>
                        </div>
                        <div class="inner">
                          Últimas Ordenes pendientes:
                        </div>
                        <div class="inner" id="pending-orders-inner-type">
                        </div>

                      </div>
                    </div>
                    <!-- ./col -->
                    
                </div>
            </div>
    
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


<script>
    $(document).ready(function (e) {
      
      AWApi.get('{{ url('/api/locations') }}',function (response) {
          $("#total_orders_by_location").empty();
          $('<option />', {value: 0, text: "TODOS LOS ALMACENES" }).appendTo($("#total_orders_by_location"));
          locationsData = response.data.locations;           
          for (var i = 0; i < response.data.tree.length; i++) {
              name = "";
              for (var j = 0; j < response.data.tree[i].level -1; j++) {
                name += "";
              }
              name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
              $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#total_orders_by_location"));
          }
      });

      AWApi.get('{{ url('/api/dashboards') }}?type=total_orders_by_location&id=0',function (response) {
          $("#approved-orders-num").text(response.data.approved);
          $("#pending-orders-num").text(response.data.pending);
      });

      AWApi.get('{{ url('/api/dashboards') }}?type=total_orders_by_type&id=all',function (response) {
          $("#approved-orders-num-type").text(response.data.approved);
          $("#pending-orders-num-type").text(response.data.pending);
      });

      AWApi.get('{{ url('/api/dashboards') }}?type=last_orders_by_location&id=0',function (response) {
          html = "";
          for (var i = 0; i < response.data.approved.length; i++) {
            html += '<i class="fa fa-check" style="padding-right: 5px;"></i>';
            if(response.data.approved[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.approved[i].date + " - [" + response.data.approved[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#approved-orders-inner").html(html);

          html = "";
          for (var i = 0; i < response.data.pending.length; i++) {
            html += '<i class="fa fa-exclamation" style="padding-right: 5px;"></i>';
            if(response.data.pending[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.pending[i].date + " - [" + response.data.pending[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#pending-orders-inner").html(html);

      });

      AWApi.get('{{ url('/api/dashboards') }}?type=last_orders_by_type&id=all',function (response) {
          html = "";
          for (var i = 0; i < response.data.approved.length; i++) {
            html += '<i class="fa fa-check" style="padding-right: 5px;"></i>';
            if(response.data.approved[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.approved[i].date + " - [" + response.data.approved[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#approved-orders-inner-type").html(html);

          html = "";
          for (var i = 0; i < response.data.pending.length; i++) {
            html += '<i class="fa fa-exclamation" style="padding-right: 5px;"></i>';
            if(response.data.pending[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.pending[i].date + " - [" + response.data.pending[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#pending-orders-inner-type").html(html);

      });

      $("#total_orders_by_location").change(function(event) {
        AWApi.get('{{ url('/api/dashboards') }}?type=total_orders_by_location&id='+$("#total_orders_by_location").val() ,function (response) {
          $("#approved-orders-num").text(response.data.approved);
          $("#pending-orders-num").text(response.data.pending);
      });

        AWApi.get('{{ url('/api/dashboards') }}?type=last_orders_by_location&id='+$("#total_orders_by_location").val(),function (response) {
          html = "";
          for (var i = 0; i < response.data.approved.length; i++) {
            html += '<i class="fa fa-check" style="padding-right: 5px;"></i>';
            if(response.data.approved[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.approved[i].date + " - [" + response.data.approved[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#approved-orders-inner").html(html);

          html = "";
          for (var i = 0; i < response.data.pending.length; i++) {
            html += '<i class="fa fa-exclamation" style="padding-right: 5px;"></i>';
            if(response.data.pending[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.pending[i].date + " - [" + response.data.pending[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#pending-orders-inner").html(html);

      });
      });

      
      $("#total_orders_by_type").change(function(event) {
        AWApi.get('{{ url('/api/dashboards') }}?type=total_orders_by_type&id='+$("#total_orders_by_type").val() ,function (response) {
          $("#approved-orders-num-type").text(response.data.approved);
          $("#pending-orders-num-type").text(response.data.pending);
      });
        AWApi.get('{{ url('/api/dashboards') }}?type=last_orders_by_type&id='+$("#total_orders_by_type").val(),function (response) {
          html = "";
          for (var i = 0; i < response.data.approved.length; i++) {
            html += '<i class="fa fa-check" style="padding-right: 5px;"></i>';
            if(response.data.approved[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.approved[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.approved[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.approved[i].date + " - [" + response.data.approved[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#approved-orders-inner-type").html(html);

          html = "";
          for (var i = 0; i < response.data.pending.length; i++) {
            html += '<i class="fa fa-exclamation" style="padding-right: 5px;"></i>';
            if(response.data.pending[i].movement_type == "sales"){
              html += '<a href="sales/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "transfers"){
              html += '<a href="transfers/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "adjustments"){
              html += '<a href="adjustments/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            if(response.data.pending[i].movement_type == "purchases"){
              html += '<a href="purchases/'+response.data.pending[i].order_id+'" class="inner" style="color: white;"><u>';
            }
            html += response.data.pending[i].date + " - [" + response.data.pending[i].order_code + "]";
            html += '</u></a><br>';
          }

          $("#pending-orders-inner-type").html(html);

      });
      });

    });
   
</script>
@endsection 

