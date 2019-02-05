<?php

require_once("DB/conexion.php");

Class Producto extends Conexion {

	public function __construct(){

	}

	public function leerTodos($id = NULL){

		if (!is_null($id)):
			$categoria = "AND p.id_categoria='$id'";
		else:
			$categoria = "";
		endif;

		$productos = $this->conectar()->query("SELECT p.nombre as descripcion, p.precio, p.imagen, p.id, p.id_categoria, u.nombre FROM  producto AS p,usuario AS u WHERE p.id_usuario = u.id $categoria");

		$productos->setFetchMode(PDO::FETCH_OBJ);

		return $productos;
	}

	public function leerUno($id){

		$producto = $this->conectar()->query("SELECT p.nombre as descripcion, p.codigo, p.precio, p.existencia, p.imagen, p.id, p.id_usuario, p.id_categoria, u.nombre FROM producto AS p, usuario AS u WHERE p.id_usuario = u.id AND p.id =".$id);

		$producto->setFetchMode(PDO::FETCH_OBJ);
	

		return $producto->fetch();
	}

	public function insertar($usuario,$codigo,$nombre,$categoria,$precio,$existencia,$ruta){

		try{
		
		return $producto = $this->conectar()->exec("INSERT INTO producto(codigo,nombre,precio,existencia,imagen,id_categoria,id_usuario) VALUES('$codigo','$nombre','$precio','$existencia','$ruta','$categoria','$usuario')");

		}catch(PDOException $e){

			return $e->getMessage(); 
		}
	}

	public function actualizar($id,$usuario,$codigo,$nombre,$categoria,$precio,$existencia, $ruta = NULL){

		if (!is_null($ruta)) {
			
			$imagen = "imagen='$ruta',";
		}else{
			$imagen ="";
		}

		$producto = $this->conectar()->exec("UPDATE  producto SET codigo ='$codigo',nombre='$nombre',precio='$precio',existencia='$existencia',$imagen id_categoria='$categoria',id_usuario='$usuario' WHERE id='$id'");
	}

	public function eliminar($id){

		$productos = $this->conectar()->exec("DELETE FROM producto WHERE id =".$id);
	}

	public function buscarCodigo($codigo){

		$producto = $this->conectar()->query("SELECT * FROM producto AS p WHERE p.codigo='$codigo'");
			
			$producto->setFetchMode(PDO::FETCH_OBJ);

			return $producto;

	}


}