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
        <i class="fa fa-arrow-left" style="padding-right: 5px;"></i> Cajas

         <button type="button" class="btn btn-success btn-xs" id="myButton"> <i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar nueva caja</button> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cajas</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat box-solid">

                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Cajas</h3>  
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">

                         <table id="datas_draft" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Nombre</th>
                                      <th>Sucursal</th>
                                      <th>Estado</th>
                                    </tr>
                                    </thead>
                                  </table>
                          <!-- nav-tabs-custom -->
                        </div>
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

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>


    <script>

        //variables globales
        table_draft = null;
        fieldValues = [];
        branch_offices = [];

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'},
                {field: 'Sucursal', type: 'combo', id: 'branch_office_id', value: 'Sucursal'}
            ]
        ];

        var params = {
            title: 'Crear Caja',
            rows: rows,
            button: 'Agregar'
        }

        var modal_add_purchase = "modal_add_purchase";

        AWModal.create(modal_add_purchase, params);


        $(document).ready(function() {

            AWApi.get('{{ url('/api/branch_office') }}',function (response) {

                $('#branch_office_id').empty();
                $('<option />', {value: '', text:'TODOS' }).appendTo($("#branch_office_id"));
                branch_offices  = response.data.branch_office;
                for (var i = 0; i < response.data.branch_office.length; i++) {
                    name = "";
                    $('<option />', {value: response.data.branch_office[i].id, text: response.data.branch_office[i].name }).appendTo($("#branch_office_id")); 
                }
            });

            // data Table --------------

            table_draft = $('#datas_draft').DataTable( {
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

                    AWApi.get('{{ url('/api/sale_box' ) }}?', function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.sale_boxes
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false},
                    { "data": "name",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return data;
                        }
                    },
                    { "data": "branch_office_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "";
                            for (var i = 0; i < branch_offices.length; i++) {
                               if(branch_offices[i].id == data){
                                name = branch_offices[i].name
                               }
                            }
                            return name;
                        }
                    },
                    { "data": "status",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            status = "cerrada";
                            if(data == 1){
                              status = "abierta";
                            }
                            return status;
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';
                fieldValues['branch_office_id'] = 'branch_office_id';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
                table_approved.ajax.reload();
                table_draft.ajax.reload();
            });

            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#code').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                $('#branch_office_id').val('');
                $('#supplier_id').val('');

                table.ajax.reload();
                table_approved.ajax.reload();
                table_draft.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_add_purchase);
                $(".modal-body input").val("");

                 AWApi.get('{{ url('/api/branch_office') }}',function (response) {

                        $('#branch_office_id').empty();
                        $('<option />', {value: '', text:'TODOS' }).appendTo($("#branch_office_id"));
                        branch_offices  = response.data.branch_office;
                        for (var i = 0; i < response.data.branch_office.length; i++) {
                            name = "";
                            $('<option />', {value: response.data.branch_office[i].id, text: response.data.branch_office[i].name }).appendTo($("#branch_office_id")); 
                        }
                    });
                $('#' +modal_add_purchase).modal('show');
            });

            $('#' + modal_add_purchase + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('branch_office_id', $('#branch_office_id').val());

                AWApi.post('{{ url('/api/sale_box') }}', data, function(datas){
                    submit(modal_add_purchase, datas, "Agregar Nueva Caja");


                });
            });

        });

        function submit(id,data, message)
        {
            var count = 0;
            if (id != ""){
                table_draft.ajax.reload();
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
                swal(message, "Información actualizada de forma exitosa", "success");
                $('#' +modal_add_purchase).modal('hide');
                table.ajax.reload();
                table_draft.ajax.reload();
                table_approved.ajax.reload();
                if(id == "modal_add_purchase"){
                    window.location.href = "{{ url( '/purchases' ) }}/"+data.data.id;
                }
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Orden de Entrada",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/purchases' ) }}/'+id,function(data) {
                        submit("",data ,"Eliminar Orden de Entrada");
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/purchases' ) }}/"+id;
        }
    </script>
@endsection 

