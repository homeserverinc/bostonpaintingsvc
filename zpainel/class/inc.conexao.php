<?php

include "inc.erros.php";

/**
 * Classe de conexão e manipulação do Banco de Dados
 * 
 * <i>Criada em: 05/08/2006
 * Última Alteração: 05/10/2006</i>
 * 
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright © 2006, Zaib Tecnologia
 * @package Class
 */
class cConexao extends cErros {
 	
 	/**
 	 * Variável que detem a conexão (id)
 	 */
 	private $v_con;
 	
 	/**
 	 * Variavel que mantem a seleção do banco de dados
 	 */
 	private $v_sel_banco;
 	
 	/**
 	 * Host do servidor
 	 */
 	private $v_servidor;
 	
 	/**
 	 * Usuário do banco de dados
 	 */
    private $v_usuario;
    
    /**
     * Senha do banco de dados
     */
    private $v_senha;
    
    /**
     * Banco de dados a ser selecionado
     */
    private $v_db;
    
    /**
     * Mantem a Ultima query para maiores informações como
     * numero de linhas rerotnadas
     */
    private $v_ultima_query;
    
    /**
     * Clausula Sql
     */
    private $v_clausula_sql;
    
    /**
     * Exclusivo p/ mater sql de alterações de cliente
     */
    public $v_sql_manipula;
    
	/**
	 * Método Construtor que inicia a conexão
	 * com o banco de dados e seta também.
	 * @param $host » Host do servidor
	 * @param $user » User do banco
	 * @param $pass » Senha do banco
	 * @param $db   » Banco de dados a ser selecionado
	 */
    function __construct($p_host="localhost", $p_user="root", $p_pass="su10n11n3", $p_db="bostonpaintingsvc") {
		
    	$this->v_servidor = $p_host;
    	$this->v_usuario  = $p_user;
        $this->v_senha    = $p_pass;
        $this->v_db       = $p_db;
        $this->v_email    = "erros@zaib.com.br";
        $this->mConecta();    
	}
	
	/**
	 * Efetua conexão com o banco de dados
	 */
	private function mConecta() {
		
		// Estabelece conexão com o banco de dados
		//$this->v_con = @mysql_connect($this->v_servidor, $this->v_usuario, $this->v_senha);
		try {
			$this->v_con = new PDO('mysql:host='.$this->v_servidor.';dbname='.$this->v_db, $this->v_usuario, $this->v_senha);
		} catch (PDOException $e) {
			$this->v_msg_erro  = $e->getCode()." - ".$e->getMessage();
			$this->v_tipo      = "Conexão com Banco de Dados";
			$this->v_endereco  = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
			$this->mExibeMsgErro();
		}


		// Em caso de erro, seta mensagem de erro
		/* if(!$this->v_con) { 
			$this->v_msg_erro  = mysql_errno()." - ".mysql_error();
			$this->v_tipo      = "Conexão com Banco de Dados";
			$this->v_endereco  = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
			$this->mExibeMsgErro();
		}else{
			$this->mSelecionaBanco();
		} */
	}
	
	/**
	 * Seleciona o banco de dados desejado
	 */
	/* private function mSelecionaBanco() {
		// Seleciona banco de dados
        $this->v_sel_banco = @mysql_select_db($this->v_db, $this->v_con);
        
        // Em caso de erro, seta mensagem de erro
        if(!$this->v_sel_banco) { 
			$this->v_msg_erro  = mysql_errno()." - ".mysql_error();
			$this->v_tipo     = "Seleção do Banco de Dados";
			$this->v_endereco = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
			$this->mExibeMsgErro();
		}
	} */
	
	/**
	 * Efetua a query no banco de dados.
	 * @param string $sql » Comandos sql
	 * @return stron $ultimaQuery » Ultima query executada
	 */
	public function mQuery($p_query){
		$this->v_ultimo_sql   = $p_query;
		try {
			$this->v_ultima_query = $this->v_con->query($p_query);
			return $this->v_ultima_query;
		} catch (PDOException $e) {
			$this->v_msg_erro  = $e->getCode()." - ".$e->getMessage();
			$this->v_tipo     = "Erro de Manipulação de Dados (Sql)";
			$this->v_endereco = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
			$this->mExibeMsgErro();
			return 0;
		}
	}
	
	/**
	 * Retorna o total de linhas
	 * @param string $query » Sql
	 * @return string rows » Número de Linhas
	 */
	public function mRows($p_query = "") {
		$s_consulta = !empty($p_query) ? $p_query : $this->v_ultima_query;
		return @mysql_num_rows($s_consulta);
	}
	
