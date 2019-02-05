<?php

Class Conexion {

	public function conectar()
	{
		$dns = "mysql:host=localhost;dbname=db_prueba";
		$user = "root";
		$pass = "";

		$conexion = new PDO($dns,$user,$pass);

		return $conexion;
	}
}
