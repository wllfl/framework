<?php 

namespace WllFrame\helper;

use PHPUnit_Framework_TestCase;
use WllFrame\helper\helperValidation;

class helper_validationTest extends PHPUnit_Framework_TestCase{

	public function testRetornaTrueCpfValido(){
		$cpf = '233.824.886-47';
		$retorno = helperValidation::isCPFValido($cpf);

		$this->assertTrue($retorno);
	}

	public function testRetornaFalseCpfInvalido(){
		$cpf = '233.824.886-4';
		$retorno = helperValidation::isCPFValido($cpf);
		$this->assertFalse($retorno);

		$cpf = '000-000-000-00';
		$retorno = helperValidation::isCPFValido($cpf);
		$this->assertFalse($retorno);
	}

	public function testRetornaTrueCnpjValido(){
		$cnpj = '68.253.836/0001-31';
		$retorno = helperValidation::isCNPJValido($cnpj);
		$this->assertTrue($retorno);

	}

	public function testRetornaFalseCnpjInvalido(){
		$cnpj = '68.253.836/0001-3';
		$retorno = helperValidation::isCNPJValido($cnpj);
		$this->assertFalse($retorno);

		$cnpj = '68.253.836/0001-3196';
		$retorno = helperValidation::isCNPJValido($cnpj);
		$this->assertFalse($retorno);
	}

	public function testRetornaTrueEmailValido(){
		$email = 'teste@teste.com.br';
		$retorno = helperValidation::isEmailValido($email);
		$this->assertTrue($retorno);
	}

	public function testRetornaFalseEmailInvalido(){
		$email = 'testeteste.com.br';
		$retorno = helperValidation::isEmailValido($email);
		$this->assertFalse($retorno);
	}
}