	/**
	 * Seta a consulta como objeto
	 * @param string $consulta
	 * @return object » Retorna consulta como um objeto 
	 */
	public function mFetchObject($p_consulta = "") { 
		$p_consulta = !empty($p_consulta) ? $p_consulta : $this->v_ultima_query;
		$s_object = @mysql_fetch_object($p_consulta);
		return $s_object;
	}
	
	/**
	 * Seta a consulta como um array
	 * @param string $consulta » Sql
	 * @return array » Retorna como array
	 */
	public function mFetchArray($p_consulta = "") { 
		$p_consulta = !empty($p_consulta) ? $p_consulta : $this->v_ultima_query;
		$s_arr = @mysql_fetch_array($p_consulta);
		return $s_arr;
	}
	
	/**
	 * Fecha conexão com o Banco de Dados
	 */
	public function mClose() {
		@mysql_close($this->v_con);
	}
	
	/**
    * Cria cláusulas SQL INSERT
    * @param string $tabela » Nome da Tabela
    * @param array $campos » Campos a serem incluidos
    * @param array $valores » Valores a serem incluidos
    * @return strong » Sql formado
    */
    public function mGeraInsert($p_tabela, $p_campos, $p_valores) {
        $this->v_clausula_sql = "INSERT INTO " . $p_tabela . " (";

		// Quantidade de campos
        $s_quantidade_campos = count($p_campos);
        
        for ($i = 0; $i < $s_quantidade_campos; ++$i) {
            $this->v_clausula_sql .= $p_campos[$i];

            if ($i < ($s_quantidade_campos-1)) {
                $this->v_clausula_sql .= ", ";
            }
        }

        $this->v_clausula_sql .= ") VALUES (";

        for ($i = 0; $i < $s_quantidade_campos; ++$i) {
            $this->v_clausula_sql .= $this->mEscreveValor($p_valores[$i], $i, $s_quantidade_campos);
        }

        $this->v_clausula_sql .= ");";

		$this->v_sql_manipula = $this->v_clausula_sql;	
        
        return $this->mQuery($this->v_clausula_sql);
    }
    
    /**
    * Cria cláusulas SQL UPDATE
    * @param string $tabela » Nome da Tabela
    * @param array $campos » Campos a serem incluidos
    * @param array $valores » Valores a serem incluidos
    * @param string $sentenca » Condição que satisfaz
    * @return string » Sql formada
    */
    public function mGeraUpdate($p_tabela, $p_campos, $p_valores, $p_sentenca) {
        $this->v_clausula_sql = "UPDATE " . $p_tabela . " SET ";

        $s_quantidade_campos = count($p_campos);

        for ($i = 0; $i < $s_quantidade_campos; ++$i) {
            $this->v_clausula_sql .= $p_campos[$i] . " = ";
            $this->v_clausula_sql .= $this->mEscreveValor($p_valores[$i], $i, $s_quantidade_campos);
        }

        $this->v_clausula_sql .= " " . $p_sentenca . ";";
        
        $this->v_sql_manipula = $this->v_clausula_sql;
        
        return $this->mQuery($this->v_clausula_sql);
    }

    /**
    * Retorna um valor formatado para se inserir na query SQL
    * @param string $valor » Valores vindos do array de valores
    * @param int $atual » Campo o qual deve ser inserido o valor
    * @param int $total » Total de Campos
    * @return string » Junção dos campos do Sql (valores)
    */
    private function mEscreveValor($p_valor, $p_atual, $p_total) {
        if (is_string($p_valor)) {
            $s_retorno = "'" . $p_valor . "'";
        } elseif (empty($p_valor)) {
            $s_retorno = "NULL";
        } else {
            $s_retorno = $p_valor;
        }

        if ($p_atual < ($p_total-1)) {
            $s_retorno .= ", ";
        }

        return $s_retorno;
    }
    
    /**
    * Cria cláusulas SQL DELETE
    * @param string $tabela » Nome da Tabela
    * @param string $sentenca » Condição que satusfaz
    */
    public function mGeraDelete($p_tabela, $p_sentenca) {
        $this->v_clausula_sql = "DELETE FROM " . $p_tabela;

        $this->v_clausula_sql .= " " . $p_sentenca . ";";

		$this->v_sql_manipula = $this->v_clausula_sql;
		
        return $this->mQuery($this->v_clausula_sql);
    }
}
?>
