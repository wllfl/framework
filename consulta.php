<?php  
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once "classes/controller.class.php";
define('LIMITE', '4');

// Paginação para o banco de dados PostgreSQL
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$offset = LIMITE;
$inicio = ($page * LIMITE) - LIMITE; 

/*
* Instância o objeto controller e executa uma consulta
*/
$controller = new controller();
$sql = "SELECT * FROM tab_usuario ORDER BY id LIMIT $offset OFFSET $inicio";
$dados = $controller->getDados($sql);

$sqlCount = "SELECT COUNT(*) as total FROM tab_usuario";
$retorno = $controller->getDados($sqlCount, null, FALSE);
$contador = ceil($retorno->total / LIMITE);
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
    			<legend align="center">Usuários Cadastrados</legend>
                <span class='msg-servidor'><?php echo (isset($_SESSION['MENSAGEM'])) ? $_SESSION['MENSAGEM'] : ''; ?></span>
    			<table border="1" width="860">
    				<thead>
    					<tr>
    						<th align="center">Id</th>
    						<th align="left">Nome</th>
    						<th align="left">E-mail</th>
    						<th align="center">Privilégio</th>
    						<th align="center">Data do Cadastro</th>
                            <th width="150">Ação</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php foreach($dados as $reg): ?>
    						<tr>
    							<td><?= $reg->id ?></td>
    							<td><?= $reg->nome ?></td>
    							<td><?= $reg->email ?></td>
    							<td align="center"><?= $reg->privilegio ?></td>
    							<td align="center"><?= $reg->data ?></td>
                                <td>
                                    <input type='button' name='editar' id='editar' value='Editar' onclick="window.location='index.php?acao=editar&id=<?=$reg->id?>'"/>
                                    <input type='submit' name='excluir' id='excluir' value='Excluir' onclick="Excluir(<?=$reg->id?>)"/>
                                </td>
    						</tr>
    					<?php endforeach; ?>
    				</tbody>
    			</table>
                <div id="box-paginacao">
                    <?php for ($i=1; $i <= $contador; $i++):?> 
                       <a href="consulta.php?page=<?=$i?>" class='link-paginacao <?=($page == $i) ? 'page-atual': '';?>'><?=$i?></a>
                    <?php endfor;?>
                </div>
                <input type='button' name='cadastro' id='cadastro' value='Cadastrar Usuário' onclick="window.location='index.php'" />
    		</fieldset>
    	</div>
        <script type="text/javascript">
            function Excluir(id){
                if(confirm('Deseja excluir esse registros?')){
                    window.location = 'script-client.php?acao=excluir&id=' + id;
                }
            }
        </script>
    </body>
</html>
<?php
    unset($_SESSION['MENSAGEM']);
?>