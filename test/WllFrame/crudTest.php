<?php 

namespace WllFrame;

use PHPUnit_Framework_TestCase;
use WllFrame\crud;
use WllFrame\conexao;

class crudTest extends PHPUnit_Framework_TestCase{

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
    }

	public function testRetornaNomeDaTabela(){
		$crud = new crud('tab_cidade');
		$this->assertEquals('tab_cidade', $crud->getTableName());

		$crud->setTableName('tab_cidade2');
		$this->assertEquals('tab_cidade2', $crud->getTableName());
	}

	public function testRetornaStringSqlInsert(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'id'=> '2',
			'nome'=> 'William',
			'sexo'=> 'Masculino'
		];

		$sql = $crud->buildInsert($arrayDados);
		$expected = "INSERT INTO tab_cidade (id, nome, sexo)VALUES(?, ?, ?)";

		$this->assertEquals($expected, $sql);
	}

	public function testRetornaStringSqlUpdateComCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'nome'=> 'William Francico',
			'fone'=> '11 998452278'
		];
		$arrayCondicao = [
			'id=' => '1'
		];

		$sql = $crud->buildUpdate($arrayDados, $arrayCondicao);
		$expected = "UPDATE tab_cidade SET nome=?, fone=? WHERE id=?";

		$this->assertEquals($expected, $sql);
	}

	public function testRetornaStringSqlUpdateSemCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'nome'=> 'William Francico',
			'fone'=> '11 998452278'
		];

		$sql = $crud->buildUpdate($arrayDados);
		$expected = "UPDATE tab_cidade SET nome=?, fone=?";

		$this->assertEquals($expected, $sql);
	}

	public function testRetornaStringSqlDeleteComCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayCondicao = [
			'id=' => '1'
		];

		$sql = $crud->buildDelete($arrayCondicao);
		$expected = "DELETE FROM tab_cidade WHERE id=?";

		$this->assertEquals($expected, $sql);
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testRetornaErroSqlDeleteSemCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayCondicao = [];

		$sql = $crud->buildDelete($arrayCondicao);
	}

	/**
     * @depends testRetornaStringSqlInsert
     */
	public function testInseriUmRegistro(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'São Roque',
			'id_uf'=> 56
		];

		$this->assertEquals(true, $crud->insert($arrayDados));
	}

	/**
     * @depends testRetornaStringSqlInsert
     * @expectedException PDOException
     */
	public function testRetornaErroAoInserirUmRegistro(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descrica'=> 'São Roque',
			'id_uf'=> 56
		];

		$this->assertEquals(false, $crud->insert($arrayDados));
	}

	/**
     * @depends testRetornaStringSqlUpdateComCondicaoWhere
     */
	public function testAtualizaUmRegistroComCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'São Paulo',
			'id_uf'=> 10
		];
		$arrayCondicao = [
			'id=' => 10
		];

		$this->assertEquals(true, $crud->update($arrayDados, $arrayCondicao));
	}

	/**
     * @depends testRetornaStringSqlUpdateComCondicaoWhere
     * @expectedException PDOException
     */
	public function testRetornaErroAoAtualizarUmRegistro(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descrica'=> 'São Paulo',
			'id_uf'=> 10
		];
		$arrayCondicao = [
			'id=' => 10
		];

		$this->assertEquals(false, $crud->update($arrayDados, $arrayCondicao));
	}

	/**
     * @depends testRetornaStringSqlUpdateSemCondicaoWhere
     */
	public function testAtualizaVariosSemCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'Mairinque',
			'id_uf'=> 10
		];
		$this->assertEquals(true, $crud->update($arrayDados));
	}

	/**
     * @depends testRetornaStringSqlDeleteComCondicaoWhere
     */
	public function testDeletaUmRegistroComCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayCondicao = [
			'id='=> 21
		];

		$this->assertEquals(true, $crud->delete($arrayCondicao));
	}

	/**
     * @depends testRetornaStringSqlDeleteComCondicaoWhere
     * @expectedException InvalidArgumentException
     */
	public function testErroDeletaUmRegistroSemCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayCondicao = [];

		$this->assertEquals(true, $crud->delete($arrayCondicao));
	}

	/**
     * @depends testInseriUmRegistro
     */
	public function testRetornaUmRegistroSelectComCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayDados = [
			'descricao'=> 'Jundiai',
			'id_uf'=> 56
		];

		$this->assertEquals(true, $crud->insert($arrayDados));

		$sql = "SELECT descricao FROM tab_cidade WHERE id_uf = ?";
		$arrayParams = [56];
		$arrayRetorno = $crud->getSelectGeneric($sql, $arrayParams, false);

		$descricao = $arrayRetorno->descricao;
		$this->assertEquals('Jundiai', $descricao);
	}

	/**
     * @depends testInseriUmRegistro
     */
	public function testRetornaVariosRegistrosSelectSemCondicaoWhere(){
		$crud = new crud('tab_cidade');
		$arrayDados1 = [
			'descricao'=> 'Jundiai',
			'id_uf'=> 100
		];

		$arrayDados2 = [
			'descricao'=> 'Sorocaba',
			'id_uf'=> 100
		];

		$crud->insert($arrayDados1);
		$crud->insert($arrayDados2);

		$sql = "SELECT descricao FROM tab_cidade";
		$arrayRetorno = $crud->getSelectGeneric($sql, null, true);

		$count = count($arrayRetorno);
		$this->assertEquals(2, $count);
	}

	public function testInseriUmRegistroMetodoExec(){
		$crud = new crud('tab_cidade');

		$sql = "INSERT INTO tab_cidade (descricao, id_uf)VALUES('Aluminio', 8596)";
		$retorno = $crud->execInstrucaoSQL($sql);

		$this->assertEquals(1, $retorno);
	}

	public function testAtualizaUmRegistroMetodoExec(){
		$crud = new crud('tab_cidade');

		$sql = "INSERT INTO tab_cidade (descricao, id_uf)VALUES('Aluminio', 8596)";
		$retorno = $crud->execInstrucaoSQL($sql);

		$this->assertEquals(1, $retorno);

		$sql2 = "UPDATE tab_cidade SET descricao = 'Ibiuna' WHERE id_uf = 8596";
		$retorno2 = $crud->execInstrucaoSQL($sql2);

		$this->assertEquals(1, $retorno2);
	}

	public function testExcluirUmRegistroMetodoExec(){
		$crud = new crud('tab_cidade');

		$sql = "INSERT INTO tab_cidade (descricao, id_uf)VALUES('Aluminio', 8596)";
		$retorno = $crud->execInstrucaoSQL($sql);

		$this->assertEquals(1, $retorno);

		$sql2 = "DELETE FROM tab_cidade WHERE id_uf = 8596";
		$retorno2 = $crud->execInstrucaoSQL($sql2);

		$this->assertEquals(1, $retorno2);
	}
}
