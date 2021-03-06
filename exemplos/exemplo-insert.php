<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Require nos scripts necessários
require_once "classes/controller.class.php";

// Script para auxiliar na formatação da data
require_once "classes/helper/helper_format.class.php";

// Instância um objeto Controller passando como parâmetro o nome da tabela que será manipulada
$controller = new controller('tab_usuario');

// Array com dados para inserção no banco de dados
$arrayDados = array('nome' => 'User PostgreSQL', 'email' => 'postgresql@ig.com', 'senha' => '23233', 'privilegio' => '', 'data' => helper_format::dataBrToEng('06/07/2014'));

// Array com nome dos campos que serão aceitos com valor null 
$arrayNullAceito = array('privilegio');

// Seta a array com os campos que serão aceitos com valor null
// Essa configuração é opcional mas, senão for informada todos os campos serão considerados de preenchimento obrigatório
$controller->setNullAceito($arrayNullAceito);

// Array com os campos, condições e valores que serão utilizados para verificar a duplicidade de registros
$arrayCondicaoDuplicidade = array('nome=' => 'User PostgreSQL', 'email=' => 'postgresql@ig.com');

// Seta a array com as condições de duplicidade
// Essa configuração é opcional mas, senão for informada poderão ser inseridos registros duplicados
$controller->setCondicaoDuplicidade($arrayCondicaoDuplicidade);

// Chama o método necessário INSERT, UPDATE ou DELETE
echo $controller->insert($arrayDados);
