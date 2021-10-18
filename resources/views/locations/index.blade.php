
@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link href="https://cdn.rawgit.com/atatanasov/gijgo/master/dist/combined/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-archive" style="padding-right: 5px;"></i>Almacenes
       
        
        <!--<button class="btn btn-xs btn-success" onclick="createSubLocation('')"><i class="fa fa-plus" style="padding-right: 5px;"></i> Nuevo Almacén</button>
        -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Almacenes</li>
      </ol>
    </section>
	
	<section class="content">
        <div class="row">

            
            <!-- /.box -->
            <div class="col-xs-12">
                <div class="row">
                    
                    <div id="locations_tree">  
                    </div>
                </div>      
            </div>
<!--
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Filtros</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div> -->
                <!-- /.box-header
                    <div class="box-body">
                      <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <input type="text" class="form-control" id="description" placeholder="Descripción">
                                </div>
                            </div>
                        </div>
                    </div>-->
                <!-- /.box-body 
                    <div class="box-footer">
                        <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                        <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                    </div>-->
                <!-- /.box-footer 
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="myButton"><i class="fa fa-plus"></i></button>                    </div>
                    </div>-->
                    <!-- /.box-header -->
                   <!-- <div class="box-body">
                      <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Descripción</th>
                          <th>Ruta</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                      </table>
                    </div>
                </div>
            </div> -->
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
    <script src="https://cdn.rawgit.com/atatanasov/gijgo/master/dist/combined/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/js/adminlte.min.js"></script>
    <script>

        //variables globales
        table = null;

        fieldValues = [];

        // modals --------
        var rows = [
            [
                {field: 'Padre', type: 'combo', id: 's_location', value: ''}
                
            ],
            [
                {field: 'Código', type: 'text', id: 's_code', value: 'Código'},
                {field: 'Tipo', type: 'combo', id: 's_location_type', value: ''}
            ],
            [
            	{field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ],
            
            [
                {field: 'Dirección', type: 'text', id: 's_address', value: 'Dirección'}
            ],
            [
                {field: 'Descripción', type: 'textarea', rows: '4',id: 's_description', value: 'Descripción'}
            ],
            [
                {field: 'X', type: 'number', id: 's_x', value: 'x'},
                {field: 'Y', type: 'number', id: 's_y', value: 'y'},
                {field: 'Z', type: 'number', id: 's_z', value: 'z'}
            ],
            [
                {field: 'Latitud', type: 'number', id: 's_latitude', value: 'Latitud'},
                {field: 'Longitud', type: 'number', id: 's_longitude', value: 'Latitud'}
            ]
        ];

        var params = {
            title: 'Creaci&oacute;n Almacen',
            rows: rows,
            button: 'Agregar'
        }

        var modal_add_location = "modal_add_location";

        AWModal.create(modal_add_location, params);


        $(document).ready(function() {
        	$("#s_location").parent().hide();
        	$("#s_location_type").parent().hide();
            $("#s_x").parent().hide();
            $("#s_y").parent().hide();
            $("#s_z").parent().hide();
            $("#s_latitude").parent().hide();
            $("#s_longitude").parent().hide();
            AWApi.get('{{ url('/api/locations') }}',function (response) {
                
                $('<option />', {value: '', text:'Ninguno' }).appendTo($("#s_location"));
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location")); 
                }

                createLocationBoxes(response);     
                addBoxesInfo(response);   
                /*$('#locations_tree').tree({
                  dataSource: response.data.locations_tree,
                  autoLoad: true
                });*/
            });

            // data Table --------------
            /*
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

                    //extra data
                    var filters = new Object();
                    
                    filters.name = $('#name').val();
                    filters.description = $('#description').val();

                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/locations' ) }}?' + $.param(data), function (response) {

                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.locations
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "description"},
                    { "data": "name",
                        render: function ( data, type, full, meta ) {

                            route = "";
                            
                            for (var i = full.route.length ; i >0; i--) {
                                if(i==full.route.length){
                                    route += full.route[i-1];
                                }else{
                                    route += "/" + full.route[i-1];
                                }
                                
                            }

                            return route;
                        }
                    },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=edit("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=del("+data+");></i></button>";

                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            */

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';
                fieldValues['code'] = 's_code';
            })();


            // Buttons triggers ------------------
            /*
            $('#filter').click(function(){
                table.ajax.reload();
            });

            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#s_name').val('');
                $('#s_lastname').val('');
                table.ajax.reload();
            });

        */

            $('#myButton').click(function () {
                AWValidator.clean(modal_add_location);
                $(".modal-body input").val("");

                AWApi.get('{{ url('/api/locations') }}',function (response) {
                    $("#s_location").empty();
                    $('<option />', {value: '', text:'Ninguno' }).appendTo($("#s_location"));
                    for (var i = 0; i < response.data.tree.length; i++) {
                        name = "";
                        for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                        name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                        $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location")); 
                    } 
                });

                AWApi.get('{{ url('api/location_types')}}', function (response) {
                    $("#s_location_type").empty();
                    $('<option />', {value: '', text:'Ninguno' }).appendTo($("#s_location"));

                    for (var i = 0; i < response.data.locations.length; i++) {
                        nombre = "";
                        for (var j = response.data.locations[i].route.length; j > 0; j--) {
                            if (j == response.data.locations[i].route.length){
                                nombre += response.data.locations[i].route[j-1];
                            }else{
                                nombre += "/" + response.data.locations[i].route[j-1];
                            }
                        }
                        //$('<option />', {text: nombre, value: response.data.locations[i].id }).appendTo($("#s_location"));
                    }

                });
                
                $('#' +modal_add_location).modal('show');
            });

            $('#' + modal_add_location + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('description', $('#s_description').val());
                data.append('location_id', $('#s_location').val());
                data.append('latitude',$('#s_latitude').val());
                data.append('longitude',$('#s_longitude').val());
                data.append('address', $('#s_address').val());
                data.append('code', $('#s_code').val());
                data.append('location_type_id', $('#s_location_type').val());
                data.append('x',$('#s_x').val());
                data.append('y',$("#s_y").val());
                data.append('z',$("#s_z").val());
                //console.log($("#s_code").val());
                if ($("#s_code").val() === ""){
                    swal({
                        title: "Almacén sin Código",
                        text: "Está a punto de crear un almacén sin código, el sistema proporcionará un código automático. \n ¿Esta seguro de realizar esta acción?'",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "SI",
                        closeOnConfirm: true,
                        cancelButtonText: "NO"
                    },
                    function () {
                        AWApi.post('{{ url('/api/locations') }}', data, function(datas){
                            submit(modal_add_location, datas);

                            AWApi.get('{{ url('/api/locations') }}',function (response) {
                                $("#locations_tree").html("");
                                createLocationBoxes(response);
                                addBoxesInfo(response);
                            });
                        });
                    });
                }else{
                    AWApi.post('{{ url('/api/locations') }}', data, function(datas){
                        submit(modal_add_location, datas);

                        AWApi.get('{{ url('/api/locations') }}',function (response) {
                            $("#locations_tree").html("");
                            createLocationBoxes(response);
                            addBoxesInfo(response);
                        });
                    });
                }
            });

        });

        // extra functions--------

        function submit(id,data){
            var count = 0;
            AWValidator.clean(id);
   
            for (x in data.data.errors)
            {

                if(data.data.errors.unauthorized){
                    swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                } else if(data.data.errors.code){
                    swal("error", data.data.errors.code, "error");
                }
                else{
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                }
                count++;   
            }

            if (count == 0)
            {
                swal("Crear Almacen", "Información actualizada de forma exitosa", "success");
                $('#' +modal_add_location).modal('toggle');
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Almacen",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/locations' ) }}/'+id,function(data) {

                        //table.ajax.reload();
                        if(data.data.errors){
                            if(data.data.errors.unauthorized){
                                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                            }else{
                                swal("Error", data.data.errors, "error");
                            }
                        }else{
                            swal("Eliminar Almacen", "Información actualizada correctamente", "success");
                            AWApi.get('{{ url('/api/locations') }}',function (response) {
                                $("#locations_tree").html("");
                                createLocationBoxes(response);
                                addBoxesInfo(response);
                            });
                        }
                    });
                });
        }

        function generateInfoLoc(data) {
            //console.log(data);
            html = '<div class="row">';
            html += '<div class="col-xs-4">';
            html += '<dl class="dl-horizontal">';

            html += '<dt>Código</dt>';
            if(data.location.code!==null){
                html += '<dd>'+data.location.code+'</dd>';
            }else{
                html += '<dd> - </dd>';
            }

            html += '<dt>Nombre</dt>';
            if(data.location.name!==null){
                html += '<dd>'+data.location.name+'</dd>';
            }else{
                html += '<dd>-</dd>';
            }

            /*html += '<dt>Tipo</dt>';
            if(data.location_type!==null){
                html += '<dd>'+data.location_type.name+'</dd>';
            }else{
                html += '<dd>DEFAULT</dd>';
            */


            html += '<dt>Descripción</dt>';
            if(data.location.description){
                html += '<dd>'+data.location.description+'</dd>';
            }
            else{ html += '<dd>'+"-"+'</dd>'; }

          	html += '<dt>Dirección</dt>';
            if(data.location.address){
                html += '<dd>'+data.location.address+'</dd>';
            }
            else{ html += '<dd>'+"-"+'</dd>'; }

            html += '</dl></div>';

            html += "<div class='col-xs-8'>"
            html += '<div class="row">'+
                    '<div class=" col-xs-6">'+
                      '<div class="small-box bg-green">'+
                       ' <div class="inner">'+
                          '<h3 id="approved-orders-num_'+data.location.id+'">0</h3>'+

                          '<p><strong><h4>Ordenes Aprobadas</h4></strong></p>'+
                        '</div>'+
                        '<div class="icon">'+
                          '<i class="fa fa-check"></i>'+
                       ' </div>'+
                      '  <div class="inner" >'+
                         ' Últimas Ordenes aprobadas:'+
                      '  </div>'+

                        '<div class="inner" id="approved-orders-inner_'+data.location.id+'">'+
                            
                        '</div>'+
                        
      

                      '</div>'+
                   ' </div>'+
                    '<!-- ./col -->'+
                    '<div class="col-xs-6">'+
                      '<!-- small box -->'+
                      '<div class="small-box bg-yellow">'+
                         '<div class="inner">'+
                         ' <h3 id="pending-orders-num_'+data.location.id+'">0</h3>'+
                          '<p><strong><h4>Ordenes Pendientes</h4></strong></p>'+
                        '</div>'+
                        '<div class="icon">'+
                          '<i class="fa fa-exclamation"></i>'+
                        '</div>'+
                        '<div class="inner">'+
                         ' Últimas Ordenes pendientes:'+
                        '</div>'+
                        '<div class="inner" id="pending-orders-inner_'+data.location.id+'">'+

                        '</div>'+

                      '</div>'+
                    '</div>'+
                   ' <!-- ./col -->'+
                    
                '</div>';
            html += "</div>"
            /*
            html += '<div class="col-xs-6">';
            html += '<dl class="dl-vertical">';

            
            
            html += '<dt>Posición (Latitud - Longitud)</dt>';
            if(data.location.latitude && data.location.longitude){
                html += '<dd>'+data.location.latitude +' - '+ data.location.longitude+'</dd>';
            }
            else{ html += '<dd>'+"000.000000 - 000.00000"+'</dd>'; }
            
            html += '<dt>Posición (X - Y - Z)</dt>';
            if(data.location.x && data.location.y && data.location.z){
                html += '<dd>'+data.location.x +" - "+ data.location.y +" - "+ data.location.z+'</dd>';
            }
            else{ html += '<dd>'+"00 - 00 - 00"+'</dd>'; }
            

            html += '</dl></div>';
*/
            html += '</div>';
            /*
            ;*/
            return html;
        }

        function createSubLocation(data) {
            AWValidator.clean(modal_add_location);
                $(".modal-body input").val("");

                AWApi.get('{{ url('/api/locations') }}',function (response) {
                $("#s_location").empty();
                $('<option />', {value: '', text:'Ninguno' }).appendTo($("#s_location"));
                    for (var i = 0; i < response.data.tree.length; i++) {
                        name = "";
                        for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                        name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                        $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location")); 
                    }
                    $("#s_location").val(data);
                });

                AWApi.get('{{ url('api/location_types')}}', function (response) {
                    $("#s_location_type").empty();
                    $('<option />', {value: '', text:'Ninguno' }).appendTo($("#s_location_type"));

                    for (var i = 0; i < response.data.location_types.length; i++) {
                        $('<option />', {text: response.data.location_types[i].name, value: response.data.location_types[i].id }).appendTo($("#s_location_type"));
                    }

                });


                
                $('#' +modal_add_location).modal('show');
        }
        function addBoxesInfo(response) {
        	 for (var k = 0; k < response.data.locations.length; k++) {
        	 	idLocation = response.data.locations[k].id;
        	 	AWApi.get('{{ url('/api/dashboards') }}?type=total_orders_by_location&id='+idLocation,function (r) {
        	 		console.log(r.data);
			          $("#approved-orders-num_"+r.data.id).text(r.data.approved);
			          $("#pending-orders-num_"+r.data.id).text(r.data.pending);
			          console.log( $("#pending-orders-num_"+idLocation).text());
			      });

        	 	 AWApi.get('{{ url('/api/dashboards') }}?type=last_orders_by_location&id='+idLocation,function (response) {
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

			          $("#approved-orders-inner_"+response.data.id).html(html);

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

			          $("#pending-orders-inner_"+response.data.id).html(html);

			      });
            }
        }

        function createLocationBoxes(response) {
            html = "";
                for (var i = 0; i < response.data.locations_tree.length; i++) {
                    html += '<div class="col-xs-12">'
                    html += '<div class="box flat box-solid box-primary" id="loc_box'+response.data.locations_tree[i].location.id+'">';
                    html += '<div class="box-header with-border">';
                    html += '<i class="fa fa-archive"></i> <h3 class="box-title">  [';
                    html += response.data.locations_tree[i].location.code+"] - ";
                    html += '<i>' +response.data.locations_tree[i].location.name+ '</i></h3>';
                    if(response.data.locations_tree[i].location.description){
                        html += '<small> '+response.data.locations_tree[i].location.description +'</small>';
                    }
                    

                    html += '<div class="box-tools pull-right">';

                    html += '<a class="btn btn-xs" href="locations/'+response.data.locations_tree[i].location.id+'"';
                    html += 'data-toggle="tooltip" title="Ver Almacén"><i class="fa fa-eye"></i></a>';
                    html += '<button class="btn btn-primary btn-xs" onclick="del('+response.data.locations_tree[i].location.id+')" ';
                    html += 'data-toggle="tooltip" title="Eliminar Almacén"><i class="fa fa-times"></i></button>';
                    html += '<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Expandir"><i class="fa fa-plus"></i></button>';
                    html += '</div></div> ';
                   
                    html += '<div id="loc_box_body'+response.data.locations_tree[i].location.id +'" class="box-body">';

                    html += generateInfoLoc(response.data.locations_tree[i]);            
                    html += '</div>';
                    html += '<div class="box-footer">';
                    html += '<a class="btn btn-primary pull-right" href="locations/'+response.data.locations_tree[i].location.id+'">Ver Bodega</a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                 $('#locations_tree').append(html);
        }

        function generateChildBox(data) {
            html = "";
            for (var i = 0; i < data.childrens.length; i++) {
                    html += '<div class="col">'
                    html += '<div class="box box-solid box-primary" id="loc_box'+data.childrens[i].location.id+'">';
                    html += '<div class="box-header with-border">';
                    html += '<i class="fa fa-archive"></i> <h3 class="box-title">  ';
                    html += data.childrens[i].location.name + '</h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div></div> ';
                   
                    html += '<div id="loc_box_body'+data.childrens[i].location.id +'" class="box-body">';
                    html += generateInfoLoc(data.childrens[i]);
                    html += '<div class="col-xs-12">'
                    html += '<div class="row pull-right">';
                    html += '<a class="btn btn-primary" href="locations/'+data.childrens[i].location.id+'">Ver </a> ';
                    html += '<button class="btn btn-primary" onclick="createSubLocation('+data.childrens[i].location.id+')">Crear Sub-Almacen</button> ';
                    html += '<button class="btn btn-danger" onclick="del('+data.childrens[i].location.id+')">Eliminar </button>';
                    html += '</div></div>';
                    html += '<h4>Sub-Almacenes:</h4>'
                    html += generateChildBox(data.childrens[i]);
                    html += '</div></div></div>';
                }
            return html;
        }

        function edit(id) {
            window.location.href = "{{ url( '/locations' ) }}/"+id;

        }
    </script>
@endsection