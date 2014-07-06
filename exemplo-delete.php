<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/Controller.class.php";
require_once "classes/Helper.class.php";

// Array contendo condições para o delete
$arrayCondicao = array('id=' => 13);

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new Controller('tab_usuario');

// Chama o método necessário INSERT, UPDATE ou DELETE
echo $controller->delete($arrayCondicao);