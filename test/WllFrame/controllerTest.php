<?php 

namespace WllFrame;

use PHPUnit_Framework_TestCase;
use WllFrame\crud;
use WllFrame\controller;
use WllFrame\conexao;

class controllerTest extends PHPUnit_Framework_TestCase{

	public function setUp()
    {
        $conexao = conexao::getInstance();
        $sql = "TRUNCATE TABLE tab_cidade";
        $conexao->exec($sql);
    }

    public function tearDown()
    {
    	$conexao = conexao::getInstance();
        $sql = "TRUNCATE TABLE tab_cidade";
        $conexao->exec($sql);
        unset($controller);
    }

	public function testRetornaNomeDaTabela(){
		$controller = new controller('tab_cidade');
		$this->assertEquals('tab_cidade', $controller->getTableName());

		$controller->setTableName('tab_cidade2');
		$this->assertEquals('tab_cidade2', $controller->getTableName());
	}

	public function testRetornoDeSelectAposMudancaDoNomeDaTabela(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$arrayRetorno = $controller->insert($arrayDados);

		$arrayExpected = ['codigo' => 1, 'mensagem' => 'Registro incluído com sucesso!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);

		$controller->setTableName('tab_uf');
		$arrayDados2 = [
			'descricao'=> 'Bahia',
		];
		$arrayRetorno2 = $controller->insert($arrayDados2);

		$arrayExpected2 = ['codigo' => 1, 'mensagem' => 'Registro incluído com sucesso!'];
		$this->assertEquals($arrayExpected, $arrayRetorno2);
	}

	public function testRetornaNomeDaPrimaryKey(){
		$controller = new controller(null, 'id');
		$this->assertEquals('id', $controller->getFieldPK());

		$controller->setFieldPK('id2');
		$this->assertEquals('id2', $controller->getFieldPK());
	}

	public function testRetornaValorBooleanoVerificacaoNull(){
		$controller = new controller(null, null);
		$controller->setVerificaNull(TRUE);
		$this->assertTrue($controller->getVerificaNull());

		$controller->setVerificaNull(FALSE);
		$this->assertFalse($controller->getVerificaNull());
	}

	public function testRetornaValorBooleanoVerificacaoDuplicidade(){
		$controller = new controller(null, null);
		$controller->setVerificaDuplicidade(TRUE);
		$this->assertTrue($controller->getVerificaDuplicidade());

		$controller->setVerificaDuplicidade(FALSE);
		$this->assertFalse($controller->getVerificaDuplicidade());
	}

	public function testRetornaValorArrayCamposNullAceito(){
		$controller = new controller(null, null);
		$arrayNullAceito= ['descricao', 'estado'];
		$controller->setNullAceito($arrayNullAceito);;
		$this->assertEquals($arrayNullAceito, $controller->getNullAceito());
	}

	public function testRetornaArrayCondicaoDuplicidade(){
		$controller = new controller(null, null);
		$arrayCondicao = ['id'=>12, 'descricao'=>'São Roque'];
		$controller->setCondicaoDuplicidade($arrayCondicao);
		$this->assertEquals($arrayCondicao, $controller->getCondicaoDuplicidade());
	}

	public function testRetornaValorBooleanoNaValidacaoArray(){
		$controller = new controller(null, null);
		$arrayDados = ['id'=>12, 'descricao'=>'', 'id_uf'=> 522];
		$arrayNullAceito= ['descricao', 'estado'];
		$controller->setNullAceito($arrayNullAceito);
		$this->assertTrue($controller->isArrayValida($arrayDados));

		$arrayDados2 = ['id'=>12, 'descricao'=>'', 'id_uf'=> 522];
		$arrayNullAceito2 = ['estado'];
		$controller->setNullAceito($arrayNullAceito2);
		$this->assertFalse($controller->isArrayValida($arrayDados2));
	}

	public function testRetornaFalsoVerificaDuplicidadeDeRegistrosAntesDaInclusao(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'Itapevi',
			'id_uf'=> 600
		];
		$crud->insert($arrayDados);

		$controller = new controller('tab_cidade', 'id');
		$arrayCondicao = ['descricao='=>'Cotia'];
		$controller->setCondicaoDuplicidade($arrayCondicao);
		$this->assertFalse($controller->isRegistroDuplicadoInsert());
	}

	public function testRetornaTrueVerificaDuplicidadeDeRegistrosAntesDaInclusao(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'Cotia',
			'id_uf'=> 600
		];
		$crud->insert($arrayDados);

