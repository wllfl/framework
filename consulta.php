<?php  
require_once "classes/controller.class.php";

$controller = new controller();
$sql = "SELECT * FROM tab_usuario";
$dados = $controller->getDados($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Consulta de Usuário</title>
        <meta http-equiv="Content-Type" content="text/html; utf-8"/>
        <meta http-equiv="content-language" content="pt-br" />
        <link rel="stylesheet" type="text/css" href="css/estilo.css">
    </head>
    <body>
    	<div id='corpo'>
    		<fieldset class='box-consulta'>
    			<legend align="center">Usuário Cadastrados</legend>
    			<table border="1">
    				<thead>
    					<tr>
    						<th align="center">Id</th>
    						<th align="left">Nome</th>
    						<th align="left">E-mail</th>
    						<th align="center">Privilégio</th>
    						<th align="center">Data do Cadastro</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php foreach($dados as $reg): ?>
    						<tr>
    							<td><?= $reg->id ?></td>
    							<td><?= $reg->nome ?></td>
    							<td><?= $reg->email ?></td>
    							<td><?= $reg->privilegio ?></td>
    							<td><?= $reg->data ?></td>
    						</tr>
    					<?php endforeach; ?>
    				</tbody>
    			</table>
    		</fieldset>
    	</div>
    </body>
</html>