<?
session_start("zpainel");

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

//autentica��o de seguran�a
if (!$_SESSION['sessionZpainelLogin']) exit();
if ($_SESSION['sessionZpainelLogin'] != "zaib") {
	$verificaUrl = explode("/",$_SERVER['REQUEST_URI']);
	$verificaUrl = $verificaUrl[count($verificaUrl)-2];
	$cAutomates = new cAutomates("zpainel_links");
	$dadosLink = $cAutomates->mRetornaDados("","link_link_zpainel LIKE '%".$verificaUrl."/%'");
	$codigoLink = $dadosLink[0]["cd_link_zpainel"];
	$infUsuario = new cAutomates("zpainel_usuarios");
	$infUsuario = $infUsuario->mRetornaDados("","login_usuario_zpainel = '".$_SESSION['sessionZpainelLogin']."' AND situacao_usuario_zpainel = 'Ativo'");
	$linksMenuUsuario = explode(",",$infUsuario[0]["cd_link_zpainel"]);
	for($i=0;$i<count($linksMenuUsuario);$i++) {
		if ($linksMenuUsuario[$i] == $codigoLink) $sessionLiberado = true;
	}
	if ($sessionLiberado != true) exit();
}

//verifica se o usu�rio n�o � o da zaib
if ($_POST['login_usuario_zpainel'] == "zaib") exit("N�o � poss�vel cadastrar um usu�rio com este login.");
$cAutomates = new cAutomates("zpainel_usuarios");
$cAutomates->mAdmin();
?>