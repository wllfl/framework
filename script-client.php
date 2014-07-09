<?php
session_start();
require_once "classes/controller.class.php";
require_once "classes/helper/helper_format.class.php";

$acao 		 = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$id 		 = (isset($_POST['id'])) ? $_POST['id'] : '';
$nome 		 = (isset($_POST['nome'])) ? $_POST['nome'] : '';
$email 	     = (isset($_POST['email'])) ? $_POST['email'] : '';
$senha 		 = (isset($_POST['senha'])) ? $_POST['senha'] : '';
$confirmacao = (isset($_POST['confirmacao'])) ? $_POST['confirmacao'] : '';
$privilegio  = (isset($_POST['privilegio'])) ? $_POST['privilegio'] : '';
$data	     = date('d/m/Y');

$controller = new controller('tab_usuario');
$arrayCondicaoDuplicidade = array('nome=' => $nome, 'email=' => $email);
$arrayNull = array('privilegio');
$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);
$controller->setNullAceito($arrayNull);

if($acao == 'incluir'):
	$array = array(
			'nome' => $nome,
			'email' => $email,
			'senha' => base64_encode($senha),
			'privilegio' => $privilegio,
			'data' => helper_format::dataBrToEng($data)
		);

	$_SESSION['MENSAGEM'] = $controller->insert($array);
	echo "<script>window.location='index.php'</script>";
endif;

if($acao == 'editar'):

endif;

if($acao == 'excluir'):

endif;

?>