@extends('layouts.master')

@section('css')
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-refresh" style="padding-right: 5px;"></i>
        Ajustes de Inventario:
        <span id="order_code"></span>
        
        <button class="btn btn-primary btn-md" id="publishOrder-btn" onclick="publishOrder('{{$id}}')"><i class="fa fa-mail-forward" style="padding-right: 5px;"></i>Publicar</button>
        <button class="btn btn-success btn-md" id="approveOrder-btn" onclick="approveOrder('{{$id}}')"><i class="fa fa-thumbs-o-up" style="padding-right: 5px;"></i>Aprobar</button>
      
      </h1>
      
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}">Inicio</a></li>
        <li><a href="{{url('adjustments')}}">Ajustes de Inventario</a></li>
        <li class="active" id="breadcrumb_active"></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary box-solid flat">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-info" style="padding-right: 5px;"></i>Información General</h3>
                <div class="pull-right">
                  
                  <small id="movement_status" class="label label-default">Borrador</small>
                </div>
                
              </div>

              <div class="box-body" id="form">
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="code">Codigo</label>
                          <input type="text" class="form-control" id="code" placeholder="Nombre">
                      </div>
                  </div>

                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="date">Fecha</label>
                          <input type="text" class="form-control" id="date" placeholder="Fecha">
                      </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="location_from_id">Almacén</label>
                          <select class="form-control" id="location_id" ></select>   
                      </div>
                  </div>
                   <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="reason">Causa</label>
                          <input type="text" class="form-control" id="reason" ></input>   
                      </div>
                  </div>
                </div>
              </div>  
              <div class="box-footer">
                  <button type="button" class="btn btn-primary pull-right" id="editBtn" >Guardar</button>
              </div>    
            </div>
          </div>

          <div class="col-md-12">
            <div class="box box-primary box-solid flat">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i>Listado de Productos</h3>
              </div>

              <div class="box-body" >
                <div class="col-xs-12" style="padding-bottom: 4px;">
                  <button id="add_item_btn" class="btn btn-success btn-xs" onclick="showAddProduct()"><i class="fa fa-plus" style="padding-right: 5px;"></i> Añadir Producto</button>

                </div>
                
                <div class="col-xs-12">
                  <table id="stock" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>Imagen</th>
                      <th>Detalles</th>
                      <th>Cantidad</th>
                      <th>Precio Unitario</th>
                      <th>Stock</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>  
              <div class="box-footer">
                  <button type="button" class="btn btn-primary pull-right" id="saveDetailsBtn" >Guardar</button>
              </div>    
            </div>
          </div>      
        </div>
    </section>
    <!-- /.content -->

    

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
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
           
          </div>
        </div>
      </div>
    </div>
@endsection

