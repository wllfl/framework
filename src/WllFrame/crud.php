<?php  

namespace WllFrame;
  
 /*************************************************************************************************************  
 * @author William F. Leite                                                                                   *  
 * Data: 20/06/2014                                                                                           *  
 * Descrição: Classe elaborada com o objetivo de auxlilar nas operações CRUDs em diversos SGBDS, possui       *  
 * funcionalidades para construir instruções de INSERT, UPDATE E DELETE onde as mesmas podem ser executadas   *  
 * nos principais SGBDs, exemplo SQL Server, MySQL e Firebird. Instruções SELECT são recebidas integralmente  *  
 * via parâmetro.                                                                                             *  
 *************************************************************************************************************/  


error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "conexao.php";

use WllFrame\conexao;

class crud{   
  
   // Atributo para guardar uma conexão PDO   
   private $pdo = null;   
    
   // Atributo onde será guardado o nome da tabela    
   private $tabela = null;   
   
      
   /*   
   * Método construtor da classe     
   * @param $tabela = Nome da tabela    
   */   
   public function __construct($tabela=NULL){   
         
       $this->pdo = Conexao::getInstance();

       if (!empty($tabela)) $this->tabela =$tabela;   
   }     

   /*  
   * Método para setar o nome da tabela na propriedade $tabela  
   * @param $tabela = String contendo o nome da tabela  
   */   
   public function setTableName($tabela){  
     if(!empty($tabela)){  
       $this->tabela = $tabela;  
     }  
   } 

    /*  
   * Método para retornar o nome da tabela
   * @return tring contendo o nome da tabela  
   */   
   public function getTableName(){  
      return $this->tabela;
   } 
    
   /*   
   * Método privado para construção da instrução SQL de INSERT   
   * @param $arrayDados = Array de dados contendo colunas e valores   
   * @return String contendo instrução SQL   
   */    
   public function buildInsert($arrayDados){   
    
       // Inicializa variáveis   
       $sql = "";   
       $campos = "";   
       $valores = "";   
        
       // Loop para montar a instrução com os campos e valores 
       $cont = 1;  
       foreach($arrayDados as $chave => $valor):
            if($cont < count($arrayDados)):
                $campos .= $chave . ', ';   
                $valores .= '?, ';   
            else:
                $campos .= $chave . ' ';   
                $valores .= '?';   
            endif;   
        
             $cont++;
       endforeach;   

       // Concatena todas as variáveis e finaliza a instrução   
       $sql .= "INSERT INTO {$this->tabela} (" . trim($campos) . ")VALUES(" . $valores . ")";   
        
       // Retorna string com instrução SQL   
       return trim($sql);   
   }   
    
   /*   
   * Método privado para construção da instrução SQL de UPDATE   
   * @param $arrayDados = Array de dados contendo colunas, operadores e valores   
   * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE   
   * @return String contendo instrução SQL   
   */    
   public function buildUpdate($arrayDados, $arrayCondicao=null){   
    
       // Inicializa variáveis   
       $sql = "";   
       $valCampos = "";   
       $valCondicao = "";   
        
       // Loop para montar a instrução com os campos e valores 
       $cont = 1;  
       foreach($arrayDados as $chave => $valor): 
          if($cont < count($arrayDados)):
             $valCampos .= $chave . '=?, '; 
          else:
             $valCampos .= $chave . '=?'; 
          endif; 

          $cont++;  
       endforeach;   
        
       // Loop para montar a condição WHERE
       if(!empty($arrayCondicao)):
         $cont = 1;   
         foreach($arrayCondicao as $chave => $valor): 
             if($cont < count($arrayCondicao)):
                 $valCondicao .= $chave . '? AND ';   
             else:
                 $valCondicao .= $chave . '?';   
             endif;
    
            $cont++;
         endforeach;  
       endif; 
   
       // Concatena todas as variáveis e finaliza a instrução 
       if (!empty($valCondicao)):  
          $sql .= "UPDATE {$this->tabela} SET " . $valCampos . " WHERE " . $valCondicao;  
       else:
          $sql .= "UPDATE {$this->tabela} SET " . $valCampos;
       endif; 
        
       // Retorna string com instrução SQL   
       return trim($sql);   
   }   
    
   /*   
   * Método privado para construção da instrução SQL de DELETE   
   * @param $arrayCondicao = Array de dados contendo colunas, operadores e valores para condição WHERE   
   * @return String contendo instrução SQL   
   */    
   public function buildDelete($arrayCondicao){   
    
       if(!empty($arrayCondicao)):
           // Inicializa variáveis   
           $sql = "";   
           $valCampos= "";   
            
           // Loop para montar a instrução com os campos e valores 
           $cont = 1;  
           foreach($arrayCondicao as $chave => $valor):
               if($cont < count($arrayCondicao)):   
                  $valCampos .= $chave . '? AND '; 
               else:
                  $valCampos .= $chave . '?'; 
               endif;

             $cont++;  
           endforeach;   

           // Concatena todas as variáveis e finaliza a instrução   
           $sql .= "DELETE FROM {$this->tabela} WHERE " . $valCampos;   
            
           // Retorna string com instrução SQL   
           return trim($sql);   
        else:
          throw new \InvalidArgumentException("Argumento Inválido!");    
        endif;
   }   
    
