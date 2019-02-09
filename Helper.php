<?php

Class Helper {

	protected $atributo = NULL;
	protected $imagen = NULL;

	public function validarString($campo,$cadena)
	{	
			
			if (!is_string($cadena) || is_numeric($cadena)) {
			
				$this->atributo = $campo;
			
			}

		return $this;
	}

	public function validarDigito($campo, $digito)
	{
		if (!is_int(intval($digito)) || !is_numeric($digito) || intval($digito) < 0) {
				
				$this->atributo = $campo;

			}

		return $this;
	}

	public function validarNumero($campo, $decimal)
	{
		if (!is_numeric($decimal) || floatval($decimal) < 0) {
				
				$this->atributo = $campo;

			}

		return $this;
	}

	public function validarImagen($extension)
	{
		if ($extension != "image/jpeg" && $extension != "image/jpg" && $extension != "image/png"){
			
			$this->imagen = "Por favor adjunte una imagen con extension: JPG/JPEG/PNG!";

			}

		return $this;
	}

	public function message()
	{	
		if (isset($this->atributo)) {

			return 'El campo '.$this->atributo.' es invalido!';

		}elseif(!is_null($this->imagen)){

			return $this->imagen;
		}
		
	}
}