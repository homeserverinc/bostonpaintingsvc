/*******************************************************************
	@Zaib Tecnologia - www.zaib.com.br (48) 3527 0104
********************************************************************/
function mValidar() {
	if (document.getElementById("zpainel_login").value == "") {
		alert("Por favor, preencha o seu nome de usuário!");
		document.getElementById("zpainel_login").focus();
		return false;
	} else {
		if (document.getElementById("zpainel_pass").value == "") {
			alert("Por favor, preencha a sua senha!");
			document.getElementById("zpainel_pass").focus();
			return false;
		} else {
			document.getElementById("zpainel_login").readOnly = true;
			document.getElementById("zpainel_pass").readOnly = true;
			document.getElementById("btSubmit").disabled = true;
			document.getElementById("btSubmit").style.width = "190px";
			document.getElementById("btSubmit").value = "Aguarde carregando...";
			return true;
		}
	}
	
}
/*******************************************************************
	@Zaib Tecnologia - www.zaib.com.br (48) 3527 0104
********************************************************************/