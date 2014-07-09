<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/controller.class.php";

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new controller('tab_usuario');

// Variável contendo instrução SQL
$sql = "SELECT * FROM tab_usuario WHERE id > ?";

// Array com valores para cláusula WHERE
$arrayCondicao = array(10);

// Chama o método consulta contendo cláusula WHERE com vários registro de retorno
echo json_encode($controller->getDados($sql, $arrayCondicao, TRUE)); 