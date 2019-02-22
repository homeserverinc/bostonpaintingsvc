<?php
include "../../../inc.conexao.php";
include "../../../automates/inc.automates.php";
$cAutomates = new cAutomates($_GET['tabela']);

$caminhoFotos = "../../../".$_GET['caminho'];

// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

echo 'Upload result:<br>'; // At least one symbol should be sent to response!!!

$target_encoding = "ISO-8859-1";
echo '<pre>';
if(count($_FILES) > 0) {
	$arrfile = pos($_FILES);
	
	$alfabeto = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$extensao = explode(".",$arrfile['name']);
	$extensao = strtolower($extensao[count($extensao)-1]);
	$nmArquivo = $_GET['tabela'].date("_Y_m_d__H_i_s_")."c".$_GET['codigo']."_r".str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].".".$extensao;
	
	move_uploaded_file($arrfile['tmp_name'], $caminhoFotos.$nmArquivo);
	
	$dados = array();
	$dados["".$_GET['campoRelacao'].""] = $_GET['codigo'];
	$dados["".$_GET['campoArquivo'].""] = "'".$nmArquivo."'";
	$cAutomates->mAtualizaDados($dados);
}
else
	echo 'No files sent. Script is OK!'; //Say to Flash that script exists and can receive files

echo 'Here is some more debugging info:';
print_r($_FILES);

echo "</pre>";
?>