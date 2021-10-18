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
        <i class="fa fa-arrow-left" style="padding-right: 5px;"></i> Sucursales

         <button type="button" class="btn btn-success btn-xs" id="myButton"> <i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar nueva Sucursal</button> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sucursales</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat box-solid">

                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Sucursales</h3>  
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">

                         <table id="datas_draft" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Nombre</th>
                                      <th>Codigo</th>
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
        locations = [];

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'},
                {field: 'Código', type: 'text', id: 's_code', value: 'Código'}
            ]
        ];

        var params = {
            title: 'Crear Sucursal',
            rows: rows,
            button: 'Agregar'
        }

        var modal_add_purchase = "modal_add_purchase";

        AWModal.create(modal_add_purchase, params);


        $(document).ready(function() {

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

                    AWApi.get('{{ url('/api/branch_office' ) }}?', function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.branch_office
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
                    { "data": "code",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                           
                            return data;
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';
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
                $('#location_id').val('');
                $('#supplier_id').val('');

                table.ajax.reload();
                table_approved.ajax.reload();
                table_draft.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_add_purchase);
                $(".modal-body input").val("");

                AWApi.get('{{ url('/api/locations') }}',function (response) {
                
                    $("#s_location_id").empty();
                    for (var i = 0; i < response.data.tree.length; i++) {
                        name = "";
                        for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                        name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                        $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location_id")); 
                    }
                });
                $('#' +modal_add_purchase).modal('show');
            });

            $('#' + modal_add_purchase + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('code', $('#s_code').val());

                AWApi.post('{{ url('/api/branch_office') }}', data, function(datas){
                    submit(modal_add_purchase, datas, "Agregar Nueva Sucursal");


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

