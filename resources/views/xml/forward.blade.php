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
        <i class="fa fa-dollar" style="padding-right: 5px;"></i> Reenvio de Documentos
        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reenvio de Documentos</li> 
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat box-xs ">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i>Filtrar</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="start_date">Fecha Inicio</label>
                                    <input type="text" class="form-control" id="start_date" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="end_date">Fecha Fin</label>
                                    <input type="text" class="form-control" id="end_date" placeholder="Fecha Fin">
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de documento</label>
                                    <select class="form-control" id="tipo_documento" placeholder="Tipo de documento">
                                        <option value = "-1">
                                            Seleccionar...
                                        </option>
                                        <option value = "1">
                                            Boleta
                                        </option>
                                        <option value = "2">
                                            Factura
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="dte_folio">Folio</label>
                                    <input type="text" class="form-control" id="dte_folio" placeholder="Folio">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                        <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                    </div>
                </div>
                <div class="box box-primary box-solid flat">
                    <div class="box-body">
                        <div class="col-xs-12" id="div_seller">
                            <table id="xml_forward" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Folio</th>
                                    <th>Tipo de Documento</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Correo de reenvio por defecto</th>
                                    <th>Total</th>
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
@endsection
@section('js')
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
    let curFolio = -1;
    let curType = -1;
    let curRut = '';
    let documents_table = $("#xml_forward").DataTable({
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

                    if (moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.start_date = moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                    if (moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.end_date = moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                    if($("#dte_folio").val() != "") {
                        filters.folio = $("#dte_folio").val();
                    }
                    if( $("#tipo_documento").val() != -1) {
                        filters.tipo_doc = $("#tipo_documento").val();
                    }
                    data.filters = filters;

                    AWApi.get('{{ url('/api/sales/forward' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.sales
                        });
                    });
                },
                "paging": true,
                "ordering": true,
                "columns": [
                    { "data": "id", "visible": false},
                    { "data": "folio"},
                    { 
                        "data": "type",
                        render: function(data, type, full, meta ) {
                            let desc = data == 1 ? 'Boleta Electrónica' : 'Factura Electrónica';
                            return desc;
                        }
                    },
                    { "data": "date"},
                    { "data": "name"},
                    { "data": "email"},
                    { 
                        "data": "total",
                        className:'text-right',
                        render: function(data, type, full, meta) {
                            return '$'+formatMoney(data, 'CL');
                        }
                    },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var forward = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'"
                            + " onclick=forwardDocument("+full.folio+","+full.type+","+full.rut+","+full.rut_dv+")> ";
                            forward += "<i class='fa fa-lg fa-edit fa-fw' ></i></button>";
                            return "<div class='btn-group'>"+forward+"</div>";
                        }
                    }
                ]
            });

    
        // modals --------
        var rows = [
            [
                {field: 'Correo Electronico (para multiples correos, separar con coma y sin espacios). Si no hay correo asignado, se va a tomar el primer correo de la lista como nuevo correo por defecto', type: 'textarea', id: 'f_emails', value: 'Correo Electronico'}
            ]
        ];

        var params = {
            title: 'Reenviar documento',
            rows: rows,
            button: 'Reenviar'
        };

        let modal_forward = 'modal_forward';
        AWModal.create(modal_forward, params, 'custom', 'Reenviar');

        function forwardDocument(folio, docType, rut, dv) {
            curFolio = folio;
            curType = docType == 1 ? 39 : 33;
            curRut = rut + "-" + dv;
            $('#'+modal_forward).modal('toggle');
        }

    $(document).ready(function() {
        $("#f_emails").attr('maxlength', 191);

        $('#start_date').datepicker({
            format: 'dd/mm/yyyy',
            gotoCurrent: true,
            language:'es'
        });
        $('#end_date').datepicker({
            format: 'dd/mm/yyyy',
            gotoCurrent: true,
            language:'es'
        });

        $("#f_emails").attr('placeholder', 'correo1@correo.com,correo2@correo.cl,correo3@correo.com');
        $("#filter").click(function() {
            documents_table.ajax.reload();
        });

        $("#clean").click(function() {
            $("#start_date").val("");
            $("#end_date").val("");
            $("#dte_folio").val("");
            $("#tipo_documento").val(-1);
        });

        $("#"+modal_forward+"_Reenviar").click(function() {
            //Validar Mails
            let mails = $("#f_emails").val();
            mails.replaceAll(' ', '');
            let splitMail = mails.split(",")
            if(splitMail.length > 1) {
                let mailAmt = splitMail.length;
                let curIndex = 0;
                let success = true;
                for(let i = 0; i < mailAmt; i++) {
                    if(!validateEmail(splitMail[i]) || splitMail[i].trim() == "" || (splitMail[i].match(/@/g)).length != 1) {
                        success = false;
                        break;
                    }
                    curIndex++;
                }
                if(!success) {
                    swal('Error', 'El correo: ' + splitMail[curIndex] + ' Es invalido');
                    return;
                }
            } else if (splitMail.length == 1 || splitMail.length == 0) {
                mails = mails.replace(',','');
                if(!validateEmail(mails) || mails.trim() == "" || (mails.match(/@/g)).length > 1) {
                    swal('Error', 'El correo: ' + mails + ' Es invalido');
                    return;
                }
            }
            if(curType != -1 && curFolio != -1 && curRut != '') {
                let data = new FormData();
                data.append('emails', mails);
                data.append('doctype', curType);
                data.append('folio', curFolio);
                data.append('cliente', curRut);
                AWApi.post('{{ url('/api/sales/forward') }}', data, function(response){
                    
                    if(response.data.errors && response.data.errors.message){
                        swal("Error", data.data.errors.message, "error");
                    } if(response.data.errors) {
                        let errList = generateErrorList(response.data.errors);
                        swal("Error", errList, "error");
                    } else if (response.data.success && response.data.success == 'true' && response.data.data == null) {
                        swal('Error del sistema', 'Error del sistema, contacte al administrador', 'error');
                    }else if(response.data.data && response.data.data.success && response.data.data.success == 'warning') {
                        swal("Error", response.data.data.msg, "error");
                    } else if(response.data.data && response.data.data.success && response.data.data.success == 'success') {
                        swal('Success', "Correo Reenviado de forma exitosa", "success");
                        $('#' +modal_forward).modal('toggle');
                        curFolio = -1;
                        curType = -1;
                        curRut = '';
                        $("#f_emails").val("");
                        documents_table.ajax.reload();
                    }
                });
            }
        });
    });
    </script>
@endsection