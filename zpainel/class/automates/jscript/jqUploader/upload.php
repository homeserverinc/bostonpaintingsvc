<?
$caminhoFotos = "../../../".$_GET['caminho'];

if ($_FILES['Filedata']['name']) {
	$extensao = explode(".",$_FILES['Filedata']['name']);
	$extensao = strtolower($extensao[count($extensao)-1]);
	
	$nmArquivo = $_GET['nmArquivo'].".".$extensao;
	
	if (move_uploaded_file ($_FILES['Filedata']['tmp_name'], $caminhoFotos.$nmArquivo)) {
		return $nmArquivo;
	}
} else {
	if ($_FILES['Filedata']['error']) {
		return $_FILES['Filedata']['error'];
	}
}
?>