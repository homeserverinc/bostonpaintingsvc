var ifila = 0
var fila = new Array();

try{
    xmlhttp = new XMLHttpRequest();
    try {
        if (xmlhttp.overrideMimeType) xmlhttp.overrideMimeType('text/xml');
    } catch (e1) { }
}catch(e2){
    try{ xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e3){
        try{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(e4){ xmlhttp = false; }
    }
}
if (!xmlhttp){ alert("AJAX erro. Seu browser não da suporte a XMLHttpRequest."); }

function ajaxLink(id_target,url, htmlCarregar, carregar){
	if(carregar == 0) {
	} else ajaxMensagemCarregando(id_target,htmlCarregar);
	//url = antiCacheRand(url);
    fila[fila.length]=[id_target,url,"GET",null];
    if(fila.length==1) ajaxRun();
    return;
}
function ajaxForm(id_target,id_form, htmlCarregar, carregar){
    var url = document.getElementById(id_form).action;
    var metodoEnvio = document.getElementById(id_form).method.toUpperCase();
    var elementos_form = BuscaElementosForm(id_form);

	if(carregar == 0){
	} else ajaxMensagemCarregando(id_target,htmlCarregar);

    fila[fila.length]=[id_target,url,metodoEnvio,elementos_form];
    if(fila.length==1) ajaxRun();
    return;
}
function ajaxRun(){
    var url = fila[ifila][1];

    var metodoEnvio;
    if (fila[ifila][3]==null) metodoEnvio = "GET";
    else{
        metodoEnvio = fila[ifila][2];
        if (metodoEnvio=="" || metodoEnvio==null) metodoEnvio = "POST";
        if (metodoEnvio=="GET") url = url + "?" + fila[ifila][3];
    }
    xmlhttp.open(metodoEnvio,url, true);
    xmlhttp.onreadystatechange=ajaxXMLHTTP_StateChange;
    if (metodoEnvio=="POST"){
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp.send(fila[ifila][3]);
    }else xmlhttp.send(null);
    return;
}
function ajaxXMLHTTP_StateChange() {
    if (xmlhttp.readyState==1) ajaxXMLHTTP_StateChange_Carregando(fila[ifila][0]);
    else{
        if (xmlhttp.readyState==4) ajaxXMLHTTP_StateChange_Completo(xmlhttp, fila[ifila][0]);
    }
}
function ajaxXMLHTTP_StateChange_Carregando(id){
    return;
}
function ajaxXMLHTTP_StateChange_Completo(xmlhttp, id){
    var retorno;

    if (xmlhttp.status == 200 || xmlhttp.status==0) retorno=unescape(xmlhttp.responseText.replace(/\+/g," "));
    else retorno=ajaxPaginaErro(xmlhttp);
    document.getElementById(id).innerHTML=retorno;
    ExtraiScript(retorno);

    ifila++;
    if(ifila<fila.length) setTimeout("ajaxRun()",20);
    else{
        fila = null;
        fila = new Array();
        ifila = 0;
    }
    return;
}
function ajaxPaginaErro(xmlhttp){
    var retorno;
    switch (xmlhttp.status) {
        case 404:
            return "Página não encontrada!!!"; break;
        case 500:
            return "Erro interno do servidor!!!"; break;
        default:
            return "Erro desconhecido!!!<br>" + xmlhttp.status + " - " + xmlhttp.statusText.replace(/\+/g," ");
    }
}
function ajaxMensagemCarregando(id,html) {
	if (html == undefined) html = "Aguarde carregando...";
	document.getElementById(id).innerHTML = html;
}
function ExtraiScript(texto){
    var ini, pos_src, fim, codigo, texto_pesquisa;
    var objScript = null;

    texto_pesquisa = texto.toLowerCase()
    ini = texto_pesquisa.indexOf('<script', 0)
    while (ini!=-1){
        var objScript = document.createElement("script");

        pos_src = texto_pesquisa.indexOf(' src', ini)
        ini = texto_pesquisa.indexOf('>', ini) + 1;

        if (pos_src < ini && pos_src >=0){
            ini = pos_src + 4;
            fim = texto_pesquisa.indexOf('.', ini)+4;
            codigo = texto.substring(ini,fim);
            codigo = codigo.replace("=","").replace(" ","").replace("\"","").replace("\"","").replace("\'","").replace("\'","").replace(">","");
            objScript.src = codigo;
        }else{
            fim = texto_pesquisa.indexOf('</script>', ini);
            codigo = texto.substring(ini,fim);
            objScript.text = codigo;
        }

        document.body.appendChild(objScript);
        ini = texto.indexOf('<script', fim);

        objScript = null;
    }
}
function BuscaElementosForm(idForm) {
    var elementosFormulario = document.getElementById(idForm).elements;
    var qtdElementos = elementosFormulario.length;
    var queryString = "";
    var elemento;

    this.ConcatenaElemento = function(nome,valor) {
                                if (queryString.length>0) queryString += "&";
                                queryString += encodeURIComponent(nome) + "=" + encodeURIComponent(valor);
                             };

    for (var i=0; i<qtdElementos; i++) {
        elemento = elementosFormulario[i];
        if (!elemento.disabled) {
            switch(elemento.type) {
                case 'text': case 'password': case 'hidden': case 'textarea':
                    this.ConcatenaElemento(elemento.name,elemento.value); break;
                case 'select-one':
                    if (elemento.selectedIndex>=0) { this.ConcatenaElemento(elemento.name,elemento.options[elemento.selectedIndex].value); } break;
                case 'select-multiple':
                    for (var j=0; j<elemento.options.length; j++) {
                        if (elemento.options[j].selected) { this.ConcatenaElemento(elemento.name,elemento.options[j].value); } 
                    }
                    break;
                case 'checkbox': case 'radio':
                    if (elemento.checked) { this.ConcatenaElemento(elemento.name,elemento.value); } break;
            }
        }
    }
    return queryString;
}
function antiCacheRand(aurl){
        var dt = new Date();
        if(aurl.indexOf("?")>=0) return aurl + "&" + encodeURI(Math.random() + "_" + dt.getTime());
        else return aurl + "?" + encodeURI(Math.random() + "_" + dt.getTime());
}