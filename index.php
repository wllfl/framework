<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8'); 
    require_once "classes/controller.class.php"; 

    /*
    * Captura valores passados via GET
    */
    $acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
    $id = (isset($_GET['id'])) ? $_GET['id'] : '';

    /*
    * Verifica se o valor da variável $acao é igual a editar, se verdadeiro
    * Executa um SELECT baseado no id passado como parâmetros via URL
    */
    if (!empty($acao) && $acao == "editar"):
        $param = array($id);
        $controller = new controller();
        $sql = "SELECT nome, senha, email, privilegio FROM tab_usuario WHERE id = ?";
        $dados = $controller->getDados($sql, $param, FALSE);
    endif;
?>
<!DOCTYPE html>
<html>
    <head> 
        <title>Cadastro de Usuários</title>
        <meta http-equiv="Content-Type" content="text/html; utf-8"/>
        <meta http-equiv="content-language" content="pt-br" />
        <link rel="stylesheet" type="text/css" href="css/estilo.css">
    </head>
    <body>
    	<div id="corpo">
    		<fieldset class='box-cadastro'>
    			<legend align="center">Informações do Usuário</legend>
    			<form action="script-client.php" method="post">
    				<span class='msg-servidor'><?php echo (isset($_SESSION['MENSAGEM'])) ? $_SESSION['MENSAGEM'] : ''; ?></span>
    				<label>Nome:</label>
    				<input type='text' name='nome' id='nome' placeholder='Informe o Nome' size='40' value='<?= (!empty($dados->nome)) ? $dados->nome : '' ;?>'/><span class='msg-validacao'>*</span><br>
    				<label>E-mail:</label>
    				<input type='text' name='email' id='email' placeholder='Informe o E-mail' size='40'/ value='<?= (!empty($dados->email)) ? $dados->email : '' ;?>'><span class='msg-validacao'>*</span><br>
    				<label>Senha:</label>
    				<input type='text' name='senha' id='senha' placeholder='Informe a senha' value='<?= (!empty($dados->senha)) ? base64_decode($dados->senha) : '' ;?>'/><span class='msg-validacao'>*</span><br>
    				<label>Confirmação:</label>
    				<input type='text' name='confirmacao' id='confirmacao' value='<?= (!empty($dados->senha)) ? base64_decode($dados->senha) : '' ;?>' placeholder='Confirme a senha'/><span class='msg-validacao'>*</span><br>
    				<label>Privilégio:</label> 
    				<select name='privilegio' id='privilegio'>
    					<option value=''>Selecione</option>
    					<option value="A" <?= (!empty($dados->privilegio) and $dados->privilegio == 'A') ? 'selected' : '' ;?>>Administrador</option>
    					<option value="U" <?= (!empty($dados->privilegio) and $dados->privilegio == 'U') ? 'selected' : '' ;?>>Usuário</option>
    				</select><span class='msg-validacao'>*</span><br>
    				<span class='msg-validacao'>* Campos de preenchimento obrigatório.</span>
    				<hr>
    				<input type='hidden' name='acao' id='acao' value="<?= (isset($acao) && !empty($acao)) ? $acao : 'incluir' ; ?>" />
    				<input type='hidden' name='id' id='id' value='<?= (isset($id)) ? $id : '' ; ?>'/>
    				<input type='submit' name='submit' id='submit' value='Gravar' />
    				<input type='reset' name='limpar' id='limpar' value='Limpar' />
    				<input type='button' name='consultar' id='consultar' value='Consultar' onclick="window.location='consulta.php'" />
    			</form>
    		</fieldset>
    	</div>
    </body>
</html>
<?php
	unset($_SESSION['MENSAGEM']);
?>