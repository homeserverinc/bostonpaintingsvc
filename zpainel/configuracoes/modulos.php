<?
session_start("zpainel");
if (!$_SESSION['sessionZpainelLogin']) exit();
if ($_SESSION['sessionZpainelLogin'] != "zaib") exit();

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

$cAutomates = new cAutomates("zpainel_modulos");

if ($_GET['action'] == "update") {
	$_SESSION['linkVoltarModulos'] = "modulos.php?action=update&codigo=".$_GET['codigo']."&palavra=".$_GET['palavra']."&pagina=".$_GET['pagina']."";
	$_SESSION['codigoModulo'] = $_GET['codigo'];
} else {
	$_SESSION['linkVoltarModulos'] = null;
	$_SESSION['codigoModulo'] = null;
}

$cAutomates->v_html_extra_admin = "<input type=\"button\" value=\"Voltar\" onclick=\"window.location='index.php';\">";
$cAutomates->v_html_extra_update = "<input type=\"button\" value=\"Itens\" onclick=\"window.location='modulos-itens.php?';\">";
$cAutomates->mAdmin();
?>