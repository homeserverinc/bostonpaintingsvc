<?
if ($_GET['valor'] != "") {
	$this->v_tabelaBase = $_GET['tabela'];
	$dados = $this->mRetornaDados("","".$_GET['id']." = '".$_GET['valor']."'");
	if ($dados[0][$_GET['campo']]) {
		$tela = $dados[0][$_GET['campo']];
	} else {
		$tela = "C�digo n�o encontrado!";
	}
}

//trata ajax
$tela = urlencode($tela);
print($tela);
?>