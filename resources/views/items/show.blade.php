@extends('layouts.master')

@section('css')
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
    <style>
      .hide-bullets {
          list-style:none;
          margin-left: -40px;
          margin-top: 20px;
      }

      .thumbnail {
          padding: 0;
      }

      .carousel-inner>.item>img, .carousel-inner>.item>a>img {
          width: 100%;
      }
    </style>
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
        <h1 id="item_name"><i class="fa fa-cubes" style="padding-right: 5px;"></i> Producto</h1>
      
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}">Inicio</a></li>
        <li><a href="{{url('items')}}">Productos</a></li>
        <li class="active" id="breadcrumb_active"></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-8">
          <div class="box box-primary box-solid flat">
            <div class="box-header">
               <h3 class="box-title"><i class="fa fa-info" style="padding-right: 5px;"></i> Información General</h3>
            </div>
            <div class="box-body" id="form">

              <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Identificación
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                      <div class="row">
                
                <div class="form-group col-md-3 col-xs-6">
                    <label for="manufacturer_sku">Manufact. SKU</label>
                    <input type="text" class="form-control" id="manufacturer_sku" placeholder="Manufact. SKU">
                </div>

                <div class="form-group col-md-3 col-xs-6">
                    <label for="custom_sku">Custom SKU</label>
                    <input type="text" class="form-control" id="custom_sku" placeholder="Custom SKU">
                </div>
                <div class="form-group col-md-3 col-xs-6">
                    <label for="active_without_stock">Mostrar productos sin stock</label>
                    <select class="form-control" id="active_without_stock">
                        <option value=0>No</option>
                        <option value=1>Si</option>
                    </select>
                </div>
                <!--<div class="form-group col-md-3 col-xs-6">
                    <label for="ean">EAN</label>
                    <input type="text" class="form-control" id="ean" placeholder="EAN">
                </div>
                <div class="form-group col-md-3 col-xs-6">
                    <label for="upc">UPC</label>
                    <input type="text" class="form-control" id="upc" placeholder="UPC">
                </div>-->
              </div>
                    </div>
                  </div>
                </div>

                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Detalles
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse in">
                    <div class="box-body">
                      <div class="row">
                        <div class="form-group col-md-12 col-xs-12">
                          <label for="name">Nombre</label>
                          <input type="text" class="form-control" id="name" placeholder="Nombre">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6 col-xs-6">
                          <label for="item_type_id">Tipo de Producto</label>
                          <select class="form-control" id="item_type_id" ></select>
                        </div>
                        <div class="form-group col-md-6 col-xs-6">
                          <label for="category_id">Categoría</label>
                          <select class="form-control" id="category_id" ></select>
                        </div>
                        <div class="form-group col-md-6 col-xs-6">
                          <label for="brand_id">Marca</label>
                          <select class="form-control" id="brand_id" ></select>
                        </div>
                        <div class="form-group col-md-6 col-xs-6">
                                <label for="block_discount">Bloquear Descuento:</label>
                                    <select id="block_discount" class="form-control" >
                                        <option value="0">NO </option>
                                        <option value="1">SI </option>
                                    </select>
                            </div>
                      </div>
                      <div class="row">
                          <div class="col-xs-12">
                              <label for="description">Descripción</label>
                              <textarea rows="4" class="form-control" id="description"  style="overflow:hidden;" placeholder="Descripción"></textarea>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>

              
              
              

              
            </div>  
      
            <div class="box-footer">
                <button type="submit" class="btn btn-default" id="goBack" >Cancelar</button>
                <button type="button" class="btn btn-primary pull-right" id="editBtn" >Guardar</button>
            </div>    
          </div>

          <div class="box box-primary box-solid flat">

            <div class="box-header">
              <h3 class="box-title">Stock</h3>
            </div>
                <!-- /.box-header -->
            <div class="box-body">
              <table id="stock" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Ubicación</th>
                  <th>Cantidad</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>

          <div class="box box-primary box-solid flat">
              <div class="box-header">
                <h3 class="box-title">Precios</h3>
              </div>
              <div class="box-body">
                <div class="row" >
                  <div class="form-group col-xs-12" id="price_type_add_item">
                    <label for="i_price_type">Agregar Precio</label>
                      <div class="input-group">
                              
                                  
                        <select id="i_price_type" class="form-control"></select>

                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-flat" id="i_price_type_btn">Agregar</button>
                        </span>
                      </div>
                  </div>

                </div>
                <div class="row" id="div_price_type">
                    <div class="col-sm-12">
                      <table class="table table-bordered table-prices">
                        <thead>
                          <tr>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
              <!--<div class="box-footer">
                  <button type="button" class="btn btn-primary pull-right" id="savePricesBtn" >Guardar</button>
                </div>-->
          </div>

          
          <div class="box box-primary box-solid flat">
              <div class="box-header">
                <h3 class="box-title">Atributos</h3>
              </div>
              <div class="box-body">
                  <div class="row" >
                      <div class="form-group col-xs-12" id="attributes_add_item">
                          <label for="i_attributes">Agregar Atributo</label>
                          <div class="input-group">
                              
                                  
                              <select id="i_attributes" class="form-control"></select>

                              <span class="input-group-btn">
                                  <button class="btn btn-primary btn-flat" id="i_attributes_btn">Agregar</button>
                              </span>
                          </div>
                      </div>

                  </div>
                  <div class="row" id="div_atribbutes">
                     

                  </div>
              </div>

              <div class="box-footer">
                  <button type="button" class="btn btn-primary pull-right" id="saveAttributesBtn" >Guardar</button>
                </div>
          </div>

          <div class="box box-primary box-solid flat" id="bom_items_div" hidden>

                <div class="box-header">
                    <h3 class="box-title">Producto Compuesto</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <button type="button" class="btn btn-success btn-xs" style="margin-bottom: 5px;"onclick=showAddProduct();><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Producto</button>
                 <table id="tbl_bomitems" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>Imagen</th>
                      <th>Detalles</th>
                      <th>Cantidad</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
                </div>
                <div class="box-footer">
                  <button type="button" class="btn btn-primary pull-right" id="saveDetailsBtn" >Guardar</button>
                </div>
          </div>
        </div> 
        

        <div class="col-md-4">
          <div class="box box-primary box-solid flat">
            <div class="box-header">
              <h3 class="box-title">Imágenes</h3>
            </div>
            <div class="box-body">
              <div class="row">
                  <div class="col-xs-12">
                      <div class="col-xs-12" id="slider">
                          <!-- Top part of the slider -->
                          <div class="row">
                              <div class="col-sm-12" id="carousel-bounding-box">
                                  <div class="carousel slide" id="myCarousel">
                                      <!-- Carousel items -->
                                      <div class="carousel-inner" id="items_carousel">
                                          
                                      </div>
                                      <!-- Carousel nav -->
                                      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                          <span class="glyphicon glyphicon-chevron-left"></span>
                                      </a>
                                      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                          <span class="glyphicon glyphicon-chevron-right"></span>
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-xs-12" id="slider-thumbs">
                      <!-- Bottom switcher of slider -->

                  </div>            
                </div>
              </div>
            
              <div class="box-footer">
                  <form id="upload-image">
                    <span id="fileselector">
                        <label class="btn btn-primary btn-block" for="upload-file-selector">
                            <input id="upload-file-selector" style="display:none;" type="file">
                            Subir Imagen
                        </label>
                    </span>
                  </form>
                  <button type="button" class="btn btn-danger col-xs-12" id="delete_image" style="display: none;"> Eliminar Imagen</button>
                  <!--<button type="button" class="btn btn-primary pull-right" id="editBtn" >Subir Foto</button>-->
              </div>  
          </div>


          <div class="box box-primary box-solid flat" hidden>
            <div class="box-header">
              <h3 class="box-title">Control de Reaprovisionamiento</h3>
            </div>
            <div class="box-body">
              <div class="row">
                  <div class="col-xs-12">
                     <div class="form-group">
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="cs_location_id"> Almacén:</label>
                          <select id="cs_location_id" onchange="onChangeControlStockLocation();" class="form-control" ></select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-8">
                          <label for="cs_reoder_point"> Punto de Reorden:</label>
                          <input type="number" id="cs_reoder_point" class="form-control" placeholder="Valor">
                        </div>
                        <div class="col-xs-4" id="cs_reoder_point_unit" style="padding-top: 30px;">
                          Unidades
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-8">
                          <label for="cs_order_up_to"> Ordenar hasta:</label>
                          <input type="number" id="cs_order_up_to" class="form-control" placeholder="Valor">
                        </div>
                        <div class="col-xs-4" id="cs_order_up_to_unit" style="padding-top: 30px;">
                          Unidades
                        </div>
                      </div>
                       
                     </div>
                  </div>
                     
                </div>
              </div>
            
              <div class="box-footer">

                  <button type="button" class="btn btn-primary pull-right" onclick="saveControlStockData();"> Guardar</button>
                  <!--<button type="button" class="btn btn-primary pull-right" id="editBtn" >Subir Foto</button>-->
              </div>  
          </div>
        </div>

        <div class="col-md-4">
          <div class="box box-primary box-solid flat">
            <div class="box-header">
              <h3 class="box-title">Movimientos producto</h3>
            </div>
            <div class="box-body">
              <div class="row">   
                <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Descripción</th>
                      <th>Responsable</th>
                      <th>Fecha</th>
                    </tr>
                  </thead>
                </table>  
              </div>
            </div>

          </div>


          <div class="box box-primary box-solid flat" hidden>
            <div class="box-header">
              <h3 class="box-title">Control de Reaprovisionamiento</h3>
            </div>
            <div class="box-body">
              <div class="row">
                  <div class="col-xs-12">
                     <div class="form-group">
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="cs_location_id"> Almacén:</label>
                          <select id="cs_location_id" onchange="onChangeControlStockLocation();" class="form-control" ></select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-8">
                          <label for="cs_reoder_point"> Punto de Reorden:</label>
                          <input type="number" id="cs_reoder_point" class="form-control" placeholder="Valor">
                        </div>
                        <div class="col-xs-4" id="cs_reoder_point_unit" style="padding-top: 30px;">
                          Unidades
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-8">
                          <label for="cs_order_up_to"> Ordenar hasta:</label>
                          <input type="number" id="cs_order_up_to" class="form-control" placeholder="Valor">
                        </div>
                        <div class="col-xs-4" id="cs_order_up_to_unit" style="padding-top: 30px;">
                          Unidades
                        </div>
                      </div>
                       
                     </div>
                  </div>
                     
                </div>
              </div>
            
              <div class="box-footer">

                  <button type="button" class="btn btn-primary pull-right" onclick="saveControlStockData();"> Guardar</button>
                  <!--<button type="button" class="btn btn-primary pull-right" id="editBtn" >Subir Foto</button>-->
              </div>  
          </div>
        </div>

                
      </div>
    </section>
    <!-- /.content -->

    

