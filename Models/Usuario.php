<?php

require_once("DB/conexion.php");

Class Usuario extends Conexion {

	public function __construct(){

	}


	public function insertar($nombre,$login,$password){

		try{
			
			return $usuario = $this->conectar()->exec("INSERT INTO usuario(nombre,login,password) VALUES('$nombre','$login','$password')");
		
		}catch(PDOException $e){

			return $e->getMessage(); 
		}
		
	}

	public function buscar($login,$pass){

		
		try {
			$usuario = $this->conectar()->query('SELECT u.id, u.nombre FROM  usuario AS u WHERE u.login = "'.$login.'" AND u.password = "'.$pass.'" ');
		} catch (PDOException $e) {
			echo "error: ".$e->getMessage();
		}

		$usuario->setFetchMode(PDO::FETCH_OBJ);

	
		return $usuario;
	}


}