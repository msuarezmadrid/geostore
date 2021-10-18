@extends('layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa" style="padding-right: 5px;"></i> Parámetros del Sistema
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa"></i> Inicio</a></li>
        <li class="active">Parámetros del Sistema</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                
            <div class="box box-primary box-xs flat">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa" style="padding-right: 5px;"></i>Listado</h3>
                </div> 
                <!-- /.box-header -->
                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="row">
                                <div class="form-group">
                                   <label class="col-sm-2 control-label">REDONDEO</label> 
                                   <div class="col-sm-8">
                                        <select class="form-control form-control-sm rnd_val">
                                            <option {{ $rounding->value == "DEFAULT" ? "selected" : ""}} value="DEFAULT">CERO MÁS CERCANO</option>
                                            <option {{ $rounding->value == "ROUND_DOWN" ? "selected" : ""}} value="ROUND_DOWN">REDONDEAR HACIA ABAJO</option>
                                        </select>
                                   </div>
                                   <div class="col-sm-2">
                                       <button type="button" onclick="updateConfig({{ $rounding->id }}, 'rnd')" class="btn btn-sm btn-danger">Actualizar</button>
                                   </div>   
                                </div>

                                <div class="form-group">
                                   <label class="col-sm-2 control-label">TEMA DE MODULO PREVENTA</label> 
                                   <div class="col-sm-8">
                                        <select class="form-control form-control-sm light_theme_val">
                                            <option {{ $light_theme->value == "0" ? "selected" : ""}} value="0">DARK</option>
                                            <option {{ $light_theme->value == "1" ? "selected" : ""}} value="1">LIGHT</option>
                                        </select>
                                   </div>
                                   <div class="col-sm-2">
                                       <button type="button" onclick="updateConfig({{ $light_theme->id }}, 'light_theme')" class="btn btn-sm btn-danger">Actualizar</button>
                                   </div>   
                                </div>

                                <div class="form-group">
                                   <label class="col-sm-2 control-label">IMPRESION DIRECTA MODULO PREVENTA</label> 
                                   <div class="col-sm-8">
                                        <select class="form-control form-control-sm print_direct_val">
                                            <option {{ $print_direct->value == "0" ? "selected" : ""}} value="0">NO</option>
                                            <option {{ $print_direct->value == "1" ? "selected" : ""}} value="1">SI</option>
                                        </select>
                                   </div>
                                   <div class="col-sm-2">
                                       <button type="button" onclick="updateConfig({{ $print_direct->id }}, 'print_direct')" class="btn btn-sm btn-danger">Actualizar</button>
                                   </div>   
                                </div>

                                <div class="form-group">
                                   <label class="col-sm-2 control-label">CANTIDAD DE PRODUCTOS EN MODULO PREVENTA / VENTA</label> 
                                   <div class="col-sm-4">
                                        <input type="number" class="form-control pos_item_qty_val" value={{ $pos_item_qty->value }}>
                                   </div>
                                   <label class="col-sm-4 control-label">UNA CANTIDAD MUY ALTA PUEDE AFECTAR EN LA VELOCIDAD DE AMBOS MODULOS</label> 
                                   <div class="col-sm-2">
                                       <button type="button" onclick="updateConfig({{ $pos_item_qty->id }}, 'pos_item_qty')" class="btn btn-sm btn-danger">Actualizar</button>
                                   </div>   
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">COPIA CEDIBLE PARA FACTURAS</label> 
                                    <div class="col-sm-8">
                                         <select class="form-control form-control-sm cedible">
                                             <option {{ $cedible->value == "0" ? "selected" : ""}} value="0">NO</option>
                                             <option {{ $cedible->value == "1" ? "selected" : ""}} value="1">SI</option>
                                         </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" onclick="updateConfig({{ $cedible->id }}, 'cedible')" class="btn btn-sm btn-danger">Actualizar</button>
                                    </div>   
                                 </div>

                                 <div class="form-group">
                                    <label class="col-sm-2 control-label">Tipo de Cierre de caja</label> 
                                    <div class="col-sm-8">
                                         <select @if($caja->id == -1) disabled @endif class="form-control form-control-sm caja">
                                             <option {{ $caja->value == "0" ? "selected" : ""}} value="0">DESGLOSE SIMPLE</option>
                                             <option {{ $caja->value == "1" ? "selected" : ""}} value="1">DESGLOSE DETALLADO</option>
                                         </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button @if($caja->id == -1) disabled @endif type="button" onclick="updateConfig({{ $caja->id }}, 'caja')" class="btn btn-sm btn-danger">Actualizar</button>
                                    </div>   
                                 </div>

                                 <div class="form-group">
                                    <label class="col-sm-2 control-label">Impresión Multiple</label> 
                                    <div class="col-sm-8">
                                        <input @if($multiprint->id == -1) disabled @endif type="text" class="form-control multiprint" value="{{ $multiprint->value }}">
                                        @if($multiprint->id == -1)
                                            <label class="control-label">CONFIGURACIÓN INACTIVA, SI SE REQUIERE UTILIZAR, POR FAVOR SOLICITAR ACTIVACIÓN POR PARTE DEL ADMINISTRADOR</label>
                                        @else
                                            <label class="control-label">DEFAULT: GENERA EL VALOR PREDEFINIDO DE ORDEN CON TITULO E ID, DELIMITADOR DE TITULOS: ;</label>
                                            <label class="control-label">PARA GENERAR MÁS VOUCHERS EN UNA ACCIÓN DE IMPRESIÓN SE DEBE INGRESAR EL TITULO DE CADA VOUCHER SEPARADOS POR EL DELIMITADOR</label> 
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <button @if($multiprint->id == -1) disabled @endif type="button" onclick="updateConfig({{ $multiprint->id }}, 'multiprint')" class="btn btn-sm btn-danger">Actualizar</button>
                                        <button @if($multiprint->id == -1) disabled @endif type="button" onclick="resetConfig('multiprint')" class="btn btn-sm">Reiniciar</button>
                                    </div>
                                 </div>
  

                                 <div class="form-group">
                                    <label class="col-sm-2 control-label">GENERAR BOLETA CON TARJETA DE CREDITO</label> 
                                    <div class="col-sm-8">
                                         <select @if($submitTicket->id == -1) disabled @endif  class="form-control form-control-sm submitTicket">
                                             <option {{ $submitTicket->value == "0" ? "selected" : ""}} value="0">NO</option>
                                             <option {{ $submitTicket->value == "1" ? "selected" : ""}} value="1">SI</option>
                                         </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button @if($submitTicket->id == -1) disabled @endif  type="button" onclick="updateConfig({{ $submitTicket->id }}, 'submitTicket')" class="btn btn-sm btn-danger">Actualizar</button>
                                    </div>   
                                 </div>
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
            <!-- /.box -->
        </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>
        function resetConfig(type) {
            if(type == 'multiprint') {
                $(".multiprint").val('DEFAULT');
            }
        }

        function updateConfig(id, type) {
            if(type == 'rnd') {
                let formData = new FormData();
                formData.append('value', $('.rnd_val').val());
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio de redondeo realizado', "success");
                });
            }

            if(type == 'light_theme') {
                let formData = new FormData();
                formData.append('value', $('.light_theme_val').val());
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio de tema realizado', "success");
                });
            }

            if(type == 'print_direct') {
                let formData = new FormData();
                formData.append('value', $('.print_direct_val').val());
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio en impresión realizado', "success");
                });
            }

            if(type == 'pos_item_qty') {
                let formData = new FormData();
                if ($('.pos_item_qty_val').val() < 1){
                    swal("Error", 'Cantidad no debe ser menor o igual a 0', "error");
                    return;
                }
                formData.append('value', (Math.round($('.pos_item_qty_val').val())));
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio en impresión realizado', "success");
                });
            }

            if(type == 'cedible') {
                let formData = new FormData();
                formData.append('value', $('.cedible').val());
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio en cedible realizado', "success");
                });
            }

            if(type == 'caja') {
                let formData = new FormData();
                formData.append('value', $('.caja').val());
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio en tipo de caja realizado', "success");
                });
            }

            if(type == 'multiprint') {
                let formData = new FormData();
                let multiprintVal = $('.multiprint').val().trim();
                if(multiprintVal.charAt(multiprintVal.length-1) == ';') {
                    multiprintVal = multiprintVal.substring(0, multiprintVal.length-1);
                }
                formData.append('value', multiprintVal);
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    $(".multiprint").val(multiprintVal);
                    swal("Correcto", 'Cambio en impresión de documentos realizada', "success");
                });
            }

            if(type == 'submitTicket') {
                let formData = new FormData();
                let submitVal = $('.submitTicket').val();
                formData.append('value', submitVal);
                AWApi.put('{{ url('api/configs')}}/'+id, formData, function(response){
                    swal("Correcto", 'Cambio en generación de boletas con credito realizada', "success");
                });
            }
        }
    </script>
@endsection