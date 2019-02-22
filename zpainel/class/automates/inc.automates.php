<?php
/**
 * Classe de administração e interação com banco de dados a partir de uma tabela base
 * 
 * <i>Criada em: 23/01/2009
 * Última Alteração: 17/02/2010</i>
 * 
 * @author Eduardo Firmino Leitão
 * @version 1.7.9
 * @copyright Copyright © 2009, Eduardo Firmino Leitão = www.eduardofirmino.com
 * @package Class
 */
class cAutomates {

	/**
 	 * Variável de nome da tabela base
 	*/
	private $v_tabelaBase;
	
	/**
 	 * Variável do objeto de conexão com o banco de dados
 	*/
	private $cCon;
	
	/**
 	 * Variável para guardar a paginação caso queira mostrar
 	*/
	public $v_paginacao;
	
	/**
 	 * Variável para guardar o caminho dos arquivos de upload
 	*/
	public $v_caminho_imagem = "../../fotos/";
	
	/**
	 * Método Construtor que seta a tabela base
	 * @param String $tabelaBase » Tabela base a qual quer trabalhar
	*/
	function __construct($tabelaBase="") {
		$this->cCon = new cConexao;
		
		$tabelaBase = trim($tabelaBase);
		if (empty($tabelaBase)) {
			exit("Erro: Tabela base em branco");
		} else {
			$this->v_tabelaBase = $tabelaBase;
		}
	}
	
	/**
	 * Método para retornar informações do comentário da tabela base
	 * @return Array $result » Informações da tabela
	*/
	function mRetornaInfTabela() {
		$consultaInfTabela = $this->cCon->mQuery("SHOW TABLE STATUS LIKE '".$this->v_tabelaBase."'");
		$dadosInfTabela = $this->cCon->mFetchObject($consultaInfTabela);
		
		//verifica se tem comment setado pelo admin
		if ($this->v_tabela_comment) $separaPontoVirgula = explode(";",$this->v_tabela_comment);
		else $separaPontoVirgula = explode(";",$dadosInfTabela->Comment);
		$result = array();
		$result["comment"] = $dadosInfTabela->Comment;
		
		for($i=0;$i<count($separaPontoVirgula);$i++) {
			$separaDoisPontos = explode(":",$separaPontoVirgula[$i]);
			
			//title
			if ($separaDoisPontos[0] == "t") $result["title"] = $separaDoisPontos[1];
			//insert
			if ($separaDoisPontos[0] == "i") $result["insert"] = $separaDoisPontos[1];
			//manage
			if ($separaDoisPontos[0] == "m") $result["manage"] = $separaDoisPontos[1];
			//update
			if ($separaDoisPontos[0] == "u") $result["update"] = $separaDoisPontos[1];
			//delete
			if ($separaDoisPontos[0] == "d") $result["delete"] = $separaDoisPontos[1];
			//order
			if ($separaDoisPontos[0] == "o") {
				$separaVirgula = explode(",",$separaDoisPontos[1]);
				if ($separaVirgula[1] == "a") $result["order"] = $separaVirgula[0]." ASC";
				else $result["order"] = $separaVirgula[0]." DESC";
			}
		}
		
		//relation
		$consultaInfCampo = $this->mRetornaCampos();
		$result["relation"] = $consultaInfCampo[0]["relationTable"];
		$result["relationTitle"] = $consultaInfCampo[0]["relationCampo"];
		
		return $result;
	}
	
