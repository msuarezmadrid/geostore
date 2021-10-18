@extends('layouts.master')

@section('css')
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-arrow-left" style="padding-right: 5px;"></i> 
        Orden de Entrada:
        <span id="order_code"></span>
        <button class="btn btn-primary btn-md" id="publishOrder-btn" onclick="publishOrder('{{$id}}')"><i class="fa fa-mail-forward" style="padding-right: 5px;"></i>Publicar</button>
        <button class="btn btn-success btn-md" id="approveOrder-btn" onclick="approveOrder('{{$id}}')"><i class="fa fa-thumbs-o-up" style="padding-right: 5px;"></i>Aprobar</button>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}">Inicio</a></li>
        <li><a href="{{url('purchases')}}">Ordenes de Entrada</a></li>
        <li class="active" id="breadcrumb_active">Orden de Entrada</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-primary box-solid flat">
              <div class="box-header">
                <h3 class="box-title"> <i class="fa fa-info" style="padding-right: 5px;"></i> Información General</h3>
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
                          <label for="supplier_id">Proveedor (Origen)</label>
                          <select class="form-control" id="supplier_id" ></select>   
                      </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="location_id">Almacén (Destino)</label>
                          <select class="form-control" id="location_id" ></select>   
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
                <h3 class="box-title"> <i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Productos</h3>
              </div>

              <div class="box-body" >
                <div class="col-xs-12" style="padding-bottom: 4px;">
                  <button class="btn btn-success btn-xs" id="add_item" onclick="showAddProduct()">
                  <i class="fa fa-plus" style="padding-right: 5px;"></i> 
                  Añadir Producto</button>

                </div>
                
                <div class="col-xs-12">
                  <table id="stock" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>Imagen</th>
                      <th>Detalles</th>
                      <th>Cantidad</th>
                      <th>Precio Unitario</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>  
              <div class="box-footer">
                <div class="row">
                  <div class="col-xs-12">
                    
                      <h4 id="total_order_price" class="pull-right" style="font-weight: bold;" >Total: $0</h4>
                    
                  </div>
                    
                </div>
                  
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
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    
	
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
      tbl_add_orderitems_minimal = null;
      tbl_oderitems = null;
      view = 0;
      order_status = null;
      let itemMax = 10000;

     
       $(document).ready(function() {

        $('#supplier_id').selectpicker({
                liveSearch:true,
            });

            $('#location_id').selectpicker({
                liveSearch:true,
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

            AWApi.get('{{url('api/unit_of_measures')}}', function (response) {
              uoms = response.data.uoms;

            });
            AWApi.get('{{ url('/api/locations') }}',function (response) {
                $("#location_id").empty();                
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {
                      name += "";
                    }
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#location_id"));
                }
                $("#location_id").append($("#location_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                $('#location_id').selectpicker('refresh');
              });

            AWApi.get('{{ url('/api/suppliers') }}',function (response) {
                $("#supplier_id").empty();                
                for (var i = 0; i < response.data.suppliers.length; i++) {
                  $('<option />', {value: response.data.suppliers[i].id, text: response.data.suppliers[i].razon_social }).appendTo($("#supplier_id"));
                }
                $("#supplier_id").append($("#supplier_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                $('#supplier_id').val('');
                $('#supplier_id').selectpicker('refresh');
              });
            $("#date").datepicker({
                format: 'dd/mm/yyyy'
            });

            fieldValues = [];

            (function setFieldValues() {
                fieldValues['code'] = 'code';
                fieldValues['date'] = 'date';
                fieldValues['location_id'] = 'location_id';
                fieldValues['supplier_id'] = 'supplier_id';
               

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

                    AWApi.get('{{ url('/api/purchases' ) }}/{{$id}}/items', function (response) {
                        order_items = response.data.items;
                        $("#total_order_price").html("Total:    $ "+Math.round(response.data.total_order_price,0));
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.items
                        });
                        $( '.itemSave' ).change(function() {
                          for (var i = 0; i < order_items.length; i++) {
                            var data = new FormData();
                            data.append('quantity', parseFloat($("#quantity_"+order_items[i].purchase_order_item_id).val()));
                            data.append('price', $("#price_"+order_items[i].purchase_order_item_id).val());
                            data.append('unit_of_measure_id', $("#uom_id_"+order_items[i].purchase_order_item_id).val());
                            AWApi.put('{{url('api/purchase_order_items')}}/'+order_items[i].purchase_order_item_id, data, function (response) {
                              AWApi.get('{{ url('/api/purchases' ) }}/{{$id}}/items', function (response) {
                                $("#total_order_price").html("Total:    $ "+Math.round(response.data.total_order_price,0));
                                });
                            });
                          }
                                      
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
                            return '<img src="'+img_url+'" class="rounded" style="height: auto;width: 70px;">';
                        }
                    },
                    { "data": "id",
                        render: function ( data, type, full, meta ) {
                          console.log(full);
                            
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
                                html += "<strong>" + full.attributes[i].name + ": </strong>";
                                html += "<span class='pull-right'>" +full.attributes[i].value+ "</span> <br>";
                              }
                            }

                            return html;
                        }
                    },
                    {
                      "data": "purchase_order_item_id",
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
                          html += '<input class="form-control itemSave" placeholder="0"  id="quantity_'+data+'" value="'+full.quantity+'" ';
                          html += 'style="width: 70px;">';
                          html += '<select class="form-control itemSave" id="uom_id_'+data+'" style="width: 185px; padding-left: 2px;">';


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
                      "data": "purchase_order_item_id",
                      className: "text-center",
                      render: function ( data, type, full, meta ) {
                        console.log("full");
                        console.log(full);
                        html = '$ <input class="form-control itemSave" placeholder="0"  id="price_'+data+'" value="'+full.price+'" ';
                        html += 'style="width: 70px;">';

                        return html;
                      }
                    },
                    { "data": "purchase_order_item_id",
                      className: "text-center",
                      render: function ( data, type, full, meta ) {

                        var del = "<button class='btn btn-default btn-xs dt_del_item' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delOrderItem("+data+");>";
                        del += "<i class='fa fa-lg fa-trash-o fa-fw' o></i></button>";

                        return "<div class='btn-group'>"+del+"</div>";
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
                          console.log(full);
                            
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
                    {
                      "data": "id",
                      className: 'text-center',
                       render: function ( data, type, full, meta ) {
                        html = '<button class="btn btn-primary btn-sm" onclick="addOrderItem('+data+')"> Agregar Producto</button>';

                        return html;
                      }
                    }
                   
                ]
            });
            AWApi.get('{{ url('/api/purchases') }}/{{ $id }}', loadForm);
			// Extra Data (combos) --------------

            $("#item_search").keyup(function(event) {
               tbl_add_orderitems.ajax.reload();
               tbl_add_orderitems_minimal.ajax.reload();
            });
          
            $("#editBtn").click(function(){
              var data = new FormData();
                      
              data.append('code', $('#code').val());
              data.append('supplier_id', $('#supplier_id').val());
              data.append('location_id', $('#location_id').val());
              data.append('date', moment($("#date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD'));
              AWApi.put('{{url('api/purchases')}}/{{$id}}', data, function (response) {
                submit("", response, "Actualizar Orden de Entrada");
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
                    submitDetails();
                  }
                });
              } else {
                swal('No se puede realizar esta acción.', 
                'No se pueden guardar los detalles porque el total de productos que se desea añadir en esta orden excede los ' + itemMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + '.', 
                'error');
              }
              
              
                    
              console.log(order_items);
            });


            function submitDetails() {
              for (var i = 0; i < order_items.length; i++) {
                var data = new FormData();
                data.append('quantity', parseFloat($("#quantity_"+order_items[i].purchase_order_item_id).val()));
                data.append('price', $("#price_"+order_items[i].purchase_order_item_id).val());
                data.append('unit_of_measure_id', $("#uom_id_"+order_items[i].purchase_order_item_id).val());
                AWApi.put('{{url('api/purchase_order_items')}}/'+order_items[i].purchase_order_item_id, data, function (response) {
                  submit("", response, "Actualizar Listado de Productos");
                  AWApi.get('{{ url('/api/purchases' ) }}/{{$id}}/items', function (response) {
                      $("#total_order_price").html("Total:    $ "+Math.round(response.data.total_order_price,0));
                    });
                });
              }
            }
        });

      

      function showAddProduct() {
        $("#"+"modal_add_item").modal("show");
      }

      function delOrderItem(id) {
        if( order_status != 3){
          AWApi.delete('{{url('api/purchase_order_items')}}/'+id,function (response) {
            submit("", response, "Eliminar Item");
            tbl_orderitems.ajax.reload();
          });
        }else{
          swal('No se puede realizar esta acción.', 'El producto no puede ser eliminado debido a que la orden se encuentra en estado APROBADA.', 'error');
        }
        
      }
      function addOrderItem(item_id) {
        // $("#"+"modal_add_item").modal("hide");
        

        AWApi.get('{{url('api/items')}}/' + item_id, function (response) {
          data = new FormData();

          data.append('purchase_order_id', '{{$id}}');
          data.append('item_id', item_id);
          data.append('quantity', 0);
          data.append('unit_of_measure_id', response.data.item.unit_of_measure_id);
          AWApi.post('{{url('api/purchase_order_items')}}', data, function (response) {
            console.log(response);
            tbl_orderitems.ajax.reload();
            swal('¡Producto Agregado!', "Producto Agregado de forma exitosa", "success");
          });
          
        });
        
      }


      function loadForm(datas) {
          $('#code').val(datas.data.code);
          $("#order_code").text(datas.data.code);
          $("#breadcrumb_active").text(datas.data.code);
          $('#supplier_id').val(datas.data.supplier_id);
          $('#supplier_id').selectpicker('refresh');
          $("#location_id").val(datas.data.location_id);
          $("#location_id").selectpicker('refresh');

          $("#date").val(moment(datas.data.date, 'YYYY-MM-DD').format('DD/MM/YYYY'));
          order_status = datas.data.movement_status_id;
          console.log(datas);
          if(datas.data.movement_status_id == 3){
            $("#approveOrder-btn").addClass('disabled').hide();
            $("#publishOrder-btn").addClass('disabled').hide();
            $("#movement_status").addClass('label-success').text('APROBADA');
            $("#add_item_btn").hide();
            $("#editBtn").hide();
            $("#saveDetailsBtn").hide();
            $("#add_item").hide();
            $(".dt_del_item").prop('disabled', true);

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

      function publishOrder(id) {
            swal({
                title: "Publicar Orden de Entrada",
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
                AWApi.put('{{ url('/api/purchases') }}/' + id, data, function(datas){
                    submit("", datas, "Publicar Orden de Entrada");

                    AWApi.get('{{ url('/api/purchases') }}/{{ $id }}', loadForm);

                });
            });
            
        }

        function checkPurchaseSize() {
              let totalItems = 0
              var blockAlert = false;
              for (var i = 0; i < order_items.length; i++) {
                totalItems += parseFloat($("#quantity_"+order_items[i].purchase_order_item_id).val());
                console.log({totalItems});
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
                title: "Aprobar Orden de Entrada",
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
                AWApi.put('{{ url('/api/purchases') }}/' + id, data, function(datas){
                    submit("", datas, "Aprobar Orden de Entrada");
                    AWApi.get('{{ url('/api/purchases') }}/{{ $id }}', loadForm);
                });
              }
            });
          } else {
            swal('No se puede realizar esta acción.', 
            'No se puede aprobar la orden porque el total de productos que se desea aprobar en esta orden excede los ' + itemMax.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + '.', 
            'error');
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
                    swal("Error", data.data.errors.message, "error");
                }
                else{
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                }
                count++;   
            }

            if (count == 0)
            {
                swal(message, "Información actualizada de forma exitosa", "success");
                $('#' +id).modal('toggle');
            }
        }
        
    </script>
@endsection

