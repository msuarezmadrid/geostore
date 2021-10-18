  <?php
  use Carbon\Carbon; 
  ?>  
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('uploads/avatars') }}/{{ Auth::user()->avatar }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ DE NAVEGACIÓN</li>
		<li class="treeview" hidden>
		  <a href="{{ url('/dashboards' ) }}">
			<i class="fa fa-dashboard"></i> 
			<span>Indicadores</span>
		  </a> 	
		</li>
    <li class="treeview">
      <a href="#">
        <i class="fa  fa fa-money"></i>
        <span>Ventas</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
            </a>
      <ul class="treeview-menu">
            @if (Auth::user()->type() == 'admin' || Auth::user()->type() == 'gseller')
            <li><a href="{{ url('/presale' ) }}"><i class="fa fa-cart-plus"></i>Preventa</a></li>
            <li><a href="{{ url('/price_quote' ) }}"><i class="fa fa-buysellads"></i>Cotización</a></li>
            @endif
            @if (Auth::user()->type() == 'admin' || Auth::user()->type() == 'cashier')
            <li><a href="{{ url('/pos' ) }}"><i class="fa fa-cart-plus"></i>Punto de Venta</a></li>
            <li><a href="{{ url('/creditnotes' ) }}"><i class="fa fa fa-file-text"></i>Notas de Crédito</a></li>
            @endif
            @if (Auth::user()->type() == 'admin')
            <li><a href="{{ url('/box_sales' ) }}"><i class="fa fa fa-desktop"></i>Cajas</a></li>
            <li><a href="{{ url('/branch_offices' ) }}"><i class="fa fa fa-home"></i>Sucursales</a></li>
            <li><a href="{{ url('/dte_forward' ) }}"><i class="fa fa fa-file-text"></i>Reenviar documentos</a></li>
            @endif
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dollar"></i>
        <span>Cobranza</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
            </a>
      <ul class="treeview-menu">
            <li><a href="{{ url('/concilliation/docs' ) }}"><i class="glyphicon glyphicon-piggy-bank"></i>Documentos Conciliación</a></li>
            <li><a href="{{ url('/concilliation' ) }}"><i class="glyphicon glyphicon-ok"></i> Conciliación</a></li>
            <li><a href="{{ url('/collection' ) }}"><i class="fa fa-balance-scale"></i> Cobranza</a></li>
      </ul>
    </li>
    @if (Auth::user()->type() == 'admin')
    <li class="treeview">
      <a href="{{ url('/items' ) }}">
      <i class="fa fa-cubes"></i> 
      <span>Productos</span>
      </a>  
    </li>
    <li class="treeview">
      <a href="{{ url('/locations' ) }}">
      <i class="fa fa-archive"></i> 
      <span>Almacenes</span>
      </a>  
    </li>
    <li class="treeview" hidden>
      <a href="{{ url('/work_stations' ) }}">
      <i class="fa fa-industry"></i> 
      <span>Estaciones de Trabajo</span>
      </a>  
    </li>
    <li class="treeview">
      <a href="{{ url('/suppliers' ) }}">
      <i class="fa fa-truck"></i> 
      <span>Proveedores</span>
      </a>  
    </li>
    <li class="treeview">
      <a href="{{ url('/clients' ) }}">
      <i class="fa fa-users"></i> 
      <span>Clientes</span>
      </a>  
    </li>
    <li class="treeview">
      <a href="{{ url('/comunes' ) }}">
      <i class="fa fa-map-marker"></i> 
      <span>Comunas</span>
      </a>  
    </li>


    <li class="treeview">
      <a href="#">
        <i class="fa  fa-file"></i>
        <span>Reportes</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
            </a>
      <ul class="treeview-menu">
            <li><a href="{{ url('/reports' ) }}"><i class="fa fa-exchange"></i>Ventas</a></li>
            <li><a href="{{ url('/salesreport' ) }}"><i class="fa fa-dollar"></i>Ventas por vendedor</a></li>
            <li><a href="{{ url('/salesreportcategory' ) }}"><i class="fa fa-dollar"></i>Ventas por categoria</a></li>
            <li><a href="{{ url('/reports/movements' ) }}"><i class="fa fa-arrow-circle-right"></i>Movimientos Caja</a></li>
            <li><a href="{{ url('/reports/sales_without_stock' ) }}"><i class="glyphicon glyphicon-screenshot"></i>Ventas sin stock</a></li>
          </ul>
    </li>
    
    <li class="treeview">
      <a href="#">
        <i class="fa  fa-refresh"></i>
        <span>Movimientos</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
            </a>
      <ul class="treeview-menu">
            <li><a href="{{ url('/movements' ) }}"><i class="fa fa-bar-chart"></i>Resumen</a></li>
            <li><a href="{{ url('/purchases' ) }}"><i class="fa fa-arrow-left"></i>Ordenes de Entrada</a></li>
            <li><a href="{{ url('/sales' ) }}"><i class="fa fa-arrow-right"></i>Ordenes de Salida</a></li>
            <!--<li><a href="{{ url('/transfers' ) }}"><i class="fa fa-exchange"></i>Transferencias</a></li>-->
            <li hidden><a href="{{ url('/work_orders' ) }}"><i class="fa fa-rotate-left"></i>Ordenes de Trabajo</a></li>
            <li><a href="{{ url('/adjustments' ) }}"><i class="fa fa-refresh"></i>Ajustes de Inventario</a></li>
          </ul>
    </li>
    <li class="treeview">
			<a href="#">
				<i class="fa fa-cog"></i>
				<span>Sistema</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
            </a>
			<ul class="treeview-menu">
            <li><a href="{{ url('/users' ) }}"><i class="fa fa-user"></i>Usuarios</a></li>
            <li><a href="{{ url('/roles' ) }}"><i class="fa fa-lock"></i>Roles y permisos</a></li>
            <li><a href="{{ url('/defaults' ) }}"><i class="fa fa-tasks"></i>Valores por defecto</a></li>
            <li><a href="{{ url('/configs' ) }}"><i class="fa fa-tasks"></i>Parámetros del Sistema</a></li>
          </ul>
		</li>
    @endif
        
        <!--
        <li class="treeview">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>UI Elements</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
            <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
            <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
            <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
            <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
            <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
            <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
            <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
            <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>
        <li>
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
            <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
            <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
            <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
            <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
            <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
            <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
            <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li>
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          </ul>
        </li>
        <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li> -->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>