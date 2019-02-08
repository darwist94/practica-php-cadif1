<?php 
require_once("Models/Usuario.php"); 
	
	$usuario = new Usuario();

	// proceso de login

	if( isset($_POST["loguear"]) ){

		$login = $_POST["login"];
		$pass = $_POST["password"];

		
		 $usuarioLogueado = $usuario->buscar($login,$pass);

		if ($usuarioLogueado->rowCount() != 0) {

			session_start();

			$_SESSION["usuario"] = $usuarioLogueado->fetch();

			$datos = json_encode($_SESSION["usuario"]);


			setcookie("sesion",$datos,time()+3600);

			header("location:/index.php");
			exit();
		}else{

			$mensajeError = "usuario o contraseña incorrecta!";
			}
			
	}

	// proceso de registro
	if( isset($_POST["registrar"]) ){

		$nombre = $_POST["nombre"];
		$login = $_POST["login"];
		$pass = $_POST["password"];

		$insertado = $usuario->insertar($nombre,$login,$pass);

		if ($insertado != 0) {
			$mensajeExito = "Usuario registrado con exito!";
		}else{
			$mensajeError = "Error al registrar al usuario!".$insertado;
		}
		
	}

	// cerrar sesion

	if ( isset($_GET["cerrarSesion"])) {

		session_start();

		session_destroy();

		setcookie("sesion","",time()-1000);

		$mensajeExito = "Sesión cerrada con exito!";
	}
		
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>login</title>

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
			<div class="col-12 col-md-4 col-lg-4 offset-md-4 offset-lg-4 mt-2">
				<?php 
				// solo para mostrar un mensaje de error!

				if( isset($mensajeError)){
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
					<b><?php echo $mensajeError; ?></b>
					
				</div>
				<?php } ?>

				<?php 
				// solo para mostrar un mensaje d exito!
				if( isset($mensajeExito) ){
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
					<?php echo $mensajeExito; ?>
					</b>
				</div>
				<?php } ?>

				
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-6 ml-auto mr-auto  mt-4">

				<div class="card card-login  mt-4">
				<?php 
					// si esta definida crear cuenta oculta el form del ogin y muestra el de crear cuenta
					if( isset($_GET["crearCuenta"]) ) {	
				?>

					<form class="form" method="POST" action="login.php">
						<div class="card-header card-header-primary text-center">
							<h3 class="card-title">Crear Cuenta</h3>
						</div>
						<div class="card-body">

							<div class="form-group">

				                <label for="nombre" class="bmd-label-floating">Nombre</label>
								<input type="text" name="nombre" class="form-control" id="nombre"  required>
							</div>

							<div class="form-group">
			                	<label for="usuario" class="bmd-label-floating">Usuario</label>
								<input type="text" name="login" class="form-control" id="usuario"  required>
							</div>

							<div class="form-group">
			                	<label for="password" class="bmd-label-floating">Contraseña</label>
								<input type="password" name="password" class="form-control" id="password" required>
							</div>
						</div>
						
						<div class="footer text-center">
						<button name="registrar" class="btn btn-primary btn-link btn-wd btn-lg">registrar</button>
						</div>
					</form>
				<?php 

					} else {
					
				?>
				<form class="form" method="POST">
					<div class="card-header card-header-primary text-center">
						<h3 class="card-title">Login</h3>
					</div>
					<div class="card-body">
						<div class="form-group">
			                <label for="usuario" class="bmd-label-floating">Usuario</label>
							<input type="text" name="login" class="form-control" id="usuario"  required>
						</div>
						<div class="form-group">
			                <label for="password" class="bmd-label-floating">Contraseña</label>
							<input type="password" name="password" class="form-control" id="password" required>
						</div>
					</div>
						<div class="footer text-center">
						<button name="loguear" class="btn btn-primary btn-sm">iniciar sesión</button>
						</div>
					</form>
					<a href="?crearCuenta" class="btn btn-primary btn-link btn-wd btn-lg">crear cuenta</a>
				

				<?php } ?>
				</div>
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