	/**
	 * Método para retornar um ou mais campos da tabela base
	 * @param String $nmCampo » Campo que quiser obter detalhes (opcional)
	 * @return Array $result » Dados consultados
	*/
	function mRetornaCampos($nmCampo="") {
		if ($nmCampo) $consultaInfCamposLike = "LIKE '".$nmCampo."'";
		
		$result = array();
		$i = 0;
		$consultaInfCampos = $this->cCon->mQuery("SHOW FULL COLUMNS FROM ".$this->v_tabelaBase." $consultaInfCamposLike");
		while ($dadosInfCampos = $this->cCon->mFetchObject($consultaInfCampos)) {
			$result[$i]["field"] = $dadosInfCampos->Field;
			$result[$i]["type"] = $dadosInfCampos->Type;
			$result[$i]["collation"] = $dadosInfCampos->Collation;
			$result[$i]["null"] = $dadosInfCampos->Null;
			$result[$i]["key"] = $dadosInfCampos->Key;
			$result[$i]["default"] = $dadosInfCampos->Default;
			$result[$i]["extra"] = $dadosInfCampos->Extra;
			$result[$i]["privileges"] = $dadosInfCampos->Privileges;
			$result[$i]["comment"] = $dadosInfCampos->Comment;
			
			//informacoes do campo comment
			if ($this->v_campo_comment[$dadosInfCampos->Field]) $separaPontoVirgula = explode(";",$this->v_campo_comment[$dadosInfCampos->Field]);
			else $separaPontoVirgula = explode(";",$dadosInfCampos->Comment);
			for($j=0;$j<count($separaPontoVirgula);$j++) {
				$separaDoisPontos = explode(":",$separaPontoVirgula[$j]);
				
				//title campo
				if ($separaDoisPontos[0] == "t") $result[$i]["titleCampo"] = $separaDoisPontos[1];
				//size
				if ($separaDoisPontos[0] == "s") $result[$i]["size"] = $separaDoisPontos[1];
				//validation
				if ($separaDoisPontos[0] == "v") $result[$i]["validation"] = $separaDoisPontos[1];
				//mask
				if ($separaDoisPontos[0] == "m") {
					if (count($separaDoisPontos) > 2) {
						$result[$i]["mask"] = $separaDoisPontos[1].":".$separaDoisPontos[2];
					} else {
						$result[$i]["mask"] = $separaDoisPontos[1];
					}
				}
				//list
				if ($separaDoisPontos[0] == "l") $result[$i]["list"] = $separaDoisPontos[1];
				//align
				if ($separaDoisPontos[0] == "a") $result[$i]["align"] = $separaDoisPontos[1];
				//relation
				if ($separaDoisPontos[0] == "r") {
					$separaVirgula = explode(",",$separaDoisPontos[1]);
					$result[$i]["relationTable"] = $separaVirgula[0];
					$result[$i]["relationCampo"] = $separaVirgula[1];
					$result[$i]["relationCampo2"] = $separaVirgula[2];
				}
				//file
				if ($separaDoisPontos[0] == "f") $result[$i]["file"] = $separaDoisPontos[1];
				//padrao
				if ($separaDoisPontos[0] == "p") $result[$i]["padrao"] = $separaDoisPontos[1];
				//maxlength
				if ($separaDoisPontos[0] == "ml") $result[$i]["maxlength"] = $separaDoisPontos[1];
				//oculto
				if ($separaDoisPontos[0] == "o") $result[$i]["oculto"] = $separaDoisPontos[1];
				//tipo do campo
				if ($separaDoisPontos[0] == "c") $result[$i]["tipoCampo"] = $separaDoisPontos[1];
				//read only
				if ($separaDoisPontos[0] == "ro") $result[$i]["readOnly"] = $separaDoisPontos[1];
				//exemplo
				if ($separaDoisPontos[0] == "ex") $result[$i]["exemplo"] = $separaDoisPontos[1];
				//relation codigo
				if ($separaDoisPontos[0] == "rc") $result[$i]["relationCodigo"] = $separaDoisPontos[1];

				//html com tinymce
				if ($separaDoisPontos[0] == "h") $result[$i]["tinyhtml"] = $separaDoisPontos[1];
			}
			$i++;
		}
		
		return $result;
	}
	
