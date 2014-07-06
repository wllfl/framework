<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/Controller.class.php";

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new Controller('tab_usuario');

// Variável contendo instrução SQL
$sql = "SELECT * FROM tab_usuario";

// Chama o método consulta contendo apenas 1 registros de retorno
echo json_encode($controller->getDados($sql, null, false));