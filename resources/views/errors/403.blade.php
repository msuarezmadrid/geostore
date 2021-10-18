@extends('layouts.master')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 ">
            	<div class="alert alert-danger alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-ban"></i> ACCESO RESTRINGIDO !</h4>
	                <div style="padding-top: 10px; padding-bottom: 10px; ">
	                	No puede acceder a esta sección del sistema ya que no dispone de los permisos necesarios. 
	                </div>
                
              	</div>
            </div> 
        </div>
    </section>
@endsection