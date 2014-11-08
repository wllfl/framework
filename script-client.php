<?php
session_start();
require_once "classes/controller.class.php";
require_once "classes/helper/helper_format.class.php";

/*
* Esta sendo atribuido os valores enviados via formulário ou via URL
* para esse exemplo estou usando o recurso do PHP $_REQUEST[] para capturar tanto POST quanto GET.
*/
$acao 		 = (isset($_REQUEST['acao'])) ? $_REQUEST['acao'] : '';
$id 		 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
$nome 		 = (isset($_POST['nome'])) ? $_POST['nome'] : '';
$email 	     = (isset($_POST['email'])) ? $_POST['email'] : '';
$senha 		 = (isset($_POST['senha'])) ? $_POST['senha'] : '';
$confirmacao = (isset($_POST['confirmacao'])) ? $_POST['confirmacao'] : '';
$privilegio  = (isset($_POST['privilegio'])) ? $_POST['privilegio'] : '';
$data	     = date('d/m/Y');

/*
* Instâncio o objeto controller e configuro a opções de 
* Campos que deverão ser comparados para identificar duplicidade de registros
* Campos que podem ser inseridos como NULL
*/
$controller = new controller('tab_usuario');
$arrayCondicaoDuplicidade = array('nome=' => $nome, 'email=' => $email);
$arrayNull = array('privilegio');
$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
$controller->setNullAceito($arrayNull);

/*
* Array associativo retornado pelas instruções CRUD contém 2 elementos
*
* codigo:
* 0 -> Operação duplicando registros
* 1 -> Operação efetuada com sucesso
* 2 -> Erro interno da instrução SQL
* 3 -> Campos de preechimento obigatório sem preenchimento 
* 4 -> Erro capturados pelo bloco try..catch 
*
* mensagem:
* As mensagem tem como objetivo ilustrar melhor o erro encontrado e facilitar a depuração.
*/

// Verifica se foi requisitada uma inclusão de registros
if($acao == 'incluir'):
	$array = array(
			'nome' => $nome,
			'email' => $email,
			'senha' => base64_encode($senha),
			'privilegio' => $privilegio,
			'data' => helper_format::dataBrToEng($data)
		);

	$retorno = $controller->insert($array);
	$_SESSION['MENSAGEM'] = $retorno['mensagem'];
	echo "<script>window.location='index.php'</script>";
endif;


// Verifica se foi requisitada uma edição de registros
if($acao == 'editar'):
	$condicao = array('id=' => $id);
	$array = array(
			'nome' => $nome,
			'email' => $email,
			'senha' => base64_encode($senha),
			'privilegio' => $privilegio
		);

	$retorno = $controller->update($array, $condicao);
	$_SESSION['MENSAGEM'] = $retorno['mensagem'];
	echo "<script>window.location='index.php'</script>";
endif;


// Verifica se foi requisitada uma exclusão de registros
if($acao == 'excluir'):
	$condicao = array('id=' => $id); 
	$retorno = $controller->delete($condicao);
	$_SESSION['MENSAGEM'] = $retorno['mensagem'];
	echo "<script>window.location='consulta.php'</script>";
endif;

?>