   /*   
   * Método público para inserir os dados na tabela   
   * @param $arrayDados = Array de dados contendo colunas e valores   
   * @return Retorna resultado booleano da instrução SQL   
   */   
   public function insert($arrayDados){   
      try {   
    
        // Atribui a instrução SQL construida no método   
        $sql = $this->buildInsert($arrayDados);   
    
        // Passa a instrução para o PDO   
        $stm = $this->pdo->prepare($sql);   
    
        // Loop para passar os dados como parâmetro   
        $cont = 1;   
        foreach ($arrayDados as $valor):   
              $stm->bindValue($cont, $valor);   
              $cont++;   
        endforeach;   
    
        // Executa a instrução SQL e captura o retorno   
        $retorno = $stm->execute();   
    
        return $retorno;   
           
      } catch (PDOException $e) {   
        echo "Erro: " . $e->getMessage(); 
        return false;   
      }   
   }   
    
   /*   
   * Método público para atualizar os dados na tabela   
   * @param $arrayDados = Array de dados contendo colunas e valores   
   * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE - Exemplo array('$id='=>1)   
   * @return Retorna resultado booleano da instrução SQL   
   */   
   public function update($arrayDados, $arrayCondicao=null){   
      try {   
    
        // Atribui a instrução SQL construida no método   
        $sql = $this->buildUpdate($arrayDados, $arrayCondicao);   
    
        // Passa a instrução para o PDO   
        $stm = $this->pdo->prepare($sql);   
    
        // Loop para passar os dados como parâmetro   
        $cont = 1;   
        foreach ($arrayDados as $valor):   
          $stm->bindValue($cont, $valor);   
          $cont++;   
        endforeach;   
        
        // Loop para passar os dados como parâmetro cláusula WHERE   
        if (!empty($arrayCondicao)):
            foreach ($arrayCondicao as $valor):   
              $stm->bindValue($cont, $valor);   
              $cont++;   
            endforeach;  
        endif; 
    
        // Executa a instrução SQL e captura o retorno   
        $retorno = $stm->execute();   
    
        return $retorno;   
           
      } catch (PDOException $e) {   
        echo "Erro: " . $e->getMessage();   
        return false; 
      }   
   }   
    
   /*   
   * Método público para excluir os dados na tabela   
   * @param $arrayCondicao = Array de dados contendo colunas e valores para condição WHERE - Exemplo array('$id='=>1)   
   * @return Retorna resultado booleano da instrução SQL   
   */   
   public function delete($arrayCondicao){   
      try {   
    
        // Atribui a instrução SQL construida no método   
        $sql = $this->buildDelete($arrayCondicao);   
    
        // Passa a instrução para o PDO   
        $stm = $this->pdo->prepare($sql);   
    
        // Loop para passar os dados como parâmetro cláusula WHERE   
        $cont = 1;   
        foreach ($arrayCondicao as $valor):   
          $stm->bindValue($cont, $valor);   
          $cont++;   
        endforeach;   
    
        // Executa a instrução SQL e captura o retorno   
        $retorno = $stm->execute();   
    
        return $retorno;   
           
      } catch (PDOException $e) {   
        echo "Erro: " . $e->getMessage();  
        return false; 
      }   
   }   

   /*  
   * Método genérico para executar instruções de consulta independente do nome da tabela passada no _construct  
   * @param $sql = Instrução SQL inteira contendo, nome das tabelas envolvidas, JOINS, WHERE, ORDER BY, GROUP BY e LIMIT  
   * @param $arrayParam = Array contendo somente os parâmetros necessários para clásusla WHERE  
   * @param $fetchAll  = Valor booleano com valor default TRUE indicando que serão retornadas várias linhas, FALSE retorna apenas a primeira linha  
   * @return Retorna array de dados da consulta em forma de objetos  
   */  
   public function getSelectGeneric($sql, $arrayParams=null, $fetchAll=TRUE){  
      try {   
          // Passa a instrução para o PDO   
          $stm = $this->pdo->prepare($sql);   
      
          // Verifica se existem condições para carregar os parâmetros    
          if (!empty($arrayParams)):   
      
            // Loop para passar os dados como parâmetro cláusula WHERE   
            $cont = 1;   
            foreach ($arrayParams as $valor):   
              $stm->bindValue($cont, $valor);   
              $cont++;   
            endforeach;   
          
          endif;   
      
          // Executa a instrução SQL    
          $stm->execute();   
      
          // Verifica se é necessário retornar várias linhas  
          if($fetchAll):   
            $dados = $stm->fetchAll(\PDO::FETCH_OBJ);   
          else:  
            $dados = $stm->fetch(\PDO::FETCH_OBJ);   
          endif;  
      
          return $dados;   
           
      } catch (PDOException $e) {   
          echo "Erro: " . $e->getMessage();   
      }   
   } 

   /*  
   * Método genérico para executar instruções SQL para INSERT, UPDATE E DELETE quando não houver necessidade de se montar bind dos parâmetros
   * @param $sql = Instrução SQL inteira contendo, com nome da tabela, campos e valores
   * @return Retorna valor booleano da método execute()
   */ 
   public function execInstrucaoSQL($sql){
      try {   
          
          $stm = $this->pdo->prepare($sql);
          $retorno = $stm->execute();
           
          return $retorno;
      } catch (PDOException $e) {   
          echo "Erro: " . $e->getMessage();   
      }   
   }  
}  
