<?
session_start("zpainel");

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

//autentica��o de seguran�a
if (!$_SESSION['sessionZpainelLogin']) exit("Sem conex�o com o servidor. Favor fazer login novamente!");

$cAutomates = new cAutomates("cenarios");
$cAutomates->v_html_extra_campo["ds_cenario"] = "style=\"width: 520px; height: 300px;\"";

$cAutomates->mAdmin();
?>
