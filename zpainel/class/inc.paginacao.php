<?php
/**
 * Classe de paginação de resultados
 * 
 * <i>Criada em: 05/08/2006
 * Última Alteração: 05/10/2006</i>
 * 
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright © 2006, Zaib Tecnologia
 * @package Class
 */
class cPaginacao {
	/**
	 * Objeto de Conexão
	 */
	private $v_con;
	
	/**
	 * Número de resultados por página
	 */
	private $v_n_de_resultados;
	
	/**
	 * Número de página que quer mostrar, 0 1 2 3, -1 não mostra nada
	 */
	private $v_n_de_paginas;
	
	/**
	 * Pagina atual recebe pela variavel GET, vem da URL
	 */
	private $v_pagina;
	
	/**
	 * Número de linhas retornadas pela consulta
	 */
	public $v_linhas;
	
	/**
	 * Conteudo que vai no botão próximo
	 */
	private $v_msg_proximo;
	
	/**
	 * Conteudo que vai no botão anterior
	 */
	private $v_msg_nterior;
	
	private $v_class_anterior;
	
	private $v_class_proximo;
	
	/**
	 * Construtor
	 * @param object $p_con -> Objeto de Conexão com o banco de dados
	 */
	public function __construct($p_con){
		$this->v_con = $p_con;
	}
	
	/**
	 * Efetua a query
	 * @param string $p_query -> SQL desejada
	 * @param int    $p_n_de_resultados -> Número de Resultados por página
	 * @param int    $p_n_de_paginas -> Número de páginas, -1 não aparece nº de páginas
	 * @param int    $p_pagina -> Página atual vinda do navegador pelo mehotd GET
	 * @param strgin $p_msg_anterior » String da msg anterior
	 * @param strgin $p_msg_proximo » String da msg proximo
	 * @return $consulta -> Id da Consulta
	 */
	public function mQuery($p_query, $p_n_de_resultados, $p_n_de_paginas, $p_pagina, $p_msg_anterior="Anterior", $p_msg_proximo="Próximo", $p_class_anterior="", $p_class_proximo=""){
		$this->v_msg_proximo     = $p_msg_proximo;
		$this->v_msg_nterior     = $p_msg_anterior;
		$this->v_class_anterior  = $p_class_anterior;
		$this->v_class_proximo   = $p_class_proximo;
		$this->v_n_de_resultados = $p_n_de_resultados;
		$this->v_n_de_paginas    = $p_n_de_paginas;
		$this->v_pagina 		 = $p_pagina;
		$this->v_linhas 		 = $this->v_con->mRows($this->v_con->mQuery($p_query));
		
		$s_pg = $this->v_n_de_resultados * $p_pagina;
		$p_query    .= $this->v_linhas > $this->v_n_de_resultados ? " LIMIT $s_pg, $this->v_n_de_resultados" : "";
		$p_consulta  = $this->v_con->mQuery($p_query);
		
		return $p_consulta;
	}

	public function mPaginas($p_QUERY_STRING){
		$s_msg   = "";
		$s_total = sizeof(explode("pagina=", $p_QUERY_STRING));
		
		$s_resultados = intval($this->v_linhas / $this->v_n_de_resultados);
		$s_resultados = (($this->v_linhas / $this->v_n_de_resultados) == $s_resultados) ? $s_resultados = $s_resultados - 1 : $s_resultados;

		$inicio = intval($s_resultados > $this->v_n_de_paginas ? ($this->v_pagina >= ($this->v_n_de_paginas / 2) ? (($this->v_pagina + ($this->v_n_de_paginas / 2)) <= $s_resultados ? $this->v_pagina - ($this->v_n_de_paginas / 2) : $s_resultados - $this->v_n_de_paginas) : 0) : 0);
		$s_final  = intval(($inicio + $this->v_n_de_paginas) <= $s_resultados ? ($inicio + $this->v_n_de_paginas) : $s_resultados);

		$s_msg .= $this->v_pagina > 0 ? "<a href=".$_SERVER["PHP_SELF"]."?".str_replace("pagina=$this->v_pagina","pagina=".($this->v_pagina - 1), $p_QUERY_STRING)." class=\"".$this->v_class_anterior."\">".$this->v_msg_nterior."</a>" : "";
		for($i = $inicio; $i <= $s_final; $i++) $s_msg .= $i == $this->v_pagina ? ($this->v_linhas > $this->v_n_de_resultados ? "<span>&nbsp;".($i+1)."&nbsp;</span>" : "") : "&nbsp;<a href=".$_SERVER["PHP_SELF"]."?".($s_total > 1 ? str_replace("pagina=$this->v_pagina","pagina=$i", $p_QUERY_STRING) : $p_QUERY_STRING."&pagina=$i").">".($i+1)."</a>&nbsp;";
		$s_msg .= ($this->v_pagina + 1) <= $s_resultados ? "&nbsp;<a href=".$_SERVER["PHP_SELF"]."?".($s_total > 1 ? str_replace("pagina=$this->v_pagina","pagina=".($this->v_pagina + 1), $p_QUERY_STRING) : $p_QUERY_STRING."&pagina=".($this->v_pagina + 1))." class=\"".$this->v_class_proximo."\">".$this->v_msg_proximo."</a>" : "";
		
		return $s_msg;
	}
	