	/**
	 * Método para retornar dados da tabela base
	 * @param int $codigo » Código do cadastro que queira buscar os dados (opcional)
	 * @param String $where » Opções de where no sql de consulta
	 * @param String $order » Opções de order no sql de consulta
	 * @param String $order » Opções de limit no sql de consulta
	 * @return Array $result » Dados consultados
	*/
	function mRetornaDados($codigo="",$where="",$order="",$limit="") {
		$campos = $this->mRetornaCampos();
		
		//verifica se tem campos relacionados para fazer INNER JOIN ou LEFT JOIN
		$realJoin = "";
		$tabelaAtual = $this->v_tabelaBase;
		for($i=1;$i<count($campos);$i++) {
			if ($campos[$i]["relationTable"]) {
				if ($campos[$i]["null"] != "YES") $realJoin.= "INNER JOIN ".$campos[$i]["relationTable"]." ON (".$campos[$i]["relationTable"].".".$campos[$i]["field"]." = ".$this->v_tabelaBase.".".$campos[$i]["field"].") ";
				else $realJoin.= "LEFT JOIN ".$campos[$i]["relationTable"]." ON (".$campos[$i]["relationTable"].".".$campos[$i]["field"]." = ".$this->v_tabelaBase.".".$campos[$i]["field"].") ";
				
				$this->v_tabelaBase = $campos[$i]["relationTable"];
				$campos = $this->mRetornaCampos();
				for($j=1;$j<count($campos);$j++) {
					if ($campos[$j]["relationTable"]) {
						if ($campos[$j]["null"] != "YES") $realJoin.= "INNER JOIN ".$campos[$j]["relationTable"]." ON (".$campos[$j]["relationTable"].".".$campos[$j]["field"]." = ".$this->v_tabelaBase.".".$campos[$j]["field"].") ";
						else $realJoin.= "LEFT JOIN ".$campos[$j]["relationTable"]." ON (".$campos[$j]["relationTable"].".".$campos[$j]["field"]." = ".$this->v_tabelaBase.".".$campos[$j]["field"].") ";
					}
				}
				$this->v_tabelaBase = $tabelaAtual;
				$campos = $this->mRetornaCampos();
			}
		}
		
		//codigo
		if ($codigo) $realWhere = "WHERE ".$campos[0]["field"]." = '".$codigo."'";
		//where
		if ($where) {
			if ($realWhere) $realWhere = $realWhere." AND ".$where;
			else $realWhere = "WHERE ".$where;
		}
		//order
		$tabelaOrder = $this->mRetornaInfTabela();
		if ($tabelaOrder["order"]) $realOrder = "ORDER BY ".$tabelaOrder["order"];
		if ($order) $realOrder = "ORDER BY ".$order;
		//limit
		if ($limit) $realLimit = "LIMIT ".$limit;
	
		$result = array();
		$i = 0;
		
		$consultaDados = $this->cCon->mQuery("SELECT * FROM ".$this->v_tabelaBase." $realJoin $realWhere $realOrder $realLimit");
		while ($dadosDados = $this->cCon->mFetchObject($consultaDados)) {
			foreach($dadosDados as $key => $val) {
				$result[$i][$key] = $val;
			}
			$i++;
		}
		
		return $result;
	}
	
	/**
	 * Método para atualizar dados como cadastrar e modificar
	 * @param Array $dados » Dados para atualizar no banco
	 * @param int $codigo » Código do cadastro caso queira modificar (opcional)
	 * @param String $where » Opções de where no sql
	*/
	function mAtualizaDados($dados="",$codigo="",$where="") {
		if (empty($dados)) exit("Erro: Dados em branco");
		
		if (($codigo) || ($where)) {
			//where
			if ($codigo) {
				$camposTabelaBase = $this->mRetornaCampos();
				$realWhere = "WHERE ".$camposTabelaBase[0]["field"]." = '".$codigo."'";
				if ($where) $realWhere = $realWhere." AND ".$where;
			} else {
				if ($where) $realWhere = "WHERE ".$where;
			}
			
			//campos e valores
			$realCamposValores = array();
			foreach($dados as $key => $val) {
				array_push($realCamposValores,$key." = ".$val);
			}
			$realCamposValores = implode(", ",$realCamposValores);

			//update
			$this->cCon->mQuery("UPDATE ".$this->v_tabelaBase." SET ".$realCamposValores." $realWhere");
		} else {
			//campos
			$realCampos = array_keys($dados);
			$realCampos = implode(",",$realCampos);
			
			//values
			$realValues = array_values($dados);
			$realValues = implode(",",$realValues);
		
			//insert
			$this->cCon->mQuery("INSERT INTO ".$this->v_tabelaBase." (".$realCampos.") VALUES (".$realValues.")");
		}
	}
	
