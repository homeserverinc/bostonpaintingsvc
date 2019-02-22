<?
if ($_GET['dados']) {
	//autenticacao
	$s_obj = simplexml_load_file("http://www2.zaib.com.br/zpainel/autentica_zaib.xml");
	$s_login_usuario  = (string) $s_obj->logar->login;
	$s_senha_usuario  = (string) $s_obj->logar->password;
	
	if(($s_login_usuario == md5($_POST['user'])) && ($s_senha_usuario == md5($_POST['pass']))) {
		//tabela base
		if ($_GET['tabelaBase']) $this->v_tabelaBase = $_GET['tabelaBase'];
		
		//altera tabela
		$this->cCon->mQuery("ALTER TABLE ".$this->v_tabelaBase." COMMENT = '".$_POST['tabela']."'");
		
		//altera campos
		foreach($_POST as $key => $value) {
			if (($key != "user") && ($key != "pass") && ($key != "tabela")) {
				$infCampo = $this->mRetornaCampos($key);
				//null
				if ($infCampo[0]["null"] == "YES") $nullConfig = "NULL";
				else $nullConfig = "NOT NULL";
				
				//default
				if ($infCampo[0]["default"] != "") $defaultConfig = "DEFAULT '".$infCampo[0]["default"]."'";
				else $defaultConfig = "";
				
				$this->cCon->mQuery("ALTER TABLE ".$this->v_tabelaBase." CHANGE ".$key." ".$key." ".$infCampo[0]["type"]." ".$nullConfig." ".$defaultConfig." ".$infCampo[0]["extra"]." COMMENT '".$value."'");
			}
		}
		
		header("location: ?action=config&tabelaBase=".$_GET['tabelaBase']."&msg=yes");
	} else {
		print("Autenticação incorreta!");
	}
} else {
	include "../class/inc.template.php";
	$cTpl = new cTemplate("../class/automates/templates/tpl.interfaceConfig.htm");

	//tabela base
	if ($_GET['tabelaBase']) {
		$this->v_tabelaBase = $_GET['tabelaBase'];
		$cTpl->mSet("tabelaBase",$_GET['tabelaBase']);
	} else $cTpl->mSet("tabelaBase",$this->v_tabelaBase);
	
	$infTabela = $this->mRetornaInfTabela();
	$cTpl->mSet("tabelaComment",$infTabela["comment"]);
	
	//msg
	if ($_GET['msg'] == "yes") $cTpl->mSet("msg","<br /><span style=\"color: #FF0000;\">Alterações efetuadas com sucesso!</span>");
	$cTpl->mShow("inicio");
	
	$campos = $this->mRetornaCampos();
	for($i=0;$i<count($campos);$i++) {
		$cTpl->mSet("field",$campos[$i]["field"]);
		$cTpl->mSet("comment",$campos[$i]["comment"]);
		$cTpl->mShow("while");
	}
	
	$cTpl->mShow("fim");
}
?>