	public function mPaginasSite($p_REQUEST_URI){
		$s_msg   = "";
		
		$separaURL = explode("/",$p_REQUEST_URI);
		if ($separaURL[count($separaURL)-3] == "pagina") $s_total = 1;
		else $s_total = 0;
		
		$s_resultados = intval($this->v_linhas / $this->v_n_de_resultados);
		$s_resultados = (($this->v_linhas / $this->v_n_de_resultados) == $s_resultados) ? $s_resultados = $s_resultados - 1 : $s_resultados;

		$inicio = intval($s_resultados > $this->v_n_de_paginas ? ($this->v_pagina >= ($this->v_n_de_paginas / 2) ? (($this->v_pagina + ($this->v_n_de_paginas / 2)) <= $s_resultados ? $this->v_pagina - ($this->v_n_de_paginas / 2) : $s_resultados - $this->v_n_de_paginas) : 0) : 0);
		$s_final  = intval(($inicio + $this->v_n_de_paginas) <= $s_resultados ? ($inicio + $this->v_n_de_paginas) : $s_resultados);

		$s_msg .= $this->v_pagina > 0 ? "<a href=".str_replace("/pagina/$this->v_pagina/","/pagina/".($this->v_pagina - 1)."/", $p_REQUEST_URI)." class=\"".$this->v_class_anterior."\">".$this->v_msg_nterior."</a>" : "";
		for($i = $inicio; $i <= $s_final; $i++) $s_msg .= $i == $this->v_pagina ? ($this->v_linhas > $this->v_n_de_resultados ? "<span>".($i+1)."</span>" : "") : "&nbsp;<a href=".($s_total == 1 ? str_replace("/pagina/$this->v_pagina/","/pagina/$i/", $p_REQUEST_URI) : $p_REQUEST_URI."pagina/$i/").">".($i+1)."</a>&nbsp;";
		$s_msg .= ($this->v_pagina + 1) <= $s_resultados ? "&nbsp;<a href=".($s_total == 1 ? str_replace("/pagina/$this->v_pagina/","/pagina/".($this->v_pagina + 1)."", $p_REQUEST_URI) : $p_REQUEST_URI."pagina/".($this->v_pagina + 1))."/ class=\"".$this->v_class_proximo."\">".$this->v_msg_proximo."</a>" : "";
		
		return $s_msg;
	}
	
	public function mPaginasSiteAjax($p_REQUEST_URI,$id,$html=""){
		$s_msg   = "";
		
		$separaURL = explode("/",$p_REQUEST_URI);
		if ($separaURL[count($separaURL)-3] == "pagina") $s_total = 1;
		else $s_total = 0;
		
		$s_resultados = intval($this->v_linhas / $this->v_n_de_resultados);
		$s_resultados = (($this->v_linhas / $this->v_n_de_resultados) == $s_resultados) ? $s_resultados = $s_resultados - 1 : $s_resultados;

		$inicio = intval($s_resultados > $this->v_n_de_paginas ? ($this->v_pagina >= ($this->v_n_de_paginas / 2) ? (($this->v_pagina + ($this->v_n_de_paginas / 2)) <= $s_resultados ? $this->v_pagina - ($this->v_n_de_paginas / 2) : $s_resultados - $this->v_n_de_paginas) : 0) : 0);
		$s_final  = intval(($inicio + $this->v_n_de_paginas) <= $s_resultados ? ($inicio + $this->v_n_de_paginas) : $s_resultados);

		if (!empty($html)) $html = ",'".$html."'";

		$s_msg .= $this->v_pagina > 0 ? "<a href=\"javascript:;\" onclick=\"ajaxLink('$id','".str_replace("/pagina/$this->v_pagina/","/pagina/".($this->v_pagina - 1)."/", $p_REQUEST_URI)."'".$html.");\" class=\"".$this->v_class_anterior."\">".$this->v_msg_nterior."</a>" : "";
		for($i = $inicio; $i <= $s_final; $i++) $s_msg .= $i == $this->v_pagina ? ($this->v_linhas > $this->v_n_de_resultados ? "&nbsp;".($i+1)."&nbsp;" : "") : "&nbsp;<a href=\"javascript:;\" onclick=\"ajaxLink('$id','".($s_total == 1 ? str_replace("/pagina/$this->v_pagina/","/pagina/$i/", $p_REQUEST_URI) : $p_REQUEST_URI."pagina/$i/")."'".$html.");\">".($i+1)."</a>&nbsp;";
		$s_msg .= ($this->v_pagina + 1) <= $s_resultados ? "&nbsp;<a href=\"javascript:;\" onclick=\"ajaxLink('$id','".($s_total == 1 ? str_replace("/pagina/$this->v_pagina/","/pagina/".($this->v_pagina + 1)."", $p_REQUEST_URI) : $p_REQUEST_URI."pagina/".($this->v_pagina + 1))."/'".$html.");\" class=\"".$this->v_class_proximo."\">".utf8_encode($this->v_msg_proximo)."</a>" : "";
		
		return $s_msg;
	}
}
?>
