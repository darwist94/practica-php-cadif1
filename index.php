<?php
require_once("Models/Producto.php"); 
require_once("Models/Categoria.php");
	
	session_start();
	$producto = new Producto();

	if ( isset($_POST["eliminar"]) ) {

			$productosEliminar = isset($_POST["idProductos"]) ? $_POST["idProductos"] : "";

			//$productoE = new Producto(); 
			if (!empty($productosEliminar)) {
				
				foreach ($productosEliminar as $prod) {
					
					$producto->eliminar($prod);

				}

				$success = "Producto(s) eliminado(s) con exito!";

			}else{

				$warning = "debe seleccionar almenos un producto!";
			}
			
		} 

	if ( isset($_GET["registrado"]) ) {

		$success = "Producto registrado con exito!";
	}

	if ( isset($_GET["actualizado"]) ) {

		$success = "Producto actualizado con exito!";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Bienvenido</title>

	<!--JS-LIBRERIAS NECESARIOS-->

<!-- Material kit-->
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="./public/assets/css/material-kit.css?v=2.0.3" rel="stylesheet" />
</head>
<body>
	
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6 offset-md-3 col-lg-6 offset-lg-3">
			<?php 


			if (!isset($_SESSION["usuario"]) && isset($_COOKIE["sesion"]) ) {

				$sesion =  json_decode($_COOKIE["sesion"]);
				
				$_SESSION["usuario"] = $sesion;

			}

			if (! isset($_SESSION["usuario"]) ) {

				echo '<h1 class="text-center">Permiso no autorizado.</h1>';
				echo '<a class="btn btn-link btn-lg text-primary" href="login.php">Haga Click aqui para iniciar sesión</a>';
				die();
			}else{

				$usuario = $_SESSION["usuario"];
				echo '<h1 class="text-center font-weight-bold text-capitalize">Bienvenido '.$usuario->nombre.'</h1>';
			}


			?>
			<?php 
				// solo para mostrar un mensaje d exito!
				if( isset($success) ){
				?>
				<div class="alert alert-success">
					<div class="container">
						<div class="alert-icon"><i class="material-icons">check</i></div>
					</div>
					<button type="button" class="close" data-dismiss="alert" aria-label="close">
						<span aria-hidden="true">
							<i class="material-icons">clear</i>
						</span>
					</button>
					<b>
					<?php echo $success; ?>
					</b>
				</div>
				<?php } ?>

				<?php 
				// solo para mostrar un mensaje de alerta!
				if( isset($warning) ){
				?>
				<div class="alert alert-warning">
					<div class="container">
						<div class="alert-icon"><i class="material-icons">warning</i></div>
					</div>
					<button type="button" class="close" data-dismiss="alert" aria-label="close">
						<span aria-hidden="true">
							<i class="material-icons">clear</i>
						</span>
					</button>
					<b>
					<?php echo $warning; ?>
					</b>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col-9">
				<div id="listaProducto" class="card text-center">
				  <div class="card-header card-header-primary">
					    <ul class="nav nav-tabs">
					      <li class="nav-item">
					        <a class="nav-link active" href="index.php">Productos</a>
					      </li>
					      <li class="nav-item">
					        <a class="nav-link" href="producto.php">Agregar</a>
					      </li>
					      <li class="nav-item">
					        <a target="_blank" class="nav-link disabled" href="reporte.php">ver en pdf</a>
					      </li>
					    </ul>
				  </div>
				  <div class="card-body">
					    <h4 class="card-title">Listado de productos</h4>
					    <form class="form" method="POST">
					    	<table class="table table-hover">
							<thead>
								<th>Nombre Producto</th>
								<th>Precio</th>
								<th>Imagen</th>
								<th>Nombre Usuario</th>
								<th>Eliminar</th>
								<th>Editar</th>
							</thead>
							<tbody>
						
								<?php

									if( isset($_GET["buscarPorCategoria"]) ){

										$productos = $producto->leerTodos($_GET["id"]);
									}else{

										$productos = $producto->leerTodos();
									}

									foreach ($productos as $prod) {

										 	echo "<tr>";
										 	echo "<td>".$prod->descripcion."</td>";
										 	echo "<td>".$prod->precio."</td>";
										 	echo '<td><img width="40" height="40"  src="imagenes/'.$prod->imagen.'"/></td>';
										 	echo "<td>".$prod->nombre."</td>";
										 	echo '<td><div class="form-check">
												    <label class="form-check-label">
												        <input class="form-check-input" type="checkbox" name="idProductos[]" value="'.$prod->id.'">
												        <span class="form-check-sign">
												            <span class="check"></span>
												        </span>
												    </label>
												</div></td>';
										 	echo '<td><a class="btn btn-link text-danger" href="producto.php?modificar=true&id='.$prod->id.'"><i class="material-icons">edit</i></a></td>';
										 	echo "</tr>";

										 
								 	} 
								
								 ?>
				 
							</tbody>
							</table>
					    	<button class="btn btn-warning" name="eliminar" type="submit">Elminiar seleccionados</button>
						 </form>
				  </div>
				</div>
			</div>
		
			<div class="col-3">
			<div class="card card-nav-tabs">
			  <h4 class="card-header card-header-primary">Lista de Categoria</h4>
			  <div class="card-body">
			  		<table class="table table-hover">
						<thead>
							<th class="text-center">Nombre</th>
						</thead>
						<tbody>
							<?php

							$categorias = new Categoria();

							foreach ($categorias->leerTodos() as $categoria) {

							 	echo '<tr class="text-center">';
							 	echo '<td><a class="btn btn-link text-primary" href="?buscarPorCategoria=true&id='.$categoria->id.'">'.$categoria->nombre.'</a></td>';
							 	echo "</tr>";
							 } 
							 ?>
						</tbody>
					</table>
			  </div>
			</div>
			</div>
		</div>
		<div class="row">
			<span><a href="login.php?cerrarSesion=true" class="btn btn-link btn-lg btn-primary">cerrar sesión</a></span>
		</div>
		
	</div>
		
	

	<!--   Core JS Files   -->
<script src="./public/assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="./public/assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="./public/assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="./public/assets/js/plugins/moment.min.js"></script>
<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="./public/assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="./public/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
<script src="./public/assets/js/material-kit.js?v=2.0.3" type="text/javascript"></script>
</body>
</html>


