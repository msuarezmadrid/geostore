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
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
        <h1 id="item_name"><i class="glyphicon glyphicon-piggy-bank" style="padding-right: 5px;"></i> Conciliación</h1>
 
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}">Inicio</a></li>
        <li><a href="{{url('items')}}">Conciliación</a></li>
        <li class="active" id="breadcrumb_active"></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    
      <div class="row">

        <div class="col-md-8">
          <div class="box box-primary box-solid flat general-info">
            <div class="box-header">
               <h3 class="box-title"><i class="fa fa-info" style="padding-right: 5px;"></i> Información General</h3>
               <div class="pull-right">
                  
                  <small id="movement_status" class="label label-default">Borrador</small>
                </div>
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
                
                <div class="form-group col-md-12 col-xs-6">
                    <label for="s_client">Cliente</label>
                    <select class="form-control"  id="s_client">
                    </select>
                </div>
                <div class="form-group col-md-4 col-xs-6">
                    <label for="s_amount">Cantidad</label>
                    <input type="number" class="form-control" id="s_amount" placeholder="Cantidad" value="{{ $concilliation->amount }}">
                </div>
                <div class="form-group col-md-4 col-xs-6">
                    <label for="s_payment_method">Método de pago</label>
                    <select class="form-control"  id="s_payment_method">
                        <option value="0">Efectivo</option>
                        <option value="1">Tarjeta</option>
                        <option value="2">Cheque</option>
                        <option value="3">Transferencia</option>
                    </select>
                </div>
                <div class="form-group col-md-4 col-xs-6">
                    <label for="s_doc_number">Numero de documento</label>
                    <input type="text" class="form-control" id="s_doc_number" placeholder="Numero de documento" value="{{ $concilliation->doc_number }}">
                </div>
                <div class="form-group col-md-12 col-xs-6">
                    <label for="s_description">Descripción</label>
                    <textarea class="form-control" id="s_description" placeholder="Descripción" value="{{ $concilliation->description }}">
                    </textarea>
                </div>
              </div>
                    </div>
                  </div>
                </div>
            </div>  
            <div class="box-body" id="form">

              <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Documentos
                      </a>
                      <span id="fileselector" class="file-selector">
                        <label class="btn btn-primary btn-block" for="upload-file-selector">
                            <input id="upload-file-selector" style="display:none;" type="file">
                            Subir Documento
                        </label>
                    </span>
                    </h4>
                  </div>
                  
                  <div id="collapseTwo" class="panel-collapse collapse in">
                    <div class="box-body">
                    
                    <div class="col-xs-12" id="div_docs">
                            <table id="docs" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            </table>
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
			</div>
            <div class="col-md-4">
          <div class="box box-primary box-solid flat img-info">
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
                    <span id="fileselector" class="file-selector">
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
          
        </div>
        
    </section>

    @endsection