		$controller = new controller('tab_cidade', 'id');
		$arrayCondicao = ['descricao='=>'Cotia'];
		$controller->setCondicaoDuplicidade($arrayCondicao);
		$this->assertTrue($controller->isRegistroDuplicadoInsert());

	}

	public function testRetornaFalsoVerificaDuplicidadeDeRegistrosAntesDaAtualizacao(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'Piracicaba',
			'id_uf'=> 7000
		];
		$crud->insert($arrayDados);

		$sql = "SELECT id FROM tab_cidade ORDER BY id DESC LIMIT 1";
		$arrayRetorno = $crud->getSQLGeneric($sql, null, false);
		$id = $arrayRetorno->id;

		$controller = new controller('tab_cidade', 'id');
		$arrayDados2 = [
			'descricao'=> 'Piracicaba',
			'id_uf'=> 70400
		];
		$arrayCondicao = ['id='=>$id];
		$arrayCondicaoDuplicidade = ['descricao='=>'Piracicaba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
		$this->assertFalse($controller->isRegistroDuplicadoUpdate($arrayDados2, $arrayCondicao));

		$arrayDados2 = [
			'descricao'=> 'Piracicaba',
			'id_uf'=> 70400
		];
		$arrayCondicao = ['id='=>968];
		$arrayCondicaoDuplicidade = ['descricao='=>'Piracicaba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
		$this->assertTrue($controller->isRegistroDuplicadoUpdate($arrayDados2, $arrayCondicao));
		
	}

	public function testRetornaTrueVerificaDuplicidadeDeRegistrosAntesDaAtualizacao(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'Piracicaba',
			'id_uf'=> 7000
		];
		$crud->insert($arrayDados);

		$controller = new controller('tab_cidade', 'id');
		$arrayDados2 = [
			'descricao'=> 'Piracicaba',
			'id_uf'=> 70400
		];
		$arrayCondicao = ['id='=>968];
		$arrayCondicaoDuplicidade = ['descricao='=>'Piracicaba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
		$this->assertTrue($controller->isRegistroDuplicadoUpdate($arrayDados2, $arrayCondicao));
	}

	public function testInseriRegistroComSucessoSemVerificacaoNullSemVerificacaoDuplicidade(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$arrayRetorno = $controller->insert($arrayDados);

		$arrayExpected = ['codigo' => 1, 'mensagem' => 'Registro incluído com sucesso!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testInseriRegistroComSucessoComVerificacaoNullComVerificacaoDuplicidade(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(TRUE);
		$controller->setVerificaDuplicidade(TRUE);
		$arrayCondicaoDuplicidade = ['descricao='=>'Piracicaba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
		$arrayNullAceito = ['id_uf'];
		$controller->setNullAceito($arrayNullAceito);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$arrayRetorno = $controller->insert($arrayDados);

		$arrayExpected = ['codigo' => 1, 'mensagem' => 'Registro incluído com sucesso!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testRetornaErroInclusaoRegistroDuplicado(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(TRUE);
		$arrayCondicaoDuplicidade = ['descricao='=>'Piracicaba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
		$arrayDados = [
			'descricao'=> 'Piracicaba',
			'id_uf'=> 70400
		];
		$controller->insert($arrayDados);
		$arrayRetorno = $controller->insert($arrayDados);
	
		$arrayExpected = ['codigo' => 0, 'mensagem' => 'Inclusão cancelada, está duplicando registros!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testRetornaErroInclusaoRegistroCampoNull(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(TRUE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayNullAceito = ['id_uf'];
		$controller->setNullAceito($arrayNullAceito);
		$arrayDados = [
			'descricao'=> '',
			'id_uf'=> 70400
		];
		$arrayRetorno = $controller->insert($arrayDados);
	
		$arrayExpected = ['codigo' => 3, 'mensagem' => 'Existem campos obrigatórios sem preenchimento!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testAtualizaRegistroComSucessoSemVerificacaoNullSemVerificacaoDuplicidade(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Santo André',
			'id_uf'=> 7000
		];
		$controller->insert($arrayDados);

		$sql = "SELECT id FROM tab_cidade ORDER BY id DESC LIMIT 1";
		$arrayRetorno = $controller->getDados($sql, Null, false);
		$id = $arrayRetorno->id;

		$arrayDados2 = [
			'descricao'=> 'Santo André',
			'id_uf'=> 70580
		];
		$arrayCondicao = ['id='=>$id];
		$arrayRetorno  = $controller->update($arrayDados2, $arrayCondicao);

		$arrayExpected = ['codigo' => 1, 'mensagem' => 'Registro alterado com sucesso!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testAtualizaRegistroComSucessoComVerificacaoNullComVerificacaoDuplicidade(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(TRUE);
		$controller->setVerificaDuplicidade(TRUE);
		$arrayNullAceito = ['id_uf'];
		$controller->setNullAceito($arrayNullAceito);
		$arrayCondicaoDuplicidade = ['descricao='=>'Indaiatuba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$controller->insert($arrayDados);

		$sql = "SELECT id FROM tab_cidade ORDER BY id DESC LIMIT 1";
		$arrayRetorno = $controller->getDados($sql, Null, false);
		$id = $arrayRetorno->id;


		$arrayDados2 = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70580
		];
		$arrayCondicao = ['id='=>$id];
		$arrayRetorno  = $controller->update($arrayDados2, $arrayCondicao);

		$arrayExpected = ['codigo' => 1, 'mensagem' => 'Registro alterado com sucesso!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testRetornaErroAtualizacaoRegistroCampoNull(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$controller->insert($arrayDados);

		$sql = "SELECT id FROM tab_cidade ORDER BY id DESC LIMIT 1";
		$arrayRetorno = $controller->getDados($sql, Null, false);
		$id = $arrayRetorno->id;

		$controller->setVerificaNull(TRUE);
		$arrayNullAceito = ['descricao'];
		$controller->setNullAceito($arrayNullAceito);

		$arrayDados2 = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> NULL
		];
		$arrayCondicao = ['id='=>$id];
		$arrayRetorno  = $controller->update($arrayDados2, $arrayCondicao);

		$arrayExpected = ['codigo' => 3, 'mensagem' => 'Existem campos obrigatórios sem preenchimento!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testRetornaErroAtualizacaoRegistroRegistroDuplicado(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$controller->insert($arrayDados);

		$arrayDados = [
			'descricao'=> 'Itapeva',
			'id_uf'=> 70400
		];
		$controller->insert($arrayDados);

		$sql = "SELECT id FROM tab_cidade ORDER BY id DESC LIMIT 1";
		$arrayRetorno = $controller->getDados($sql, Null, false);
		$id = $arrayRetorno->id;

		$controller->setVerificaDuplicidade(TRUE);
		$arrayCondicaoDuplicidade = ['descricao='=>'Indaiatuba'];
		$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);

		$arrayDados2 = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70580
		];
		$arrayCondicao = ['id='=>$id];
		$arrayRetorno  = $controller->update($arrayDados2, $arrayCondicao);

		$arrayExpected = ['codigo' => 0, 'mensagem' => 'Edição cancelada, está duplicando registros!'];
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testExcluiRegistro(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 70400
		];
		$controller->insert($arrayDados);

		$crud = new crud('tab_cidade');
		$sql = "SELECT id FROM tab_cidade ORDER BY id DESC LIMIT 1";
		$arrayRetorno = $crud->getSQLGeneric($sql, null, false);
		$id = $arrayRetorno->id;

		$arrayCondicao = ['id='=>$id];
		$arrayExpected = ['codigo' => 1, 'mensagem' => 'Registro excluído com sucesso!'];
		$arrayRetorno  = $controller->delete($arrayCondicao);
		$this->assertEquals($arrayExpected, $arrayRetorno);
	}

	public function testConsultaRetornaUmRegistro(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 858585
		];
		$controller->insert($arrayDados);

		$sql = "SELECT descricao FROM tab_cidade WHERE id_uf = ?";
		$arrayParams = [858585];
		$arrayRetorno = $controller->getDados($sql, $arrayParams, false);

		$descricao = $arrayRetorno->descricao;
		$this->assertEquals('Indaiatuba', $descricao);
	}

	public function testConsultaRetornaVariosRegistros(){
		$controller = new controller('tab_cidade', 'id');
		$controller->setVerificaNull(FALSE);
		$controller->setVerificaDuplicidade(FALSE);
		$arrayDados = [
			'descricao'=> 'Indaiatuba',
			'id_uf'=> 858585
		];
		$arrayDados2 = [
			'descricao'=> 'São Roque',
			'id_uf'=> 41525
		];
		$controller->insert($arrayDados);
		$controller->insert($arrayDados2);

		$sql = "SELECT descricao FROM tab_cidade";
		$arrayRetorno = $controller->getDados($sql, null, true);

		$count = count($arrayRetorno);
		$this->assertEquals(2, $count);
	}
}
