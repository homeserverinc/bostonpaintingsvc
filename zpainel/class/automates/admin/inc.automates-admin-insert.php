<?
if ($_GET['dados']) {
	$dados = $_POST;
	foreach($dados as $key => $value) {
		$basico = 0;
		$infCampo = $this->mRetornaCampos($key);

		//int
		if (substr($infCampo[0]["type"],0,3) == "int") {
			$basico = 1;
			if ($value) $dados[$key] = $value;
			else $dados[$key] = "''";
		}
		//date
		if ($infCampo[0]["type"] == "date") { $basico = 1; $dados[$key] = "'".$this->mConverteData($value,0)."'"; }
		//datetime
		if ($infCampo[0]["type"] == "datetime") { $basico = 1; $dados[$key] = "'".$this->mConverteData($value,0,1)."'"; }
		//double
		if (substr($infCampo[0]["type"],0,6) == "double") { $basico = 1; $dados[$key] = "'".$this->mTrataMoeda($value)."'"; }
		//checkbox
		if ($infCampo[0]["tipoCampo"]) {
			$separaCheckbox = explode(",",$infCampo[0]["tipoCampo"]);
			if ($separaCheckbox[0] == "checkbox") {
				if (count($value) > 0) { $basico = 1; $dados[$key] = "'".implode(",",$value)."'"; }
			}
		}

		//se não tiver nenhum criterio, vira uma string basica
		if ($basico == 0) $dados[$key] = "'".addslashes(str_replace("\"","'",$value))."'";
	}
	$this->mAtualizaDados($dados);

	//VERIFICA SE TEM TABELA DE BACKUP
	if ($this->v_tabela_backup != "") {
		$tabelaBasePadrao = $this->v_tabelaBase;
		$this->v_tabelaBase = $this->v_tabela_backup;
		$this->mAtualizaDados($dados);
		$this->v_tabelaBase = $tabelaBasePadrao;
	}

	if ($this->v_redireciona_cadastro["link"]) {
		$resultadoRedirecionar = $this->mRetornaDados("","",$this->v_redireciona_cadastro["campo"]." DESC",1);
		header("location: ".$this->v_redireciona_cadastro["link"].$resultadoRedirecionar[0][$this->v_redireciona_cadastro["campo"]]);
	} else {
		header("location: ?action=insert&ok=yes");
	}
} else {
	include "../class/inc.template.php";

	$cTpl = new cTemplate("../class/automates/templates/tpl.interfaceInsert.htm");
	$infTabela = $this->mRetornaInfTabela();
	$cTpl->mSet("title",$infTabela["title"]);

	//verifica se tem titulo alternativo
	if ($this->v_title_alternativo) $cTpl->mSet("title_alternativo","<div class=\"titulosSistemas\" style=\"font-weight: normal;\">".$this->v_title_alternativo."</div>");

	//verifica campos do tipo file para adicionar código javascript
	$fileJavascript = "";
	$campos = $this->mRetornaCampos();
	for($i=1;$i<count($campos);$i++) {
		if ($campos[$i]["file"]) {
			$separaExtensoesFile = explode(",",$campos[$i]["file"]);
			$separaExtensoesFile = implode("; *.",$separaExtensoesFile);
			$realExtensoesFile = "*.".$separaExtensoesFile;
			$fileJavascript.= "\t\t\t$('#file".$campos[$i]["field"]."').jqUploader({ allowedExt:'".$realExtensoesFile."', allowedExtDescr: '".$realExtensoesFile."', nmArquivo: '".$campos[$i]["field"]."_".date("Y_m_d__H_i_s")."', nmCaminho: '".$this->v_caminho_imagem."' });\n";
		}
	}
	$cTpl->mSet("fileJavascript",$fileJavascript);
	$cTpl->mShow("inicio");

	//mensagem de sucesso
	if ($_GET['ok'] == "yes") $cTpl->mShow("mensagemSucesso");

	//verifica se tem html extra pra colocar
	if ($this->v_html_extra_insert) $cTpl->mSet("html_extra_insert",$this->v_html_extra_insert);
	$cTpl->mShow("inicio2");

	//campos
	$campos = $this->mRetornaCampos();
	for($i=1;$i<count($campos);$i++) {
		//title campo
		$cTpl->mSet("titleCampo",$campos[$i]["titleCampo"]);
		//nome do campo para id e name
		$cTpl->mSet("field",$campos[$i]["field"]);
		//campo requerido
		if ($campos[$i]["null"] != "YES") {
			$cTpl->mSet("asteriscoReq","<span>*</span>");
			$classReal = "required";
		}
		//size
		$sizeReal = "50";
		if ($campos[$i]["size"]) { $sizePers = 1; $sizeReal = $campos[$i]["size"]; }
		$cTpl->mSet("size",$sizeReal);
		//validation
		if ($campos[$i]["validation"]) $classReal = $classReal." ".$campos[$i]["validation"];
		$cTpl->mSet("class",$classReal);
		//mask
		if ($campos[$i]["mask"]) {
			$cTpl->mSet("mask","<script type=\"text/javascript\">jQuery(function($){ $(\"#".$campos[$i]["field"]."\").mask(\"".$campos[$i]["mask"]."\"); });</script>");
		}
		//padrao
		if ($campos[$i]["padrao"]) $cTpl->mSet("padraoCampo",$campos[$i]["padrao"]);
		if ($this->v_campo_padrao) {
			foreach($this->v_campo_padrao as $keyPadrao => $valuePadrao) {
				if ($keyPadrao == $campos[$i]["field"]) $cTpl->mSet("padraoCampo",$valuePadrao);
			}
		}
		//maxlength
		if ($campos[$i]["maxlength"]) $cTpl->mSet("maxlength",$campos[$i]["maxlength"]);
		//read only
		if ($campos[$i]["readOnly"] == 1) $cTpl->mSet("readOnly","readonly=\"yes\" style=\"color: #666666;\"");
		//exemplo
		if ($campos[$i]["exemplo"]) $cTpl->mSet("exemplo","Ex: ".$campos[$i]["exemplo"]);
		if ($this->v_campo_exemplo) {
			foreach($this->v_campo_exemplo as $keyExemplo => $valueExemplo) {
				if ($keyExemplo == $campos[$i]["field"]) $cTpl->mSet("exemplo",$valueExemplo);
			}
		}
		//p extra
		if ($this->v_html_extra_p) {
			foreach($this->v_html_extra_p as $keyPExtra => $valuePExtra) {
				if ($keyPExtra == $campos[$i]["field"]) $cTpl->mSet("p_extra",$valuePExtra);
			}
		}
		//html extra campo
		if ($this->v_html_extra_campo) {
			foreach($this->v_html_extra_campo as $keyHtmlExtraCampo => $valueHtmlExtraCampo) {
				if ($keyHtmlExtraCampo == $campos[$i]["field"]) $cTpl->mSet("html_extra_campo",$valueHtmlExtraCampo);
			}
		}
		//campo title before
		if ($this->v_campo_title_before) {
			foreach($this->v_campo_title_before as $keyTitleBeforeCampo => $valueTitleBeforeCampo) {
				if ($keyTitleBeforeCampo == $campos[$i]["field"]) {
					$cTpl->mSet("campo_title_before",$valueTitleBeforeCampo);
					$cTpl->mShow("title_before");
				}
			}
		}

		/*----------------------------------------------*/
		//trata tipo dos campos

		//varchar
		if (substr($campos[$i]["type"],0,7) == "varchar") {
			//file
			if ($campos[$i]["file"]) {
				$cTpl->mShow("file");
			} else {
				if ($campos[$i]["tipoCampo"]) {
					//password
					if ($campos[$i]["tipoCampo"] == "password") {
						$cTpl->mShow("password");
					} else {
						//checkbox
						$separaCheckbox = explode(",",$campos[$i]["tipoCampo"]);
						if ($separaCheckbox[0] == "checkbox") {
							$cTpl->mShow("checkbox");
							$tabelaBasePadrao = $this->v_tabelaBase;
							$this->v_tabelaBase = $separaCheckbox[1];
							$dadosRelation = $this->mRetornaDados("","",$separaCheckbox[2]);
							$this->v_tabelaBase = $tabelaBasePadrao;

							$ctrlTableCheckbox = 0;
							for($j=0;$j<count($dadosRelation);$j++) {
								if ($campos[$i]["null"] != "YES") {
									if ($j==0) $cTpl->mSet("validateCheckbox","validate=\"required:true\"");
									else $cTpl->mSet("validateCheckbox","");
								}

								$cTpl->mSet("cd_checkbox",$dadosRelation[$j][$campos[$i]["field"]]);
								$cTpl->mSet("valor_checkbox",$dadosRelation[$j][$separaCheckbox[2]]);
								$cTpl->mSet("idCheckbox",$campos[$i]["field"]."_checkbox_".$j);

								if ($ctrlTableCheckbox == 0) $cTpl->mShow("checkbox_while");

								$cTpl->mShow("checkbox_while2");

								if (($ctrlTableCheckbox == 2) || ($j == (count($dadosRelation)-1)))  $cTpl->mShow("checkbox_while3");

								$ctrlTableCheckbox++;
								if ($ctrlTableCheckbox == 3) $ctrlTableCheckbox = 0;
							}
							$cTpl->mSet("idCheckbox","");
							$cTpl->mShow("checkbox_fim");
						}
					}
				} else {
					//varchar
					if ($campos[$i]["padrao"]) {
						if ($campos[$i]["padrao"] == "IP()") $cTpl->mSet("padraoCampo",$_SERVER['REMOTE_ADDR']);
					}
					if ($campos[$i]["oculto"] == 1) $cTpl->mShow("oculto");
					else $cTpl->mShow("varchar");
				}
			}
		}
		//int
		if (substr($campos[$i]["type"],0,3) == "int") {
			//relation
			if ($campos[$i]["relationTable"]) {
				$cTpl->mShow("relation");

				if ($campos[$i]["relationCodigo"]) {
					$cTpl->mSet("tabelaRelationCodigo",$campos[$i]["relationTable"]);
					if ($campos[$i]["relationCampo2"]) $cTpl->mSet("tabelaRelationCodigoCampo",$campos[$i]["relationCampo2"]);
					else $cTpl->mSet("tabelaRelationCodigoCampo",$campos[$i]["relationCampo"]);
					$cTpl->mSet("tabelaRelationCodigoCamposMostrar",$campos[$i]["relationCodigo"]);
					$cTpl->mShow("relation_codigo");
				} else {
					$tabelaBasePadrao = $this->v_tabelaBase;
					$this->v_tabelaBase = $campos[$i]["relationTable"];
					$dadosRelation = $this->mRetornaDados("","",$campos[$i]["relationCampo"]);
					$this->v_tabelaBase = $tabelaBasePadrao;

					if ($campos[$i]["tipoCampo"] == "radio") {

						$cTpl->mShow("relation_radio_while_inicio");
						$ctrlTableRadio = 0;
						for($j=0;$j<count($dadosRelation);$j++) {
							if ($campos[$i]["null"] != "YES") {
								if ($j==0) $cTpl->mSet("validateRadio","validate=\"required:true\"");
								else $cTpl->mSet("validateRadio","");
							}

							$cTpl->mSet("cd_relation",$dadosRelation[$j][$campos[$i]["field"]]);
							$cTpl->mSet("valor_relation",$dadosRelation[$j][$campos[$i]["relationCampo"]]);
							$cTpl->mSet("idRadio",$campos[$i]["field"]."_radio_".$j);

							if ($ctrlTableRadio == 0) $cTpl->mShow("relation_radio_while");

							$cTpl->mShow("relation_radio_while2");

							if (($ctrlTableRadio == 2) || ($j == (count($dadosRelation)-1)))  $cTpl->mShow("relation_radio_while3");

							$ctrlTableRadio++;
							if ($ctrlTableRadio == 3) $ctrlTableRadio = 0;
						}
						$cTpl->mSet("idRadio","");
						$cTpl->mShow("relation_radio_fim");
					} else {
						$cTpl->mShow("relation_select_inicio");
						for($j=0;$j<count($dadosRelation);$j++) {
							$cTpl->mSet("cd_relation",$dadosRelation[$j][$campos[$i]["field"]]);
							$cTpl->mSet("valor_relation",$dadosRelation[$j][$campos[$i]["relationCampo"]]);
							$cTpl->mShow("relation_select_while");
						}
						$cTpl->mShow("relation_select_fim");
					}
				}
				$cTpl->mShow("relation_fim");
			} else {
				//int
				$classReal = $classReal." number";
				$cTpl->mSet("class",$classReal);
				if ($campos[$i]["oculto"] == 1) $cTpl->mShow("oculto");
				else $cTpl->mShow("int");
			}
		}
		//text
		if ($campos[$i]["type"] == "text") {
            if (!$campos[$i]["tinyhtml"]) $classReal = $classReal." tinyhtml ";
    		$cTpl->mSet("class",$classReal);

			if ($sizePers != 1) $cTpl->mSet("size","40");
			if ($campos[$i]["oculto"] == 1) $cTpl->mShow("oculto");
			else $cTpl->mShow("text");
		}
		//date
		if ($campos[$i]["type"] == "date") {
			// $classReal = $classReal." date";
			$cTpl->mSet("class",$classReal);
			if ($campos[$i]["padrao"]) {
				if ($campos[$i]["padrao"] == "NOW()") $cTpl->mSet("padraoCampo",date("d/m/Y"));
			}
			if ($campos[$i]["oculto"] == 1) $cTpl->mShow("oculto");
			else $cTpl->mShow("date");
		}
		//datetime
		if ($campos[$i]["type"] == "datetime") {
			if ($campos[$i]["padrao"]) {
				if ($campos[$i]["padrao"] == "NOW()") $cTpl->mSet("padraoCampo",date("d/m/Y H:i:s"));
			}
			if ($campos[$i]["oculto"] == 1) $cTpl->mShow("oculto");
			else $cTpl->mShow("datetime");
		}
		//double
		if (substr($campos[$i]["type"],0,6) == "double") {
			if ($campos[$i]["oculto"] == 1) $cTpl->mShow("oculto");
			else $cTpl->mShow("double");
		}
		//enum
		if (substr($campos[$i]["type"],0,4) == "enum") {
			if ($campos[$i]["oculto"] == 1) {
				$cTpl->mShow("oculto");
			} else {
				$cTpl->mShow("enum");
				$valores_enum = str_replace("enum(","",$campos[$i]["type"]);
				$valores_enum = str_replace(")","",$valores_enum);
				$valores_enum = str_replace("'","",$valores_enum);
				$valores_enum = explode(",",$valores_enum);

				if ($campos[$i]["tipoCampo"] == "radio") {

					$cTpl->mShow("enum_radio_while_inicio");
					$ctrlTableRadio = 0;
					for($j=0;$j<count($valores_enum);$j++) {
						if ($campos[$i]["null"] != "YES") {
							if ($j==0) $cTpl->mSet("validateRadio","validate=\"required:true\"");
							else $cTpl->mSet("validateRadio","");
						}

						$cTpl->mSet("valor_enum",$valores_enum[$j]);
						$cTpl->mSet("idRadio",$campos[$i]["field"]."_radio_".$j);

						if ($ctrlTableRadio == 0) $cTpl->mShow("enum_radio_while");

						$cTpl->mShow("enum_radio_while2");

						if (($ctrlTableRadio == 2) || ($j == (count($valores_enum)-1)))  $cTpl->mShow("enum_radio_while3");

						$ctrlTableRadio++;
						if ($ctrlTableRadio == 3) $ctrlTableRadio = 0;
					}
					$cTpl->mSet("idRadio","");
					$cTpl->mShow("enum_radio_fim");
				} else {
					$cTpl->mShow("enum_select_inicio");
					for($j=0;$j<count($valores_enum);$j++) {
						$cTpl->mSet("valor_enum",$valores_enum[$j]);
						$cTpl->mShow("enum_select_while");
					}
					$cTpl->mShow("enum_select_fim");
				}
				$cTpl->mShow("enum_fim");
				$cTpl->mSet("valor_enum","");
				$valores_enum = "";
			}
		}

		/*----------------------------------------------*/
		//limpeza de variaveis
		$cTpl->mSet("titleCampo","");
		$cTpl->mSet("field","");
		$cTpl->mSet("class","");
		$cTpl->mSet("asteriscoReq","");
		$cTpl->mSet("size","");
		$cTpl->mSet("mask","");
		$cTpl->mSet("cd_relation","");
		$cTpl->mSet("valor_relation","");
		$cTpl->mSet("padraoCampo","");
		$cTpl->mSet("maxlength","");
		$cTpl->mSet("readOnly","");
		$cTpl->mSet("exemplo","");
		$cTpl->mSet("p_extra","");
		$cTpl->mSet("html_extra_campo","");
		$cTpl->mSet("campo_title_before","");
		$classReal = "";
		$sizeReal = "";
		$sizePers = "";
	}

	$cTpl->mShow("fim");
}
?>