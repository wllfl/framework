<?php
namespace WllFrame\helper;

/*
* Classe Helper com métodos estáticos para auxliar na validações de valores
* não é necessário instânciar a classe, uma vez que todos os métodos são estáticos
*/

class helperValidation{

	/*
	* Método estático para validar documento CPF
	* @param $valor - String contendo o número do CPF
	* @return Booleano - Retorna TRUE para válido e FALSE para inválido
	*/
	public static function isCPFValido($valor){

		// Retira os pontos se existirem
		$valor = str_replace(array('.','-','/'), "", $valor);

		// Verifiva se o número digitado contém todos os digitos
	    $cpf = str_pad(preg_replace('[^0-9]', '', $valor), 11, '0', STR_PAD_LEFT);
		
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
	    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'):
			return false;
		else: 

			// Calcula os números para verificar se o CPF é verdadeiro
	        for ($t = 9; $t < 11; $t++):
	            for ($d = 0, $c = 0; $c < $t; $c++) :
	                $d += $cpf{$c} * (($t + 1) - $c);
	            endfor;

	            $d = ((10 * $d) % 11) % 10;

	            if ($cpf{$c} != $d):
	                return false;
	            endif;
	        endfor;

	        return true;
	    endif;
	}

	/*
	* Método estático para validar documento CNPJ
	* @param $valor - String contendo o número do CNPJ
	* @return Booleano - Retorna TRUE para válido e FALSE para inválido
	*/
	public static function isCNPJValido($valor){
		$cnpj = str_pad(str_replace(array('.','-','/'),'',$valor),14,'0',STR_PAD_LEFT);

		if (strlen($cnpj) != 14):
		   return false;
		else:
		   for($t = 12; $t < 14; $t++):
			    for($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++):
			      $d += $cnpj{$c} * $p;
			      $p  = ($p < 3) ? 9 : --$p;
			    endfor;

			    $d = ((10 * $d) % 11) % 10;

			    if($cnpj{$c} != $d):
			      return false;
			    endif;
		   endfor;

		   return true;
		endif;
	}

	/*
	* Método estático para validar documento Email
	* @param $email - String contendo o número do Email
	* @return Booleano - Retorna TRUE para válido e FALSE para inválido
	*/
	public static function isEmailValido($email){
		$conta = "/[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$/";

		$pattern = $conta.$domino.$extensao;

		if (preg_match($pattern, $email))
			return true;
		else
			return false;
	}

}

