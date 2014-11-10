<?php
/*
* Classe Controller para receber os dados da View (HTML) e transferir para classe Crud
* também recebe os dados da classe Crud e transferi para View (HTML)
* Após instânciação do objeto é necessário informar 2 arrays para configuração do objeto
* 1 - $arrayNullAceito informa quais os campos serão aceitos como nulos na validação da array antes do INSERT e UPDATE
* 2 - $arrayCondicaoDuplicidade informas quais os campos, condições e valores para verificação de duplicidade de registros antes do INSERT
*/

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "crud.class.php";
require_once "helper/helper_format.class.php";

class controller{

	// Atributo privado contendo instância da classe Crud
	private $crud = null;

	// Atributo privado contendo o nome da tabela
	private $tabela = null;

	// Atributo privado contém a array com os campos que podem ser nulos
	private $arrayNullAceito = null;

	// Atributo privado contém array com condição para verificação de duplicidade
	private $arrayCondicaoDuplicidade = null;

	// Atributo privado contendo o nome do campo chave primária da tabela
	private $field_pk = null;

	/*
	* Método construtor da classe
	* @param $tabela - Contém o nome da tabela onde serão manipulados os dados
	*/
	public function __construct($tabela=null, $field_pk=null){
		$this->setTableName($tabela);
		$this->setFieldPK($field_pk);
		$this->crud = new Crud($tabela);
	}

	/*
	* Método público para setar o nome da tabela que será utizada
	* @param $tableName - String contendo o nome da tabela
	*/
	public function setTableName($tableName){
		if(!empty($tableName)):
			$this->tabela = $tableName;
		endif;
	}

	/*
	* Método público para setar o nome da campo que é chave primária da tabela
	* @param $field_pk- String contendo o nome da chave primária tabela
	*/
	public function setFieldPK($field_pk){
		if(!empty($field_pk)):
			$this->field_pk = $field_pk;
		endif;
	}

	/*
	* Método para setar a array com campos nulos aceitos
	* @param $arrayNullAceito - Array com campos nulos que poderão ser aceitos no cadastro
	*/
	public function setNullAceito(array $arrayNullAceito){
		$this->arrayNullAceito = $arrayNullAceito;
	}

	/*
	* Método para setar a array com condições para verificação de duplicidade
	* @param $arrayCondicaoDuplicidade - Array com campos e condições para cláusula WHERE 
	*/
	public function setCondicaoDuplicidade(array $arrayCondicaoDuplicidade){
		$this->arrayCondicaoDuplicidade = $arrayCondicaoDuplicidade;
	}

	/*
	* Método para validar dados de uma array
	* @param $arrayDados - Array contendo dados a serem validados
	* @param $arrayNullAceito - Array contendo os campos que podem conter valores null
	* @return Boolean - Valor booleano TRUE array válida e FALSE array inválida
	*/
	private function validaArray($arrayDados){
		$retorno = TRUE;

		// Verifica se existem campos aceitos com valor null
		if (!empty($this->arrayNullAceito)):
			foreach($this->arrayNullAceito as $valor):
				if (array_key_exists($valor, $arrayDados)):
					unset($arrayDados[$valor]);
				endif;
			endforeach;
		endif;

		// Percorre array verificando se existem elementos vazios
		foreach($arrayDados as $valor):
			$retorno = (empty($valor)) ? FALSE : TRUE;
			if ($retorno == FALSE) break;
		endforeach;

		return $retorno;
	}

