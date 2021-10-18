@extends('layouts.master')

@section('css')
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 id="h1">Almacén
      </h1>
      
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}">Inicio</a></li>
        <li><a href="{{url('locations')}}">Almacenes</a></li>
        <li class="active" id="breadcrumb_active">Orden de Entrada</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary box-solid flat">
                  <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-info" style="padding-right: 5px;"></i> Información General</h3>                  </div>
                  <div class="box-body" id="form">
                    <div class="row" hidden>

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="location_id">Padre</label>
                              <select class="form-control" id="location_id" placeholder="Almacén Padre"></select>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="location_type_id">Tipo de Almacén</label>
                              <select class="form-control" id="location_type_id" placeholder="Tipo Almacén"></select>
                          </div>
                      </div>   
                    </div>



                    <div class="row">
                      <div class="col-md-6 col-xs-6">
                          <div class="form-group">
                              <label for="code">Código</label>
                              <input type="text" class="form-control" id="code" placeholder="Código">
                          </div>
                      </div>
                      <div class="col-md-6 col-xs-6">
                          <div class="form-group">
                              <label for="name">Nombre</label>
                              <input type="text" class="form-control" id="name" placeholder="Nombre">
                          </div>
                      </div>                   
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="address">Dirección</label>
                              <input type="text" class="form-control" id="address" placeholder="Dirección">
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="description">Descripción</label>
                              <textarea rows="4" class="form-control" id="description" placeholder="Descripción"></textarea>
                          </div>
                      </div>

                      
                    </div>

                    
                    <div class="row" hidden>
                      <div class="col-md-3">
                          <div class="form-group">
                              <label for="latitude">Latitud</label>
                              <input type="text" class="form-control" id="latitude" placeholder="Latitud">
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="form-group">
                              <label for="longitude">Longitud</label>
                              <input type="text" class="form-control" id="longitude" placeholder="Longitud">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="x">X</label>
                              <input type="text" class="form-control" id="x" placeholder="X">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="y">Y</label>
                              <input type="text" class="form-control" id="y" placeholder="Y">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="z">Z</label>
                              <input type="text" class="form-control" id="z" placeholder="Z">
                          </div>
                      </div>
                    </div>

				
            </div>  
             <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-default" id="goBack" >Cancelar</button>
                <button type="button" class="btn btn-primary pull-right" id="editBtn" >Guardar</button>
            </div>    
          </div>
        </div>
        <!-- /.box -->
        <div class="col-md-12">
          <div class="box box-primary box-solid flat">
            <div class="box-header">
              <h3 class="box-title"> Stock
              </h3>
            </div>

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
                    
                    <div class="col-xs-12" id="div_datas" style="margin: 0 auto;">
                        <table id="tbl_products" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left; margin-bottom: 0px;">
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
                      <table id="tbl_products_minimal" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
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
    
	
	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>
      view = 0;
      tbl_products_minimal = null;
      tbl_products = null;
       $(document).ready(function() {

            $("#date").datepicker({
                format: 'dd/mm/yyyy'
            });

            fieldValues = [];

            (function setFieldValues() {
                fieldValues['location_id'] = 'location_id';
                fieldValues['location_type_id'] = 'location_type_id';
                fieldValues['code'] = 'code';
                fieldValues['name'] = 'name';
                fieldValues['description'] = 'description';
                fieldValues['address'] = 'address';
                fieldValues['x'] = 'x';
                fieldValues['y'] = 'y';
                fieldValues['z'] = 'z';
                fieldValues['latitude'] = 'latitude';
                fieldValues['longitude'] = 'longitude';

            })();

            function loadForm(datas) {
                $("#h1").html('<i class="fa fa-archive"></i> '+datas.data.name);
                $("#breadcrumb_active").text(datas.data.code + " - " +datas.data.name);
                $('#location_id').val(datas.data.location_id);
                $('#name').val(datas.data.name);
                $('#code').val(datas.data.code);
                $('#location_type_id').val(datas.data.location_type_id);
                $("#description").text(datas.data.description);
                $("#address").val(datas.data.address);
                $('#x').val(datas.data.x);
                $("#y").val(datas.data.y);
                $("#z").val(datas.data.z);
                $("#latitude").val(datas.data.latitude);
                $("#longitude").val(datas.data.longitude);
                //$("#date").val(moment(datas.data.date, 'YYYY-MM-DD').format('DD/MM/YYYY'));
            }


            

            AWApi.get('{{ url('api/location_types')}}', function (response) {
                    $("#location_type_id").empty();
                    $('<option />', {value: '', text:'Ninguno' }).appendTo($("#location_type_id"));

                    for (var i = 0; i < response.data.location_types.length; i++) {
                        $('<option />', {text: response.data.location_types[i].name, value: response.data.location_types[i].id }).appendTo($("#location_type_id"));
                    }

                });
            
            AWApi.get('{{ url('/api/locations') }}/{{ $id }}?with=possibleFathers',function (response) {
                $("#location_id").empty();
                $('<option />', {value: '', text:'Ninguno' }).appendTo($("#location_id"));
                
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {
                      name += "";
                    }
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#location_id"));
                
                    //$('<option />', {text: nombre, value: response.data.locations[i].id }).appendTo($("#location_id"));
 
                }

                 AWApi.get('{{ url('/api/locations') }}/{{ $id }}', loadForm);
              });

           


           tbl_products = $('#tbl_products').DataTable( {
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
                    filters.category_id = $("#f_category").val();
                    filters.brand = $("#f_brand").val();
                    data.location_id = "{{$id}}";
                    filters.all = "";
                    data.allsearch = 2;
                    data.filters = filters;
                    data.type = "stock";

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
                        "width": "35%", "targets": 1,
                    },
                    {
                        "width": "20%", "targets": 2,
                    },
                    {
                        "width": "20%", "targets": 3,
                    },
                    {
                        "width": "40px", "targets": 4,
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
                                html += "<span class='pull-right'><a href='{{url('items')}}/"+data+"'>" +full.name+ "</a></span> <br>"
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

                           /* html += "<strong>" + "Unidad de medida:" + "</strong>";
                            if(full.uom){
                                html += "<span class='pull-right'>" +full.uom+ "</span> <br>";
                            }else{
                                html +=  "<span class='pull-right'> Ninguna </span> <br> ";
                            }*/

                            for (var i = 0; i < full.attributes.length; i++) {
                                html += "<strong>" + full.attributes[i].name + ":</strong>";
                                html += "<span class='pull-right'>" +full.attributes[i].value+ "</span> <br>";
                            }
                    

                            return html;
                        }
                    },

                    { "data": "id",
                        render: function ( data, type, full, meta ) {
                            
                            html = "<strong>" + "Manufact. SKU:" + "</strong>";
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

                            html += "<strong>" + "EAN:" + "</strong>";
                            if(full.ean){
                                html += "<span class='pull-right'>" +full.ean+ "</span> <br>";
                            }else{
                                html +=  "<span class='pull-right'> - </span> <br> ";
                            }

                            html += "<strong>" + "UPC:" + "</strong>";
                            if(full.upc){
                                html += "<span class='pull-right'>" +full.upc+ "</span> <br>";
                            }else{
                                html +=  "<span class='pull-right'> - </span> <br> ";
                            }

                            return html;
                        }
                    },
                    { "data": "id",
                      "className": "text-center",
                        render: function ( data, type, full, meta ) {
                            stock = 0;
                            for (var i = 0; i < full.stock.length; i++) {
                                if(full.stock[i].location_id == "{{$id}}"){
                                    stock = full.stock[i].amount;
                                }
                            }
                            html = "<div>";
                            html += '<strong style="color: #3c8dbc; font-size: 20px;">' +  stock + '</strong> en almacén';
                            html += '<br><br>';
                            /*html += '<div style="font-size: 12px;"> <strong style="color: green; font-size: 16px;">' + full.stock_pending_purchases + '</strong> en Ordenes de Entrada</div>';
                            html += '<div style="font-size: 12px;"><strong style="color: red; font-size: 16px;">' + full.stock_pending_sales + '</strong> en Ordenes de Salida.</div>';*/
                            html += '</div>'
                            return html;
                        }
                    },
                    { "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=viewItem("+data+"); >";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i>";
                            edit += "</button>";

                            return "<div class='btn-group'>"+edit+" </div>";
                        }
                    }
                ]
            });

            tbl_products_minimal = $('#tbl_products_minimal').DataTable( {
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
                    filters.category_id = $("#f_category").val();
                    filters.brand = $("#f_brand").val();
                    data.location_id = "{{$id}}";
                    filters.all = "";
                    data.filters = filters;
                    data.allsearch = 2;

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
                    },
                    {
                        "width": "140px", "targets": 3
                    },
                    
                ],
                "columns": [
                    { "data": "id",
                        render: function ( data, type, full, meta ) {
                            name = full.name; 

                            for (var i = 0; i < full.attributes.length; i++) {
                                name += " / " + full.attributes[i].name +": " + full.attributes[i].value;
                            }
                            name += " / "

                            if(full.manufacturer_sku || full.custom_sku || full.ean || full.upc){
                                if(full.manufacturer_sku){
                                    name += "[M.SKU: "+ full.manufacturer_sku+"] ";
                                }
                                if(full.custom_sku){
                                    name += "[C.SKU: "+ full.custom_sku+"] ";
                                }
                                if(full.ean){
                                    name += "[EAN: "+ full.ean+"] ";
                                }
                                if(full.upc){
                                    name += "[C.UPC: "+ full.upc+"] ";
                                }
                            }else{
                                name += "[S.ID: "+ full.id+"] ";
                            }
                            
                            
                            //console.log(full);
                            return name;
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
                                if(full.stock[i].location_id == "{{$id}}"){
                                    stock = full.stock[i].amount;
                                }
                            }
                            return stock;
                        }
                    },
                    { "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=viewItem("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i>";
                            edit += "</button>";

                            return "<div class='btn-group'>"+edit+" </div>";
                        }
                    }
                ]
            });

			// Extra Data (combos) --------------
             

            $("#goBack").click(function(){
              window.location.href = "{{ url( '/users' ) }}";
            });

            $("#normalview_btn").click(function(event) {
                view = 0;

                $("#div_datas").show();
                $("#div_datas_minimalview").hide();
                tbl_products.ajax.reload();

                $("#normalview_btn").addClass('active');
                $("#compactview_btn").removeClass('active');
            });
            $("#compactview_btn").click(function(event) {
                view = 1;
                $("#div_datas_minimalview").show();
                $("#div_datas").hide();
                tbl_products_minimal.ajax.reload();
               
               $("#compactview_btn").addClass('active');
               $("#normalview_btn").removeClass('active');               
               
            });       
          
            $("#editBtn").click(function(){
              var data = new FormData();
                      
              data.append('name', $('#name').val());
              data.append('code', $("#code").val());
              data.append('description', $('#description').text());
              data.append('address', $('#address').val());
              data.append('x', $('#x').val());
              data.append('y', $('#y').val());
              data.append('z', $('#z').val());
              data.append('latitude', $('#latitude').val());
              data.append('longitude', $('#longitude').val());
              data.append('location_id', $('#location_id').val());
              data.append('location_type_id', $('#location_type_id').val());

              AWApi.put('{{ url('/api/locations') }}/{{ $id }}', data, function (data) {
                loadForm(data);
                submit("", data);
              });
            });

       

        });
     
        function viewItem(id)
        {
            window.location.href = "{{ url ('items')}}/"+ id;
        }
  

        function submit(id,data)
        {
            var count = 0;
            //AWValidator.clean(id);
            for (x in data.data.errors)
            {
                if(data.data.errors.unauthorized){
                    swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                }else{
                    AWValidator.error(fieldValues[x], data.data.errors[x].join('\n'));
                }
                count++;
            }

            if (count == 0)
            {
                swal("Actualizar Almacén", "Información actualizada de forma exitosa", "success");
                $('#' +id).modal('hide');
                
            }
        }

    </script>
@endsection

