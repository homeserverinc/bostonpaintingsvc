<?php
/**
 * Utilidades diversas (ferramentas)
 *
 * <i>Criada em: 04/10/2006
 * �ltima Altera��o: 05/10/2006</i>
 *
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright � 2006, Zaib Tecnologia
 * @package Class
 */
 class cTools {

	/**
	 * Pega cota��o de dolar e euro atraves do link http://www.debit.com.br/resumogratuito.php?info=novo_dolar
	 * $param int $tipo � 1 = dolar_comercial_compra, 2 = dolar_comercial_venda, 3 = dolar_paralelo_compra, 4 = dolar_paralelo_venda, 5 = euro_dolar_compra, 6 = euro_dolar_venda, 7 = euro_real_compra, 8 = euro_real_venda
	 * @return string � Valor da cota��o solicitada
	 */
	function mMoedaCotacao($tipo=1) {
		//abre o arquivo
		if (!$fp = fopen("http://www.debit.com.br/resumogratuito.php?info=novo_dolar" ,"r" )) { print("Erro ao abrir a p�gina de cota��o"); exit();	}

		//L� o arquivo
		while(!feof($fp)) {
		   $conteudo .= fgets($fp,1024);
		}
		fclose($fp);

		//trata o resultado do arquivo
		$separa = explode("<font color=black size=1 face=Verdana>",$conteudo);

		//retorna s� o tipo desejado
		switch($tipo) {
			case "1": return $this->mMoedaCotacaoBuscaValores($separa[3]); break;
			case "2": return $this->mMoedaCotacaoBuscaValores($separa[4]); break;
			case "3": return $this->mMoedaCotacaoBuscaValores($separa[5]); break;
			case "4": return $this->mMoedaCotacaoBuscaValores($separa[6]); break;
			case "5": return $this->mMoedaCotacaoBuscaValores($separa[7]); break;
			case "6": return $this->mMoedaCotacaoBuscaValores($separa[8]); break;
			case "7": return $this->mMoedaCotacaoBuscaValores($separa[9]); break;
			case "8": return $this->mMoedaCotacaoBuscaValores($separa[10]); break;
		}
	}

	/**
	 * Fun��o dependente da mMoedaCotacao();
	 */
	function mMoedaCotacaoBuscaValores($parte) {
		$moeda = explode("<",$parte);

		return trim($moeda[0]);
	}

	/**
 	 * Trata descri��o colocando ... (tres pontos) ou n�o
 	 * @param string $p_descricao � Descricao
	 * @param int $p_qtd � Quantidade permitida de caracteres
	 * @param int $p_tags � 0 = retira as tags html, 1 = deixa com tags html
 	 * @return string � Tratada p/ aparecer no site
 	 */
	function mBreveDescricao($p_descricao, $p_qtd, $p_tags=0) {
        if (!$p_tags) {
            $p_descricao = strip_tags($p_descricao);
        }

		if (strlen($p_descricao) > $p_qtd) {
			$p_descricao = substr($p_descricao,0,($p_qtd-3))."...";
		}

		return $p_descricao;
	}

	/**
 	 * Trata campos de valores de moeda, retirando os "."
 	 * @param string $p_valor � Valor no formato 10.000,00
 	 * @return string � Formatada p/ insercao no banco
 	 */
	function mTrataMoeda($p_valor) {
		$p_valor = str_replace(".", "", $p_valor);
		$p_valor = str_replace(",", ".", $p_valor);

		return $p_valor;
	}

	function checkEmail($eMailAddress) {
	    if (preg_match ("/^[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*\\.[A-Za-z0-9]{2,4}$/", $eMailAddress)) {
	    	return true;
	    } else {
	    return false;
	    }
	}

	/**
 	 * Trata campos de valores de moeda, retirando os "."
 	 * @param string $email � email no formato foo@bar.com
 	 * @return boolean � true se email for valido 
 	 */
	function mValidaEmail($email) {
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";

		$pattern = $conta.$domino.$extensao;

		if (ereg($pattern, $email))
			return true;
		else
			return false;
	}

 	/**
 	 * Valida Campos em branco via PHP
 	 * @param array $array � Array com os campos que deseja verificar
 	 */
	function mVerificaCamposBranco($p_array) {
		foreach($p_array as $s_conteudo) {
			if (trim($s_conteudo) == "") $this->mErro("Erro! H� campos obrigat�rios em branco no formul�rio.");
		}
	}

	/**
 	 * Converte o array para UTF8_DECODE (AJAX)
 	 * @param array $texto � Array com os dados que deseja transformar
 	 * @return array � Array com os dados transformados
 	 */
 	function mTrataUtf8Form ($p_texto) {

		 // Pega a chave do array e joga no array c/indice
		$s_array = (array_keys ($p_texto));

		 // Percorre novo array de indices + chave
		foreach($s_array as $s_var){

 			 // Pega a chave do array e decoda
			$p_texto[$s_var] = utf8_decode($p_texto[$s_var]);
		}
		return $p_texto;
	}

 	/**
 	 * Trata espa�os em branco do array
 	 * @param array $p_texto � Array com os campos que deseja limpar os espa�os
 	 * @return array � Array sem os espa�amentos
 	 */
 	function mTrataTxtForm ($p_texto) {

		// Pega a chave do array e joga no array c/indice
		$s_array = (array_keys ($p_texto));

		// Percorre novo array de indices + chave
		foreach($s_array as $s_var){

			// Pega a chave do array e decoda
			$p_texto[$s_var] = trim($p_texto[$s_var]);
		}
		return $p_texto;
	}

 	/**
 	 * Le o diretorio e retorna conteudo em forma de array
 	 * @param string $p_dir � Diret�rio
 	 * @param array $p_tipoArquivo � Tipo de arquivo que deseja listar
 	 * @return array � Conteudo do diret�rio
 	 */
 	function mLerDiretorio($p_dir, $p_tipoArquivo){
 	 	// Verifica se diretorio existe
 	 	if(!is_dir($p_dir)){
			$this->mErro("Diret�rio inexistente!");
 	 	}else{
			$s_conteudo_dir = array();

			 // Abre o diretorio para leitura
 	 		$s_abrir = opendir($p_dir);

 			 // Percorre diretorio aberto
			while ($s_valor = readdir($s_abrir)) {

				 // Percorre os tipos especificados
				for($i = 0; $i < count($p_tipoArquivo); $i++){

					 // N�o pegar os diret�rios ateriores
					if (($s_valor != ".") && ($s_valor != "..")){

						 // Verifica a extens�o do arquivo de acordo com o tipo especificado como parametro
						if(strcasecmp(substr($s_valor, -strlen($p_tipoArquivo[$i])), $p_tipoArquivo[$i]) == 0){
							array_push($s_conteudo_dir, $s_valor);
						}
					}
				}
			}
			 // Fecha diretorio que tinha sido aberto
			closedir($s_abrir);

			return $s_conteudo_dir;
 	 	}
 	 }

	/**
	 * Retira acentos, caracteres especiais e coloca o caracter que
	 * for passado como par�metro e retorna min�sculos
	 *
	 * @param string $p_texto
	 * @param string $p_caractere � "_" ou "" (ocupa espacamento)
	 * @return string � Texto tratado
	 */
	function mTrataCaracter($p_texto, $p_caracter="_"){
		return ereg_replace("[^a-zA-Z0-9 _.]", "", strtr($p_texto, "��������������������������'�`. ", "aaaaeeiooouucAAAAEEIOOOUUC   _ $p_caracter"));
	}

	/**
	 * Converte formto de datas
	 * @param string $p_data � Data que deseja converter
	 * @param int $p_tipo � 1 - Brasileira e 0 - Americana
	 * @param int $p_hora� 1 - Tem Horas e 0 - N�o tem
	 * @return date � Data convertida
	 */
	function mConverteData($p_data, $p_tipo, $p_hora=0){
	 	if($p_hora == 0) {
	 		$p_tipo == 0 ? $p_data = substr($p_data,6,4)."-".substr($p_data,3,2)."-".substr($p_data,0,2) : $p_data = substr($p_data,8,2) . "/" . substr($p_data,5,2) . "/" . substr($p_data,0,4);;
	 	}else {
	 		$p_tipo == 0 ? $p_data = substr($p_data,6,4)."-".substr($p_data,3,2)."-".substr($p_data,0,2)." ".substr($p_data, 10) : $p_data = substr($p_data,8,2) . "/" . substr($p_data,5,2) . "/" . substr($p_data,0,4)." ".substr($p_data, 10);
	 	}
	 	return $p_data;
	}

	/**
	 * Retorna o dia da semana em protugu�s,
	 * caso de erro retorna mensagem de erro
	 * @param string $p_dia_da_semana � date("l");
	 * @return string � Dia da semana em portugues
	 */
	function mDiaSemanaBr($p_dia_da_semana){
		switch($p_dia_da_semana){
			case "Sunday": $s_dia = "Domingo"; break;
			case "Monday": $s_dia = "Segunda-Feira"; break;
			case "Tuesday": $s_dia = "Ter�a-Feira"; break;
			case "Wednesday": $s_dia = "Quarta-Feira"; break;
			case "Thursday": $s_dia = "Quinta-Feira"; break;
			case "Friday": $s_dia = "Sexta-Feira"; break;
			case "Saturday": $s_dia = "S�bado"; break;
		}

		// Caso o dia seje passado errado retorna msg de erro
		if(empty($s_dia)) exit("Erro - N�o existe esse dia da semana!");
		else return $s_dia;
	}

	/**
	 * Retorna o dia da semana desejado por extenso.
	 *
	 * @param string $data � Data deve ser passada "2013-12-01"
	 * @param string $padrao � Se padrao for americano, data � "2013-12-01" $padrao=1 
	 * sen�o � padr�o brasileiro (01-12-2013)
	 */
	function mDiaSemanaExtenso($data, $padrao=1) { 
	    $data = implode("-", array_reverse(explode("/",$data))); 
	    
	    if ($padrao == 1) {
	    	// Padr�o americano
		    $ano =  substr("$data", 0, 4); 
		    $mes =  substr("$data", 5, -3); 
		    $dia =  substr("$data", 8, 9); 
	    } else {
	    	// Padr�o brasileiro
		    $ano = substr("$data", 6, 9);
			$mes = substr("$data", 3,-5);
		    $dia = substr("$data", 0,2);	
	    }

	    $diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) ); 

	    switch($diasemana) { 
	        case"0": $diasemana = "Domingo";  break; 
	        case"1": $diasemana = "Segunda-Feira";  break; 
	        case"2": $diasemana = "Ter�a-Feira";  break; 
	        case"3": $diasemana = "Quarta-Feira";  break; 
	        case"4": $diasemana = "Quinta-Feira";  break; 
	        case"5": $diasemana = "Sexta-Feira";  break; 
	        case"6": $diasemana = "S�bado";  break; 
	    } 
	    return "$diasemana"; 
	} 


	/**
	 * Retorna  o m�s por extenso em portugues, em caso de erro retorna
	 * mensagem de erro
	 * @param string $p_mes_por_extenso � date("F") ou date("m") ou date("M"), ou em ingles
	 * @return string � M�s por extenso em portugu�s
	 */
	function mMesExtensoBr($p_mes_por_extenso){
		if(($p_mes_por_extenso == "January") || ($p_mes_por_extenso == "01") || ($p_mes_por_extenso == "Jan")){ $p_mes = "Janeiro";}
		elseif(($p_mes_por_extenso == "February") || ($p_mes_por_extenso == "02") || ($p_mes_por_extenso == "Feb")){ $p_mes = "Fevereiro"; }
		elseif(($p_mes_por_extenso == "March") || ($p_mes_por_extenso == "03") || ($p_mes_por_extenso == "Mar")){ $p_mes = "Mar�o"; }
		elseif(($p_mes_por_extenso == "April" ) || ($p_mes_por_extenso == "04") || ($p_mes_por_extenso == "Apr")){ $p_mes = "Abril"; }
		elseif(($p_mes_por_extenso == "May") || ($p_mes_por_extenso == "05") || ($p_mes_por_extenso == "May")){ $p_mes = "Maio"; }
		elseif(($p_mes_por_extenso == "June") || ($p_mes_por_extenso == "06") || ($p_mes_por_extenso == "Jun")){ $p_mes = "Junho"; }
		elseif(($p_mes_por_extenso == "July") || ($p_mes_por_extenso == "07") || ($p_mes_por_extenso == "Jul")){ $p_mes = "Julho"; }
		elseif(($p_mes_por_extenso == "August") || ($p_mes_por_extenso == "08") || ($p_mes_por_extenso == "Aug")){ $p_mes = "Agosto"; }
		elseif(($p_mes_por_extenso == "September") || ($p_mes_por_extenso == "09")  || ($p_mes_por_extenso == "Sep")){ $p_mes = "Setembro"; }
		elseif(($p_mes_por_extenso == "October") || ($p_mes_por_extenso == "10") || ($p_mes_por_extenso == "Oct")){ $p_mes = "Outubro"; }
		elseif(($p_mes_por_extenso == "November") || ($p_mes_por_extenso == "11") || ($p_mes_por_extenso == "Nov")){ $p_mes = "Novembro"; }
		elseif(($p_mes_por_extenso == "December") || ($p_mes_por_extenso == "12") || ($p_mes_por_extenso == "Dec")){ $p_mes = "Dezembro"; }

		if(empty($p_mes)) exit("Erro - N�o existe esse M�s!");
		else return $p_mes;
	}

	/**
	 * Exibe menssagem de erro, e usa um javascript para
	 * retornar a p�gina anterior atrav�s de um link voltar e interrompe
	 * o fluxo do script
	 * @param string $p_msg � Mensagem que quer que apareca para o usu�rio
	 */
	function mErro($p_msg) {
		exit("<p style='font: 11px Tahoma, Verdana;'><strong>Retorno:</strong> $p_msg<br /><br /><a href=\"javascript:history.back();\" style='color: blue;'>Voltar</a></p>");
	}

	/**
	 * Envia para um pagina desejada com uma mensagem desejada
	 * com o tempo de 1,5 segundos
	 *
	 * @param string $p_msg � Mensagem que deseja exibir enquanto aguarda
	 * @param string $p_destino � Link de destino da p�gina (para onde queres ir)
	 */
	function mSubmit($p_msg, $p_destino) {
		print("<p style='font: 11px Tahoma, Verdana'><strong>$p_msg</strong><br /><br />Retornando...</p><script type=\"text/javascript\">setTimeout(\"window.location.href='$p_destino'\",1500);</script>");
	}

	/**
	 * Retorna janela de erro em javaScript
	 * @param string $p_msg � Mensagem de alerta que deseja criar
	 */
	function mAlert($p_msg) {
		print("<script type=\"text/javascript\">alert(\"$p_msg\");</script>");
	}

	function mBack() {
		print("<script type=\"text/javascript\">history.back();</script>");
	}

	/**
	 * Escreve um Js na tela
	 */
	function mScript ($p_msg) {
		print("<script type=\"text/javascript\">$p_msg</script>");
	}

	/**
	 * Verifica se as sessoes as quais o zpainel starta, tem que ter session_start()
	 */
	function mVerificaSessionLogin () {
		if ((!session_is_registered("ses_cd_usuario")) || (!session_is_registered("ses_nm_usuario")) || (!session_is_registered("ses_email_usuario")) || (!session_is_registered("ses_login_usuario"))) header("location: ./");
	}

	/**
	 * Remove as tags passadas por paramentros em um array
	 * @param string $p_texto � Texto que desejo tirar as tags
	 * @param array $p_tags � Array com as tags - 0 caso queira o padr�o
	 * @return string � Palavra sem as tags
	 */
	function mRemoveTags ($p_texto, $p_tags=0) {

		// Caso tags = 0, ele seta os arrays de tags padr�o
		if($p_tags == 0) $p_tags = array("h1", "h2", "h3", "h4", "font", "div", "img", "span", "table", "tr", "td");

		// Tamanho da string
		$s_tam_string = strlen($p_texto);

		// String formata (sem tags)
		$string_formatada = $p_texto;

		// Gurada tamaho do array
		$s_tam_array = count($p_tags);

		for($i = 0; $i < $s_tam_string; $i++){

			// Caracter atual
			$s_char_abre = substr($string_formatada, $i, 1);

			if($s_char_abre == "<"){

				for($t = 0; $t < $s_tam_array; $t++){
					$s_tam_cont      = strlen($p_tags[$t]) + 1; // Tamanho do Conteudo (font = 4, div = 3)
					$s_tag_atual     = trim(substr($string_formatada, $i+1, $s_tam_cont));
					$s_pos_tag_atual = $i;

					if($s_tag_atual == $p_tags[$t]){
						$s_tag_atual = "<".$s_tag_atual;

						$s_tam_resto_percorrer = $s_tam_string - $i;  // N�mero de caracteres a percorrer
						$s_resto_percorrer = substr($string_formatada, $i + $s_tam_cont, strlen($string_formatada) - $i);

						// Percorre proximos caracteres at� achar o >
						for($j = 0; $j < $s_tam_resto_percorrer; $j++){
							$s_char_fecha = substr($s_resto_percorrer, $j, 1);

							$s_tag_atual .= $s_char_fecha;

							// Busca pelo fim da tag para tirar tamb�m seus paramentros
							if($s_char_fecha == ">"){
								$s_cont_tag_atual = substr($string_formatada, $s_pos_tag_atual, $j + 1);
								$string_formatada = str_replace($p_tags[$t]."/>", " ", $string_formatada);
								$string_formatada = str_replace($s_tag_atual, " ", $string_formatada);
								$string_formatada = str_replace("</".$p_tags[$t].">", " ", $string_formatada);
							}
						}
					}
				}
			}
		}
		return $string_formatada;
	}

	/**
	 * Efetua calculo de data
	 * @param int $p_qtd_parcela � Qtd de parcelas
	 * @param int $p_parcela_inicial � Qtd tempo p/ expirar a parcela inicial
	 * @param int $p_intervalo � Tamanho do intervalo de datas
	 * @param string $p_operacao � Subtrair (-) ou Somar (+)
	 */
	function mCalculoData ($p_qtd_parcela, $p_parcela_inicial=10, $p_intervalo=30, $p_tp_data=0, $p_operacao="+") {

		$s_dia_da_compra = date('d');
		$s_mes_da_compra = date('m');
		$s_ano_da_compra = date('Y');

		$s_periodo 		   = 0;
		$s_data 		   = array();

		for ($i = 0 ; $i < $p_qtd_parcela; $i++) {
		    if($i == 0) {
		    	$s_periodo = 0;

		    	if($p_operacao == "+") $ts = mktime(0, 0, 0, $s_mes_da_compra, $s_dia_da_compra + $p_parcela_inicial, $s_ano_da_compra);
		    	else if($p_operacao == "-") $ts = mktime(0, 0, 0, $s_mes_da_compra, $s_dia_da_compra - $p_parcela_inicial, $s_ano_da_compra);
		    }else {
		    	$s_periodo = $s_periodo + $p_intervalo;

		    	if($p_operacao == "+") $ts = mktime(0, 0, 0, $s_mes_da_compra, $s_dia_da_compra + $s_periodo, $s_ano_da_compra);
		    	else if($p_operacao == "-") $ts = mktime(0, 0, 0, $s_mes_da_compra, $s_dia_da_compra - $s_periodo, $s_ano_da_compra);
		    }
		    if($p_tp_data == 0) array_push($s_data, date('Y/m/d', $ts));
		    else array_push($s_data, date('d/m/Y', $ts));
		}
		return $s_data ;
	}

	/**
	 * Calcula diferen�a entre datas
	 * @param date $p_data1 � Dt Final
	 * @param date $p_data2 � Dt Inicial
	 * @return int � difereca de dias, se positivo = ultrapassou X dias, se negativo = falta x dias
	 */
	function mEntreDatas($p_data1, $p_data2) {

		if ($p_data1 == "") $p_data1 = Date("Y-m-d");
		if ($p_data2 == "") $p_data2 = Date("Y-m-d");

		if (strpos($p_data1, "/") >= 1) $p_data1 = str_replace("/", "-", $p_data1);
		if (strpos($p_data2, "/") >= 1) $p_data2 = str_replace("/", "-", $p_data2);

		list($y1, $m1, $d1, $x1) = Explode("-", $p_data1);
		list($y2, $m2, $d2, $x2) = Explode("-", $p_data2 );

		$p_data1 = MkTime(0, 0, 0, $m1, $d1, $y1);
		$p_data2 = MkTime(0, 0, 0, $m2, $d2, $y2);

		$s_dias = ($p_data1-$p_data2)/60/60/24;
		$s_dias = floor($s_dias);

		return $s_dias;
	}

	/**
	 * Sauda��o Bom dia, Boa Tarde e Boa Noite
	 *
	 * @return string � Saudacao de acordo com o horario atual
	 */
	function mSaudacaoHorario () {
		$s_hora = date('H');

		if($s_hora > 19 && $s_hora < 06) return "Boa noite";
		else if($s_hora > 06 && $s_hora < 12) return "Bom dia";
		else return "Boa tarde";
	}

	/**
 	 * Seta fundo da tabela
	 * @param int � variavel increment (++$fundo)
	 * @return string � Cor
 	 */
	function mFundoTabela($p_fundo) {
		if ($p_fundo%2 == 0) return "#F6F6F6";
		else return "#E7E7E7";
	}

	/**
	 * Lista os estados do brasil UF
	 *
	 * @param int � Tipo 1 (padrao) UF e 0 = Extenso
	 */
	function mListaEstadoUF($p_tipo=1) {

		if($p_tipo == 1) {
			$uf_estado = array("AC", "AL", "AP", "AM","BA", "CE", "DF",
						   "GO", "ES", "MA", "MT", "MS", "MG", "PA",
						   "PB", "PR", "PE", "PI", "RJ", "RN", "RS",
						   "RO", "RR", "SP", "SC", "SE", "TO");
		}else {
			$uf_estado = array("Acre", "Alagoas", "Amap�", "Amazonas", "Bahia",
						   "Cear�", "Distrito Federal", "Goi�s", "Esp�rito Santo",
						   "Maranh�o", "Mato Grosso", "Mato Grosso do Sul",
						   "Minas Gerais", "Par�", "Paraiba", "Paran�", "Pernambuco",
						   "Piau�", "Rio de Janeiro", "Rio Grande do Norte",
						   "Rio Grande do Sul", "Rond�nia", "Ror�ima", "S�o Paulo",
						   "Santa Catarina", "Sergipe", "Tocantins");
		}
		sort($uf_estado);

		return $uf_estado;
	}

	/**
	 * Retorna o nome do estado por extenso
	 *
	 * @param string � UF
	 */
	function mRetornaEstadoExtenso ($p_uf) {
		switch($p_uf) {
			case "AC": return "Acre"; break;
			case "AL": return "Alagoas"; break;
			case "AP": return "Amap�"; break;
			case "AM": return "Amazonas"; break;
			case "BA": return "Bahia"; break;
			case "CE": return "Cear�"; break;
			case "DF": return "Distrito Federal"; break;
			case "GO": return "Goi�s"; break;
			case "ES": return "Esp�rito Santo"; break;
			case "MA": return "Maranh�o"; break;
			case "MT": return "Mato Grosso"; break;
			case "MS": return "Mato Grosso do Sul"; break;
			case "MG": return "Minas Gerais"; break;
			case "PA": return "Par�"; break;
			case "PB": return "Paraiba"; break;
			case "PR": return "Paran�"; break;
			case "PE": return "Pernambuco"; break;
			case "PI": return "Piau�"; break;
			case "RJ": return "Rio de Janeiro"; break;
			case "RN": return "Rio Grande do Norte"; break;
			case "RS": return "Rio Grande do Sul"; break;
			case "RO": return "Rond�nia"; break;
			case "RR": return "Ror�ima"; break;
			case "SP": return "S�o Paulo"; break;
			case "SC": return "Santa Catarina"; break;
			case "SE": return "Sergipe"; break;
			case "TO": return "Tocantins"; break;
		}
	}
}
?>