	/* 
	* Método para verificar duplicidade de registros no momento INSERT
	* retorna um valor booleano, se duplicado TRUE senão retorna FALSE
	*/
	private function verificaDuplicidade(){
		$valCondicao = "";

	   // Atribuindo qual será o campos de retorno, para não usar SELECT *
	   $campo = str_replace(array('<', '>', '=', '!'), "", key($this->arrayCondicaoDuplicidade));

	   // Loop para montar a condição WHERE
	   $cont = 1;   
	   foreach($this->arrayCondicaoDuplicidade as $chave => $valor):  
	   		if ($cont < count($this->arrayCondicaoDuplicidade)):
	   			$valCondicao .= $chave . '? AND '; 
	   		else:
	   			$valCondicao .= $chave . '?'; 
	   		endif; 
	       
	       $cont++;  
	   endforeach;

	   $sql = "SELECT {$campo} FROM {$this->tabela} WHERE " . $valCondicao; 
	   $retorno = $this->crud->getSQLGeneric($sql, $this->arrayCondicaoDuplicidade, TRUE);
	   
	   // Verifica se a consulta retornou vazia, se verdadeira retorna TRUE
	   if (empty($retorno)):
	   		return FALSE;
	   else:
	   		return TRUE;
	   endif;
	}

	/* 
	* Método para verificar duplicidade de registros no momento do Update
	* @param $arrayDados = Array de dados contendo colunas e valor
    * @param $valorCondicao = Array de dados contendo colunas e valor para condição WHERE - Exemplo array('$id='=>1) 
	* retorna um valor booleano, se duplicado TRUE senão retorna FALSE
	*/
	private function verificaDuplicidadeUpdate($arrayDados, $valorCondicao){
	   $valCondicao = "";
	   $parametros = array();
	   $valorPK = (String) $valorCondicao[key($valorCondicao)];

	    // Loop para montar a condição WHERE
	   $cont = 1;   
	   foreach($this->arrayCondicaoDuplicidade as $chave => $valor):  
	   		if ($cont < count($this->arrayCondicaoDuplicidade)):
	   			$valCondicao .= $chave . '? AND '; 
	   		else:
	   			$valCondicao .= $chave . '?'; 
	   		endif; 

	   		$key = str_replace(array('<', '>', '=', '!'), "", $chave);
	   		if (array_key_exists($key, $arrayDados)):
				$parametros[] = $arrayDados[$key];
			endif;
	       
	       $cont++;  
	   endforeach;

	   $sql = "SELECT {$this->field_pk} FROM {$this->tabela} WHERE " . $valCondicao; 
	   $dados = $this->crud->getSQLGeneric($sql, $parametros, TRUE);
	   $pk = $this->field_pk;
	   $valorRetorno = (String) $dados[0]->$pk;

	   // Verifica se a consulta retornou dados e compara com o valor passado como condição para o update
	   if(empty($dados)):
	   		return FALSE;
	   else:
		   if($valorRetorno != $valorPK || count($dados) > 1):
		   		return TRUE;
		   else:
			    return FALSE;
		   endif;
		endif;
	}

    /* 
    * Método para validar e enviar os dados para inserção na classe Crud
    * @param $arrayDados = Array de dados contendo colunas e valor
    * @param $duplicidade = Valor booleano TRUE obriga a verificação de duplicidade antes de inserir
    */
    public function insert($arrayDados, $duplicidade=TRUE){
    	try{
	    	if ($this->validaArray($arrayDados)):
	    		if($duplicidade == TRUE && !empty($this->arrayCondicaoDuplicidade)):
		    	    if($this->verificaDuplicidade()):
		    		   $array_retorno = array('codigo' => 0, 'mensagem' => 'Inclusão cancelada, está duplicando registros!');
		    		   return $array_retorno;
	    			   exit();
		    	    endif;
		    	endif;

	    		$retorno = $this->crud->insert($arrayDados);
	    		if($retorno == 1):
	    			$array_retorno = array('codigo' => 1, 'mensagem' => 'Registro incluído com sucesso!');
	    			return $array_retorno;
	    		else:
	    			$array_retorno = array('codigo' => 2, 'mensagem' => 'Houve um erro interno ao executar operação!');
	    			return $array_retorno;
	    		endif;
	    	else:
	    		$array_retorno = array('codigo' => 3, 'mensagem' => 'Existem campos sem preenchimento!');
	    		return $array_retorno;
	    		exit();
	    	endif;	
	    }catch(Exception $e){
	    	$erro = 'Erro: ' . $e->getMessage();
	    	$array_retorno = array('codigo' => 4, 'mensagem' => $erro);
	    	return $array_retorno;
	    }
    }

