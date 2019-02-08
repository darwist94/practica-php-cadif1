<?php

Class Helper {

	protected $valido = true;

	protected $atributo = NULL;


	public function validator(array $variable)
	{	
		foreach ($variable as $key => $value) {
			
		
			if (!is_string($value) || is_numeric($value)) {
			
				$this->valido = false;
				$this->atributo = $key;
				break;
			
			}elseif (!is_numeric($value) || floatval($value) < 0) {
				
				$this->valido = false;
				$this->atributo = $key;
				break;
				
			}elseif (!is_int(intval($value))) {
				
				$this->valido = false;
				$this->atributo = $key;
				break;

			}
		}

		return $this;
	}

	public function validate()
	{	
		if (!$this->valido) {
			return 'El campo '.$this->atributo.' es invalido!';
		}else{
			return true;
		}
		
	}
}