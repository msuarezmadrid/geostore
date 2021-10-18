@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">

@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users" style="padding-right: 5px;"></i> Cobranza
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cobranza</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">


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
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_name">Nombre</label>
                                <input type="text" class="form-control" id="f_name" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_rut">Rut</label>
                                <input type="text" class="form-control" id="f_rut" placeholder="Rut">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_type">Tipo de documento</label>
                                <select type="text" class="form-control" id="f_type">
                                    <option value=0 selected>Ambos</option>
                                    <option value=1>Boleta</option>
                                    <option value=2>Factura</option>
                                </select>
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
            <div class="col-xs-12 col-md-12">


           

            <div class="box box-primary flat box-solid">

                <div class="box-header">
                    <i class="fa fa-users"></i><h3 class="box-title"> Clientes con pagos sin conciliar</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">              
                  <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Rut</th>
                      <th>Cliente</th>
                      <th>Correo Electronico</th>
                      <th>Total sin conciliar</th>
                      <th>Ultimo Movimiento</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
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
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>



    <script>

        //variables globales
        table = null;
        fieldValues = [];
        data = [];
        var idClient = 0;
        var doc_type = 0;

        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ],
            [
                {field: 'Dirección de correo', type: 'text', id: 's_email', value: 'Dirección de correo'}
            ],
            [
                {field: 'Mensaje', type: 'textarea', id: 's_msg', value: 'Mensaje'},
            ],
            [
                {field: 'Documento', type: 'file', id: 'upload-file', value: 'Documento'}
            ],
        ];
        var params = {
            title: 'Enviar mensaje personalizado',
            rows: rows,
            button: 'Enviar'
        }
        var modal_msg = "modal_msg";
        AWModal.create(modal_msg, params);

        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name_auto', value: 'Nombre'}
            ],
            [
                {field: 'Dirección de correo', type: 'text', id: 's_email_auto', value: 'Dirección de correo'}
            ],
            [
                {field: 'Mensaje', type: 'textarea', id: 's_msg_auto', value: 'Mensaje'},
            ],
            [
                {field: '', type: 'text', id: 'file_auto', value: 'Documento'}
            ],
        ];
        var params = {
            title: 'Enviar mensaje automatico',
            rows: rows,
            button: 'Enviar'
        }
        var auto_modal_msg = "auto_modal_msg";
        AWModal.create(auto_modal_msg, params);

        $(document).ready(function() {
            $( "#f_type" ).change(function() {
                
                doc_type = $( "#f_type" ).val();
            });


            // data Table --------------

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
                    var filters = new Object();
                    filters.name = $('#f_name').val();
                    filters.rut = $('#f_rut').val();
                    filters.type = $('#f_type').val();
                    data.filters = filters;
                    AWApi.get('{{ url('/api/collection' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.collections
                        });
                    });
                    },
                
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,"searchable":false},
                    { 
                        "data": "rut",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.client.rut+'-'+full.client.rut_dv;
                        }
                    },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            if (full.client.email === null) {
                                return '! - '+full.client.name;

                            }else{
                                return full.client.name;
                            }
                        }
                    },
                    { 
                        "data": "email",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            if (full.client.email === null) {
                                return 'SIN CORREO ASIGNADO';
                            }else{
                                return full.client.email;
                            }
                        }
                    },
                    { 
                        "data": "amount",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return '$'+formatMoney(data, 'CL');
                        }
                    },
                        { "data": "updated_at", render: function(a, b, c, d) {
                            return utcToLocal(a);
                        }
                    },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=edit("+data+");></i></button>";
                            
                            var exc = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            exc += "<i class='fa fa-lg fa fa-file-excel-o fa-fw' onclick=getExcel("+data+");></i></button>";

                            

                            if (full.client.email === null) {
                                var  email = "<button disabled class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                                email += "<i class='glyphicon glyphicon-remove fa-fw'></i></button>";
                               
                            }else{
                                var email = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                                email += "<i class='glyphicon glyphicon-send fa-fw' onclick=email("+data+");></i></button>";
                            }
                            return "<div class='btn-group'>"+edit+" "+email+" "+exc+"</div>";

                        }
                    }
                ]
            });



            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['email'] = 's_email';
                fieldValues['name'] = 's_name';
                fieldValues['msg'] = 's_msg';
                fieldValues['file'] = 'upload-file';

            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
            });


            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#f_rut').val('');
                $('#f_name').val('');
                $('#f_type').val('0');
                table.ajax.reload();
            });

        });


        // function email(id) {
        //     AWApi.get('{{ url('/api/collection/auto_send_mail' ) }}/'+id, function (response) {
        //         if (response.code == 200) {
        //             swal('Exito', 'Correo enviado satisfactoriamente','success');
        //         }else{
        //             swal('Error', 'Hubo un problema enviando el correo','error');
        //         }
        //     });        
        // }
        
        function email(id) {
            idClient = id;
            let msg = "Estimado"
            msg += "\n"
            msg += "\nAdjunto estado de cuenta correspondiente al documento adjunto."
            msg += "\n"
            msg += "\nPor favor depositar en alguna de las siguientes cuentas:"
            msg += "\n"
            msg += "\nCTA CTE Nª XXXXX DEL BANCO XXXXX"
            msg += "\nCTA VISTA Nª XXXXXX DEL BANCO XXXXX"
            msg += "\n"
            msg += "\n Ambas cuentas corrientes a nombre de XXXXX XXXXX XXXXX, RUT: X.XXX.XXX-X";
            $('#auto_modal_msg_create').html('Enviar');
            AWApi.get('{{ url('/api/clients' ) }}/'+id, function (response) {
                $('#file_auto').replaceWith('<button type="button" class="btn btn-default" onClick="getExcel('+id+')";>Descargar Excel</button>');
                $('#auto_modal_msg').modal('show');
                $('#s_name_auto').val(response.data.name);
                $('#s_email_auto').val(response.data.email);
                $("#s_msg_auto").css('height', '300px');
                $("#s_msg_auto").css('width', '100%');
                $('#s_msg_auto').val(msg).change();
                $( "#s_name_auto" ).prop( "disabled", true );
                $( "#s_email_auto" ).prop( "disabled", true );
                $( "#s_msg_auto" ).prop( "disabled", true );
            });       
        }

        function getExcel(id) {
            
            AWApi.download('{{ url('/api/collection/get_excel' ) }}/'+id+'/'+doc_type, function (response) {
                if (response.code == 200) {
                    swal('Exito', 'Correo enviado satisfactoriamente','success');
                }else{
                    swal('Error', 'Hubo un problema enviando el correo','error');
                }
            });        
        }

        function edit(id) {
            idClient = id;
            $('#s_msg').val('');
            let msg = "Estimado"
            msg += "\n"
            msg += "\nAdjunto estado de cuenta correspondiente al documento adjunto."
            msg += "\n"
            msg += "\nPor favor depositar en alguna de las siguientes cuentas:"
            msg += "\n"
            msg += "\nCTA CTE Nª XXXXX DEL BANCO XXXXX"
            msg += "\nCTA VISTA Nª XXXXXX DEL BANCO XXXXX"
            msg += "\n"
            msg += "\n Ambas cuentas corrientes a nombre de XXXXX XXXXX XXXXX, RUT: X.XXX.XXX-X";
            $('#modal_msg_create').html('Enviar');
            AWApi.get('{{ url('/api/clients' ) }}/'+id, function (response) {
                $('#modal_msg').modal('show');
                $('#s_name').val(response.data.name);
                $('#s_email').val(response.data.email);
                $("#s_msg").css('height', '300px');
                $("#s_msg").css('width', '100%');
                $('#s_msg').val(msg).change();
            });
        }

        $('#modal_msg_create').click(function(){
            var data = new FormData();
            data.append('name', $('#s_name').val());
            data.append('email', $('#s_email').val());
            data.append('msg', $('#s_msg').val());
            data.append('file', $('#upload-file')[0].files[0] );
            AWApi.post('{{ url('/api/collection/manual_send_mail' ) }}/'+idClient+'/'+doc_type, data, function (response) {
                if (response.code == 200) {
                    swal('Exito', 'Correo enviado satisfactoriamente','success');
                    $('#modal_msg').modal('hide')
                }else{
                    for (x in response.data.errors)
                    {
                        if(response.data.errors.unauthorized){
                            swal("Acceso Denegado", response.data.errors.unauthorized, "error");
                        } else if(response.data.errors.message){
                            swal("error", response.data.errors.message, "error");
                        }
                        else{
                            AWValidator.error(fieldValues[x],response.data.errors[x].join('\n'));
                        }
                    }
                }
            });   
        });
        
        $('#auto_modal_msg_create').click(function(){
            AWApi.get('{{ url('/api/collection/auto_send_mail' ) }}/'+idClient+'/'+doc_type, function (response) {
                if (response.code == 200) {
                    swal('Exito', 'Correo enviado satisfactoriamente','success');
                    $('#auto_modal_msg').modal('hide')
                }else{
                    swal('Error', 'Hubo un problema enviando el correo','error');
                }
            });    
        });
    </script>
@endsection 

