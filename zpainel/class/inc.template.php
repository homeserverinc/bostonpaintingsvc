<?php
/**
 * Classe Template � uma classe que separa o c�digo php do Html
 * 
 * <i>Criada em: 05/08/2006
 * �ltima Altera��o: 05/10/2006</i>
 * 
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright � 2006, Zaib Tecnologia
 * @package Class
 */
class cTemplate{
   
   /**
    * Seta na Vari�vel o valor
    */
   private $var;
   
	/**
	 * Construtor que verifica se o arquivo (parametro)
	 * foi especificado
	 */
    public function __construct($file=""){
		if ($file != "") {
			if (file_exists("templates/".$file)) $this->file = "templates/".$file;
			else $this->file = $file;
		} else {
			print "Especifique o arquivo";
		}	
    }

	/**
	 * Gera as vari�veis da P�gina
	 * @param $var string � Nome da Vari�vel template
	 * @param $value string � Valor da vari�vel template
	 * 
	 * Ecemplo de Uso: 
	 * Php � $tpl->Set("variavel", "ol� mundo")
	 * Html � {%variavel%}
	 */
    function mSet($var, $value){
    	$this->$var = $value;
    }
	
	/**
	 * Mostra a p�gina, pode conter ou n�o inc�os
	 * @param string $indent � Identifica��o (<!-- %inicio% -->)
	 * @param string $tipo � se == 1 ele retorna o template sen�o imprimi na tela
	 * 
	 * Modo de Usar:
	 * $tpl->Show();
	 * $tpl->Show("marca��o");
	 */
    function mShow ($ident="", $tipo=""){
		
		$arr = file($this->file);
		if ($ident == ""){
			$c = 0;
			$len = count ($arr);
			while($c < $len) {
				$temp = str_replace ("{%","$"."this->", $arr[$c]);
				$temp = str_replace ("%}", "", $temp);
				$temp = addslashes($temp);
				eval("\$x=\"$temp\";");
				if ($tipo) $acum .= $x;
				else print str_replace("\'", "'", $x);
				
				$c++;
			}
		} else {
			$c = 0;
			$len = count ($arr);
			$tag = "<!-- %".$ident."% -->";
			while($c < $len){
				if (trim($arr[$c]) == $tag){
					$c++;
					while((substr( $arr[$c], 0 , 6) != "<!-- %") && ($c < $len)){
						$temp = str_replace ("{%", "$"."this->", $arr[$c]);
						$temp = str_replace ("%}", "", $temp);
						$temp = addslashes($temp);
						eval("\$x = \"$temp\";");
						if ($tipo) $acum .= $x;
						else print str_replace("\'", "'", $x);
						$c++;
					}
					$c = $len;
				}
				$c++;
			}
		}
		if ($tipo == "1") return $acum;
    }
}
?>