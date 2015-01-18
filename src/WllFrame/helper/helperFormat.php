<?php
namespace WllFrame\helper;

/*
* Classe Helper com métodos estáticos para auxliar na formatação de valores
* não é necessário instânciar a classe, uma vez que todos os métodos são estáticos
*/

class helperFormat{

	/*
	* Método estático para formatar data no formato Americano
	* @param $data - Data no formato brasileiro dd/mm/yyyy
	* @return String - Data no formato americano yyyy-mm-dd
	*/
	public static function dataBrToEng($data){
		if (!empty($data)):
           $data = explode("/", $data);
           return $data[2].'-'.$data[1].'-'.$data[0];
        else:
        	throw new \InvalidArgumentException("Argumento inválido!"); 	
        endif;
	}

	/*
	* Método estático para formatar data no formato brasileiro
	* @param $data - Data no formato americano yyyy-mm-dd
	* @return String - Data no formato brasileiro dd/mm/yyyy
	*/
	public static function dataEngToBr($data){
		if (!empty($data)):
           $data = explode("-", $data);
           return $data[2].'/'.$data[1].'/'.$data[0];
        else:
        	throw new \InvalidArgumentException("Argumento inválido!"); 	
        endif;
	}

	/*
	* Método estático para alterar letras com acento de minúsculo para maiúsculo
	* @param $texto - String a ser alterada
	* @return String - Texto com letras acentuadas em maiúsculo
	*/
	public static function acentoLowToUpper($texto){
		$texto = str_replace('á', 'Á', $texto);
        $texto = str_replace('ã', 'Ã', $texto);
        $texto = str_replace('à', 'À', $texto);
        $texto = str_replace('â', 'Â', $texto);
        $texto = str_replace('é', 'É', $texto);
        $texto = str_replace('ê', 'Ê', $texto);
        $texto = str_replace('í', 'Í', $texto);
        $texto = str_replace('ó', 'Ó', $texto);
        $texto = str_replace('ô', 'Ô', $texto);
        $texto = str_replace('õ', 'Õ', $texto);
        $texto = str_replace('ú', 'Ú', $texto);
        $texto = str_replace('ü', 'Ü', $texto);
        $texto = str_replace('ç', 'Ç', $texto);
  
        return $texto;
	}

	/*
	* Método estático para alterar letras com acento de maiúsculo para minúsculo
	* @param $texto - String a ser alterada
	* @return String - Texto com letras acentuadas em minúsculo
	*/
	public static function acentoUpperToLow($texto){
		$texto = str_replace('Á', 'á', $texto);
        $texto = str_replace('Ã', 'ã', $texto);
        $texto = str_replace('À', 'à', $texto);
        $texto = str_replace('Â', 'â', $texto);
        $texto = str_replace('É', 'é', $texto);
        $texto = str_replace('Ê', 'ê', $texto);
        $texto = str_replace('Í', 'í', $texto);
        $texto = str_replace('Ó', 'ó', $texto);
        $texto = str_replace('Ô', 'ô', $texto);
        $texto = str_replace('Õ', 'õ', $texto);
        $texto = str_replace('Ú', 'ú', $texto);
        $texto = str_replace('Ü', 'ü', $texto);
        $texto = str_replace('Ç', 'ç', $texto);
  
        return $texto;
	}

}