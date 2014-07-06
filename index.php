<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/Controller.class.php";
require_once "classes/Helper.class.php";

// Array com dados para inserção, edição ou exclusão
$arrayDados = array('RAZAO' => 'AXO', 'CNPJ' => '145.635.632/001', 'FONE' => '11 4712-9685', 'RESPONSAVEL' => 'WILLIAM', 'EMAIL' => 'wllfl@ig.com.br', 'SENHA' => '4152');

// Array com nome dos campos que serão aceitos com valor null 
$arrayNullAceito = array('SEXO', 'SENHA');

// Array com os campos, condições e valores que serão utilizados para verificar a duplicidade de registros
$arrayCondicaoDuplicidade = array('RAZAO=' => 'AXO', 'EMAIL=' => 'wllfl@gmail.com.br');

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new Controller('TAB_USUARIO');

// Seta a array com as condições de duplicidade
$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);

// Seta a array com os campos que serão aceitos com valor null
$controller->setNullAceito($arrayNullAceito);

// Chama o método necessário INSERT, UPDATE ou DELETE
echo $controller->insert($arrayDados);
