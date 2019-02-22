<?
//autenticação de segurança
if (!$_SESSION['sessionZpainelLogin']) exit("Sem conexão com o servidor. Favor fazer login novamente!");
if ($_SESSION['sessionZpainelLogin'] != "zaib") {
	$verificaUrl = explode("/",$_SERVER['REQUEST_URI']);
	$verificaUrl = $verificaUrl[count($verificaUrl)-1];
	$verificaUrl = explode("?",$verificaUrl);
	$verificaUrl = $verificaUrl[0];
	
	$cAutomates = new cAutomates("zpainel_modulos_itens");
	$verificaModulosItens = $cAutomates->mRetornaDados("","link_item_modulo_zpainel LIKE '%".$verificaUrl."%' GROUP BY zpainel_modulos_itens.cd_modulo_zpainel");
	$cd_modulo_atual = $verificaModulosItens[0]["cd_modulo_zpainel"];
	
	$cAutomates = new cAutomates("zpainel_modulos");
	$verificaModulos = $cAutomates->mRetornaDados("","link_modulo_zpainel LIKE '%".$verificaUrl."%'");
	$cd_modulo_atual2 = $verificaModulos[0]["cd_modulo_zpainel"];
	
	$infUsuarioSession = new cAutomates("zpainel_usuarios");
	$infUsuarioSession = $infUsuarioSession->mRetornaDados("","login_usuario_zpainel = '".$_SESSION['sessionZpainelLogin']."' AND situacao_usuario_zpainel = 'Ativo'");
	$modulosLiberadosUsuario = explode(",",$infUsuarioSession[0]["cd_modulo_zpainel"]);
	
	for($i=0;$i<count($modulosLiberadosUsuario);$i++) {
		if (($modulosLiberadosUsuario[$i] == $cd_modulo_atual) || ($modulosLiberadosUsuario[$i] == $cd_modulo_atual2))  $sessionLiberado = true;
	}
	if ($sessionLiberado != true) exit("Acesso negado!");
}
?>