@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
	
	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
   
    <script>
    fieldValues = [];
    image_id = 0;
    images_data = [];
    payment_method_value = '{{ $concilliation->payment_method }}';
    concilliation_status = '{{ $concilliation->status }}';
    table_docs = null;

    

    $('#s_client').selectpicker({
                liveSearch:true,
    });

        $(document).ready(function() {

            $('#s_description').val('{{ $concilliation->description }}')

            AWApi.get('{{ url('/api/concilliation') }}/{{ $id }}', loadForm);

            AWApi.get('{{ url('/api/clients') }}',function (response) {
                    $("#s_client").empty();
                    $('<option />', {value: "", text: "Ninguna" }).appendTo($("#s_client"));
                    for (var i = 0; i < response.data.clients.length; i++) {
                        
                        $('<option />', {value: response.data.clients[i].id, text: response.data.clients[i].name }).appendTo($("#s_client")) 
                        
                    }
                    $('#s_client').val( '{{ $concilliation->client_id }}');
                    $('#s_client').selectpicker('refresh');
             });

             switch (payment_method_value){
                case 'cash':
                    $('#s_payment_method').val('0')
                break;
                case 'card':
                    $('#s_payment_method').val('1')
                break;
                case 'cheque':
                    $('#s_payment_method').val('2')
                break;
                case 'transfer':
                    $('#s_payment_method').val('3')
                break;
             }

             table_docs = $('#docs').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {
                    var filters = new Object();
                    filters.doc_id      = '{{ $id }}';
                    data.filters       = filters;
                    AWApi.get('{{ url('/api/files' ) }}?'+$.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.files
                        });
                    });
                },
                "paging": false,
                //"scrollX": true,
                "columns": [
                    {"data":"id", "visible": false},
                    {"data":"name"},
                    {"data":"id","class":"text-center",  "render" : function(a,b,c,d){
                        button = null;
                        if ( concilliation_status == 'Conciliado') {
                            button = `
                        <button class="btn" onClick="exports(${c.id})" ><i class="fa fa-download"></i></button>
                        `
                        }else{
                            button = `
                        <button class="btn" onClick="delDocFile(${c.id})" ><i class="fa fa-trash"></i></button>
                        <button class="btn" onClick="exports(${c.id})" ><i class="fa fa-download"></i></button>
                        `
                        }
                        return button;
                    }}
                ]
            });


           $("#editBtn").click(function(){
              var data = new FormData();
                      
                data.append('amount', $('#s_amount').val());
                data.append('client_id', $('#s_client').val());
                data.append('payment_method', $('#s_payment_method').val());
                data.append('description', $('#s_description').val());


              AWApi.put('{{ url('/api/concilliation/') }}'+ "/" +{{$id}},data,function(datas){
                fieldValues = [];
                fieldValues['amount'] = 's_amount';
                fieldValues['client_id'] = 's_client';
                fieldValues['payment_method'] = 's_payment_method';
                fieldValues['description'] = 's_description';
                submit("", datas, 'Actualizar Conciliación');
              });
            });
        });

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
                swal(message, "Información actualizada de forma exitosa", "success").then( function(val) {
                    if (val == true) window.location.href = "/concilliation";
                });
            }
        }


        $("#delete_image").click(function(event) {
              //console.log(images_data[image_id].id);
              texto = "<img src='{{asset('uploads/concilliation')}}/"+images_data[image_id].filename+"' ";
              texto += "style='height: 150px; width: 150px; margin-top: -10px'>";
              swal({
                    title: "Eliminar imagen",
                    text: "¿Esta seguro de realizar esta acción?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeModal: true,
                    cancelButtonText: "NO",
                    buttons: [
                                                    'No',
                                                    'Si'
                                                ]
                }).then(
                function (isConfirm) {
                    if(isConfirm){
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
                        AWApi.get('{{ url('/api/concilliation') }}/{{ $id }}', loadForm);
                        //table.ajax.reload();
                    });
                };
                });
            });

            function loadForm(datas) {
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
                    items_carousel +=  '<img src="{{asset("storage/uploads/concilliation")}}/'+datas.data.images[i].filename+'" style="overflow: visible; max-height: 500px;"></div>'  

                    items_thumbnails += '<li class="col-xs-3"><a class="thumbnail" id="carousel-selector-0">';
                    items_thumbnails += '<img src="{{asset("storage/uploads/concilliation")}}/'+datas.data.images[i].filename+'"></a></li>';
          
                  }
                  else{
                    items_carousel +=  '<div class="item" data-slide-number="'+i+'">';
                    items_carousel +=  '<img src="{{asset("storage/uploads/concilliation")}}/'+datas.data.images[i].filename+'"></div>';

                    items_thumbnails += '<li class="col-xs-3"><a class="thumbnail" id="carousel-selector-'+i+'">';
                    items_thumbnails += '<img src="{{asset("storage/uploads/concilliation")}}/'+datas.data.images[i].filename+'"></a></li>';
                  }
                  
                }
                items_thumbnails += '</ul>';
                items_carousel += '</div>';
                $("#slider-thumbs").append(items_thumbnails);
                $("#items_carousel").append(items_carousel);

                $('[id^=carousel-selector-]').click(function () {
                    var id_selector = $(this).attr("id");
                    try {
                        var id = /-(\d+)$/.exec(id_selector)[1];
                        image_id = id;
                        $('#myCarousel').carousel(parseInt(id));
                    } catch (e) {
                    }
                });

            if(datas.data.concilliation.status == 'Conciliado'){
            $("#approveOrder-btn").addClass('disabled').hide();
            $("#movement_status").addClass('label-success').text('APROBADA');
            $("#editBtn").hide();
            $('#delete_image').hide();
            $('.file-selector').hide();


          }else if(datas.data.concilliation.status == 'No Conciliado'){
            $("#approveOrder-btn").removeClass('disabled').show();
            $("#publishOrder-btn").addClass('disabled').hide();
            $("#movement_status").addClass('label-warning').text('PENDIENTE');

          }
          table_docs.ajax.reload();
            }
            
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

            $('#upload-file-selector').click(function(){
              $('#upload-file-selector')[0].files[0] = null;
            });

            $('#upload-file-selector').change(function(){

                var data = new FormData;
                data.append('file', $('#upload-file-selector')[0].files[0] );
                data.append('object_id', '{{ $id }}' );
                data.append('name', $("#name"))
                data.append('object_type', "concilliation_docs");
                data.append('type', "IMG");
                AWApi.post('{{ url('/api/files') }}', data, function(datas){
                    AWApi.get('{{ url('/api/concilliation') }}/{{ $id }}', loadForm);
                });
                

                    //$('.profile-user-img').attr('src',"{{ asset('/uploads/avatars') }}/"+datas.data.avatar);
            });

        function delOrderItem(id) {
            if( concilliation_status != 'Conciliado'){
            AWApi.delete('{{url('api/concilliation')}}/'+id,function (response) {
                submit("", response, "Eliminar Item");
            });
            }else{
            swal('No se puede realizar esta acción.', 'El producto no puede ser eliminado debido a que la orden se encuentra en estado APROBADA.', 'error');
            }
            
        }

        function delDocFile(id) {
            if( concilliation_status != 'Conciliado'){
            AWApi.delete('{{url('api/files')}}/'+id,function (response) {
                swal('Correcto.', 'Documento eliminado.', 'success');
            });
            }else{
            swal('No se puede realizar esta acción.', 'El producto no puede ser eliminado debido a que la orden se encuentra en estado APROBADA.', 'error');
            }
            AWApi.get('{{ url('/api/concilliation') }}/{{ $id }}', loadForm);
            
        }

        function exports(sid) {
            data    = {};
            filters = {};
            filters.doc_type = 'concilliation';
            data.filters = filters;
            AWApi.get('{{ url('/api/files') }}/'+sid+'?'+$.param(data), function (response) {
                        console.log(response);
                        if (response.data.startsWith("/storage/")){
                            response.data = response.data.slice(9);
                        }
                        window.open(response.data, '_blank');
                    });
        }

        function approveOrder(id) {
            swal({
                    title: "Aprobar Conciliación",
                    text: "¿Esta seguro de realizar esta acción?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeModal: true,
                    cancelButtonText: "NO",
                    buttons: [
                                                    'No',
                                                    'Si'
                                                ]
                }).then(
                function (isConfirm) {
                    if(isConfirm){
                        data = new FormData();

                        data.append('movement_status_id', 'Conciliado');
                        data.append('amount', $('#s_amount').val());
                        data.append('client_id', $('#s_client').val());
                        data.append('payment_method', $('#s_payment_method').val());
                        data.append('description', $('#s_description').val());
                        
						AWApi.put('{{ url('/api/concilliation') }}/' + id, data, function(datas){
							submit("", datas, "Aprobar Conciliación");
							AWApi.get('{{ url('/api/concilliation') }}/{{ $id }}', loadForm);
						});
					};
                });
        }

        $("#goBack").click(function(){
              window.location.href = "{{ url( '/concilliation/docs' ) }}";
        });

   </script>
@endsection