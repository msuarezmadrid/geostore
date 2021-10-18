@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <style>
      .attribute-item{
        max-width: 400px;
        word-wrap: break-word;
      }
    </style>
@endsection


@section('content')

  <section class="content-header">
    <h1>
      <i class="fa fa-cubes" style="padding-right: 5px;"></i> Productos
       <button type="button" class="btn btn-success btn-xs" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar Producto</button>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Productos</li> 
    </ol>

  </section>

  

	<section class="content">
        <div class="row">
            <div class="col-xs-12">

            <div class="box box-primary flat box-xs collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i>Filtrar</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div> 
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="f_code">Código</label>
                                <input type="text" class="form-control" id="f_code" placeholder="Custom SKU, Manufact. SKU">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_name">Nombre</label>
                                <input type="text" class="form-control" id="f_name" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="f_active_without_stock">Mostrar productos sin stock</label>
                                <select class="form-control" id="f_active_without_stock">
                                    <option value=0>No</option>
                                    <option value=1>Si</option>
                                </select>
                            </div>
                        </div>
                      </div>
                        
                        <div class="row">
                             <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="f_category">Categoría</label>
                                    <select class="form-control" id="f_category" placeholder="Categoría"></select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="f_brand">Marca</label>
                                    <select class="form-control" id="f_brand" placeholder="Marca"></select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="f_item_type">Tipo
                                    </label>
                                    <select class="form-control" id="f_item_type" placeholder="Marca"></select>
                                </div>
                            </div>
                           
                        </div>
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                        <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                    </div>
                    <!-- /.box-footer -->
                </div>
                  
            <div class="box box-primary box-solid flat">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Productos</h3>

                </div>  
                
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-xs-12">
                        
                        <div class="pull-right">
                            
                        </div>
                        <div class="btn-group btn-toggle pull-right">
                        
                            <button class="btn btn-xs btn-default active" id="normalview_btn">
                                <i class="fa fa-th-list"></i>
                            </button>
                            <button class="btn btn-xs btn-default" id="compactview_btn">
                                <i class="fa fa-align-justify"></i>
                            </button>
                        </div>
                        
                    </div>
                    
                    <div class="col-xs-12" id="div_datas">
                        <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                          <thead>
                            <tr>
                              
                              <th>Imagen</th>
                              <th>Detalles</th>
                              <th>Identificación</th>
                              <th>Stock</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                        </table>
                    </div>
                  

                  <div class="col-xs-12" id="div_datas_minimalview" hidden>
                    <table id="datas_minimalview" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
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
            <!-- /.box -->
        </div>
        </div>

        

    </section>

@endsection

