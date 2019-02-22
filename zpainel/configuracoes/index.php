<?
session_start("zpainel");
if (!$_SESSION['sessionZpainelLogin']) exit();
if ($_SESSION['sessionZpainelLogin'] != "zaib") exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>Configurações</title>
	<link rel="stylesheet" type="text/css" href="../class/automates/css/screen.css" />
</head>
<body>
<form class="cmxform">
	<fieldset>
		<div class="titulosSistemas">Configurações</div>
		<p>
			<input type="button" value="Projeto" onclick="window.location = 'projeto.php?action=update&codigo=1';" />
			<input type="button" value="Módulos" onclick="window.location = 'modulos.php?';" />
		</p>
	</fieldset>
</form>
</body>
</html>