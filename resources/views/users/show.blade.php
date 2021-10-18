@extends('layouts.master')

@section('css')
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <i class="fa fa-user" style="padding-right: 5px;"></i>
        Usuario: <span id="user_id_name"></span>
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}">Inicio</a></li>
        <li><a href="{{url('users')}}">Usuarios</a></li>
        <li class="active" id="breadcrumb_active">usuario</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
              <!-- Profile Image -->
              <div class="box box-primary box-solid flat">
                <div class="box-body box-profile">
                  <div class="row">
                    <div class="col-xs-12">
                        <img class="profile-user-img img-responsive img-circle" src="{{ asset('uploads/avatars/default.png') }}" alt="User profile picture">

                  <h3 id="user_name" class="profile-username text-center"></h3>

                  <p id="role_name" class="text-muted text-center"></p>

                  <!-- <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Programas</b> <a class="pull-right">10</a>
                    </li>
                    <li class="list-group-item">
                      <b>Cursos</b> <a class="pull-right">20</a>
                    </li>
                    <li class="list-group-item">
                      <b>Estudiantes</b> <a class="pull-right">287</a>
                    </li>
                  </ul> -->

                  <form id="avatar">
                      <span id="fileselector">
                          <label class="btn btn-primary btn-block" for="upload-file-selector">
                              <input id="upload-file-selector" style="display:none;" type="file">
                              Cambiar Imagen
                          </label>
                      </span>
                  </form>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <div class="col-md-9">
                <div class="box box-primary box-solid flat">
                  <div class="box-header">
                    <h3 class="box-title"> <i class="fa fa-list" style="padding-right: 5px;"></i> Detalles</h3>
                  </div>
                  <div class="box-body" id="form">
                    <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="name">Nombre</label>
                              <input type="text" class="form-control" id="name" placeholder="Nombre">
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="email">Email</label>
                              <input type="text" class="form-control" id="email" placeholder="Email">
                          </div>
                      </div>
                    </div>
					<!--<div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="email">Color</label>
							  <div id="color" class="input-group colorpicker-component">
									<input type="text" class="form-control" >
									<span class="input-group-addon"><i></i></span>
							  </div>							  
						 </div>
                      </div>
                    </div>-->
                    <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="role_id">Rol</label>
                              <select class="form-control" id="role_id"></select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="admin">Admin</label>
                              <select class="form-control" id="admin"></select>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="password">Contraseña</label>
                              <input type="password" class="form-control" id="password" placeholder="Contraseña">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="password_confirmation">Confirmar Contraseña</label>
                              <input type="password" class="form-control" id="password_confirmation" placeholder="Confirmar Contraseña">
                          </div>
                      </div>
                    </div>
                    <div class="row" hidden>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="created_at">F. Creación</label>
                              <input type="text" class="form-control" id="created_at" placeholder="F. Creación">
                          </div>
                      </div>   
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="updated_at">F. Modificación</label>
                              <input type="text" class="form-control" id="updated_at" placeholder="F. Modificación">
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
       $(document).ready(function() {

            fieldValues = [];

            (function setFieldValues() {
                fieldValues['name'] = 'name';
                fieldValues['email'] = 'email';
                fieldValues['role_id'] = 'role_id';
                fieldValues['admin'] = 'admin';
                fieldValues['password'] = 'password';
                fieldValues['password_confirmation'] = 'password_confirmation';
                fieldValues['created_at'] = 'created_at';
                fieldValues['updated_at'] = 'updated_at';
				fieldValues['color'] = 'color';
            })();

            function loadForm(datas) {
                $('#name').val(datas.data.name);
                $('#user_id_name').text(datas.data.name);
                $('#breadcrumb_active').text(datas.data.name);
                $('#email').val(datas.data.email);
                $('#role_id').val(datas.data.role_id);
                $('#admin').val(datas.data.admin);
                $('#created_at').val(datas.data.created_at);
                $('#updated_at').val(datas.data.updated_at);
                //$("#color").colorpicker('setValue',datas.data.color);

                $('#user_name').text(datas.data.name);
                $('#role_name').text($("#role_id option:selected").text());

                $('.profile-user-img').attr('src',"{{ asset('/uploads/avatars') }}/"+datas.data.avatar);
            }

			$("#color").colorpicker();	

			// Extra Data (combos) --------------
             
            var admin = $('#admin');
            $('<option />', {value: 0, text: 'No'}).appendTo(admin);
            $('<option />', {value: 1, text: 'Si'}).appendTo(admin);

            AWApi.get('{{ url('/api/roles') }}', function selectRoles(datas) {

                var roles = $('#role_id');

                $.map(datas.data.roles, function (data) {
                    $('<option />', {
                        value: data.id,
                        text: data.name
                    }).appendTo(roles);
                });

				
				
                AWApi.get('{{ url('/api/users') }}/{{ $id }}', loadForm);
            });

            $("#goBack").click(function(){
              window.location.href = "{{ url( '/users' ) }}";
            });
          
            $("#editBtn").click(function(){
              var data = new FormData();
                      
              data.append('name', $('#name').val());
              data.append('email', $('#email').val());
              data.append('admin', $('#admin').val());
              data.append('role_id', $('#role_id').val());
			  data.append('color',$("#color").colorpicker('getValue'));

              var password = $('#password').val();
              var password_confirmation = $('#password_confirmation').val();

              if(password.length > 0 || password_confirmation.length > 0) {
                data.append('password', password);
                data.append('password_confirmation', password_confirmation);
              }

              AWApi.put('{{ url('/api/users/') }}'+ "/" +{{$id}},data,function(datas){
                  submit('', datas);
              });
            });

            $('#upload-file-selector').change(function(){

              var data = new FormData;
              data.append('file', $('#upload-file-selector')[0].files[0] );
              data.append('id', '{{ $id }}' );

              AWApi.post('{{ url('/api/users/avatar') }}', data, function(datas){
                  $('.profile-user-img').attr('src',"{{ asset('/uploads/avatars') }}/"+datas.data.avatar);
              });

            });
        });

        

        function submit(id,data)
        {
            var count = 0;

            AWValidator.clean('form');

            for (x in data.data.errors)
            {
                AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                count++;
            }

            if (count == 0)
            {
                swal("Actualizar Usuario", "Información actualizada de forma exitosa", "success");
                $('#' +id).modal('toggle');
            }
        }

    </script>
@endsection