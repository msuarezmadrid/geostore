@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}} ">
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sistema
        <small>Valores por defecto</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Sistema</li>
        <li class="active">Configuración de Valores</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
              <div class="col-xs-12 col-md-6">
                <div class="box box-primary flat box-solid">
                    <div class="box-header">

                        <h3 class="box-title">Categorias</h3>
                        
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>

                    </div>  
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="myButton_categories"><i class="fa fa-plus"></i></button>
                        </div>

                        <table id="datas_categories" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="box box-primary flat box-solid">
                    <div class="box-header">

                        <h3 class="box-title">Precios</h3>
                        
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>

                    </div>  
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="myButton_price_types"><i class="fa fa-plus"></i></button>
                        </div>

                        <table id="datas_price_types" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Descripción</th>
                              <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                  <div class="box box-primary flat box-solid">
                    <div class="box-header">

                        <h3 class="box-title">Descuentos POS</h3>
                        
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>

                    </div>  
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="add_discount"><i class="fa fa-plus"></i></button>
                        </div>

                        <table id="datas_add_discount" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Porcentaje</th>
                              <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                @if($app == 1)
                <div class="box box-primary flat box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Formas de pago de aplicación</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="addApp"><i class="fa fa-plus"></i></button>
                        </div>
                        <table id="data_apps" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @endif

            </div>
            
            <div class="col-xs-12 col-md-6">

                  
                

                <div class="box box-primary flat box-solid ">
                    <div class="box-header">
                        <h3 class="box-title">Atributos de Productos</h3>    
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>  
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="add_attribute"><i class="fa fa-plus"></i></button>
                        </div>
                        <table id="datas_attributes" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Tipo</th>
                              <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="box box-primary flat box-solid">
                    <div class="box-header">

                        <h3 class="box-title">Marcas</h3>
                        
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>

                    </div>  
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            
                            <button type="button" class="btn btn-primary" id="myButtonBrand"><i class="fa fa-plus"></i></button>
                            
                        </div>
                      <table id="datas_brands" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                      </table>
                    </div>
                </div>

                <div class="box box-primary flat box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Vendedores</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="addEmployee"><i class="fa fa-plus"></i></button>
                        </div>
                        <table id="data_employees" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>


                <div class="box box-primary flat box-solid" hidden>
                    <div class="box-header">

                        <h3 class="box-title">Tipos de Almacenes</h3>
                        
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>

                    </div>  
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            
                            <button type="button" class="btn btn-primary" id="myButton"><i class="fa fa-plus"></i></button>
                            
                        </div>
                      <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                      </table>
                    </div>
                </div>

            
            </div>

          

           
            
                

            

<!--
            <div class="box box-primary flat box-solid">
                <div class="box-header">

                    <h3 class="box-title">Unidades de Medida</h3>
                    
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>

                </div>  
                
                <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" id="myButton_uom"><i class="fa fa-plus"></i></button>
                    </div>

                    <table id="datas_uom" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Nombre Largo</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div> -->
            <!-- /.box -->
