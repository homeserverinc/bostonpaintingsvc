<?
$infTabela = $this->mRetornaInfTabela();
$this->v_tabelaBase = $infTabela["relation"];
	
include "../class/inc.gd.php";
$cGd = new cGd;
$cGd->mRemoveGd($this->v_caminho_imagem,$_GET['nmArquivo']);

$campos = $this->mRetornaCampos($_GET['nmCampo']);
$separaExtensoesFile = explode(",",$campos[0]["file"]);
$separaExtensoesFile = implode("; *.",$separaExtensoesFile);
$realExtensoesFile = "*.".$separaExtensoesFile;
print("<input id=\"".$_GET['nmCampo']."\" name=\"".$_GET['nmCampo']."\" size=\"40\" type=\"file\" /><script type=\"text/javascript\">$('#file".$campos[0]["field"]."').jqUploader({ allowedExt:'".$realExtensoesFile."', allowedExtDescr: '".$realExtensoesFile."', nmArquivo: '".$_GET['nmId']."', nmCaminho: '".$this->v_caminho_imagem."' });</script>");
?>