@endsection

@section('modals')

    <div id="modal_add_bomitem" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myLargeModalLabel"> Añadir Producto</h4><br>
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
                  
                    <table id="tbl_add_bomitems" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
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
                  
                    <table id="tbl_add_bomitems_minimal" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
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

@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
	
	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>
      image_id = 0;
      images_data = [];
      view = 0;
      bom_items = null;
      tbl_bomitems = null;
      tbl_add_bomitems = null;
      tbl_add_bomitems_minimal =null;
      tbl_stock = null;
      fieldValues = [];
      attributesData = [];
      priceTypeData = [];
      itemPrices = [];
      table = null;


      var rows = [
            [
                {field: '', type: 'grid', id: 'grid_addbomitems', value: '', data: ['#','Nombre','Acciones']}
            ]
        ];

      var params = {
          title: 'Añadir Producto',
          rows: rows,
          button: 'Agregar'
      }

      var modal_bomitems_id = "modal_bomitems";

      AWModal.create(modal_bomitems_id, params);

      function changeItemPrice(ip_id) {
        var data = new FormData();
        data.append('price', $('.price-'+ip_id).val());
        AWApi.put('{{url('api/itemprices')}}/'+ip_id, data, function (response) {
            if(response.code == 404) swal("Precio no se encuentra en el sistema", "Error.", "error");
            if(response.code == 403) {
              if(response.data.errors) {
                let errList = generateErrorList(response.data.errors);
                swal("Error", "Error de validación\n"+errList, "error");
              }
            }
            if(response.code == 201) {
              swal("Precio Modificado", "Precio Modificado correctamente", "success");
              loadPrices();
              loadPriceTypes();
            }
        });
      }

      function updItemPrice(ip_id) {
        var data = new FormData();
        data.append('price', $('.price-'+ip_id).val());
        data.append('item_active', 1);
        AWApi.put('{{url('api/itemprices')}}/'+ip_id, data, function (response) {
            if(response.code == 404) swal("Precio no se encuentra en el sistema", "Error.", "error");
            if(response.code == 403) {
              if(response.data.errors) {
                let errList = generateErrorList(response.data.errors);
                swal("Error", "Error de validación\n"+errList, "error");
              }
            }
            if(response.code == 201) {
              loadPrices();
              loadPriceTypes();
            }
        });
      }
      function delItemPrice(ip_id) {
          AWApi.delete('{{ url('/api/itemprices' ) }}/'+ip_id,function(response) {
              if(response.code == 404) swal("Precio no se encuentra en el sistema", "Error.", "error");
              if(response.code == 403) swal("Error", "No se puede eliminar un precio activo", "error");
              if(response.code == 201) {
                loadPrices();
                loadPriceTypes();
              }
          });
      }
      function loadPrices() {
         AWApi.get('{{ url('/api/itemprices' ) }}?item_id={{$id}}', function (response) {
            if(response.code == 200) {
              itemPrices = response.data;
              AWApi.get('{{ url('/api/price_types' ) }}', function (response) {
                  if(response.code == 200) {
                    types = response.data.price_types;
                    $('.table-prices >tbody').empty();
                    itemPrices.map( function(price) {
                      console.log(price);
                        let cols = '<tr>';
                        let typeName = '';
                        for(x in types) {
                          if(price.price_type_id == types[x].id) typeName = types[x].name;
                        }
                        cols += '<td style="padding-top:1em"><label class="control-label">'+typeName+'</label></td>'+'<td><input style="width:40%" type="number" class="form-control price-'+price.id+' price-input" value="'+price.price+'" onChange="changeItemPrice('+price.id+')"/></td>';
                        cols += '<td style="padding-top:1em"><label class="control-label">'+(price.item_active == 1 ? 'SI':'NO')+'</label></td>';
                        cols += `<td style="text-align: right">`
                        + `<button style="margin-right:5px" class="btn-upd-price btn btn-success onClick="changeItemPrice(`+price.id+`)">Guardar</button>`
                        + (price.item_active == 0 
                            ? `<button onClick="updItemPrice(`+price.id+`)" style="margin-right:5px" class="btn btn-success">Activar</button>`
                            : ``) 
                            +`<button onClick="delItemPrice(`+price.id+`)" class="btn btn-danger">Eliminar</button></td></tr>`;
                        $('.table-prices >tbody').append(cols);
                    });
                  }
              });
            }  
         });

      }

      function loadPriceTypes() {
        AWApi.get('{{ url('/api/pricetypes' ) }}?item_id={{$id}}', function (response) {
              $("#i_price_type").empty();
              for (var i = 0; i < response.data.length; i++) {
                  $('<option />', {value: response.data[i].id, 
                                  text: response.data[i].name }).appendTo($("#i_price_type"));   
              }
        });
      }


      function onChangeControlStockLocation(){
        location_id = $("#cs_location_id").val();
        AWApi.get('{{url('api/items')}}/{{$id}}/locationItem?location_id='+location_id, function (response) {
          console.log(response);
          $("#cs_reoder_point").val(response.data.reorder_point);
          $("#cs_order_up_to").val(response.data.order_up_to_level);
        });
      }
      function saveControlStockData(){
        var data = new FormData;
        data.append('order_up_to', $('#cs_order_up_to').val() );
        data.append('reorder_point', $('#cs_reoder_point').val() );
        data.append('location_id', $("#cs_location_id").val() )
        AWApi.post('{{ url('/api/items') }}/{{$id}}/controlstock', data, function(datas){
            
          swal("Actualizar Control de Reaprovisionamiento", "Operación realizada con exito.", "success");
        });
      }
       $(document).ready(function() {

        table = $('#datas').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "bInfo" : false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    //extra data
                    var filters = new Object();
                    filters.item_id = '{{$id}}';
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/transacts/search' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.transacts
                           
                            
                        });

                       
                        
                    });
                    },
                
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,"searchable":false},
                    { "data": "description"},
                    { "data": "created_by",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.users.name;
                        }
                    },
                    { "data": "created_at",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return utcToLocal(full.created_at);
                        }
                    },
                ]
            });

        AWApi.get('{{url('api/locations')}}', function (response) {
          $('<option />', {value: "DEFAULT", text: "DEFAULT" }).appendTo($("#cs_location_id"));
              for (var i = 0; i < response.data.locations.length; i++) {
                    $('<option />', {value: response.data.locations[i].id, 
                                    text: response.data.locations[i].name }).appendTo($("#cs_location_id"));
                }

            });
          AWApi.get('{{url('api/unit_of_measures')}}', function (response) {
              uoms = response.data.uoms;

            });
          AWApi.get('{{ url('/api/brands' ) }}', function (response) {
                $("#brand_id").editableSelect('destroy');
                $("#brand_id").empty();
                $("#brand_id").attr('placeholder', "Ninguna");

                for (var i = 0; i < response.data.brands.length; i++) {
                    $('<option />', {value: response.data.brands[i].id, 
                                    text: response.data.brands[i].name }).appendTo($("#brand_id"));
                }
                $("#brand_id").editableSelect();

                AWApi.get('{{ url('/api/categories' ) }}', function (response) {
                  $('<option />', {value: "", text:"Ninguna" }).appendTo($("#category_id"));
                  for (var i = 0; i < response.data.categories.length; i++) {
                    $('<option />', {value: response.data.categories[i].id, text:response.data.categories[i].full_route.toUpperCase() }).appendTo($("#category_id"));
                  }

                  AWApi.get('{{ url('/api/item_types' ) }}', function (response) {
                      for (var i = 0; i < response.data.item_types.length; i++) {
                        $('<option />', {value: response.data.item_types[i].id, text:response.data.item_types[i].name }).appendTo($("#item_type_id"));
                      }                
                    });

                  AWApi.get('{{ url('/api/items' ) }}/{{$id}}', loadForm);
                });
          });

           AWApi.get('{{ url('/api/attributes' ) }}', function (response) {
              $("#i_attributes").empty();
         
              for (var i = 0; i < response.data.attributes.length; i++) {
                  $('<option />', {value: response.data.attributes[i].id, 
                                  text: response.data.attributes[i].name }).appendTo($("#i_attributes"));
                  
              }
              if($('#i_attributes option').size() > 0){
                  $("#attributes_add_item").show();
              }
              attributesData = response.data.attributes;
          
          });

          loadPriceTypes();

          $("#i_price_type_btn").click(function(event) {
             $("#i_price_type_btn").attr("disabled", true);
             let data = new FormData();
             data.append('item_id', '{{$id}}');
             data.append('price_type_id', $("#i_price_type").val());
             AWApi.post('{{ url('/api/itemprices') }}', data, function(response){
                  $("#i_price_type_btn").attr("disabled", false);
                  if(response.code == 201) {
                      loadPrices();
                      loadPriceTypes();
                  } else {
                    swal("Error", "Error General", "error");
                  }
             });
          });

          // AWApi.get('{{ url('/api/price_types' ) }}', function (response) {
          //     priceTypeData = response.data.price_types;
          //     for (var i = 0; i < response.data.price_types.length; i++) {
          //         $('<option />', {value: response.data.price_types[i].id, 
          //                         text: response.data.price_types[i].name }).appendTo($("#i_price_type"));   
          //     }
          // });

          //  $("#i_price_type_btn").click(function(event) {

          //       for (var i = 0; i < priceTypeData.length; i++) {
          //           if(priceTypeData[i].id == $("#i_price_type").val()){
          //               html = '<div class="col-xs-6">';
          //               html += '<div class="form-group">';
          //               html += '<label for="i_price_type_'+priceTypeData[i].id+'" >'
          //               html += priceTypeData[i].name;
          //               html += '</label>';
                        
          //               html += '<input type="number" class="form-control" id="i_price_type_value_'+priceTypeData[i].id+'" placeholder="0.00">';
                        
                        
          //               html += '</div>';
          //               html += '</div>';
                        
          //               $("#div_price_type").append(html);
                        
          //           }

          //       }

          //       $("#i_price_type option[value='"+$("#i_price_type").val()+"']").remove();
          //       if($('#i_price_type option').size() == 0){
          //           $("#price_type_add_item").hide();
          //       }
          //   });


          $("#i_attributes_btn").click(function(event) {

              for (var i = 0; i < attributesData.length; i++) {
                  if(attributesData[i].id == $("#i_attributes").val()){
                      html = '<div class="col-xs-6">';
                      html += '<div class="form-group">';
                      html += '<label for="i_attribute_'+attributesData[i].id+'" >'
                      html += attributesData[i].name;
                      html += '</label>';
                      if(attributesData[i].type == "integer" || attributesData[i].type == "float"){
                          html += '<input type="number" class="form-control" id="attribute_value_'+attributesData[i].id+'" placeholder="Valor">';
                      }else{
                          html += '<input type="text" class="form-control" id="attribute_value_'+attributesData[i].id+'" placeholder="Valor">';
                      }
                      
                      html += '</div>';
                      html += '</div>';
                      
                      $("#div_atribbutes").append(html);
                      if(attributesData[i].type == "date"){
                          $('#attribute_value_'+attributesData[i].id).datepicker({
                              format: 'dd/mm/yyyy',
                              gotoCurrent: true,
                              language:'es'
                          });
                      }
                  }

              }

              $("#i_attributes option[value='"+$("#i_attributes").val()+"']").remove();
              if($('#i_attributes option').size() == 0){
                  $("#attributes_add_item").hide();
              }
          });                
      
            

            tbl_addbomitems = $('#grid_addbomitems').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "dom": "t",
                "ajax": function (data, callback, settings) {

                    //extra data

                    AWApi.get('{{ url('/api/items' ) }}/{{$id}}?with=availableBomItems' , function (response) {
                        callback({
                            recordsTotal: response.data.bom_items.availableTotal,
                            recordsFiltered: response.data.bom_items.availableFiltered,
                            data: response.data.bom_items.available_items,
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editItem("+data+");>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });
            tbl_bomitems = $('#tbl_bomitems').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/items' ) }}/{{$id}}/bomitems', function (response) {
                        bom_items = response.data.bom_items;
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.bom_items
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
                      "data": "bom_item_id",
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
                          html += '<input class="form-control" placeholder="0"  id="amount_'+data+'" value="'+full.quantity+'" ';
                          html += 'style="width: 70px;">';
                          html += '<select class="form-control" id="uom_id_'+data+'" style="width: 185px; padding-left: 2px;">';


                          for (var i = 0; i < uoms.length; i++) {
                            if(default_measure == uoms[i].measure){
                              if(default_uom == uoms[i].id){
                                html += '<option selected="selected" value="'+uoms[i].id+'">'+ "("+uoms[i].abbr+") - "+uoms[i].plural + '</option>';
                              }else{
                                html += '<option value="'+uoms[i].id+'">'+ "("+uoms[i].abbr+") - "+uoms[i].plural + '</option>';
                              }
                              
                            }
                            
                          }
                          html += '</select>';
                          html += '</div>';
                        return html;
                        
                      }
                    },
                    
                    { "data": "bom_item_id",
                      className: "text-center",
                      render: function ( data, type, full, meta ) {

                        var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delBom("+data+");>";
                        del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                        return "<div class='btn-group'>"+del+"</div>";
                      }
                    }
                        
                ]
            });
            tbl_stock = $('#stock').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "dom": "tp",
                "ajax": function (data, callback, settings) {

                    //extra data

                    AWApi.get('{{ url('/api/items' ) }}/{{$id}}/stock' , function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.stock,
                        });
                    });
                },
                "paging": false,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "location",
                        render: function ( data, type, full, meta ) {
                            html = "<div>";
                            if(data != "TOTAL"){
                              html += '<a href="{{url('locations')}}/'+full.location_id+'">' + data + '</a> ';
                            }else{
                               html += '<strong style="font-size: 16px">' + data + '</strong> ';
                            }
                            
                            html += '</div>'
                            return html;

                        }
                      },
                    { "data": "amount",
                      render: function ( data, type, full, meta ) {
                            html = "<div>";
                            html += '<strong style="color: #3c8dbc; font-size: 20px;padding-right: 3px;">' + Math.round(data*100)/100 + '</strong> '+full.uom_plural;
                            html += '</div>'
                            return html;

                        }
                    }
                    
                ]
            });

            tbl_add_bomitems = $('#tbl_add_bomitems').DataTable( {
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
                    data.item_id = "{{$id}}";
                    data.with = "availableBomItems";
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
                        html = '<button class="btn btn-primary btn-sm" onclick="addOrderItem('+data+')"> Agregar Producto</button>'
                        return html;
                      }
                    }
                    
                
                ]
            });

             tbl_add_bomitems_minimal = $('#tbl_add_bomitems_minimal').DataTable( {
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
                    data.item_id = "{{$id}}";
                    data.with = "availableBomItems";
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
                        html = '<button class="btn btn-primary btn-sm" onclick="addOrderItem('+data+')"> Agregar Producto</button>'
                        return html;
                      }
                    }
                   
                ]
            });
            

            $('#myButton').click(function () {
                AWValidator.clean(modal_bomitems_id);
                $(".modal-body input").val("");
                $('#' +modal_bomitems_id).modal('show');

                
            });

            $('#' + modal_bomitems_id + "_create").click(function(){

                
            });

            $("#delete_image").click(function(event) {
              //console.log(images_data[image_id].id);
              texto = "<img src='{{asset('uploads/items')}}/"+images_data[image_id].filename+"' ";
              console.log(images_data);
              texto += "style='height: 150px; width: 150px; margin-top: -10px'>";
              swal({
                    title: "¿Eliminar imagen?",
                    text: texto,
                    html: true,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/files' ) }}/'+images_data[image_id].id,function(data) {
                        if(data.data.errors){
                            if(data.data.errors.unauthorized){
                                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                            }else{
                                swal("Error", data.data.errors, "warning");
                            }
                            
                        }else{
                            swal("Eliminado", "Registro eliminado de forma exitosa", "success");
                        }
                        AWApi.get('{{ url('/api/items') }}/{{ $id }}', loadForm);
                        //table.ajax.reload();
                    });
                });
            });
            
            $('#myCarousel').carousel({
                interval: 0
            });
            $('[id^=carousel-selector-]').click(function () {
                var id_selector = $(this).attr("id");
                try {
                    var id = /-(\d+)$/.exec(id_selector)[1];
                    image_id = id;
                    console.log(id_selector, id);
                    //console.log("ID: " + id);
                    $('#myCarousel').carousel(parseInt(id));
                } catch (e) {
                    console.log('Regex failed!', e);
                }
            });
            $('#myCarousel').on('slid.bs.carousel', function (e) {
                var id = $('.item.active').data('slide-number');

                $('#carousel-text').html($('#slide-content-'+id).html());
            });

            fieldValues = [];

            (function setFieldValues() {
                fieldValues['name'] = 'name';
                fieldValues['email'] = 'email';

            })();

            function loadForm(datas) {
              console.log(datas);
                $("#cs_reoder_point").val(datas.data.item.reorder_point);
                $("#cs_order_up_to").val(datas.data.item.order_up_to);
                $("#items_carousel").text("");
                $("#slider-thumbs").text("");
                items_carousel = '<div class="carousel-inner" id="items_carousel">';
                items_thumbnails = '<ul class="hide-bullets" >';
                images_data = datas.data.images;              

                if(datas.data.images.length == 0){
                    items_carousel +=  '<div class="active item" data-slide-number="0">';
                    items_carousel +=  '<img src="{{asset("img/defaults/box.png")}}"></div>'  

                    items_thumbnails += '<li class="col-xs-3"><a class="thumbnail" id="carousel-selector-0">';
                    items_thumbnails += '<img src="{{asset("img/defaults/box.png")}}"></a></li>';
                }else{
                  $("#delete_image").show();
                }
                for (var i = 0; i < datas.data.images.length; i++) {
                  if(i==0){
                    items_carousel +=  '<div class="active item" data-slide-number="0">';
                    items_carousel +=  '<img src="{{asset("uploads/items")}}/'+datas.data.images[i].filename+'" style="overflow: visible; max-height: 500px;"></div>'  

                    items_thumbnails += '<li class="col-xs-3"><a class="thumbnail" id="carousel-selector-0">';
                    items_thumbnails += '<img src="{{asset("uploads/items")}}/'+datas.data.images[i].filename+'"></a></li>';
          
                  }
                  else{
                    items_carousel +=  '<div class="item" data-slide-number="'+i+'">';
                    items_carousel +=  '<img src="{{asset("uploads/items")}}/'+datas.data.images[i].filename+'"></div>';

                    items_thumbnails += '<li class="col-xs-3"><a class="thumbnail" id="carousel-selector-'+i+'">';
                    items_thumbnails += '<img src="{{asset("uploads/items")}}/'+datas.data.images[i].filename+'"></a></li>';
                  }
                  //console.log(datas.data.images[i]);
                  
                }
                items_thumbnails += '</ul>';
                items_carousel += '</div>';
                //console.log(items_carousel);
                $("#slider-thumbs").append(items_thumbnails);
                $("#items_carousel").append(items_carousel);

                $('[id^=carousel-selector-]').click(function () {
                    var id_selector = $(this).attr("id");
                    try {
                        var id = /-(\d+)$/.exec(id_selector)[1];
                        image_id = id;
                        console.log(id_selector, id);
                        $('#myCarousel').carousel(parseInt(id));
                    } catch (e) {
                        console.log('Regex failed!', e);
                    }
                });


                itemAttributes = datas.data.item.attributes;
                AWApi.get('{{ url('/api/attributes' ) }}', function (response) {
                    $("#i_attributes").empty();
            
                    for (var i = 0; i < response.data.attributes.length; i++) {
                        $('<option />', {value: response.data.attributes[i].id, 
                                        text: response.data.attributes[i].name }).appendTo($("#i_attributes"));
                        
                    }
                    attributesData = response.data.attributes;

                    $("#div_atribbutes").html("");
                    for (var i = 0; i < attributesData.length; i++) {
                        for (var j = 0; j < itemAttributes.length; j++) {
                            if(attributesData[i].name == itemAttributes[j].name){
                                html = '<div class="col-xs-6">';
                                html += '<div class="form-group">';
                                
                                

                                html += '<label for="i_attribute_'+attributesData[i].id+'" >'
                                html += attributesData[i].name;
                                html += '</label>';
                                if(attributesData[i].type == "integer" || attributesData[i].type == "float"){
                                    html += '<input type="number" class="form-control" id="attribute_value_'+attributesData[i].id+'" placeholder="Valor" value="'+itemAttributes[j].value+'">';
                                }else{
                                    html += '<input type="text" class="form-control" id="attribute_value_'+attributesData[i].id+'" placeholder="Valor" value="'+itemAttributes[j].value+'">';
                                }
                                
                                html += '</div>';
                                html += '</div>';
                                
                                $("#div_atribbutes").append(html);
                                if(attributesData[i].type == "date"){
                                    $('#attribute_value_'+attributesData[i].id).datepicker({
                                        format: 'dd/mm/yyyy',
                                        gotoCurrent: true,
                                        language:'es'
                                    });
                                    $('#attribute_value_'+attributesData[i].id).datepicker("setDate", 
                                      moment(itemAttributes[j].value, "YYYY-MM-DD" ).format("DD/MM/YYYY") );
                                }

                                $("#i_attributes option[value='"+attributesData[i].id+"']").remove();
                            }
                        }
                    
                    }

                    
                    if($('#i_attributes option').size() == 0){
                        $("#attributes_add_item").hide();
                    }

                });


                itemPrices = datas.data.prices;
                loadPrices();


                // AWApi.get('{{ url('/api/price_types' ) }}', function (response) {

                //     $("#i_price_type").empty();
                //     priceTypeData = response.data.price_types;
                //     for (var i = 0; i < response.data.price_types.length; i++) {
                //         $('<option />', {value: response.data.price_types[i].id, 
                //                         text: response.data.price_types[i].name }).appendTo($("#i_price_type"));   
                //     }

                //     priceTypeData = response.data.price_types;
                //     $("#div_price_type").html("");
                //     for (var i = 0; i < priceTypeData.length; i++) {
                //         for (var j = 0; j < itemPrices.length; j++) {
                //             if(priceTypeData[i].id == itemPrices[j].price_type_id){
                //                 html = '<div class="col-xs-6">';
                //                 html += '<div class="form-group">';
                //                 html += '<label for="i_price_type_'+priceTypeData[i].id+'" >';
                //                 html += priceTypeData[i].name;
                //                 html += '</label>';
                //                 html += '</div>';
                //                 html += '<div class="col-md-1 text-center"><input type="radio" name="price_active" value="'+itemPrices[j].id+'" '+(itemPrices[j].item_active == 1 ? "checked" : "")+'></div>';
                //                 html += '<div class="col-md-10"><input type="number" class="form-control" id="i_price_type_value_'+priceTypeData[i].id+'" placeholder="Valor" value="'+itemPrices[j].price+'"></div>';
                //                  html += '</div>'
                //                 /*
                //                 if(priceTypeData[i].type == "integer" || priceTypeData[i].type == "float"){
                //                     html += '<input type="number" class="form-control" id="attribute_value_'+priceTypeData[i].id+'" placeholder="Valor" value="'+itemPrices[j].value+'">';
                //                 }else{
                //                     html += '<input type="text" class="form-control" id="attribute_value_'+priceTypeData[i].id+'" placeholder="Valor" value="'+itemPrices[j].value+'">';
                //                 }
                //                 */
                                
                //                 html += '</div>';
                //                 html += '</div>';
                                
                //                 $("#div_price_type").append(html);

                                
                //                 $("#i_price_type option[value='"+priceTypeData[i].id+"']").remove();
                //             }
                //         }
                    
                //     }

                    
                //     if($('#i_price_type option').size() == 0){
                //         $("#price_type_add_item").hide();
                //     }

                // });


                if(datas.data.item.custom_sku){
                  $("#custom_sku").val(datas.data.item.custom_sku);
                }
                if(datas.data.item.active_without_stock){
                  $("#active_without_stock").val(datas.data.item.active_without_stock);
                }
                if(datas.data.item.manufacturer_sku){
                  $("#manufacturer_sku").val(datas.data.item.manufacturer_sku);
                }
                if(datas.data.item.ean){
                  $("#ean").val(datas.data.item.ean);
                }
                if(datas.data.item.upc){
                  $("#upc").val(datas.data.item.upc);
                }
                if(datas.data.item.description){
                  $("#description").text(datas.data.item.description);
                }
                if (datas.data.item.brand) {
                  $("#brand_id").val(datas.data.item.brand);
                }
                $("#name").val(datas.data.item.name);
                if(datas.data.item.category_id){
                  
                  $("#category_id").val(datas.data.item.category_id);
                }
                if(datas.data.item.brand){
                  $("#brand").val(datas.data.item.brand);
                }
                if(datas.data.item.block_discount){
                  $("#block_discount").val(datas.data.item.block_discount);
                }
                if(datas.data.item.item_type_id){
                  $("#item_type_id").val(datas.data.item.item_type_id);
                
                  if(datas.data.item.item_type_id == 1){
                    $("#bom_items_div").hide();
                  } 
                  else if(datas.data.item.item_type_id == 2){
                    $("#bom_items_div").show();
                  }
                  else if(datas.data.item.item_type_id == 3){
                    $("#bom_items_div").show();
                  }
                }

                if(datas.data.item.name){
                  $("#breadcrumb_active").text(datas.data.item.name);
                  $("#item_name").html('<i class="fa fa-cubes" style="padding-right: 5px;"></i>'+datas.data.item.name);
                }
                //$('.profile-user-img').attr('src',"{{ asset('/uploads/avatars') }}/"+datas.data.avatar);
            }


            $('#upload-file-selector').click(function(){
              $('#upload-file-selector')[0].files[0] = null;
            });

            $('#upload-file-selector').change(function(){

              var data = new FormData;
              data.append('file', $('#upload-file-selector')[0].files[0] );
              let fullFileName = $('#upload-file-selector')[0].files[0].name;
              let fileExtension = fullFileName.split('.')[fullFileName.split('.').length-1];
              if(fullFileName == fileExtension) {
                fileExtension = '';
              }
              data.append('object_id', '{{ $id }}' );
              data.append('name', $("#name"))
              data.append('object_type', "items");
              data.append('type', "IMG");
              data.append('imageFileExtension', fileExtension)
              AWApi.post('{{ url('/api/files') }}', data, function(datas){
                if(datas.data && datas.data.errors) {
                  let errList = generateErrorList(datas.data.errors);
                  swal("Error", errList, "error");
                }
                AWApi.get('{{ url('/api/items') }}/{{ $id }}', loadForm);
              });
                  //$('.profile-user-img').attr('src',"{{ asset('/uploads/avatars') }}/"+datas.data.avatar);
            });

            $("#saveDetailsBtn").click(function(){
              for (var i = 0; i < bom_items.length; i++) {
                var data = new FormData();
                data.append('amount', $("#amount_"+bom_items[i].bom_item_id).val());
                data.append('unit_of_measure_id', $("#uom_id_"+bom_items[i].bom_item_id).val());
                AWApi.put('{{url('api/bom_items')}}/'+bom_items[i].bom_item_id, data, function (response) {
                  submit("", response, "Actualizar Listado de Productos");
                });
                
              }
              
            });


            $("#saveAttributesBtn").click(function(){
                
              var attr = [];
              for (var i = 0; i < attributesData.length; i++) {
                  if($("#attribute_value_" + attributesData[i].id).val() !=null && $("#attribute_value_" + attributesData[i].id).val() !=""){
                      if(attributesData[i].type == "date"){
                          attribute = [ attributesData[i].id,  moment($("#attribute_value_" + attributesData[i].id).val(), "DD/MM/YYYY").format("YYYY-MM-DD") ];
                      }else{
                          attribute = [ attributesData[i].id,  $("#attribute_value_" + attributesData[i].id).val() ];
                      }
                      
                      attr.push(attribute);
                      //console.log( $("#attribute_value_" + attributesData[i].id).val() );

                  }
              }
                
              
              data = new FormData();

              data.append('attributes', attr);

              AWApi.put('{{url('api/items')}}/{{$id}}', data, function (response){
                console.log(response);
                submit("", response, 'Actualizar Atributos');
              });
            });

            // $("#savePricesBtn").click(function(){
                
            //   var prices = [];

            //   for (var i = 0; i < priceTypeData.length; i++) {
            //      console.log($("#i_price_type_value_" + priceTypeData[i].id).val());
            //       if($("#i_price_type_value_" + priceTypeData[i].id).val() !=null && $("#i_price_type_value_" + priceTypeData[i].id).val() !=""){  
            //           price_type = [ priceTypeData[i].id,  $("#i_price_type_value_" + priceTypeData[i].id).val() ];
            //           prices.push(price_type);

            //       }
            //   }

            //   data = new FormData();
            //   data.append('prices', prices);
            //   data.append('item_active',$("input[type='radio'][name='price_active']:checked").val());

            //   console.log(prices);

            //   AWApi.put('{{url('api/items')}}/{{$id}}', data, function (response){
            //     console.log(response);
            //     submit("", response, 'Actualizar Precios');
            //   });
            // });
             
			// Extra Data (combos) --------------
             
            var admin = $('#admin');
            $('<option />', {value: 0, text: 'No'}).appendTo(admin);
            $('<option />', {value: 1, text: 'Si'}).appendTo(admin);


            $("#goBack").click(function(){
              window.location.href = "{{ url( '/items' ) }}";
            });

            $("#normalview_btn").click(function(event) {
                view = 0;

                $("#normal_view").show();
                $("#compact_view").hide();
                tbl_add_bomitems.ajax.reload();

                $("#normalview_btn").addClass('active');
                $("#compactview_btn").removeClass('active');
            });
            $("#compactview_btn").click(function(event) {
                view = 1;
                $("#compact_view").show();
                $("#normal_view").hide();
                tbl_add_bomitems_minimal.ajax.reload();
               
               $("#compactview_btn").addClass('active');
               $("#normalview_btn").removeClass('active');               
               
            });  

             $("#item_search").keyup(function(event) {
               tbl_add_bomitems.ajax.reload();
               tbl_add_bomitems_minimal.ajax.reload();
            });
          
            $("#editBtn").click(function(){
              var data = new FormData();
                      
              data.append('name', $('#name').val());
              data.append('manufacturer_sku', $('#manufacturer_sku').val());
              data.append('custom_sku', $('#custom_sku').val());
              data.append('active_without_stock', $('#active_without_stock').val());
              data.append('ean', $('#ean').val());
			  data.append('upc', $('#upc').val());
              data.append('description', $("#description").val());
              data.append('category_id', $('#category_id').val());
              data.append('item_type_id', $('#item_type_id').val());
              data.append('brand', $('#brand_id').val());
              data.append('block_discount', $("#block_discount").val());

              AWApi.put('{{ url('/api/items/') }}'+ "/" +{{$id}},data,function(datas){
                  submit("", datas, 'Actualizar Producto');
              });
            });

        });

        function addOrderItem(item_id) {
        $("#"+"modal_add_bomitem").modal("hide");

        AWApi.get('{{url('api/items')}}/' + item_id, function (response) {
            data = new FormData();
            data.append('item_id', "{{$id}}");
            data.append('child_item_id', item_id)
            data.append('amount', 0);
            data.append('unit_of_measure_id', response.data.item.unit_of_measure_id);
            AWApi.post('{{url('api/bom_items')}}', data, function (response) {

              tbl_bomitems.ajax.reload();
            });
          
        });
      }

         

        function showAddProduct() {
         
          $("#"+"modal_add_bomitem").modal("show");
          tbl_add_bomitems.ajax.reload();
          tbl_add_bomitems_minimal.ajax.reload();
        }

      

        function delBom(id) {

          swal({
                    title: "Eliminar Producto",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    
                  AWApi.delete('{{url('api/bom_items')}}/'+id,function (response) {
                    submit("", response, "Eliminar Producto");
                    tbl_bomitems.ajax.reload();
                  });
                    
                });

          
        }

        function editItem(id)
        {
             window.location.href = "{{ url( 'items' ) }}/"+id;
        }

         function submit(id,data, message)
        {
            var count = 0;
            if (id != ""){
                AWValidator.clean(id);
            }
            
            if(data.data.errors && data.data.errors.unauthorized){
                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
            } else if(data.data.errors && data.data.errors.message){
                swal("Error", data.data.errors.message, "error");
            } if(data.data.errors) {
                let errList = generateErrorList(data.data.errors);
                swal("Error", errList, "error");
            } else {
                swal(message, "Información actualizada de forma exitosa", "success");
                $('#' +id).modal('toggle');
            }
        }
        

    
    </script>
@endsection