@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    
	
	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>
      uoms = [];
      order_items = null;
      tbl_add_orderitems = null;
      tbl_orderitems = null;
      var location_id = null;
      signal = 1;
      let itemMax = 1000;
     
       $(document).ready(function() {

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

            AWApi.get('{{url('api/unit_of_measures')}}', function (response) {
              uoms = response.data.uoms;

            });
            AWApi.get('{{ url('/api/locations') }}',function (response) {
                $("#location__id").empty();
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {
                      name += "";
                    }
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#location_id"));
                }


                AWApi.get('{{ url('/api/adjustments') }}/{{ $id }}', loadForm);
              });

           
            $("#date").datepicker({
                format: 'dd/mm/yyyy'
            });

            fieldValues = [];

            (function setFieldValues() {
                fieldValues['code'] = 'code';
                fieldValues['date'] = 'date';
                fieldValues['location_id'] = 'location_id';
                fieldValues['reason'] = 'reason';
               

            })();    

            tbl_orderitems = $('#stock').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/adjustments' ) }}/{{$id}}/items', function (response) {
                        order_items = response.data.items;
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.items
                        });
                    });
                },
                "paging": false,
                "ordering": false,
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
                    {
                      "data": "adjustment_item_id",
                      className: 'text-center',
                       render: function ( data, type, full, meta ) {

                        
                          console.log(full);
                          for(var i=0; i<uoms.length;i++){
                            if(full.unit_of_measure_id == uoms[i].id){
                              default_uom = uoms[i].id;
                              default_measure = uoms[i].measure;
                            }
                          }
                          console.log(default_uom);
                          console.log(default_measure);

                          html = '<div class="form-group">';
                          html = '<div class="input-group">';
                          html += '<span class="input-group-btn">';
                          if(full.quantity >= 0){
                            html += '<button class="btn btn-success btn-flat" type="button" id="signal_quantity_'+data+'" onclick=changeSignal('+data+');>';
                            html += '+</button>';

                          }else{
                            html += '<button class="btn btn-danger btn-flat" type="button" id="signal_quantity_'+data+'" onclick=changeSignal('+data+');>';
                            html += '-</button>';
                            full.quantity = full.quantity*-1;

                          }
                          
                          
                          html += '</span>';
                          html += '<input class="form-control" type="text" placeholder="0" style="width: 70px;" id="quantity_'+data+'" value="'+full.quantity+'" oninput="this.value = (this.value)">';
                          html += '<select class="form-control" id="uom_id_'+data+'" style="width: 185px; padding-left: 2px;">';
                          html += '</div>';

                          for (var i = 0; i < uoms.length; i++) {
                            if(default_measure == uoms[i].measure){
                              if(default_uom == uoms[i].id){
                                html += '<option selected="selected" value="'+uoms[i].id+'">'+ "("+uoms[i].abbr+") - "+uoms[i].plural + '</option>';
                              }
                              
                            }
                            
                          }
                          html += '</select>';
                          html += '</div>';
                        return html;
                        
                      }
                    },
                    {
                      "data": "adjustment_item_id",
                       className: "text-center",
                       render: function ( data, type, full, meta ) {
                        return '<div class="input-group"><input  id="price_'+data+'" type="number" class="form-control" value="'+full.price+'" /></div>' ;
                      }  
                    },
                    {
                      "data": "adjustment_item_id",
                      className: "text-center",
                      render: function ( data, type, full, meta ) {
                        return '<button type="button" class="btn btn-primary">'+full.stock+'</button>';
                      }
                    },
                    {"data": "adjustment_item_id",
                      className: "text-center",
                      render: function ( data, type, full, meta ) {

                        var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delOrderItem("+data+");>";
                        del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                        return full.adjustment_status == 3 ? "":"<div class='btn-group'>"+del+"</div>";
                      }
                    }
                        
                ]
            });

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
                    AWApi.get('{{ url('/api/items' ) }}?' + $.param(data), function (response) {
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
                                if(full.stock[i].location_id == $("#location_id").val()){
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
                        html = '<button class="btn btn-primary btn-sm" onclick="addOrderItem('+data+')"> Agregar Producto</button>'
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
                                if(full.stock[i].location_id == $("#location_from_id").val()){
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
                        html = '<button class="btn btn-primary btn-sm" onclick="addOrderItem('+data+')"> Agregar Producto</button>'
                        return html;
                      }
                    }
                   
                ]
            });


			// Extra Data (combos) --------------
             

            $("#goBack").click(function(){
              window.location.href = "{{ url( '/adjustments' ) }}";
            });

            $("#item_search").keyup(function(event) {
               tbl_add_orderitems.ajax.reload();
            });
          
            $("#editBtn").click(function(){
              var data = new FormData();
                      
              data.append('code', $('#code').val());
              data.append('location_id', $('#location_id').val());
              data.append('reason', $('#reason').val());
              location_id = $('#location_id').val();
              data.append('date', moment($("#date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD'));
              AWApi.put('{{url('api/adjustments')}}/{{$id}}', data, function (response) {
                submit("", response, "Actualizar Ajuste de Inventario");
              });
            });

            $("#saveDetailsBtn").click(function(){
              let blockAlert = checkPurchaseSize();
              if(!blockAlert) {
                swal({
                  title: "Guardar detalles de movimiento",
                  text: "¿Esta seguro de realizar esta acción?'",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "SI",
                  closeOnConfirm: false,
                  closeOnCancel: true,
                  cancelButtonText: "NO"
                }, function(confirm) {
                  if(confirm) {
                    swal({
                      title: "Guardando detalles...",
                      type: "info",
                      showCancelButton: false,
                      showConfirmButton: false,
                      icon: "{{asset('img/pos/loading_spinner.gif')}}",
                    });
                    for (var i = 0; i < order_items.length; i++) {
                      var data = new FormData();
                      if($("#signal_quantity_"+order_items[i].adjustment_item_id).hasClass('btn-danger')){
                        signalq = -1;
                      }else{
                        signalq = 1;
                      }
                      data.append('quantity', signalq*parseFloat($("#quantity_"+order_items[i].adjustment_item_id).val()));
                      data.append('unit_of_measure_id', $("#uom_id_"+order_items[i].adjustment_item_id).val());
                      data.append('unitary_price', $("#price_"+order_items[i].adjustment_item_id).val());
                      AWApi.put('{{url('api/adjustment_items')}}/'+order_items[i].adjustment_item_id, data, function (response) {
                        submit("", response, "Actualizar Listado de Productos");
                      });
                    }
                  }
                })
              } else {
                swal('No se puede realizar esta acción.', 
                'No se pueden guardar los detalles porque el total de productos que se desea añadir en esta orden excede los ' + itemMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + '.', 
                'error');
              }
              console.log(order_items);
            });
        });

      function showAddProduct() {
        $("#"+"modal_add_item").modal("show");
        tbl_add_orderitems.ajax.reload();
        tbl_add_orderitems_minimal.ajax.reload();
      }
      function changeSignal(id) {
        console.log(id);
        if(signal == 1){
          console.log("+");
          $("#signal_quantity_"+id).removeClass('btn-success');
          $("#signal_quantity_"+id).addClass('btn-danger');
          $("#signal_quantity_"+id).html("-");
          signal = -1;
        }else{
          $("#signal_quantity_"+id).addClass('btn-success');
          $("#signal_quantity_"+id).removeClass('btn-danger');
          $("#signal_quantity_"+id).html("+");
          signal = 1;
        }
      }

      function delOrderItem(id) {
        AWApi.delete('{{url('api/adjustment_items')}}/'+id,function (response) {
          submit("", response, "Eliminar Producto");
          tbl_orderitems.ajax.reload();
        });
      }
      function addOrderItem(item_id) {
        // $("#"+"modal_add_item").modal("hide");
        

        AWApi.get('{{url('api/items')}}/' + item_id, function (response) {
          data = new FormData();

          data.append('adjustment_id', '{{$id}}');
          data.append('item_id', item_id);
          data.append('quantity', 0);
          data.append('unit_of_measure_id', response.data.item.unit_of_measure_id);
          AWApi.post('{{url('api/adjustment_items')}}', data, function (response) {
            tbl_orderitems.ajax.reload();
            swal('¡Producto Agregado!', "Producto Agregado de forma exitosa", "success");
          });
          
        });
        
      }

      function publishOrder(id) {
            swal({
                title: "Publicar Ajuste de Inventario",
                text: "¿Esta seguro de realizar esta acción?'",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "SI",
                closeOnConfirm: true,
                cancelButtonText: "NO"
            },
            function () {
                data = new FormData();

                data.append('movement_status_id', 2);
                AWApi.put('{{ url('/api/adjustments') }}/' + id, data, function(datas){
                    submit("", datas, "Publicar Ajuste de Inventario");
                    AWApi.get('{{ url('/api/adjustments') }}/{{ $id }}', loadForm);
                });
            });
            
        }

        function checkPurchaseSize() {
          let totalItems = 0
          var blockAlert = false;
          for (var i = 0; i < order_items.length; i++) {
            totalItems += parseFloat($("#quantity_"+order_items[i].adjustment_item_id).val());
            if(totalItems > itemMax) {
              blockAlert = true;
              break;
            }
          }
          return blockAlert;
        }

        function approveOrder(id) {
          
          let blockAlert = checkPurchaseSize();
          if(!blockAlert) { 
            swal({
                title: "Aprobar Ajuste de Inventario",
                text: "¿Esta seguro de realizar esta acción?'",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "SI",
                closeOnConfirm: false,
                closeOnCancel: true,
                cancelButtonText: "NO"
            },
            function (confirm) {
              
              if(confirm) {
                data = new FormData();
                swal({
                      title: "Aprobando Orden...",
                      type: "info",
                      showCancelButton: false,
                      showConfirmButton: false,
                      text: "Se está aprobando la orden, esto puede tardar varios segundos",
                      icon: "{{asset('img/pos/loading_spinner.gif')}}",
                    });
                data.append('movement_status_id', 3);
                AWApi.put('{{ url('/api/adjustments') }}/' + id, data, function(datas){
                    submit("", datas, "Aprobar Ajuste de Inventario");
                    AWApi.get('{{ url('/api/adjustments') }}/{{ $id }}', loadForm);
                });
              }

            });
          } else {
            swal('No se puede realizar esta acción.', 
            'No se puede aprobar la orden porque el total de productos que se desea aprobar en esta orden excede los ' + itemMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + '.', 
            'error');
          }
        }


        function loadForm(datas) {
                
                location_id = datas.data.location__id;
                $('#code').val(datas.data.code);
                $('#order_code').text(datas.data.code);
                $('#breadcrumb_active').text(datas.data.code);
                $("#reason").val(datas.data.reason);
                $("#location_id").val(datas.data.location_id);
                $("#date").val(moment(datas.data.date, 'YYYY-MM-DD').format('DD/MM/YYYY'));

                if(datas.data.movement_status_id == 3){
                  $("#approveOrder-btn").addClass('disabled').hide();
                  $("#publishOrder-btn").addClass('disabled').hide();
                  $("#movement_status").addClass('label-success').text('APROBADA');
                  $("#add_item_btn").hide();
                  $("#editBtn").hide();
                  $("#saveDetailsBtn").hide();
                  $("#add_item").hide();
                }else if(datas.data.movement_status_id == 2){
                  $("#approveOrder-btn").removeClass('disabled').show();
                  $("#publishOrder-btn").addClass('disabled').hide();
                  $("#movement_status").addClass('label-warning').text('PUBLICADA');

                }else if(datas.data.movement_status_id == 1){
                  $("#approveOrder-btn").addClass('disabled').hide();
                  $("#publishOrder-btn").removeClass('disabled').show();
                  $("#movement_status").addClass('label-default').text('BORRADOR');

                }
            }

        function submit(id,data, message)
        {
            var count = 0;
            if (id != ""){
                AWValidator.clean(id);
            }
            
   
            for (x in data.data.errors)
            {
                if(data.data.errors.unauthorized){
                    swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                } else if(data.data.errors.message){
                    swal("error", data.data.errors.message, "error");
                }
                else{
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                }
                count++;   
            }

            if (count == 0)
            {
                swal(message, "Información actualizada de forma exitosa", "success");
                
                tbl_orderitems.ajax.reload();
                

                
            }
     
        }

    </script>
@endsection

