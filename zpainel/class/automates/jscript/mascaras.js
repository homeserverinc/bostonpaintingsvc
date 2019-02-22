function mMascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){ 
    var sep = 0; 
    var key = ""; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = "0123456789"; 
    var aux = aux2 = ""; 
    e=e||window.event;
	var whichCode=e.charCode||e.keyCode||e.which;
     
    if (whichCode == 13) return true;
    if (whichCode == 8) return true;
    if (whichCode == 0) return true;
    if (whichCode == 9) return true;
    
    key = String.fromCharCode(whichCode);
    if (strCheck.indexOf(key) == -1) return false;
    len = objTextBox.value.length; 
    
    for(i = 0; i < len; i++) 
        if ((objTextBox.value.charAt(i) != "0") && (objTextBox.value.charAt(i) != SeparadorDecimal)) break; 
	aux = ""; 
    for(; i < len; i++) 
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) objTextBox.value = ""; 
    if (len == 1) objTextBox.value = "0"+ SeparadorDecimal + "0" + aux; 
    if (len == 2) objTextBox.value = "0"+ SeparadorDecimal + aux; 
   
    if (len > 2) { 
        aux2 = ""; 
        for (j = 0, i = len - 3; i >= 0; i--) { 
            if (j == 3) { 
                aux2 += SeparadorMilesimo; 
                j = 0; 
            } 
            aux2 += aux.charAt(i); 
            j++; 
        } 
        objTextBox.value = ""; 
        len2 = aux2.length; 
       
        for (i = len2 - 1; i >= 0; i--) 
        objTextBox.value += aux2.charAt(i); 
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len); 
    } 
    return false;
}

function mCampoNumerico(Campo, e){ 
 	var key = ''; 
    var len = 0;  
    var strCheck = '0123456789'; 
    var aux = Campo; 
    e=e||window.event;
	var whichCode=e.charCode||e.keyCode||e.which;
    
    if (whichCode == 13 || whichCode == 8 || whichCode == 0 || whichCode == 9) return true; 
    key = String.fromCharCode(whichCode); 
    if (strCheck.indexOf(key) == -1) return false; 
    return aux;
}
// Marca ou Descamrca todos os checkbox: onclick="checkAllCheckbox(this.check, 'selecionados[]');"
function mCheckAllCheckbox(check, nameInput){
	var els = document.getElementsByTagName("input");
	
	for(var i = 0; i < els.length; i++){
		element = els[i];
	
		if((element.type == "checkbox") && (element.name == nameInput)){
			 if(check == true) element.checked = true;
			 else element.checked = false;
		}
	}
}
// verficica se checck foi marcado, e pergunta se deseja mesmo excluir, nameInput: nome do input, msg1: pergunta, msg2: nada foi selecionado
function mConfirmaExcluir(nameInput, msg1, msg2){
	
	if(msg1 == undefined) msg1 = "Tem certeza que deseja excluir o(s) cadastro(s) selecionado(s)?";
	if(msg2 == undefined) msg2 = "Nenhum item foi selecionado!";
	
	var els = document.getElementsByTagName("input");
	var checar = 0;
	
	for(var i = 0; i < els.length; i++){
		element = els[i];
		
		if((element.type == "checkbox") && (element.name == nameInput) && (element.checked == true)){
			++checar;
		}
	}
	
	if(checar > 0) return confirm(msg1);
	else { alert(msg2); return false; }
}
//função para atualizar informações do relation codigo via ajax
function mGeraRespostaRelationCodigo(valor,id,tabela,campo,span) {
	ajaxLink(span,"?action=resposta-relation-codigo&valor="+valor+"&id="+id+"&tabela="+tabela+"&campo="+campo+"","<span style='color: red;'>Aguarde carregando...</span>");
}