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
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
        <h1 id="item_name"><i class="fa fa-users" style="padding-right: 5px;"></i> Cliente </h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Clientes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
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
                
                <div class="form-group col-md-12 col-xs-6">
                    <label for="s_name">Nombre</label>
                    <input type="text" class="form-control" id="s_name" placeholder="Nombre" value="{{ $client->name }}">
                </div>

                <div class="form-group col-md-12 col-xs-6">
                    <label for="s_address">Dirección</label>
                    <input type="text" class="form-control" id="s_address" placeholder="Dirección" value="{{ $client->address }}">
                </div>
                <div class="form-group col-md-12 col-xs-6">
                    <label for="s_comune">Comuna</label>
                    <select class="form-control client_comune"  id="s_comune">
                    </select>
                </div>

                <div class="form-group col-md-12 col-xs-6">
                    <label for="s_email">E-Mail</label>
                    <input type="e-mail" class="form-control" id="s_email" placeholder="E-Mail" value="{{ $client->email }}">
                </div>

                <div class="form-group col-md-6 col-xs-6">
                    <label for="s_industries">Giro</label>
                    <input type="text" class="form-control" id="s_industries" placeholder="Giro" value="{{ $client->industries }}">
                </div>
                <div class="form-group col-md-6 col-xs-6">
                    <label for="s_internal_code">Codigo Interno</label>
                    <input type="text" class="form-control" id="s_internal_code" placeholder="Codigo Interno" value="{{ $client->internal_code }}">
                </div>
                <div class="form-group col-md-6 col-xs-6">
                    <label for="s_rut">Rut</label>
                    <input type="text" class="form-control" id="s_rut" placeholder="Rut" value="{{ $client->rut }}-{{ $client->rut_dv }}">
                </div>
                <div class="form-group col-md-6 col-xs-6">
                    <label for="s_phone">Teléfono</label>
                    <input type="text" class="form-control" id="s_phone" placeholder="Teléfono" value="{{ $client->phone }}">
                </div>
                <div class="form-group col-md-6 col-xs-6">
                    <label for="s_rut">Descuento</label>
                    <select class="form-control"  id="s_has_discount">
                        <option value="0">NO</option>
                        <option value="1">SI</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-xs-6">
                    <label for="s_phone">Valor</label>
                    <input type="text" class="form-control" id="s_discount_percent" placeholder="Valor" value="{{ $client->discount_percent }}">
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
			</div>
          </div>
    </section>

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
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
   
    <script>
    fieldValues = [];

        $(document).ready(function() {

            

        $('.client_comune').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione una comuna',
            noneResultsText: ''
        });


        $('.client_comune').find('.bs-searchbox').find('input').on('keyup', function(event) {
            //get code 
            if(event.keyCode != 38 && event.keyCode != 40) {
                $('.client_comune').find('option').remove();
                $('.client_comune').selectpicker('refresh');
                let data = new Object();
                data.comune_detail = $(this).val();
                AWApi.get('{{ url('/api/comunes') }}?' + $.param(data), function(response) {
                    if(response.code == 200) {
                        src1 = [{
                            id: null, txt:""
                        }];

                        response.data.rows.map( (comunes) => {
                            src1.push({
                                id:comunes.id,
                                txt:comunes.comune_detail
                            });
                        });

                        var options = [];
                        var option = '<optgroup label="Comuna">';
                        src1.forEach(function (item) {
                            
                            option += "<option value="+item.id+" >" + item.txt + "</option>"
                            
                        });
                        option += '</optgroup>';


                        options.push(option);
                        $('#s_comune').html(options);
                        $('.client_comune').selectpicker('refresh');
                    }
                });
            }
        });  
        
        AWApi.get('{{ url('/api/comunes') }}?id={{$client->comune_id}}', function(response) {
                    if(response.code == 200) {
                        src1 = [{
                            id: null, txt:""
                        }];

                        response.data.rows.map( (comunes) => {
                            src1.push({
                                id:comunes.id,
                                txt:comunes.comune_detail
                            });
                        });

                        var options = [];
                        var option = '<optgroup label="Comuna">';
                        src1.forEach(function (item) {
                            
                            option += "<option value="+item.id+" >" + item.txt + "</option>"
                            
                        });
                        option += '</optgroup>';


                        options.push(option);
                        $('#s_comune').html(options);
                        $("#s_comune").val('{{$client->comune_id}}');
                        $('.client_comune').selectpicker('refresh');
                    }
                });

            $('#s_has_discount').val('{{ $client->has_discount }}');
            if ($( "#s_has_discount" ).val() === '0') {
                    $( "#s_discount_percent" ).prop( "disabled", true );
                    $( "#s_discount_percent").val('0');

                }

            $("#editBtn").click(function(){
              var data = new FormData();
                      
                data.append('name', $('#s_name').val());
                data.append('address', $('#s_address').val());
                data.append('phone', $('#s_phone').val());
                data.append('comune_id', $('#s_comune').val());
                
                data.append('email', $('#s_email').val());
                
                data.append('industries', $('#s_industries').val());
                data.append('internal_code', $('#s_internal_code').val());
                data.append('has_discount', $('#s_has_discount').val());
                data.append('discount_percent', $('#s_discount_percent').val());

              if ( validaRut($('#s_rut').val())===true){
                    data.append('rut', $('#s_rut').val());
                }
                else{
                    data.append('rut', '');
                }
              

              AWApi.put('{{ url('/api/clients/') }}'+ "/" +{{$id}},data,function(datas){
                fieldValues = [];
                fieldValues['name'] = 's_name';
                fieldValues['address'] = 's_address';
                fieldValues['industries'] = 's_industries';
                fieldValues['internal_code'] = 's_internal_code';
                fieldValues['comune_id'] = 's_comune';

                fieldValues['email'] = 's_email';

                fieldValues['rut'] = 's_rut';
                fieldValues['phone'] = 's_phone';
                fieldValues['has_discount'] = 's_has_discount';
                fieldValues['discount_percent'] = 's_discount_percent';
                submit("", datas, 'Actualizar Cliente');
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
                    if (val == true) window.location.href = "/clients";
                });
            }
        }

        function validaRut(valor){
            if ( valor.length == 0 ){ return false; }
            if ( valor.length < 8 ){ return false; }

            valor = valor.replace('-','')
            valor = valor.replace(/\./g,'')

            var suma = 0;
            var caracteres = "1234567890kK";
            var contador = 0;    
            for (var i=0; i < valor.length; i++){
                u = valor.substring(i, i + 1);
                if (caracteres.indexOf(u) != -1)
                contador ++;
            }
            if ( contador==0 ) { return false }
            
            var rut = valor.substring(0,valor.length-1)
            var drut = valor.substring( valor.length-1 )
            var dvr = '0';
            var mul = 2;
            
            for (i= rut.length -1 ; i >= 0; i--) {
                suma = suma + rut.charAt(i) * mul
                        if (mul == 7) 	mul = 2
                        else	mul++
            }
            res = suma % 11
            if (res==1)		dvr = 'k'
                        else if (res==0) dvr = '0'
            else {
                dvi = 11-res
                dvr = dvi + ""
            }
            if ( dvr != drut.toLowerCase() ) { return false; }
            else { return true; }
        }

        $("#goBack").click(function(){
              window.location.href = "{{ url( '/clients' ) }}";
            });

            $( "#s_has_discount" ).change(function() {
                if ($( "#s_has_discount" ).val() === '0') {
                    $( "#s_discount_percent" ).prop( "disabled", true );
                    $( "#s_discount_percent").val('0');
                }else{
                    $( "#s_discount_percent" ).prop( "disabled", false );
                }
            });

   </script>
@endsection