@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">

@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users" style="padding-right: 5px;"></i> Clientes
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Clientes</li>
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
                  </div>                      
                </div>
                
                <div class="box-footer">
                    <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                    <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                
                <!-- LISTADO DE CLIENTES -->
                <div class="box box-primary flat box-solid">

                    <div class="box-header">
                        <i class="fa fa-users"></i><h3 class="box-title"> Clientes</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            
                            <button type="button" class="btn btn-xs btn-success" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Cliente</button>
                        </div>
                  
                      <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                        <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Dirección</th>
                          <th>Rut</th>
                          <th>Teléfono</th>
                          <th>E-Mail</th>
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
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <script>

        //variables globales
        table = null;
        table_contacts = null;
        fieldValues = [];

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ],
            [
                {field: 'Direccion', type: 'text', id: 's_address', value: 'Direccion'}
            ],
            [                
                {field: 'Comuna', type: 'combo', id: 's_comune', value: 'Comuna'}
            ],

            [                
                {field: 'E-Mail', type: 'text', id: 's_email', value: 'E-Mail'}
            ],

            [
                {field: 'Giro', type: 'text', id: 's_industries', value: 'Giro'},
                {field: 'Codigo interno', type: 'text', id: 's_internal_code', value: 'Codigo interno'}                
            ],
            [
                {field: 'Telefono', type: 'text', id: 's_phone', value: 'Telefono'},
                {field: 'Rut', type: 'text', id: 's_rut', value: 'Rut'}
            ],
            [
                {field: 'Descuento', type: 'combo', id: 's_has_discount', value: 'Descuento'},
                {field: 'Valor', type: 'text', id: 's_discount_percent', value: '0'}

            ]
        ];
        var params = {
            title: 'Creaci&oacute;n Cliente',
            rows: rows,
            button: 'Agregar'
        }
        var modal_client = "modal_client";
        AWModal.create(modal_client, params);
        $("#s_comune").addClass('select_comuna');

        var rows = [
            [
                {field: 'Cliente', type: 'combo', id: 'add_contact_id', value: 'Cliente'}
            ],
            [
                {field: 'Nombre', type: 'text', id: 'add_contact_name', value: 'Nombre'}
            ],
            [
                {field: 'Teléfono', type: 'text', id: 'add_contact_phone', value: 'Teléfono'},
                {field: 'Email', type: 'text', id: 'add_contact_email', value: 'Email'}
            ]
        ];
        var params = {
            title: 'Crear Contacto',
            rows: rows,
            button: 'Guardar'
        }
        var modal_add_contact = "modal_add_contact";
        AWModal.create(modal_add_contact, params);


        $(document).ready(function() {

            $( "#s_discount_percent" ).prop( "disabled", true );

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

                    //extra data
                    var filters = new Object();
                    
                    filters.name = $('#f_name').val();
                    filters.rut = $('#f_rut').val();

                    console.log(data);

                    data.filters = filters;

                    //extra data
                    AWApi.get('{{ url('/api/clients' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.clients
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,"searchable":false},
                    { "data": "name"},
                    { "data": "address"},
                    { 
                        "data": "rut",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.rut+'-'+full.rut_dv;
                        }
                    },
                    { "data": "phone"},

                    { "data": "email"},

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
            
            table_contacts = $('#datas_contacts').DataTable( {
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

                    data.filters = filters;
                    data.with = "contacts"
                    //extra data

                    AWApi.get('{{ url('/api/clients' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.contactsTotal,
                            recordsFiltered: response.data.contactsFiltered,
                            data: response.data.contacts
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "client.name"},
                    { "data": "contact.name"},
                    { "data": "contact.phone"},
                    { "data": "contact.email"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=editContact("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=delContact("+data+");></i></button>";

                            return "<div class='btn-group'>"+" "+del+"</div>";
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['rut'] = 'f_rut';
                fieldValues['name'] = 'f_name';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
            });


            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#f_rut').val('');
                $('#f_name').val('');
                table.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_client);
                $(".modal-body input").val("");
                $( "#s_discount_percent").val('0');
                $('#' +modal_client).modal('show');
            });

            var discount = $('#s_has_discount');
            $('<option />', {value: 0, text: 'NO'}).appendTo(discount);
            $('<option />', {value: 1, text: 'SI'}).appendTo(discount);

            $( "#s_has_discount" ).change(function() {
                if ($( "#s_has_discount" ).val() === '0') {
                    $( "#s_discount_percent" ).prop( "disabled", true );
                }else{
                    $( "#s_discount_percent" ).prop( "disabled", false );
                    $( "#s_discount_percent").val('0');

                }
            });


            $('.select_comuna').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione una comuna',
            noneResultsText: ''
        });


        $('.select_comuna').find('.bs-searchbox').find('input').on('keyup', function(event) {
            //get code 
            if(event.keyCode != 38 && event.keyCode != 40) {
                $('.select_comuna').find('option').remove();
                $('.select_comuna').selectpicker('refresh');
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
                        $('.select_comuna').selectpicker('refresh');
                    }
                });
            }
        });

            $('#' + modal_client + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('address', $('#s_address').val());
                data.append('comune_id', $('#s_comune').val());

                data.append('email', $('#s_email').val());

                data.append('industries', $('#s_industries').val());
                data.append('internal_code', $('#s_internal_code').val());
                data.append('phone', $('#s_phone').val());
                data.append('has_discount', $('#s_has_discount').val());
                data.append('discount_percent', $('#s_discount_percent').val());
                
                if ( validaRut($('#s_rut').val())===true){
                    data.append('rut', $('#s_rut').val());
                }
                else{
                    data.append('rut', '');
                }
                
                AWApi.post('{{ url('/api/clients') }}', data, function(datas){
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
                    submit(modal_client, datas, "Crear Cliente");
                });
            });

            $('#myButton_contacts').click(function () {
                AWValidator.clean(modal_add_contact);
                AWApi.get("{{ url('api/clients')}}"+"?with=contacts", function (datas) {
                    $("#add_contact_id").empty();
                    for (var i = 0; i < datas.data.clients.length; i++) {
                        $('<option />', {value: datas.data.clients[i].id, text:datas.data.clients[i].name }).appendTo($("#add_contact_id"));
                    }
                    
                });
                $(".modal-body input").val("");
                $('#' +modal_add_contact).modal('show');
            });

            $('#' + modal_add_contact + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#add_contact_name').val());
                data.append('phone', $('#add_contact_phone').val());
                data.append('email', $('#add_contact_email').val());
                data.append('client_id', $("#add_contact_id").val());
                AWApi.post('{{ url('/api/contacts') }}', data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 'add_contact_name';
                    fieldValues['email'] = 'add_contact_email';
                    fieldValues['phone'] = 'add_contact_phone';
                    fieldValues['client_id'] = 'add_contact_id';
                    submit(modal_add_contact, datas, "Crear Contacto");
                });
            });

        });

        // extra functions--------

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
                swal(message, "Información agregada de forma exitosa", "success");
                $('#' +modal_client).modal('hide');
                $('#' +modal_add_contact).modal('hide');
                table.ajax.reload();
                table_contacts.ajax.reload();
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Cliente",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/clients' ) }}/'+id, function(data) {
                        submit("", data, "Eliminar Cliente");
                    });
                });
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

        function delContact(id) {

            swal({
                    title: "Eliminar Contacto",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/client_contacts' ) }}/'+id, function(data) {
                        submit("", data, "Eliminar Contacto");
                        table_contacts.ajax.reload();
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/clients' ) }}/"+id;
        }
    </script>
@endsection 

