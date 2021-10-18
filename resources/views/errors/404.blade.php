<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'SOS') }}</title>
	  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" >


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
	<style>
		body { background-color: #ECF0F5;}
.error-template {padding: 60px 15px;text-align: center;}
.error-actions {margin-top:15px;margin-bottom:15px;}
.error-actions .btn { margin-right:10px; }
	</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h3>
                    404 Not Found</h3>
                <div class="error-details">
                    La página que está buscando no existe.
                </div>
                <div class="error-actions">
                    <a href="{{url('dashboards')}}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Ir a Inicio </a>
                </div>
            </div>
        </div>
    </div>
</div>



</body>
</html>
