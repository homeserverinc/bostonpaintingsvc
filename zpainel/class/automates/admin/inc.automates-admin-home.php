<?
include "../class/inc.template.php";
				
$cTpl = new cTemplate("../class/automates/templates/tpl.interfaceAdmin.htm");
$infTabela = $this->mRetornaInfTabela();
$cTpl->mSet("title",$infTabela["title"]);

//verifica se tem titulo alternativo
if ($this->v_title_alternativo) $cTpl->mSet("title_alternativo","<div class=\"titulosSistemas\" style=\"font-weight: normal;\">".$this->v_title_alternativo."</div>");

$cTpl->mShow("inicio");

if ($infTabela["insert"] == "1") $cTpl->mShow("insert");
if ($infTabela["manage"] == "1") $cTpl->mShow("manage");

//verifica se tem html extra pra colocar
if ($this->v_html_extra_admin) $cTpl->mSet("html_extra_admin",$this->v_html_extra_admin);

$cTpl->mShow("fim");
?>