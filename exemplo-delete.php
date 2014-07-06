<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/Controller.class.php";

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new Controller('tab_usuario');

// Array contendo condições para o delete
$arrayCondicao = array('id=' => 8);

// Chama o método necessário INSERT, UPDATE ou DELETE
echo $controller->delete($arrayCondicao);