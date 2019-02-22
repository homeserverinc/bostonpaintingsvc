<?
session_start("zpainel");
if (!$_SESSION['sessionZpainelLogin']) exit();
if ($_SESSION['sessionZpainelLogin'] != "zaib") exit();

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

$cAutomatesMod = new cAutomates("zpainel_modulos");
$dadosModulo = $cAutomatesMod->mRetornaDados($_SESSION['codigoModulo']);

$cAutomates = new cAutomates("zpainel_modulos_itens");
$cAutomates->v_title_alternativo = "Módulo: \"<strong>".$dadosModulo[0]["nm_modulo_zpainel"]."</strong>\"";
$cAutomates->v_html_extra_admin = "<input type=\"button\" value=\"Voltar\" onclick=\"window.location='".$_SESSION['linkVoltarModulos']."';\">";
$cAutomates->v_sql_where_manage = "cd_modulo_zpainel = '".$_SESSION['codigoModulo']."'";
$cAutomates->v_campo_padrao["cd_modulo_zpainel"] = $_SESSION['codigoModulo'];
$cAutomates->mAdmin();
?>