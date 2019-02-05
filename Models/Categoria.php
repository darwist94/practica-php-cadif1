<?php

require_once("DB/conexion.php");

Class Categoria extends Conexion {

	public function __construct(){

	}

	public function leerTodos(){

		$categorias = $this->conectar()->query("SELECT * FROM  categoria");

		$categorias->setFetchMode(PDO::FETCH_OBJ);

		return $categorias;
	}

	
}