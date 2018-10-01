<nav class="navbar navbar-default ">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">::: SGB :::</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav" id="Administrador" style="display: none;">   
	  		<li class="<?php echo $active_administracion ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i  class='glyphicon glyphicon-cog'></i> 
					Administración 
				</a>
				<ul class="dropdown-menu">					
					<!--<li><a href="libros_banco.php">Libros de Banco</a></li>-->					
					<li><a href="personal.php">Personal</a></li>
					<li><a href="usuarios.php">Usuarios</a></li>
				</ul>
			</li>

			<li class="<?php echo $active_facturas ?>"><a href="nueva_factura.php"><i  class='glyphicon glyphicon-usd'></i> Nueva Factura</a></li> 

			<li class="<?php echo $active_ingresos ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class='glyphicon glyphicon-list-alt'></i>
					Ingresos 
				</a>
				<ul class="dropdown-menu">
					<li><a href="clientes.php">Clientes</a></li>
					<li><a href="facturas.php">Facturas</a></li>
				</ul>
			</li>

			<li class="<?php echo $active_egresos ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i  class='glyphicon glyphicon-shopping-cart'></i>
					Egresos 
				</a>
				<ul class="dropdown-menu">
					<li><a href="proveedores.php">Proveedores</a></li>
					<li><a href="caja_chica.php">Caja Chica</a></li>
					<li><a href="banco.php">Banco</a></li>
					<li><a href="compras.php">Compras</a></li>
					<li><a href="libro_diario.php">Libros Diario</a></li>
				</ul>
			</li>

			<li class="<?php echo $active_bodega ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class='glyphicon glyphicon-barcode'></i>
					Bodega 
				</a>
				<ul class="dropdown-menu">
					<li><a href="productos.php">Productos</a></li>
					<li><a href="kardex.php">Kardex</a></li>
					<li><a href="inventario.php">Inventario</a></li>
				</ul>
			</li>

			<li class="<?php echo $active_guias ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class='glyphicon glyphicon-th-list'></i>
					Guías 
				</a>
				<ul class="dropdown-menu">
					<li><a href="guia_entrega.php">Guías de Entrega</a></li>
					<li><a href="guia_remision.php">Guías de Remisión</a></li>
					<li><a href="guia_recepcion.php">Guías de Recepción</a></li>
				</ul>
			</li>
			<li class="<?php echo $active_reportes ?>"><a href="reportes.php"><i  class='glyphicon glyphicon-paste'></i> Reportes</a></li> 
	  	</ul>

			<!-- Menú Perfil Supervisor (Secretaria y Supervisor) -->
			<ul class="nav navbar-nav" id="Supervisor" style="display: none;">   	  		

			<li class="<?php echo $active_facturas ?>"><a href="nueva_factura.php"><i  class='glyphicon glyphicon-usd'></i> Nueva Factura</a></li> 

			<li class="<?php echo $active_ingresos ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class='glyphicon glyphicon-list-alt'></i>
					Ingresos 
				</a>
				<ul class="dropdown-menu">
					<li><a href="clientes.php">Clientes</a></li>
					<li><a href="facturas.php">Facturas</a></li>
				</ul>
			</li>		

			<li class="<?php echo $active_guias ?> dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class='glyphicon glyphicon-th-list'></i>
					Guías 
				</a>
				<ul class="dropdown-menu">
					<li><a href="guia_entrega.php">Guías de Entrega</a></li>
					<li><a href="guia_remision.php">Guías de Remisión</a></li>
					<li><a href="guia_recepcion.php">Guías de Recepción</a></li>
				</ul>
			</li>			
	  	</ul>

			<!-- Menú Perfil Ventas (Vendedor y Repartidor)-->
			<ul class="nav navbar-nav" id="Ventas" style="display: none;">   	  		
			<li class="<?php echo $active_facturas ?>"><a href="nueva_factura.php"><i  class='glyphicon glyphicon-usd'></i> Nueva Factura</a></li> 

			<li class="<?php echo $active_reportes_usuarios ?>"><a href="reportes_usuarios.php"><i  class='glyphicon glyphicon-paste'></i> Reportes</a></li> 
	  	</ul>

			<ul class="nav navbar-nav navbar-right">
				<!--<li><a href="caja.php"><i class='glyphicon glyphicon-shopping-cart'></i> Caja</a></li>-->
        <li><a href="http://www.proconty.com" target='_blank'><i class='glyphicon glyphicon-envelope'></i> Soporte</a></li>        
				<li><a href="" id="logout"><i class='glyphicon glyphicon-off'></i> Salir</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>