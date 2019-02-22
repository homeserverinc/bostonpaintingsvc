<?
include "../inc.erros.php";
include "../inc.gd.php";

//Obrigatório passar o caminho, image e width

$gd = new cGd;
$gd->mGeraMiniatura($_GET['caminho'], $_GET['image'], $_GET['width'], $_GET['tipo']=1, $_GET['quality']=1, $_GET['borda']=0, $_GET['fundo']="255,255,255", $_GET['cor_borda']="0,0,0");
?>