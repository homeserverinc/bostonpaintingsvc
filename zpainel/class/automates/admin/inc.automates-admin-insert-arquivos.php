<?
include "../class/inc.template.php";
	
$cTpl = new cTemplate("../class/automates/templates/tpl.interfaceInsertArquivos.htm");
$cTpl->mSet("palavraRedir",urlencode($_GET['palavra']));
$cTpl->mSet("paginaRedir",$_GET['pagina']);
$cTpl->mSet("codigoRedir",$_GET['codigo']);

$infTabela = $this->mRetornaInfTabela();
$cTpl->mSet("title",$infTabela["title"]);
$cTpl->mSet("relationTitle",$infTabela["relationTitle"]);
$cTpl->mSet("tabela",$infTabela["relation"]);
$cTpl->mSet("caminho",$this->v_caminho_imagem);
$campos = $this->mRetornaCampos();
$cTpl->mSet("campoRelacao",$campos[0]["field"]);

//campos
$this->v_tabelaBase = $infTabela["relation"];
$campos = $this->mRetornaCampos();
for($i=1;$i<count($campos);$i++) {
	//file
	if ($campos[$i]["file"]) {
		$separaExtensoesFile = explode(",",$campos[$i]["file"]);
		$separaExtensoesFile = implode("\; *.",$separaExtensoesFile);
		$realExtensoesFile = "*.".$separaExtensoesFile;
		$cTpl->mSet("realExtensoesFile",$realExtensoesFile);
		$cTpl->mSet("campoArquivo",$campos[$i]["field"]);
		break;
	}
}

$cTpl->mShow();
?>