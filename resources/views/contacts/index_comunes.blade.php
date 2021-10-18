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
        <i class="fa fa-map-marker" style="padding-right: 5px;"></i> Comunas
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Comunas</li>
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
                                <label for="f_name">Nombre de Comuna</label>
                                <input type="text" class="form-control" id="f_name" placeholder="Nombre">
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
                        <i class="fa fa-map-marker"></i><h3 class="box-title"> Comunas</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="btn-group">
                            
                            <button type="button" class="btn btn-xs btn-success" id="btnCrearComuna"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Comuna</button>
                        </div>
                
                    <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                    </table>
                    </div>
                </div>
            <!-- /.box -->
            </div>
        </div>
    </section>
@endsection



@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>



    <script>

        //variables globales
        table_comunes = null;
        $('#f_name').val('');

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Creaci&oacute;n Comuna',
            rows: rows,
            button: 'Agregar'
        }
        var modal_comune = "modal_comune";
        AWModal.create(modal_comune, params);


        $(document).ready(function() {


            // data Table --------------

            table_comunes = $("#datas").DataTable({
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {
                    data.comune_detail = $("#f_name").val();
                    AWApi.get('{{ url('/api/comunes' ) }}?' + $.param(data), function (response) {
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
                    { "data": "comune_detail"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=delComune("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+del+"</div>";
                        }
                    }
                ]
            });


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table_comunes.ajax.reload();
            });


            $('#clean').click(function(){
                $('#f_name').val('');
                table_comunes.ajax.reload();
            });

        

            $('#btnCrearComuna').click(function () {
                AWValidator.clean(modal_comune);
                $(".modal-body input").val("");
                $('#' +modal_comune).modal('show');
            });

            $('#' + modal_comune + "_create").click(function(){

                var data = new FormData();
                
                data.append('comune_detail', $('#s_name').val());
                
                AWApi.post('{{ url('/api/comunes') }}', data, function(datas){
                    submit(modal_comune, datas, "Crear Comuna");
                });
            });
            
            table_comunes.ajax.reload();
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
                    let errList = generateErrorList(data.data.errors);
                    swal("Error", errList, "error");
                }
                count++;   
            }

            if (count == 0)
            {
                if(message !== "Eliminar Comuna") {
                    swal(message, "Información agregada de forma exitosa", "success");
                } else {
                    swal(message, "Información eliminada de forma exitosa", "success");
                }
                $('#' +modal_comune).modal('hide');
                table_comunes.ajax.reload();
            }
     
        }

        function delComune(id) {

            swal({
                    title: "Eliminar Comuna",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/comunes' ) }}/'+id,function(data) {
                        submit("", data, "Eliminar Comuna");
                });
            });
        }
    </script>
@endsection 