<!--
            <div class="box box-primary flat box-solid">
                <div class="box-header">

                    <h3 class="box-title">Conversión de Unidades</h3>
                    
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>

                </div>  
                
                <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" id="myButton_uom_conversion"><i class="fa fa-plus"></i></button>
                    </div>

                    <table id="datas_uom_conversion" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>UOM Origen</th>
                          <th>Factor</th>
                          <th>UOM Destino</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div> -->

            
            
            
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
	<script src="{{ asset('js/awsidebar.js') }}"></script>

	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
     <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
	<!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>

        //variables globales
        table = null;
        table_attributes = null;
        table_uom = null;
        table_uom_conversion = null;
        table_categories = null;
        table_brands = null;
        fieldValues = [];
        edit_employee_id = null;

        edit_category_id = null;
        edit_brand_id = null;
        edit_uom_conversion_id = null;
        edit_attribute_id = null;
        edit_uom_id = null;
        edit_location_type_id = null;
        edit_price_type_id = null;
        edit_discount_id = null;
        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Agregar Tipo de Almacén',
            rows: rows
        }
        var modal_location_type = "modal_location_type";
        AWModal.create(modal_location_type, params);


        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'add_brand_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Agregar Marca',
            rows: rows
        }
        var modal_brand = "modal_brand";
        AWModal.create(modal_brand, params);

        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'edit_location_type_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Agregar Tipo de Almacén',
            rows: rows
        }
        var modal_edit_location_type = "modal_edit_location_type";
        AWModal.create(modal_edit_location_type, params, 'update');


        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'add_attribute_name', value: 'Nombre'},
                {field: 'Tipo', type: 'combo', id: 'add_attribute_type', value: 'Tipo'}
            ]
        ];
        var params = {
            title: 'Agregar Atributo',
            rows: rows
        }
        var modal_add_attribute = "modal_add_attribute";
        AWModal.create(modal_add_attribute, params);



        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'add_discount_name', value: 'Nombre'},
                {field: 'Porcentaje', type: 'number', id: 'add_discount_percent', value: 'Porcentaje'}
            ]
        ];
        var params = {
            title: 'Agregar Descuento',
            rows: rows
        }
        var modal_add_discount = "modal_add_discount";
        AWModal.create(modal_add_discount, params);


        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'edit_discount_name', value: 'Nombre'},
                {field: 'Porcentaje', type: 'number', id: 'edit_discount_percent', value: 'Porcentaje'}
            ]
        ];
        var params = {
            title: 'Editar Descuento',
            rows: rows
        }
        var modal_edit_discount = "modal_edit_discount";
        AWModal.create(modal_edit_discount, params, 'update');


        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'edit_attribute_name', value: 'Nombre'},
                {field: 'Tipo', type: 'combo', id: 'edit_attribute_type', value: 'Tipo'}
            ]
        ];
        var params = {
            title: 'Editar Atributo',
            rows: rows
        }
        var modal_edit_attribute = "modal_edit_attribute";
        AWModal.create(modal_edit_attribute, params, 'update');

        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'edit_price_type_name', value: 'Nombre'}
            ],
            [
                {field: 'Descripción', type: 'textarea', rows: 2, id: 'edit_price_type_description', value: 'Descripción'}
            ]
        ];
        var params = {
            title: 'Editar Precio',
            rows: rows
        }
        var modal_edit_price_type = "modal_edit_price_type";
        AWModal.create(modal_edit_price_type, params, 'update');

        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'add_price_type_name', value: 'Nombre'}
            ]
            ,
            [
                {field: 'Descripción', type: 'textarea', rows: 2, id: 'add_price_type_description', value: 'Descripción'}
            ]
        ];
        var params = {
            title: 'Agregar Precio',
            rows: rows
        }
        var modal_add_price_type = "modal_add_price_type";
        AWModal.create(modal_add_price_type, params);

		/*
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'add_uom_name', value: 'Nombre'}
            ],
            [
                {field: 'Nombre Largo', type: 'text', id: 'add_uom_longname', value: 'Nombre Largo'}
            ]
        ];
        var params = {
            title: 'Agregar Unidad de Medida',
            rows: rows
        }
        var modal_add_uom = "modal_add_uom";
        AWModal.create(modal_add_uom, params);


        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'edit_uom_name', value: 'Nombre'}
            ],
            [
                {field: 'Nombre Largo', type: 'text', id: 'edit_uom_longname', value: 'Nombre Largo'}
            ]
        ];
        var params = {
            title: 'Editar Unidad de Medida',
            rows: rows
        }
        var modal_edit_uom = "modal_edit_uom";
        AWModal.create(modal_edit_uom, params);

         var rows = [
            [
                {field: 'UOM Origen', type: 'combo', id: 'conv_uom_from_id', value: 'UOM Origen'},
                {field: 'Factor', type: 'text', id: 'conv_factor', value: 'Factor'},
                {field: 'UOM Destino', type: 'combo', id: 'conv_uom_to_id', value: 'UOM Destino'}
            ]
        ];
        var params = {
            title: 'Agregar Conversión',
            rows: rows
        }
        var modal_add_uom_conversion = "modal_add_uom_conversion";
        AWModal.create(modal_add_uom_conversion, params);

        var rows = [
            [
                {field: 'UOM Origen', type: 'combo', id: 'edit_conv_uom_from_id', value: 'UOM Origen'},
                {field: 'Factor', type: 'text', id: 'edit_conv_factor', value: 'Factor'},
                {field: 'UOM Destino', type: 'combo', id: 'edit_conv_uom_to_id', value: 'UOM Destino'}
            ]
        ];
        var params = {
            title: 'Editar Conversión',
            rows: rows
        }
        var modal_edit_uom_conversion = "modal_edit_uom_conversion";
        AWModal.create(modal_edit_uom_conversion, params);
        */

         var rows = [
            [
                {field: 'Padre', type: 'combo', id: 'cat_category_id', value: 'Padre'},
                {field: 'Nombre', type: 'text', id: 'cat_name', value: 'Nombre'}
            ]
        ];

        var params = {
            title: 'Agregar Categoría',
            rows: rows
        }

        var modal_add_category = "modal_add_category";

        AWModal.create(modal_add_category, params);

         var rows = [
            [
                {field: 'Padre', type: 'combo', id: 'edit_cat_category_id', value: 'Padre'},
                {field: 'Nombre', type: 'text', id: 'edit_cat_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Editar Categoría',
            rows: rows,
            button: "Guardar"
        }

        var modal_edit_category = "modal_edit_category";
        AWModal.create(modal_edit_category, params, 'update');

         var rows = [
            [
                {field: 'Nombre', type: 'text', id: 'edit_brand_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Editar Marca',
            rows: rows,
            button: "Guardar"
        }

        var modal_edit_brand = "modal_edit_brand";
        AWModal.create(modal_edit_brand, params, 'update');

        rows = [
            [{ field: 'Nombre', type: 'text', id: 'add_employee_name', value: 'Nombre'}]
        ];
        params = {
            title : 'Agregar Vendedor',
            rows  : rows
        };
        var modal_employee = "modal_employee";
        AWModal.create(modal_employee, params);


        rows = [
            [{ field: 'Nombre', type:'text', id:'edit_employee_name', value:'Nombre' }]
        ];
        params = {
            title: 'Editar Vendedor',
            rows: rows,
            button: 'Guardar'
        };
        var modal_edit_employee = "modal_edit_employee";
        AWModal.create(modal_edit_employee, params, 'update');

        rows = [
            [{ field: "Nombre", type:'text', id:'add_app_payment_type_name', value:'Nombre'}]
        ];

        params = {
            title: 'Agregar Tipo de pago por aplicación',
            rows: rows
        }; 

        let modal_app = "modal_app";
        AWModal.create(modal_app, params);



        
        $(document).ready(function() {

        	$('<option />', {value: 'text', text:'Texto' }).appendTo($("#add_attribute_type"));
        	$('<option />', {value: 'date', text:'Fecha' }).appendTo($("#add_attribute_type"));
        	$('<option />', {value: 'integer', text:'Número Entero' }).appendTo($("#add_attribute_type"));
        	$('<option />', {value: 'float', text:'Número Decimal' }).appendTo($("#add_attribute_type"));

            $('<option />', {value: 'text', text:'Texto' }).appendTo($("#edit_attribute_type"));
            $('<option />', {value: 'date', text:'Fecha' }).appendTo($("#edit_attribute_type"));
            $('<option />', {value: 'integer', text:'Número Entero' }).appendTo($("#edit_attribute_type"));
            $('<option />', {value: 'float', text:'Número Decimal' }).appendTo($("#edit_attribute_type"));

            AWApi.get('{{ url('/api/unit_of_measures' ) }}', function (response) {
                for (var i = 0; i < response.data.uoms.length; i++) {
                    
                    $('<option />', {value: response.data.uoms[i].id, text:response.data.uoms[i].name }).appendTo($("#conv_uom_from_id"));
                    $('<option />', {value: response.data.uoms[i].id, text:response.data.uoms[i].name }).appendTo($("#conv_uom_to_id"));
                }
            });

            // data Table --------------

            table_employees = $("#data_employees").DataTable({
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {
                    AWApi.get('{{ url('/api/sellers' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.total,
                            recordsFiltered: response.data.filtered,
                            data: response.data.rows
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editEmployee("+data+");> ";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delEmployee("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });


            table_brands = $('#datas_brands').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/brands' ) }}?' + $.param(data), function (response) {
                        
                        console.log(response);
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.brands
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editBrand("+data+");> ";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delBrand("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            table = $('#datas').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/location_types' ) }}?' + $.param(data), function (response) {
                        
                        console.log(response);
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.location_types
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editLocationType("+data+");>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });


            table_attributes = $('#datas_attributes').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/attributes' ) }}?' + $.param(data), function (response) {
                        
                        
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.attributes
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "type",
                        render: function ( data, type, full, meta ) {
                            if(data == "text"){
                                name = "Texto";
                            }else if(data == "date"){
                                name = "Fecha";
                            }else if(data == "integer"){
                                name = "Número Entero";
                            }else if(data == "float"){
                                name = "Número Decimal";
                            }else{
                                name = data;
                            }


                            return name;
                        }
                    },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editAttribute("+data+");>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delAttribute("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });
            /*
            table_uom = $('#datas_uom').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/unit_of_measures' ) }}', function (response) {
                        
                        
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.uoms
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "longname"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=editUnitOfMeasure("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=delUnitOfMeasure("+data+");></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            table_uom_conversion = $('#datas_uom_conversion').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/unit_of_measure_conversions' ) }}', function (response) {
                        
                        
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.uoms_conversions
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "uom_from.name"},
                    { "data": "factor"},
                    { "data": "uom_to.name"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=editUOMConversion("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=delUnitOfMeasureConversion("+data+");></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });
            */
            table_categories = $('#datas_categories').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/categories' ) }}?' + $.param(data), function (response) {
                        
                        
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.categories
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "full_route"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editCategory("+data+");>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delCategory("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            table_price_types = $('#datas_price_types').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/price_types' ) }}?' + $.param(data), function (response) {
                        
                        
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.price_types
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "description"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editPriceType("+data+");>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delPriceType("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            table_discount = $('#datas_add_discount').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    AWApi.get('{{ url('/api/discount' ) }}?' + $.param(data), function (response) {
                        
                        
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.discounts
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false},
                    { "data": "name"},
                    { "data": "percent"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=editDiscount("+data+");>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delDiscount("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            @if($app == 1)
            table_app = $("#data_apps").DataTable({
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {
                    AWApi.get('{{ url('/api/AppPaymentTypes' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.rows
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delApp("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+del+"</div>";
                        }
                    }
                ]
            });
            @endif



            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';          
            })();



            $('#myButton').click(function () {
                AWValidator.clean(modal_location_type);
                $(".modal-body input").val("");
				$('#color_s_color').colorpicker('setValue','#003399');
                $('#' +modal_location_type).modal('show');
            });

            $('#myButtonBrand').click(function () {
                console.log("yey?");
                AWValidator.clean(modal_brand);
                $(".modal-body input").val("");
                $('#' +modal_brand).modal('show');
            });

            $('#addEmployee').click(function() {
                AWValidator.clean(modal_employee);
                $(".modal-body input").val("");
                $('#' +modal_employee).modal('show');
            });

            /*
            $('#myButton_uom').click(function () {
                AWValidator.clean(modal_add_uom);
                $(".modal-body input").val("");
                $('#' +modal_add_uom).modal('show');
            });
            
            $('#myButton_uom_conversion').click(function () {
                AWValidator.clean(modal_add_uom_conversion);
                $(".modal-body input").val("");
                $('#' +modal_add_uom_conversion).modal('show');
                

                AWApi.get('{{ url('/api/unit_of_measures' ) }}', function (response) {
                    $("#conv_uom_from_id").empty();
                    $("#conv_uom_to_id").empty();
                    for (var i = 0; i < response.data.uoms.length; i++) {
                        
                        $('<option />', {value: response.data.uoms[i].id, text:response.data.uoms[i].name }).appendTo($("#conv_uom_from_id"));
                        $('<option />', {value: response.data.uoms[i].id, text:response.data.uoms[i].name }).appendTo($("#conv_uom_to_id"));
                    }
                });
            });
            */
            $('#myButton_categories').click(function () {
                AWValidator.clean(modal_add_category);
                $(".modal-body input").val("");
                AWApi.get('{{ url('/api/categories' ) }}', function (response) {
                    $("#cat_category_id").empty();
                    $('<option />', {value: "", text: "Ninguna" }).appendTo($("#cat_category_id"));
                    for (var i = 0; i < response.data.categories.length; i++) {
                        
                        $('<option />', {value: response.data.categories[i].id, text:response.data.categories[i].full_route.toUpperCase() }).appendTo($("#cat_category_id"));
                        
                    }
                });
                $('#' +modal_add_category).modal('show');
            });
            

            $('#myButton_price_types').click(function () {
                AWValidator.clean(modal_add_price_type);
                $(".modal-body input").val("");
                $('#' +modal_add_price_type).modal('show');
            });
         

            $('#add_attribute').click(function () {
                AWValidator.clean(modal_add_attribute);
                $(".modal-body input").val("");
                $('#' +modal_add_attribute).modal('show');
            });

             $('#add_discount').click(function () {
                AWValidator.clean(modal_add_discount);
                $(".modal-body input").val("");
                $('#' +modal_add_discount).modal('show');
            });



            $('#' + modal_location_type + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                AWApi.post('{{ url('/api/location_types') }}', data, function(datas){
                    submit(modal_location_type, datas, "Crear Tipo de Almacén");
                });
            });

            $('#' + modal_edit_location_type + "_update").click(function(){

                var data = new FormData();
                
                data.append('name', $('#edit_location_type_name').val());
                AWApi.put('{{ url('/api/location_types') }}/'+edit_location_type_id, data, function(datas){
                    submit(modal_edit_location_type, datas, "Editar Tipo de Almacén");
                });
            });

            $('#' + modal_edit_brand + "_update").click(function(){

                var data = new FormData();
                
                data.append('name', $('#edit_brand_name').val());
                AWApi.put('{{ url('/api/brands') }}/'+edit_brand_id, data, function(datas){
                    submit(modal_brand, datas, "Editar Marca");
                    $(".modal").modal('hide');
                    table_brands.ajax.reload();
                });
            });

            $('#' + modal_edit_employee + "_update").click(function(){
                var data = new FormData();
                data.append('name', $('#edit_employee_name').val());
                AWApi.put('{{ url('/api/sellers') }}/'+edit_employee_id, data, function(datas){
                    submit(modal_edit_employee, datas, "Editar Vendedor");
                    $(".modal").modal('hide');
                    table_employees.ajax.reload();
                });
            });


            $('#' + modal_employee +'_create').click( function() {
                var data = new FormData();
                data.append('name', $('#add_employee_name').val());
                AWApi.post('{{ url('/api/sellers') }}', data, function(datas){
                    submit(modal_employee, datas, "Crear Vendedor");
                    table_employees.ajax.reload();
                });
            });


            $('#' + modal_add_attribute + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#add_attribute_name').val());
                data.append('type', $('#add_attribute_type').val());
                AWApi.post('{{ url('/api/attributes') }}', data, function(datas){
                    submit(modal_add_attribute, datas, "Crear Atributo");
                });

            });
            

             $('#' + modal_add_discount + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#add_discount_name').val());
                data.append('percent', $('#add_discount_percent').val());
                AWApi.post('{{ url('/api/discount') }}', data, function(datas){
                    submit(modal_add_discount, datas, "Crear Descuento");
                });

            });

            $('#' + modal_add_price_type + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#add_price_type_name').val());
                data.append('description', $('#add_price_type_description').val());
                AWApi.post('{{ url('/api/price_types') }}', data, function(datas){
                    submit(modal_add_price_type, datas, "Crear Precio");
                    table_price_types.ajax.reload();
                });

            });

            $('#' + modal_brand + "_create").click(function(){
                var data = new FormData();
                data.append('name', $('#add_brand_name').val());
                AWApi.post('{{ url('/api/brands') }}', data, function(datas){
                    submit(modal_brand, datas, "Crear Marca");
                    table_brands.ajax.reload();
                });
            });


            $('#' + modal_edit_attribute + "_update").click(function(){

                var data = new FormData();
                data.append('name', $('#edit_attribute_name').val());
                data.append('type', $('#edit_attribute_type').val());
                AWApi.put('{{ url('/api/attributes') }}/'+ edit_attribute_id, data, function(datas){
                    submit(modal_edit_attribute, datas, "Editar Atributo");
                });
            });

            $('#' + modal_edit_price_type + "_update").click(function(){

                var data = new FormData();
                data.append('name', $('#edit_price_type_name').val());
                data.append('description', $('#edit_price_type_description').val());
                AWApi.put('{{ url('/api/price_types') }}/'+ edit_price_type_id, data, function(datas){
                    submit(modal_edit_price_type, datas, "Editar Precio");
                    table_price_types.ajax.reload();
                });
            });

            $('#' + modal_edit_discount + "_update").click(function(){

                var data = new FormData();
                data.append('name', $('#edit_discount_name').val());
                data.append('percent', $('#edit_discount_percent').val());
                AWApi.put('{{ url('/api/discount') }}/'+ edit_discount_id, data, function(datas){
                    submit(modal_edit_discount, datas, "Editar Precio");
                    table_discount.ajax.reload();
                });
            });
            /*
            $('#' + modal_add_uom + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#add_uom_name').val());
                data.append('longname', $('#add_uom_longname').val());
                AWApi.post('{{ url('/api/unit_of_measures') }}', data, function(datas){
                    submit(modal_add_uom, datas, "Crear Unidad de Medida");
                });
            });
            $('#' + modal_edit_uom + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#edit_uom_name').val());
                data.append('longname', $('#edit_uom_longname').val());
                AWApi.put('{{ url('/api/unit_of_measures') }}/'+edit_uom_id, data, function(datas){
                    submit(modal_edit_uom, datas, "Editar Unidad de Medida");
                });
            });
                        $('#' + modal_add_uom_conversion + "_create").click(function(){

                var data = new FormData();
                data.append('uom_from_id', $('#conv_uom_from_id').val());
                data.append('uom_to_id', $('#conv_uom_to_id').val());
                data.append('factor', $('#conv_factor').val());
                AWApi.post('{{ url('/api/unit_of_measure_conversions') }}', data, function(datas){
                    submit(modal_add_uom_conversion, datas, "Crear Conversión");
                });
            });

            $('#' + modal_edit_uom_conversion + "_create").click(function(){

                var data = new FormData();
                data.append('uom_from_id', $('#edit_conv_uom_from_id').val());
                data.append('uom_to_id', $('#edit_conv_uom_to_id').val());
                data.append('factor', $('#edit_conv_factor').val());

                AWApi.put('{{ url('/api/unit_of_measure_conversions') }}/'+ edit_uom_conversion_id, data, function(datas){
                    submit(modal_edit_uom_conversion, datas, "Editar Conversión");
                });
            });
            */
            $('#' + modal_add_category + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#cat_name').val());
                data.append('category_id', $('#cat_category_id').val().toUpperCase());
                AWApi.post('{{ url('/api/categories') }}', data, function(datas){
                    submit(modal_add_category, datas, "Crear Categoría");
                });
                table_categories.ajax.reload();
            });

             $('#' + modal_edit_category + "_update").click(function(){

                var data = new FormData();
                data.append('name', $('#edit_cat_name').val());
                data.append('category_id', $('#edit_cat_category_id').val().toUpperCase());
                AWApi.put('{{ url('/api/categories') }}/' + edit_category_id, data, function(datas){
                    submit(modal_edit_category, datas, "Editar Categoría");
                    table_categories.ajax.reload();
                });
            });
			
			$('#color_s_color').colorpicker();+

            $('#addApp').click(function() {
                AWValidator.clean(modal_app);
                $(".modal-body input").val("");
                $('#' +modal_app).modal('show');
            });
            
            $('#' + modal_app +'_create').click( function() {
                var data = new FormData();
                data.append('name', $('#add_app_payment_type_name').val());
                AWApi.post('{{ url('/api/AppPaymentTypes') }}', data, function(datas){
                    submit(modal_app, datas, "Crear Forma de pago por aplicación");
                    table_app.ajax.reload();
                });
            });

        });

        // extra functions--------

        function submit(id,data)
        {
            var count = 0;
            AWValidator.clean(id);
   
            for (x in data.data.errors)
            {
                if(data.data.errors.unauthorized){
                    swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                }
                else{
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                }
                count++;   
            }

            if (count == 0)
            {
                swal("Crear Tipo de Almacen", "Información actualizada de forma exitosa", "success");
                $('#' +id).modal('hide');
                table.ajax.reload();
                table_attributes.ajax.reload();
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
                table.ajax.reload();
                table_attributes.ajax.reload();
                table_discount.ajax.reload();

                table_categories.ajax.reload();
            }
        }

        function del(id) {

            swal({
                    title: "Eliminar Tipo de Almacén",
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
                    AWApi.delete('{{ url('/api/location_types' ) }}/'+id,function(data) {

                        table.ajax.reload();
                        if(data.data.errors){
                            if(data.data.errors.unauthorized){
                                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                            }else{
                                swal("Error", data.data.errors, "error");
                            }
                        }else{
                            swal("Eliminar Tipo de Almacén", "Información actualizada correctamente", "success");
                        }
                    });
                });
        }

        function delUnitOfMeasure(id) {

            swal({
                    title: "Eliminar Unidad de Medida",
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
                    AWApi.delete('{{ url('/api/unit_of_measures' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Unidad de Medida");
                    });
                });
        }

        function delUnitOfMeasureConversion(id) {

            swal({
                    title: "Eliminar Conversión",
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
                    AWApi.delete('{{ url('/api/unit_of_measure_conversions' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Conversión");
                    });
                });
        }

        function delCategory(id) {

            swal({
                    title: "Eliminar Categoría",
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
                    AWApi.delete('{{ url('/api/categories' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Categoría");
                    });
                    table_categories.ajax.reload();
                });
        }

        function delPriceType(id) {

            swal({
                    title: "Eliminar Precio",
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
                    AWApi.delete('{{ url('/api/price_types' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Precio");
                        table_price_types.ajax.reload();
                    });
                    
                });
        }
        function delDiscount(id) {

            swal({
                    title: "Eliminar Descuento",
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
                    AWApi.delete('{{ url('/api/discount' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Descuento");
                        table_discount.ajax.reload();
                    });
                    
                });
        }

        function delAttribute(id) {

            swal({
                    title: "Eliminar Atributo",
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
                    AWApi.delete('{{ url('/api/attributes' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Atributo");
                    });
                });
        }

        function delBrand(id) {

            swal({
                    title: "Eliminar Marca",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/brands' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Marca");
                        table_brands.ajax.reload();
                    });

                });
        }
        
        function delApp(id) {     
            swal({
                    title: "Eliminar aplicación de tipo de pago",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/AppPaymentTypes' ) }}/'+id,function(data) {
                        table_app.ajax.reload();
                    });
            });
        }
        

        function editCategory(id) {
            edit_category_id = id;
            AWApi.get('{{ url('/api/categories' ) }}/'+id+"?with=possibleFathers", function (response) {
                 $("#edit_cat_category_id").empty();
                 console.log(response);
                $('<option />', {value: "", text: "Ninguna" }).appendTo($("#edit_cat_category_id"));

                for (var i = 0; i < response.data.categories.length; i++) {
                    $('<option />', {value: response.data.categories[i].id, text:response.data.categories[i].full_route.toUpperCase() }).appendTo($("#edit_cat_category_id"));
                    
                }
                
                AWApi.get('{{ url('/api/categories' ) }}/'+id,function(data) {
                    $("#edit_cat_name").val(data.data.name.toUpperCase());
                    $("#edit_cat_category_id").val(data.data.category_id);
                    $("#"+modal_edit_category).modal('show'); 
                });

            });
        }

        function editPriceType(id) {
            edit_price_type_id = id;
            AWApi.get('{{ url('/api/price_types' ) }}/'+id,function(data) {
                $("#edit_price_type_name").val(data.data.name);
                $("#edit_price_type_description").text(data.data.description);
                $("#"+modal_edit_price_type).modal('show'); 
            });

        }

        function editDiscount(id) {
            edit_discount_id = id;
            AWApi.get('{{ url('/api/discount' ) }}/'+id,function(data) {
                $("#edit_discount_name").val(data.data.name);
                $("#edit_discount_percent").val(data.data.percent);
                $("#"+modal_edit_discount).modal('show'); 
            });

        }

        function editBrand(id) {
            edit_brand_id = id;
            AWApi.get('{{ url('/api/brands' ) }}/'+id,function(data) {
                $("#edit_brand_name").val(data.data.name);
                $("#"+modal_edit_brand).modal('show'); 
            });

           
        }

        function editEmployee(id) {
            edit_employee_id = id;
            AWApi.get('{{ url('/api/sellers' ) }}/'+id,function(data) {
                $("#edit_employee_name").val(data.data.name);
                $("#"+modal_edit_employee).modal('show'); 
            });
        }



         function editLocationType(id) {
            edit_location_type_id = id;
            AWApi.get('{{ url('/api/location_types' ) }}/'+id, function (response) {
                 
                $("#edit_location_type_name").val(response.data.name);
                $("#"+modal_edit_location_type).modal('show'); 

            });
        }

        function editUnitOfMeasure(id) {
            edit_uom_id = id;
            AWApi.get('{{ url('/api/unit_of_measures' ) }}/'+id, function (response) {
                 
                $("#edit_uom_name").val(response.data.name);
                $("#edit_uom_longname").val(response.data.longname);
                $("#"+modal_edit_uom).modal("show");
            });
        }

        function editUOMConversion(id) {
            edit_uom_conversion_id = id;

            AWApi.get('{{ url('/api/unit_of_measures' ) }}', function (response) {
                $("#edit_conv_uom_from_id").empty();
                $("#edit_conv_uom_to_id").empty();
                for (var i = 0; i < response.data.uoms.length; i++) {
                    $('<option />', {value: response.data.uoms[i].id, text:response.data.uoms[i].name }).appendTo($("#edit_conv_uom_from_id"));
                    $('<option />', {value: response.data.uoms[i].id, text:response.data.uoms[i].name }).appendTo($("#edit_conv_uom_to_id"));
                }
                AWApi.get('{{ url('/api/unit_of_measure_conversions' ) }}/'+id, function (response) {
                    $("#edit_conv_uom_from_id").val(response.data.uom_from_id);
                    $("#edit_conv_uom_to_id").val(response.data.uom_to_id);
                    $("#edit_conv_factor").val(response.data.factor);
                    $("#"+modal_edit_uom_conversion).modal("show");
                });
            });
           
        }

        function delEmployee(id) {     
            swal({
                    title: "Eliminar Vendedor",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/sellers' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Vendedor");
                        table_employees.ajax.reload();
                    });
            });
        }


        function editAttribute(id) {
            edit_attribute_id = id;
            AWApi.get('{{ url('/api/attributes' ) }}/'+id, function (response) {
                $("#edit_attribute_type").val(response.data.type);
                $("#edit_attribute_name").val(response.data.name);
                $("#"+modal_edit_attribute).modal("show");
            });
        }

        function edit(id) {
            window.location.href = "{{ url( '/roles' ) }}/"+id;
        }
    </script>
@endsection