    /* 
    * Método para validar e enviar os dados para atualização na classe Crud
    * @param $arrayDados = Array de dados contendo colunas e valor
    * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE - Exemplo array('$id='=>1)   
    */
    public function update($arrayDados, $arrayCondicao, $duplicidade=TRUE){
    	try{
	    	if ($this->validaArray($arrayDados)):
	    		if($duplicidade == TRUE && !empty($this->arrayCondicaoDuplicidade)):
	    			if($this->verificaDuplicidadeUpdate($arrayDados, $arrayCondicao)):
	    			   $array_retorno = array('codigo' => 0, 'mensagem' => 'Edição cancelada, está duplicando registros!');
		    		   return $array_retorno;
	    			   exit();
			    	endif;
			    endif;

	    		$retorno = $this->crud->update($arrayDados, $arrayCondicao);
	    		if($retorno == 1):
	    			$array_retorno = array('codigo' => 1, 'mensagem' => 'Registro alterado com sucesso!');
	    			return $array_retorno;
	    		else:
	    			$array_retorno = array('codigo' => 2, 'mensagem' => 'Houve um erro interno ao executar operação!');
	    			return $array_retorno;
	    		endif;
	    	else:
	    		$array_retorno = array('codigo' => 3, 'mensagem' => 'Existem campos sem preenchimento!');
	    		return $array_retorno;
	    		exit();
	    	endif;
    	}catch(Exception $e){
	    	$erro = 'Erro: ' . $e->getMessage();
	    	$array_retorno = array('codigo' => 4, 'mensagem' => $erro);
	    	return $array_retorno;
	    }
    }

    /*   
    * Método público para excluir os dados na tabela   
    * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE - Exemplo array('$id='=>1)   
    * @return Retorna resultado booleano da instrução SQL   
    */
    public function delete($arrayCondicao){
    	try{
	    	if ($this->validaArray($arrayCondicao)):
	    		$retorno = $this->crud->delete($arrayCondicao);
	    		if($retorno == 1):
	    			$array_retorno = array('codigo' => 1, 'mensagem' => 'Registro excluído com sucesso!');
	    			return $array_retorno;
	    		else:
	    			$array_retorno = array('codigo' => 2, 'mensagem' => 'Houve um erro interno ao executar operação!');
	    			return $array_retorno;
	    		endif;
	    	else:
	    		$array_retorno = array('codigo' => 3, 'mensagem' => 'Existem campos sem preenchimento!');
	    		return $array_retorno;
	    		exit();
	    	endif;
    	}catch(Exception $e){
	    	$erro = 'Erro: ' . $e->getMessage();
	    	$array_retorno = array('codigo' => 4, 'mensagem' => $erro);
	    	return $array_retorno;
	    }
    }

   /*  
   * Método público para executar instruções de consulta independente do nome da tabela passada no _construct  
   * @param $sql = Instrução SQL inteira contendo, nome das tabelas envolvidas, JOINS, WHERE, ORDER BY, GROUP BY e LIMIT  
   * @param $arrayParam = Array contendo somente os parâmetros necessários para clásusla WHERE  
   * @param $fetchAll  = Valor booleano com valor default TRUE indicando que serão retornadas várias linhas, FALSE retorna apenas a primeira linha  
   * @return Retorna array de dados da consulta em forma de objetos  
   */ 
   public function getDados($sql, $arrayParams=null, $fetchAll=TRUE){
   		try{
   			if(!empty($sql)):
   				$retorno = $this->crud->getSQLGeneric($sql, $arrayParams, $fetchAll);
   				return $retorno;
   			endif;
   		}catch(Exception $e){
	    	$erro = 'Erro: ' . $e->getMessage();
	    	$array_retorno = array('codigo' => 4, 'mensagem' => $erro);
	    	return $array_retorno;
	    }
   } 
}