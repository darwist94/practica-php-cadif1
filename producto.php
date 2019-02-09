<?php
require_once("Models/Producto.php"); 
require_once("Models/Categoria.php"); 
require_once("Helper.php");
?>
<?php 
	
	session_start();

	if ( !isset($_SESSION["usuario"]) && isset($_COOKIE["sesion"]) ) {

		$sesion =  json_decode($_COOKIE["sesion"]);
		
		$_SESSION["usuario"] = $sesion;

	}

	if (! isset($_SESSION["usuario"]) ) {

		
		$inautorizado = true;
	}else{

		$usuario = $_SESSION["usuario"];
	}

	$producto = new Producto();

	if( isset($_POST["agregar"]) ){

		$usuarioId = $_POST["usuario"];
		$codigo = $_POST["codigo"];
		$nombre = $_POST["nombre"];
		$categoria = $_POST["categoria"];
		$precio = $_POST["precio"];
		$existencia = $_POST["existencia"];
		$imagen = $_FILES["imagen"];

		$validar = new Helper;
		
		$error = $validar->validarImagen($imagen["type"])->message();

		$error = $validar->validarDigito("existencia",$existencia)->message();

		$error = $validar->validarNumero("precio",$precio)->message();

		$error = $validar->validarString("nombre",$nombre)->message();

		if (!isset($error)) {

			$existeCodigo = $producto->buscarCodigo($codigo);

			if ($existeCodigo->rowCount() != 0) {

				$error = "El codigo del producto ya existe!";

			}else{
				

					$ruta = NULL;
					if ( is_uploaded_file($imagen["tmp_name"]) ) {
						
						$base = "imagenes/";

						//print_r($imagen);


						$ruta = $codigo."-".$imagen["name"];

						copy($imagen["tmp_name"], $base.$ruta);
					}

			  		$producto->insertar($usuarioId,$codigo,$nombre,$categoria,$precio,$existencia,$ruta);

					header("location:index.php?registrado=true");
				
			}
		}
		
		
	}

	if( isset($_GET["modificar"]) ){

		$productoModificar = $producto->leerUno($_GET["id"]);
	}

	if( isset($_POST["actualizar"]) ){

		$usuarioId = $_POST["usuario"];
		$codigo = $_POST["codigo"];
		$nombre = $_POST["nombre"];
		$categoria = $_POST["categoria"];
		$precio = $_POST["precio"];
		$existencia = $_POST["existencia"];
		$imagen = $_FILES["imagen"];
		$id = $_POST["id"];

		$validar = new Helper;

		$error = !empty($imagen["type"]) ? $validar->validarImagen($imagen["type"])->message() : NULL;

		$error = $validar->validarDigito("existencia",$existencia)->message();

		$error = $validar->validarNumero("precio",$precio)->message();

		$error = $validar->validarString("nombre",$nombre)->message();

		if (!isset($error)) {

			if( $imagen["error"] == 0 ){

				if ( is_uploaded_file($imagen["tmp_name"]) ) {
						
					$base = "imagenes/";

					$rutaImagen = $codigo."-".$imagen["name"];

					copy($imagen["tmp_name"], $base.$rutaImagen);
				}

		  		$producto->actualizar($id,$usuarioId,$codigo,$nombre,$categoria,$precio,$existencia,$rutaImagen);
	  		}else{


	  		$producto->actualizar($id,$usuarioId,$codigo,$nombre,$categoria,$precio,$existencia);
	  		}


			header("location:index.php?actualizado=true");
		}
		
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Producto</title>
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

				if ( isset($inautorizado) ) {
			?>
				<h1 class="text-center">Permiso no autorizado.</h1>
				<a class="btn btn-link btn-lg text-primary" href="login.php">Haga Click aqui para iniciar sesión</a>

			<?php die();	}else{ ?>

				<h1 class="text-center font-weight-bold text-capitalize"><?php  if(isset($productoModificar)){ echo "Modificar Producto";}else{ echo "Nuevo Producto"; } ?></h1>

			<?php	} ?>
				
				<?php 
				// solo para mostrar un mensaje de error!

				if( isset($error)){
				?>
				<div class="alert alert-danger">
					<div class="container">
						<div class="alert-icon"><i class="material-icons">error_outline</i></div>
					</div>
					<button type="button" class="close" data-dismiss="alert" aria-label="close">
						<span aria-hidden="true">
							<i class="material-icons">clear</i>
						</span>
					</button>
					<b><?php echo $error; ?></b>
					
				</div>
				<?php } ?>

				
			</div>
		</div>
	
		<div class="row">
			<div class="col-6">
				<div class="card mt-4">
					<div class="card-header card-header-primary text-center">
						<h2 class="text-center card-title">
							<?php  if(isset($productoModificar)): 
										echo "Usuario: ".$productoModificar->nombre; 
								   else:
							 			echo "Usuario: ".$usuario->nombre;
							 	   endif;
							 ?>
							 	
						</h2>
					</div>
					<div class="card-body">
						<form class="form" method="POST" enctype="multipart/form-data">
						
						<div>
							<input type="hidden" name="usuario"  value="<?php 
							if(isset($productoModificar)){ 
								echo $productoModificar->id_usuario; 
							}elseif(isset($usuario->id)){ 
								echo $usuario->id; 
							}elseif(isset($_POST["usuario"])){
							 echo $_POST["usuario"]; }  ?>">
						</div>
						<div class="form-group">
							<label for="Codigo" class="bmd-label-floating">Codigo</label>
							<input class="form-control" type="text" name="codigo" id="Codigo" value="<?php  if(isset($productoModificar)){ echo $productoModificar->codigo;}elseif ( isset($_POST["codigo"])) {
								echo $_POST["codigo"];
							} ?>" required>
						</div>
						
						<div class="form-group">
							<label for="nombre" class="bmd-label-floating">Nombre</label>
							<input class="form-control" type="text" name="nombre" id="nombre" value="<?php  if(isset($productoModificar)){ echo $productoModificar->descripcion;}elseif ( isset($_POST["nombre"])) {
								echo $_POST["nombre"];
							} ?>" required>
						</div>
						
						<div class="form-group">
						<?php

							$categorias = new Categoria();

							echo '<select class="form-control" name="categoria">';

							foreach ($categorias->leerTodos() as $categoria) {
								
								$selected = "";
								if(isset($productoModificar)){
									 if($productoModificar->id_categoria == $categoria->id){
									 	$selected = "selected";
									 }
								}elseif (isset($_POST["categoria"])) {
									 	if($_POST["categoria"] == $categoria->id){
									 	$selected = "selected";
									 }
									 }
							 echo '<option value="'.$categoria->id.'" '.$selected.'>'.$categoria->nombre.'</option>';
							  } 

							  echo "</select>";
							 ?>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="precio" class="bmd-label-floating">Precio</label>
									<input class="form-control" type="text" name="precio" id="precio" value="<?php  if(isset($productoModificar)){ echo $productoModificar->precio;}elseif ( isset($_POST["precio"])) {
										echo $_POST["precio"];
									}  ?>" required>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="existencia" class="bmd-label-floating">Existencia</label>
									<input class="form-control" type="text" name="existencia" id="existencia" value="<?php  if(isset($productoModificar)){ echo $productoModificar->existencia;}elseif ( isset($_POST["existencia"])) {
										echo $_POST["existencia"];
									}  ?>" required>
								</div>
							</div>
						</div>
						<div class="class="form-group">
							<?php if(isset($_GET["modificar"])){  ?>
							<label>Cambiar Imagen</label>
							<?php  } ?>
							<input class="form-control" type="file" name="imagen" <?php if(!isset($_GET["modificar"])){ echo "required"; } ?>>
						</div>
						<?php  if ( !isset($_GET["modificar"]) ) {  ?>
						<button class="btn btn-sm btn-primary" name="agregar">Agregar</button>
						<?php }else{ ?>
						<input type="hidden" name="id" value="<?php echo $productoModificar->id ?>"/>
						<button class="btn btn-sm btn-primary" name="actualizar">Actualizar</button>
						<?php } ?>
						</form>
					</div>
				</div>
			</div>
		
			<div class="col-6">
			<?php  if ( isset($_GET["modificar"]) ) { ?>

					<h3>Imagen Actual</h3>
						<figure><img src="<?php echo "imagenes/".$productoModificar->imagen; ?>"></figure>
			<?php 	} ?>
			</div>
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