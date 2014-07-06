<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/Controller.class.php";

// Script para auxiliar na formatação da data
require_once "classes/Helper.class.php";

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new Controller('tab_usuario');

// Array com dados para inserção, edição ou exclusão
$arrayDados = array('nome' => 'User PostgreSQL', 'email' => 'postgresql@ig.com', 'senha' => '23233', 'privilegio' => '', 'data' => Helper::dataBrToEng('06/07/2014'));

// Array com nome dos campos que serão aceitos com valor null 
$arrayNullAceito = array('privilegio');

// Array com os campos, condições e valores que serão utilizados para verificar a duplicidade de registros
$arrayCondicaoDuplicidade = array('nome=' => 'User PostgreSQL', 'email=' => 'postgresql@ig.com');

// Seta a array com as condições de duplicidade
$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);

// Seta a array com os campos que serão aceitos com valor null
$controller->setNullAceito($arrayNullAceito);

// Chama o método necessário INSERT, UPDATE ou DELETE
echo $controller->insert($arrayDados);
