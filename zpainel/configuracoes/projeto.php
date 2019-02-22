<?
session_start("zpainel");
if (!$_SESSION['sessionZpainelLogin']) exit();
if ($_SESSION['sessionZpainelLogin'] != "zaib") exit();

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

$cAutomates = new cAutomates("zpainel_config");
$cAutomates->v_html_extra_admin = "<input type=\"button\" value=\"Voltar\" onclick=\"window.location='index.php';\">";
$cAutomates->mAdmin();
?>