	/**
	 * Método para deletar dados da tabela base
	 * @param int $codigo » Código do cadastro que queira deletar (opcional)
	 * @param String $where » Opções de where no sql
	*/
	function mDeletaDados($codigo="",$where="") {
		if (($codigo) || ($where)) {
			//where
			if ($codigo) {
				$camposTabelaBase = $this->mRetornaCampos();
				$realWhere = "WHERE ".$camposTabelaBase[0]["field"]." = '".$codigo."'";
				if ($where) $realWhere = $realWhere." AND ".$where;
			} else {
				if ($where) $realWhere = "WHERE ".$where;
			}
			
			//delete
			$this->cCon->mQuery("DELETE FROM ".$this->v_tabelaBase." $realWhere");
		} else {
			exit("Erro: Código ou condição em branco");
		}
	}
	
	/**
	 * Método para converter datas
	 * @param string $p_data » Data que deseja converter
	 * @param int $p_tipo » 1 - Brasileira e 0 - Americana
	 * @param int $p_hora» 1 - Tem Horas e 0 - Não tem
	 * @return string » Data convertida 
	*/
	function mConverteData($p_data, $p_tipo, $p_hora=0){
	 	if($p_hora == 0) {
	 		$p_tipo == 0 ? $p_data = substr($p_data,6,4)."-".substr($p_data,3,2)."-".substr($p_data,0,2) : $p_data = substr($p_data,8,2) . "/" . substr($p_data,5,2) . "/" . substr($p_data,0,4);;
	 	}else {
	 		$p_tipo == 0 ? $p_data = substr($p_data,6,4)."-".substr($p_data,3,2)."-".substr($p_data,0,2)." ".substr($p_data, 10) : $p_data = substr($p_data,8,2) . "/" . substr($p_data,5,2) . "/" . substr($p_data,0,4).substr($p_data, 10);
	 	}
	 	return $p_data;
	}
	
	/**
 	 * Trata campos de valores de moeda, retirando os "."
 	 * @param string $p_valor » Valor no formato 10.000,00
 	 * @return string » Formatada cadastrar no banco de dados
 	 */
	function mTrataMoeda($p_valor) {
		$p_valor = str_replace(".", "", $p_valor);
		$p_valor = str_replace(",", ".", $p_valor);
		
		return $p_valor;
	}
	
	/**
 	 * Trata descrição colocando ... (tres pontos) ou não
 	 * @param string $p_descricao » Descricao
	 * @param int $p_qtd » Quantidade permitida de caracteres
	 * @param int $p_tags » 0 = retira as tags html, 1 = deixa com tags html
 	 * @return string » Tratada p/ aparecer no site
 	 */
	function mBreveDescricao($p_descricao, $p_qtd, $p_tags=0) {
		if (strlen($p_descricao) > $p_qtd) {
			$p_descricao = substr($p_descricao,0,($p_qtd-3))."...";
			
			if ($p_tags == 0) {
				$p_descricao = strip_tags($p_descricao);
			}
		}
		
		return $p_descricao;
	}
	
