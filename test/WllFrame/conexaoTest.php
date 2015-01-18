<?php 

namespace WllFrame;

use PHPUnit_Framework_TestCase;
use WllFrame\conexao;

class conexaoTest extends PHPUnit_Framework_TestCase{

	public function testRetornaInstanciaDoPdo(){
		$conexao = conexao::getInstance();

		$this->assertInstanceOf('PDO', $conexao);
	}

	/**
     * @depends testRetornaInstanciaDoPdo
     */
	public function testRetornaTrueConexaoPdo(){
		$this->assertTrue(conexao::isConectado());
	}

}