@section('modals')

  <div id="modal_massive_file" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"> Importar Productos</h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab_1" data-toggle="tab">Digikey CSV</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <label for="digikey_massive_category">Categoria: </label>
                                        <select name="digikey_massive_category" id="digikey_massive_category" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="file_massive_digikey">Archivo .csv Digikey</label>
                                    <input type="file" name="file" id="file_massive_digikey" class="form-control">
                                </div>
    
                          </div>

                        </div>
                      </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="uploadItemsMassive();">Subir Archivo</button>
          </div>
        </div>
      </div>
    </div>


    <div id="modal_massive_file_shoes" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"> Importar Zapatillas</h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab_1" data-toggle="tab">Example CSV</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <label for="digikey_massive_category">Categoria: </label>
                                        <select name="shoes_massive_category" id="shoes_massive_category" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="file_massive_shoes">Archivo .csv Example</label>
                                    <input type="file" name="file" id="file_massive_shoes" class="form-control">
                                </div>
    
                          </div>

                        </div>
                      </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="uploadItemsMassiveShoes();">Subir Archivo</button>
          </div>
        </div>
      </div>
    </div>

    <div id="modal_item" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myLargeModalLabel"> Creación de Producto</h4>
          </div>
          <div class="modal-body">
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
                            <div class="form-group col-md-3">
                                <label for="i_manufacturer_sku">Manufact. SKU</label>
                                <input type="text" class="form-control" id="i_manufacturer_sku" placeholder="Manufact. SKU">
                            </div>
                             <div class="form-group col-md-3">
                                <label for="i_custom_sku">Custom SKU</label>
                                <input type="text" class="form-control" id="i_custom_sku" placeholder="Custom SKU">
                            </div>
                            
                            <!--<div class="form-group col-md-3">
                                <label for="ean">EAN</label>
                                <input type="text" class="form-control" id="i_ean" placeholder="EAN">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="upc">UPC</label>
                                <input type="text" class="form-control" id="i_upc" placeholder="UPC">
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
                            
                            <div class="col-md-12">
                                <img id="i_image_preview" src="" hidden style="height:180px;width:180px;">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for="file">Imagen</label>       
                                <input id="upload-file-selector" type="file" onchange="itemImage(this);">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="i_name">Nombre</label>
                                <input type="text" class="form-control" id="i_name" placeholder="Nombre">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="i_block_discount">Bloquear Descuento:</label>
                                    <select id="i_block_discount" class="form-control" >
                                        <option value="0">NO </option>
                                        <option value="1">SI </option>
                                    </select>
                            </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-3">
                                <label for="i_category_id">Categoría</label>
                                <select class="form-control" id="i_category_id" ></select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="i_brand_id">Marca</label>
                                <select class="form-control" id="i_brand_id" ></select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="i_item_type_id">Tipo</label>
                                <select class="form-control" id="i_item_type_id" ></select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="i_unit_of_measure_id">Unidad de medida</label>
                                <select class="form-control" id="i_unit_of_measure_id" ></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="i_description">Descripción</label>
                                <textarea rows="4" class="form-control" id="i_description" style="overflow:hidden;" placeholder="Descripción"></textarea>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>

               <!-- <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Atributos
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFour" class="panel-collapse collapse in">
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
                  </div>
                </div>-->

                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                        Inventario Inicial
                      </a>
                    </h4>
                  </div>
                  <div id="collapseSix" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="row" >
                            <form class="form-horizontal">
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cantidad:</label>
                                        <div class="col-sm-8">
                                            <input id="i_quantity" value=0 type="number" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Precio:</label>
                                        <div class="col-sm-8">
                                            <input id="i_price" type="number" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bodega:</label>
                                        <div class="col-sm-8">
                                            <select id="i_locations" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn btn-primary btn-add-item-price pull-right">Agregar</button>
                                </div>
                            </form>
                            

                        </div>
                        <div class="row" id="div_locations">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Bodega</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="item-price-content">
                                </tbody> 
                            </table>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button id="modal_item_create" type="button" class="btn btn-primary">Crear</button>
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
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <script>
        var tbl_products = null;
        var tbl_products_minimal = null;
        var view = 0;
        var attributesData = [];
        var priceTypeData = [];
        var locationsData = [];
        var itemAttributes = [];
        var uomsData = [];
        var ver = 10;
        fieldValues = [];

        // modals --------
      

        $(document).ready(function() {

            $('#upload-file-selector').click(function(){
              $('#upload-file-selector')[0].files[0] = null;
            });


            $('<option />', {value: 1, text: 'SI' }).appendTo($("#i_bom"));
            $('<option />', {value: 0, text: 'NO' ,selected: "selected"}).appendTo($("#i_bom"));

            $("#viewqty_select").change(function(event) {
                ver = $("#viewqty_select").val();
                tbl_products.ajax.reload();
                tbl_products_minimal.ajax.reload();
            });

            $("#normalview_btn").click(function(event) {
                view = 0;

                $("#div_datas").show();
                $("#div_datas_minimalview").hide();
                tbl_products.ajax.reload();
                tbl_products_minimal.ajax.reload();
                $("#normalview_btn").addClass('active');
                $("#compactview_btn").removeClass('active');
            });
            $("#compactview_btn").click(function(event) {
                view = 1;
                $("#div_datas_minimalview").show();
                $("#div_datas").hide();
                tbl_products_minimal.ajax.reload();
               tbl_products.ajax.reload();
               $("#compactview_btn").addClass('active');
               $("#normalview_btn").removeClass('active');               
               
            });       

            AWApi.get('{{ url('/api/locations') }}',function (response) {
                $("#i_locations").empty();     
                locationsData = response.data.locations;           
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {
                      name += "";
                    }
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#i_locations"));
                }
              });

            AWApi.get('{{ url('/api/brands' ) }}', function (response) {
                $("#i_brand_id").editableSelect('destroy');
                $("#f_brand").editableSelect('destroy');
                $("#i_brand_id").empty();
                $("#f_brand").empty();
                $("#i_brand_id").attr('placeholder', "Ninguna");
                $("#f_brand").attr('placeholder', "TODAS");

                for (var i = 0; i < response.data.brands.length; i++) {
                    $('<option />', {value: response.data.brands[i].id, 
                                    text: response.data.brands[i].name }).appendTo($("#i_brand_id"));
                     $('<option />', {value: response.data.brands[i].id, 
                                    text: response.data.brands[i].name }).appendTo($("#f_brand"));
                }
                $("#i_brand_id").editableSelect();
                $("#f_brand").editableSelect();
            });

            AWApi.get('{{ url('/api/categories' ) }}', function (response) {
                $('<option />', {value: "", text: 'Ninguna' }).appendTo($("#i_category_id"));
                $('<option />', {value: "", text: 'TODAS', selected: true }).appendTo($("#f_category"));

                for (var i = 0; i < response.data.categories.length; i++) {
                    
                    $('<option />', {value: response.data.categories[i].id, 
                                    text: response.data.categories[i].full_route.toUpperCase() }).appendTo($("#i_category_id"));
                    $('<option />', {value: response.data.categories[i].id, 
                                    text: response.data.categories[i].full_route.toUpperCase() }).appendTo($("#f_category"));
                    $('<option />', {value: response.data.categories[i].id, 
                                    text: response.data.categories[i].full_route.toUpperCase() }).appendTo($("#digikey_massive_category"));
                    $('<option />', {value: response.data.categories[i].id, 
                                    text: response.data.categories[i].full_route.toUpperCase() }).appendTo($("#shoes_massive_category"));
                }

                $('<option />', {value: "-1", text: 'Sin Categoría'}).appendTo($("#f_category"));
            });

             AWApi.get('{{ url('/api/item_types' ) }}', function (response) {
                $('<option />', {value: "", text: 'TODAS', selected: true}).appendTo($("#f_item_type"));
                for (var i = 0; i < response.data.item_types.length; i++) {
                    
                    $('<option />', {value: response.data.item_types[i].id, 
                                    text: response.data.item_types[i].name }).appendTo($("#i_item_type_id"));
                    $('<option />', {value: response.data.item_types[i].id, 
                                    text: response.data.item_types[i].name }).appendTo($("#f_item_type"));
                }

                
            });

            AWApi.get('{{ url('/api/unit_of_measures' ) }}', function (response) {
                $("#i_unit_of_measure_id").empty
                for (var i = 0; i < response.data.uoms.length; i++) {
                    
                    $('<option />', {value: response.data.uoms[i].id, 
                                    text: response.data.uoms[i].name }).appendTo($("#i_unit_of_measure_id"));
                }

                
            });

            tbl_products = $('#datas').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "dom": '<"pull-right"l><tr><ip>',
                "searching": false,
                "ajax": function (data, callback, settings) {
                    

                    var filters = new Object();
                    
                    filters.code = $('#f_code').val();
                    filters.name = $('#f_name').val();
                    filters.active_without_stock = $('#f_active_without_stock').val();
                    filters.category_id = $("#f_category").val();
                    filters.item_type = $("#f_item_type").val();
                    filters.brand = $("#f_brand").val();
                    data.filters = filters;
                   

                    AWApi.get('{{ url('/api/items') }}?'+$.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.items
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "scrollX": true,
                "columnDefs": [
                    {
                        "width": "72px", "targets": 0
                    },
                    {
                        "width": "300px", "targets": 1,
                    },
                    {
                        "width": "200px", "targets": 2,
                    },

                    {
                        "width": "100px", "targets":4,
                    }
                ],
                "columns": [
                    { "data": "id", "orderable":false,
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            img_url = "{{ asset('img/defaults/box.png') }}";
                            if(full.image_route != ""){
                                img_url = "{{asset('uploads/items')}}/"+ full.image_route;
                                console.log(img_url);
                            }
                            return '<img src="'+img_url+'" class="rounded" style="width: 80px;height: auto;">';
                        }
                    },
                
                    { "data": "name",
                        render: function ( data, type, full, meta ) {
                            //console.log(full);
                            html = "<strong>" + "Nombre:" + "</strong>";
                            if(full.name){
                                html += "<a class='pull-right'";
                                html += 'href="{{url('items')}}/'+full.id+'" >';

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
                                html +=  "<span class='pull-right'> Ninguna </span> <br> ";
                            }

                            html += "<strong>" + "Marca:" + "</strong>";
                            if(full.brand){
                                html += "<span class='pull-right'>" +full.brand+ "</span> <br>";
                            }else{
                                html +=  "<span class='pull-right'> Ninguna </span> <br> ";
                            }

                            for (var i = 0; i < full.attributes.length; i++) {
                              html += "<div class='row'><div class='col-xs-12'>";
                                html += "<strong>" + full.attributes[i].name + ":</strong>";
                                if(full.attributes[i].type == "url"){
                                  html += "<span class='pull-right attribute-item'>"+'<a href="' +full.attributes[i].value+ '">'+
                                  "Link"+"</a> </span> <br>";
                                }else{
                                  html += "<span class='pull-right attribute-item'>" +full.attributes[i].value+ "</span> <br>";
                                }
                                html += "</div></div>";
                            }

                            return html;
                        }
                    },
                    
                    { "data": "custom_sku",
                        render: function ( data, type, full, meta ) {
                          html = "";

                          html += "<strong>" + "Manufact. SKU:" + "</strong>";
                          if(full.manufacturer_sku){
                              html += "<span class='pull-right'>" +full.manufacturer_sku+ "</span> <br>"
                          }else{
                              html +=  "<span class='pull-right'> - </span> <br> ";
                          }

                          html += "<strong>" + "Custom SKU:" + "</strong>";
                          if(full.custom_sku){
                              html += "<span class='pull-right'>" +full.custom_sku+ "</span> <br>";
                          }else{
                              html +=  "<span class='pull-right'> - </span> <br> ";
                          }

                            
                           
                            
                          

                            return html;
                        }
                    },
                    { "data": "stock", "orderable":false,
                        render: function ( data, type, full, meta ) {
                            console.log(full);
                            stock = 0;
                            for (var i = 0; i < full.stock.length; i++) {
                                if(full.stock[i].location == "TOTAL"){
                                    stock = full.stock[i].amount;
                                }
                            }
                            html = "<div>";
                            html += '<strong style="color: #3c8dbc; font-size: 20px;">' + stock + '</strong> '+full.uom_plural;
                            html += '</div>'
                            return html;
                        }
                    },
                    { "data": "id", "orderable":false,
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i>";
                            edit += "</button>";

                            var variant = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=makeVariant("+data+");>";
                            variant += "<i class='fa fa-lg fa-copy fa-fw' ></i>";
                            variant += "</button>";


                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i>";
                            del += "</button>";
                            return "<div class='btn-group'>"+edit+"  "+del+" "+variant+" </div>";
                        }
                    }
                ]
            });

            tbl_products_minimal = $('#datas_minimalview').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "dom": '<"pull-right"l><tr><ip>',
                "searching": false,
                "ajax": function (data, callback, settings) {
                    var filters = new Object();
                    
                    filters.code = $('#f_code').val();
                    filters.name = $('#f_name').val();
                    filters.active_without_stock = $('#f_active_without_stock').val();
                    filters.category_id = $("#f_category").val();
                    filters.item_type = $("#f_item_type").val();
                    filters.brand = $("#f_brand").val();
                    data.filters = filters;

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
                        "width": "90px", "targets": 3
                    }
                ],
                "columns": [
                    { "data": "name",
                        render: function ( data, type, full, meta ) {
                          html = "";
                            if(full.name){
                                html += "<a ";
                                html += 'href="{{url('items')}}/'+full.id+'" >';

                                if(full.name.length > 40){
                                  name = full.name.substring(0,40) + "...";
                                }else{
                                  name = full.name;
                                }
                                html += name + "</a> <br>"
                            }else{
                                html +=  "<span class='pull-right'> - </span> <br> ";
                            }

                            if(full.manufacturer_sku || full.custom_sku ){
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

                    { "data": "category",
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
                    { "data": "id", "orderable":false,
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
                    { "data": "id", "orderable":false,
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i>";
                            edit += "</button>";

                            var variant = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=makeVariant("+data+");>";
                            variant += "<i class='fa fa-lg fa-copy fa-fw' ></i>";
                            variant += "</button>";


                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i>";
                            del += "</button>";

                            return "<div class='btn-group'>"+edit+"  "+del+" </div>";
                        }
                    }
                ]
            });

            (function setFieldValues() {
                fieldValues['name'] = 'i_name';
                fieldValues['manufacturer_sku'] = 'i_manufacturer_sku';
                fieldValues['description'] = 'i_description'; 
                fieldValues['is_bom'] = 'i_bom';     
                fieldValues['block_discount'] = 'i_block_discount';
  
            })();

            $("#clean").click(function(event) {
                $('#f_code').val("");
                $('#f_name').val("");
                $('#f_active_without_stock').val("0");
                $("#f_category").val("");
                $("#f_item_type").val("");
                $("#f_brand").val("");           
            });

            $('#myButton').click(function () {

                $('.item-price-content').html('');

                AWValidator.clean("modal_item");
                $(".modal-body input").val("");
                $("#div_atribbutes").html("");
                $("#i_image_preview").hide();
                $(".modal-body textarea").val("");
                $('#i_quantity').val(1);

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
                
                    AWApi.get('{{ url('/api/brands' ) }}', function (response) {
                        $("#i_brand_id").editableSelect('destroy');
                        $("#f_brand").editableSelect('destroy');
                        $("#i_brand_id").empty();
                        $("#f_brand").empty();
                        $("#i_brand_id").attr('placeholder', "Ninguna");
                        $("#f_brand").attr('placeholder', "TODAS");

                        for (var i = 0; i < response.data.brands.length; i++) {
                            $('<option />', {value: response.data.brands[i].id, 
                                            text: response.data.brands[i].name }).appendTo($("#i_brand_id"));
                             $('<option />', {value: response.data.brands[i].id, 
                                            text: response.data.brands[i].name }).appendTo($("#f_brand"));
                        }
                        $("#i_brand_id").editableSelect();
                        $("#f_brand").editableSelect();


                        AWApi.get('{{ url('/api/price_types' ) }}', function (response) {
                            priceTypeData = response.data.price_types;
                            for (var i = 0; i < response.data.price_types.length; i++) {
                                $('<option />', {value: response.data.price_types[i].id, 
                                                text: response.data.price_types[i].name }).appendTo($("#i_price_type"));   
                            }

                            $('#modal_item').modal('show');
                        });
                    });

                    

                   
                });
                
            });


            $("#filter").click(function(event) {
                tbl_products.ajax.reload();
                tbl_products_minimal.ajax.reload();
            });

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

            

            $(".btn-add-item-price").click(function(){

                const quantity = $('#i_quantity').val();
                const price    = $('#i_price').val();
                const storage  = $( "#i_locations option:selected" ).text();
                const sval     = $( "#i_locations option:selected" ).val();

                $(".delete-item-price").off("click");

                const content = `
                    <tr>
                        <td class="one">${quantity}</td>
                        <td class="two">${formatMoney(price,'CL')}</td>
                        <td class="three">${storage}</td>
                        <td class="four"><input type="hidden" class="st-id" value="${sval}" /><button type="button" class="btn pull-right delete-item-price btn-danger">Eliminar</button></td>
                    </tr>
                `;

                $(".item-price-content").append(content);

                $('.delete-item-price').click(function() {
                    $(this).parents("tr").remove();
                });

            });

            $('#changeView').click(function () {
                if(view == 0){
                    view = 1;
                    $("#div_datas_minimalview").show();
                    $("#div_datas").hide();
                    tbl_products_minimal.ajax.reload();
                }else{
                    view = 0;
                    $("#div_datas_minimalview").hide();
                    $("#div_datas").show();
                    tbl_products.ajax.reload();
                }
                
            });

            $("#modal_item_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#i_name').val());
                data.append('description', $('#i_description').val());
                data.append('block_discount', $('#i_block_discount').val());
                data.append('brand_id', $('#i_brand_id').val());
                data.append('category_id', $('#i_category_id').val());
                data.append('item_type_id', $('#i_item_type_id').val());
                data.append('unit_of_measure_id', $('#i_unit_of_measure_id').val());
                data.append('custom_sku', $('#i_custom_sku').val());
                data.append('manufacturer_sku', $('#i_manufacturer_sku').val());
                data.append('ean', $('#i_ean').val());
                data.append('upc', $('#i_upc').val());


                var stocks = [];

                $('.item-price-content > tr').each(function() {

                    let values = {
                        quantity : $(this).children('td.one').text(),
                        price : $(this).children('td.two').text().replace(/\./g,''),
                        location : $($(this).children('td.four')).children('input').val()
                    }

                    stocks.push(values);

                });

                var attr = [];
                /*for (var i = 0; i < attributesData.length; i++) {
                    if($("#attribute_value_" + attributesData[i].id).val() !=null && $("#attribute_value_" + attributesData[i].id).val() !=""){
                        if(attributesData[i].type == "date"){
                            attribute = [ attributesData[i].id,  moment($("#attribute_value_" + attributesData[i].id).val(), "DD/MM/YYYY").format("YYYY-MM-DD") ];
                        }else{
                            attribute = [ attributesData[i].id,  $("#attribute_value_" + attributesData[i].id).val() ];
                        }
                        
                        attr.push(attribute);
                        //console.log( $("#attribute_value_" + attributesData[i].id).val() );

                    }
                }*/

                if (attr.lenght > 0) {
                    data.append('attributes', attr);
                }

                if(stocks.length > 0) {
                    data.append('stocks', JSON.stringify(stocks));
                }
                
                location_items = [];
                $(".location-item-div").each(function(index, el) {
                    console.log($(el).attr('id'));
                    console.log($("#uom_select_"+$(el).attr('id')).val());
                    console.log($("#i_item_qty_"+$(el).attr('id')).val());
                    //almacen-cantidad-uom_id
                    location_items.push([$(el).attr('id'), $("#i_item_qty_"+$(el).attr('id')).val(), $("#uom_select_"+$(el).attr('id')).val() ])
                });
                data.append('location_items', location_items);
                data.append('file', $('#upload-file-selector')[0].files[0] );
                
                AWApi.post('{{ url('/api/items') }}', data, function(datas){
                    submit("modal_item", datas, "Crear Producto");
                });
            });

        });

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
                tbl_products.ajax.reload();
                tbl_products_minimal.ajax.reload();
            }
        }

        function makeVariant(id) {

            AWApi.get('{{ url('/api/items' ) }}/'+id, function (response) {
                console.log(response.data.item);
                $("#i_name").val(response.data.item.name);
                $("#i_block_discount").val(response.data.item.block_discount);
                /*$("#i_upc").val(response.data.item.upc);
                $("#i_ean").val(response.data.item.ean);
                $("#i_custom_sku").val(response.data.item.custom_sku);
                $("#i_manufacturer_sku").val(response.data.item.manufacturer_sku);*/
                itemAttributes = response.data.item.attributes;
                $("#i_brand_id").val(response.data.item.brand);
                $("#i_description").val(response.data.item.description);
                $("#i_category_id").val(response.data.item.category_id);

                

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
                                $("#i_attributes option[value='"+attributesData[i].id+"']").remove();
                            }
                        }
                    
                    }

                    
                    if($('#i_attributes option').size() == 0){
                        $("#attributes_add_item").hide();
                    }

                    $("#modal_item").modal('show');
                });

            });
            
        }

        function itemImage(input) {
            $("#i_image_preview").show();
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#i_image_preview').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function del(id) {

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
                    AWApi.delete('{{ url('/api/items' ) }}/'+id,function(data) {

                        if(data.data.errors){
                            if(data.data.errors.unauthorized){
                                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                            }else{
                                swal("Error", data.data.errors, "warning");
                            }
                        }else{
                            swal("Eliminado", "Registro eliminado de forma exitosa", "success");
                        }
                        tbl_products.ajax.reload();
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/items' ) }}/"+id;
        }

        function showMassiveModal() {
            $("#modal_massive_file").modal('show');      
        }

        function showMassiveShoesModal() {
            $("#modal_massive_file_shoes").modal('show');      
        }

        function uploadItemsMassive(){
          var data = new FormData;
            data.append('file', $("#file_massive_digikey")[0].files[0] );
            data.append('category_id', $("#digikey_massive_category").val());
            data.append('type', "digikey");

            swal({
                title: "Carga de productos masiva",
                text: "Los items en el archivo serán cargados. Esta acción puede tomar varios minutos." ,
                type:  "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Continuar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true, 
                showLoaderOnConfirm: true
            },
            function(isConfirm){
                if(isConfirm){
                    AWApi.post('{{ url('/api/items/massive')}}', data, function(response){

                        switch( response.code ){
                            case 200:
                                //200 - OK
                                //Shows modal with datatable
                                console.log(response.data);
                                swal("Ok", response.data, "success");
                                tbl_products.ajax.reload();
                                tbl_products_minimal.ajax.reload();
                                break;

                            case 500:
                                //500 - Internal Server Error
                                //Shows error's swal message
                                swal({
                                    title: "Error",
                                    text: "Ocurrió un error al realizar con la operación." + "<br>" + 
                                        "Intente nuevamente más tarde, si el problema persiste contacte con el administrador.",
                                    html: true,
                                    type:  "error"
                                });
                                break;

                            case 400:
                                console.error("response.code:" + response.code);
                                console.log(response);
                                swal.close();
                                break;
                            default:
                                console.error("response.code:" + response.code);
                                console.log(response);
                                break;
                        }

                    });
                }
            });
           
        }


        function uploadItemsMassiveShoes(){
          var data = new FormData;
            data.append('file', $("#file_massive_shoes")[0].files[0] );
            data.append('category_id', $("#shoes_massive_category").val());
            data.append('type', "shoes");

            swal({
                title: "Carga de productos masiva",
                text: "Los productos en el archivo serán cargados. Esta acción puede tomar varios minutos." ,
                type:  "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Continuar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true, 
                showLoaderOnConfirm: true
            },
            function(isConfirm){
                if(isConfirm){
                    AWApi.post('{{ url('/api/items/massive/shoes')}}', data, function(response){

                        switch( response.code ){
                            case 200:
                                /* Shows success message*/

                                swal("Ok", response.data, "success");
                                tbl_products.ajax.reload();
                                tbl_products_minimal.ajax.reload();
                                break;

                            case 500:
                                /* Shows errors message */
                                swal({
                                    title: "Error",
                                    text: "Ocurrió un error al realizar con la operación." + "<br>" + 
                                        "Intente nuevamente más tarde, si el problema persiste contacte con el administrador.",
                                    html: true,
                                    type:  "error"
                                });
                                break;

                            case 400:
                                swal.close();
                                break;
                            default:

                                break;
                        }

                    });
                }
            });
           
        }
        
    </script>
@endsection	