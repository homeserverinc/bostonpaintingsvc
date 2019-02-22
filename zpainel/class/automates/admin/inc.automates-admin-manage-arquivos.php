<?
if ($_GET['dados']) {
	$infTabela = $this->mRetornaInfTabela();
	$this->v_tabelaBase = $infTabela["relation"];
	
	include "../class/inc.gd.php";
	$cGd = new cGd;
	
	foreach($_POST['selecionados'] as $conteudo) {
		//verifica se tem campos file para excluir
		$dadosTabela = $this->mRetornaDados($conteudo);
		foreach($dadosTabela[0] as $key => $value) {
			$infCampo = $this->mRetornaCampos($key);
			if ($infCampo[0]["file"]) $cGd->mRemoveGd($this->v_caminho_imagem,$value);
		}
		$this->mDeletaDados($conteudo);
	}
	header("location: ?action=manageArquivos&codigo=".$_GET['codigo']."&palavra=".urlencode($_GET['palavra'])."&pagina=".$_GET['pagina']."&palavra2=".urlencode($_GET['palavra2'])."&pagina2=".$_GET['pagina2']."&ok=yes");
} else {
	include "../class/inc.template.php";
		
	$cTpl = new cTemplate("../class/automates/templates/tpl.interfaceManageArquivos.htm");
	$cTpl->mSet("palavraRedir",urlencode($_GET['palavra']));
	$cTpl->mSet("palavraRedirParaBusca",$_GET['palavra']);
	$cTpl->mSet("paginaRedir",$_GET['pagina']);
	$cTpl->mSet("codigoRedir",$_GET['codigo']);
	
	$infTabela = $this->mRetornaInfTabela();
	$cTpl->mSet("title",$infTabela["title"]);
	$cTpl->mSet("relationTitle",$infTabela["relationTitle"]);
	$campos = $this->mRetornaCampos();
	$campoRelacao = $campos[0]["field"];
	$this->v_tabelaBase = $infTabela["relation"];
	$infTabela = $this->mRetornaInfTabela();
	$cTpl->mShow("inicio");
	
	//mensagem de sucesso
	if ($_GET['ok'] == "yes") $cTpl->mShow("mensagemSucesso");
	
	//campos para gerenciar
	$camposReal = array();
	$campos = $this->mRetornaCampos();
	for($i=0;$i<count($campos);$i++) {
		if ($campos[$i]["list"] == 1) array_push($camposReal,$campos[$i]["field"]);
	}
	
	//busca
	$detalhesBusca = array();
	$cTpl->mSet("palavra2",trim($_GET['palavra2']));
	for($i=0;$i<count($camposReal);$i++) {
		$detalhesCampo = $this->mRetornaCampos($camposReal[$i]);
		$valorPadrao = $detalhesCampo[0]["field"]." LIKE '%".trim(strtoupper($_GET['palavra2']))."%'";
		
		//date
		if ($detalhesCampo[0]["type"] == "date") { $valorPadrao = "DATE_FORMAT(".$detalhesCampo[0]["field"].", '%d/%m/%Y') LIKE '%".trim($_GET['palavra2'])."%'"; }
		//datetime
		if ($detalhesCampo[0]["type"] == "datetime") { $valorPadrao = "DATE_FORMAT(".$detalhesCampo[0]["field"].", '%d/%m/%Y %H:%i:%s') LIKE '%".trim($_GET['palavra2'])."%'"; }
		//double
		if (substr($detalhesCampo[0]["type"],0,6) == "double") { $valorPadrao = $detalhesCampo[0]["field"]." LIKE '%".$this->mTrataMoeda(trim($_GET['palavra2']))."%'"; }
		//relation
		if ($detalhesCampo[0]["relationTable"]) $valorPadrao = $detalhesCampo[0]["relationTable"].".".$detalhesCampo[0]["relationCampo"]." LIKE '%".trim(strtoupper($_GET['palavra2']))."%'";
		//file
		if ($detalhesCampo[0]["file"]) $valorPadrao = "";
		
		if ($valorPadrao) array_push($detalhesBusca,$valorPadrao);
	}
	$detalhesBusca = "(".implode(" OR ",$detalhesBusca);
	$detalhesBusca = $detalhesBusca.") AND (".$campoRelacao." = '".$_GET['codigo']."')";
	
	//total resultados
	$totalResultados = $this->mRetornaDados("",$detalhesBusca);
	$totalResultados = count($totalResultados);
	$cTpl->mSet("totalResultados",$totalResultados);
	$cTpl->mShow("continua");
	
	//titulos
	$titulos = "";
	$temFile = 0;
	for($i=0;$i<count($camposReal);$i++) {
		$detalhesCampo = $this->mRetornaCampos($camposReal[$i]);
		//alinhamento
		if ($detalhesCampo[0]["align"] == "center") $titulos.= "\t\t\t<td align=\"center\"><strong>".$detalhesCampo[0]["titleCampo"]."</strong></td>\n";
		else $titulos.= "\t\t\t<td>&nbsp;&nbsp;<strong>".$detalhesCampo[0]["titleCampo"]."</strong></td>\n";
		
		//verifica se tem imagem
		if ($detalhesCampo[0]["file"]) $temFile = 1;
	}
	//verifica se pode excluir
	if ($infTabela["delete"] == 1) {
		$titulos.= "\t\t\t<td class=\"tbExcluir\" align=\"center\"><input type=\"checkbox\" title=\"selecionar todos\" onClick=\"mCheckAllCheckbox(this.checked, 'selecionados[]');\" /></td>";
		$cTpl->mSet("botaoExcluir","<input id=\"bt3\" type=\"submit\" value=\"Excluir selecionado(s)\" />");
	}
	$cTpl->mSet("titulos",$titulos);
	
	//Dados da tabela
	$dadosReal = "";
	if ($temFile == 0) $resultadosPorPagina = 10;
	else $resultadosPorPagina = 4;
	$dados = $this->mRetornaDados("",$detalhesBusca,"",$this->mPaginacao($_GET['pagina2'],$resultadosPorPagina,$totalResultados,"?action=manageArquivos&codigo=".$_GET['codigo']."&palavra=".urlencode($_GET['palavra'])."&pagina=".$_GET['pagina']."&palavra2=".urlencode(trim($_GET['palavra2']))."&pagina2="));
	for($i=0;$i<count($dados);$i++) {
		if (!$fundo) { $corFundo = "#FFF9DD"; $fundo = true; } else { $corFundo = "#FFF8CA"; $fundo = false; }
		$dadosReal.= "\t\t<tr bgcolor=\"".$corFundo."\" height=\"26\">\n";
		
		for($j=0;$j<count($camposReal);$j++) {
			$detalhesCampo = $this->mRetornaCampos($camposReal[$j]);
			$valorPadrao = $dados[$i][$detalhesCampo[0]["field"]];
			
			//align
			$align1 = "";
			$align2 = "&nbsp;&nbsp;";
			if ($detalhesCampo[0]["align"] == "center") { $align1 = "align=\"center\""; $align2 = ""; }
			//date
			if ($detalhesCampo[0]["type"] == "date") $valorPadrao = $this->mConverteData($valorPadrao,1);
			//datetime
			if ($detalhesCampo[0]["type"] == "datetime") $valorPadrao = $this->mConverteData($valorPadrao,1,1);
			//double
			if (substr($detalhesCampo[0]["type"],0,6) == "double") $valorPadrao = "R$".number_format($valorPadrao,2,",",".");
			//text
			if ($detalhesCampo[0]["type"] == "text") $valorPadrao = $this->mBreveDescricao($valorPadrao,50);
			//relation
			if ($detalhesCampo[0]["relationTable"]) $valorPadrao = $dados[$i][$detalhesCampo[0]["relationCampo"]];
			//file
			if ($detalhesCampo[0]["file"]) {
				$extensaoArquivo = explode(".",$valorPadrao);
				$extensaoArquivo = array_pop($extensaoArquivo);
				if (($extensaoArquivo == "jpg") || ($extensaoArquivo == "jpeg") || ($extensaoArquivo == "gif") || ($extensaoArquivo == "png")) {
					if (file_exists($this->v_caminho_imagem.$valorPadrao)) $valorPadrao = "<img src=\"".$this->v_caminho_imagem.$valorPadrao."\" width=\"60\" alt=\"\" style=\"border: 1px solid #666666; margin-top: 3px; margin-bottom: 3px;\" />";
					else $valorPadrao = "<img src=\"".$this->v_caminho_imagem."max_".$valorPadrao."\" width=\"60\" alt=\"\" style=\"border: 1px solid #666666; margin-top: 3px; margin-bottom: 3px;\" />";
				} else {
					$valorPadrao = "<img src=\"../class/automates/images/arquivo.jpg\" width=\"60\" alt=\"\" style=\"border: 1px solid #666666; margin-top: 3px; margin-bottom: 3px;\" />";
				}
			}
			
			//dados real
			$dadosReal.= "\t\t\t<td title=\"Editar\" ".$align1." onclick=\"window.location = '?action=updateArquivos&codigo=".$_GET['codigo']."&palavra=".urlencode($_GET['palavra'])."&pagina=".$_GET['pagina']."&codigo2=".$dados[$i][$campos[0]["field"]]."&palavra2=".urlencode(trim($_GET['palavra2']))."&pagina2=".$_GET['pagina2']."';\">".$align2."<a href=\"?action=updateArquivos&codigo=".$_GET['codigo']."&palavra=".urlencode($_GET['palavra'])."&pagina=".$_GET['pagina']."&codigo2=".$dados[$i][$campos[0]["field"]]."&palavra2=".urlencode(trim($_GET['palavra2']))."&pagina2=".$_GET['pagina2']."\">".$valorPadrao."</a></td>\n";
		}
		//verifica se pode excluir
		if ($infTabela["delete"] == 1) $dadosReal.= "\t\t\t<td title=\"Marque esta caixa para excluir\" class=\"tbExcluir\" align=\"center\"><input type=\"checkbox\" id=\"selecionados[]\" name=\"selecionados[]\" value=\"".$dados[$i][$campos[0]["field"]]."\" /></td>\n";

		$dadosReal.= "\t\t</tr>\n";
	}
	$cTpl->mSet("dados",$dadosReal);
	
	//paginação
	$cTpl->mSet("paginacao",$this->v_paginacao);
	$cTpl->mSet("palavraExcluir",urlencode(trim($_GET['palavra2'])));
	$cTpl->mSet("paginaExcluir",$_GET['pagina2']);
	$cTpl->mShow("fim");
}
?>