	/**
	 * Método para gerar paginação de resultados
	 * @param int $pagina » Número da página atual
	 * @param int $resultadosPagina » Número de resultados por página
	 * @param int $totalResultados » Total de resultados da consulta
	 * @param string $link » Link para a paginação
	 * @param int $nrPaginas » Número de páginas pra fazer a paginação
	 * @return string » Limits da query de consulta
	*/
	public function mPaginacao($pagina=0,$resultadosPagina=10,$totalResultados=0,$link="",$nrPaginas=5){
		if ($totalResultados > $resultadosPagina) $s_msg   = "<p>Páginas:</p>";
		
		$s_resultados = intval($totalResultados / $resultadosPagina);
		$s_resultados = (($totalResultados / $resultadosPagina) == $s_resultados) ? $s_resultados = $s_resultados - 1 : $s_resultados;

		$inicio = intval($s_resultados > $nrPaginas ? ($pagina >= ($nrPaginas / 2) ? (($pagina + ($nrPaginas / 2)) <= $s_resultados ? $pagina - ($nrPaginas / 2) : $s_resultados - $nrPaginas) : 0) : 0);
		$s_final  = intval(($inicio + $nrPaginas) <= $s_resultados ? ($inicio + $nrPaginas) : $s_resultados);

		$s_msg .= $pagina > 0 ? "<a href=\"".$link.($pagina-1)."\">Anterior</a>" : "";
		for($i = $inicio; $i <= $s_final; $i++) $s_msg .= $i == $pagina ? ($totalResultados > $resultadosPagina ? " <span>".($i+1)."</span>" : "") : " <a href=\"".$link.$i."\">".($i+1)."</a>";
		$s_msg .= ($pagina+1) <= $s_resultados ? " <a href=\"".$link.($pagina+1)."\">Próxima</a>" : "";
		$this->v_paginacao = $s_msg;
		
		return ($pagina*$resultadosPagina).",".$resultadosPagina;
	}
	
	/**
 	 * Método que retorna um array com os emails cadastrados no campo.
 	 * Os emails devem ser cadastrados da seguinte forma: eu@zaib.com.br;eduardo@zaib.com.br;cesare@zaib.com.br
 	 * @param string $emails » String no formato email@email.com.br;email2@email.com.br
 	 * @param string $divisor » Contém o separador entre os emails
 	 * @return array » Os emails são colocados em um array.
 	 *
 	 * Eduardo Orige - 24/01/2013
 	 */
	function mSeparaEmails($emails,$divisor=";") {
		
		return explode($divisor, $emails);

	}
	
	/**
	 * Variáveis extras para administração
	*/
	public $v_tabela_comment;		//define o comment da tabela em tempo real
	public $v_title_alternativo;	//titulo alternativo para aparecer em baixo do titulo do sistema
	public $v_sql_where_manage;		//sql alternativo de where para manage do sistema
	public $v_sql_order_manage;		//sql alternativo de order para manage do sistema
	public $v_campo_title_before;	//define um title antes de determinado campo (array)
	public $v_campo_comment;		//define o comment de determinado campo em tempo real (array)
	public $v_campo_padrao;			//define um campo com valor padrão (array)
	public $v_campo_exemplo;		//define um exemplo para determinado campo (array)
	public $v_html_extra_p;			//define um html extra ao p em determinado campo que são os itens de cadastro do sistema (array)
	public $v_html_extra_campo;		//define um html extra a um determinado campo que são os itens de cadastro do sistema (array)
	public $v_html_extra_admin;		//adiciona um html extra ao lado dos botões no link de admin (principal)
	public $v_html_extra_insert;	//adiciona um html extra ao lado dos botões no link de cadastro
	public $v_html_extra_manage;	//adiciona um html extra ao lado dos botões no link de manage
	public $v_html_extra_update;	//adiciona um html extra ao lado dos botões no link de edição
	public $v_redireciona_cadastro;	//redireciona o cadastro para outro link (array) campo e link
	public $v_redireciona_alteracao;//redireciona a alteração para outro link seguido das variáveis atuais
	public $v_tabela_backup;		//seta uma tabela de backup para guardar dados em segurança
	
	/**
	 * Método para iniciar a administração da tabela base
	*/
	function mAdmin() {
		include "../class/automates/admin/inc.automates-admin.php";
	}
}
?>