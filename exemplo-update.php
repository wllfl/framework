<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/controller.class.php";

// Array com dados para inserção, edição ou exclusão
$arrayDados = array('nome' => 'User PostgreSQL Editado', 'email' => 'postgresql@ig.com.br');

// Array contendo condições para o update
$arrayCondicao = array('id=' => 15);

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new Controller('tab_usuario');

// Chama o método necessário INSERT, UPDATE ou DELETE
echo $controller->update($arrayDados, $arrayCondicao);