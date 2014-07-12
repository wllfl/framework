<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');  
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
    				<input type='text' name='nome' id='nome' placeholder='Informe o Nome' size='40'/><span class='msg-validacao'>*</span><br>
    				<label>E-mail:</label>
    				<input type='text' name='email' id='email' placeholder='Informe o E-mail' size='40'/><span class='msg-validacao'>*</span><br>
    				<label>Senha:</label>
    				<input type='text' name='senha' id='senha' placeholder='Informe a senha'/><span class='msg-validacao'>*</span><br>
    				<label>Confirmação:</label>
    				<input type='text' name='confirmacao' id='confirmacao' placeholder='Confirme a senha'/><span class='msg-validacao'>*</span><br>
    				<label>Privilégio:</label>
    				<select name='privilegio' id='privilegio'>
    					<option value=''>Selecione</option>
    					<option value="A">Administrador</option>
    					<option value="U">Usuário</option>
    				</select><span class='msg-validacao'>*</span><br>
    				<span class='msg-validacao'>* Campos de preenchimento obrigatório.</span>
    				<hr>
    				<input type='hidden' name='acao' id='acao' value="<?php echo (isset($acao)) ? $acao : 'incluir' ; ?>" />
    				<input type='hidden' name='id' id='id'/>
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