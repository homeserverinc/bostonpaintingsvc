<?
include "../class/inc.template.php";

$cTpl = new cTemplate("../class/automates/templates/tpl.interfaceManageRelationCodigo.htm");
$this->v_tabelaBase = $_GET['tabela'];
$cTpl->mSet("tabela",$_GET['tabela']);
$cTpl->mSet("campos",$_GET['campos']);
$cTpl->mSet("id",$_GET['id']);
$cTpl->mSet("campoAjax",$_GET['campoAjax']);
$cTpl->mShow("inicio");

//campos para gerenciar
$camposReal = array();
$campos = explode(",",$_GET['campos']);
for($i=0;$i<count($campos);$i++) {
	array_push($camposReal,$campos[$i]);
}

//busca
$detalhesBusca = array();
$cTpl->mSet("palavra",trim($_GET['palavra']));
for($i=0;$i<count($camposReal);$i++) {
	$detalhesCampo = $this->mRetornaCampos($camposReal[$i]);
	$valorPadrao = $detalhesCampo[0]["field"]." LIKE '%".trim(strtoupper($_GET['palavra']))."%'";
	
	//date
	if ($detalhesCampo[0]["type"] == "date") { $valorPadrao = "DATE_FORMAT(".$detalhesCampo[0]["field"].", '%d/%m/%Y') LIKE '%".trim($_GET['palavra'])."%'"; }
	//datetime
	if ($detalhesCampo[0]["type"] == "datetime") { $valorPadrao = "DATE_FORMAT(".$detalhesCampo[0]["field"].", '%d/%m/%Y %H:%i:%s') LIKE '%".trim($_GET['palavra'])."%'"; }
	//double
	if (substr($detalhesCampo[0]["type"],0,6) == "double") { $valorPadrao = $detalhesCampo[0]["field"]." LIKE '%".$this->mTrataMoeda(trim($_GET['palavra']))."%'"; }
	//relation
	if ($detalhesCampo[0]["relationTable"]) $valorPadrao = $detalhesCampo[0]["relationCampo"]." LIKE '%".trim(strtoupper($_GET['palavra']))."%'";
	//file
	if ($detalhesCampo[0]["file"]) $valorPadrao = "";
	
	if ($valorPadrao) array_push($detalhesBusca,$valorPadrao);
}
$detalhesBusca = "(".implode(" OR ",$detalhesBusca).")";

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
$cTpl->mSet("titulos",$titulos);

//Dados da tabela
$dadosReal = "";
if ($temFile == 0) $resultadosPorPagina = 7;
else $resultadosPorPagina = 4;
$dados = $this->mRetornaDados("",$detalhesBusca,"",$this->mPaginacao($_GET['pagina'],$resultadosPorPagina,$totalResultados,"?action=consulta-relation-codigo&tabela=".$_GET['tabela']."&campos=".$_GET['campos']."&id=".$_GET['id']."&campoAjax=".$_GET['campoAjax']."&palavra=".urlencode(trim($_GET['palavra']))."&pagina="));
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
		$dadosReal.= "\t\t\t<td title=\"Selecionar\" ".$align1." onclick=\"javascript:mSetaValorCampo('".$_GET['id']."','".$dados[$i][$_GET['id']]."');void(0);\">".$align2."<a href=\"javascript:mSetaValorCampo('".$_GET['id']."','".$dados[$i][$_GET['id']]."');void(0);\">".$valorPadrao."</a></td>\n";
	}
	$dadosReal.= "\t\t</tr>\n";
}
$cTpl->mSet("dados",$dadosReal);

//paginação
$cTpl->mSet("paginacao",$this->v_paginacao);
$cTpl->mSet("palavraExcluir",urlencode(trim($_GET['palavra'])));
$cTpl->mSet("paginaExcluir",$_GET['pagina']);
$cTpl->mShow("fim");
?>