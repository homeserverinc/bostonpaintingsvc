<?
/**
 * Classe de validação de formulário através da
 * API DOM do JavaScript, requer função Trim, RTrim e LTrim
 * de js.js em: /zpainel/jscript/js.js
 */
class cJS {

	private $v_msg;
	private $v_nm_funcao;
	private $v_lista1 = array();
	private $v_lista2 = array();
	
	function __construct () {
		$this->v_nm_funcao = "validaForm";
	}

	/**
	 * Seta os campos num array
	 * @param string $p_campo » Nome real do Campo do Formulário
	 * @param string $p_descricao » Nome que irá aparecer na mensagem para o usuário
	 */
	function mSet($p_campo, $p_descricao) {
		array_push($this->v_lista1, $p_campo);
		array_push($this->v_lista2, $p_descricao);
	}
	
	/**
	 * Seta um outro nome para função
	 */
	function mNmFuncao ($p_nome) {
		if(trim($p_nome) != "") $this->v_nm_funcao = $p_nome;
	}

	/**
	 * Exibe os Script gerado pela função Set no html
	 * para validar os campos de formulário
	 */
	function mShow() {
		$this->v_msg = "<script type=\"text/javascript\">\n";

		$this->v_msg.= "\tfunction ".$this->v_nm_funcao."() {\n";
		$this->v_msg.= "\t\tvar msg = \"\";\n";
		$this->v_msg.= "\t\tvar erro = 0;\n";
		$this->v_msg.= "\t\tvar primeiroCampo = \"\";\n\n";
		$s_var = 0;

		foreach($this->v_lista1 as $s_campo) {
			if (empty($s_primeiroCampo)) $s_primeiroCampo = $s_campo;
			$s_descricao  = $this->v_lista2[$s_var++];
			$this->v_msg .="\t\tif (trim(document.getElementById('$s_campo').value) == \"\") { msg+= ++erro+\" - $s_descricao está em branco.\\n\"; if (primeiroCampo==\"\") primeiroCampo = '$s_campo'; }\n";
		}

		$this->v_msg.= "\n\t\tif (erro == 0) {\n";
		$this->v_msg.= "\t\t\treturn true;\n";
		$this->v_msg.= "\t\t} else {\n";
		$this->v_msg.= "\t\t\talert(msg);\n\t\t\tdocument.getElementById(primeiroCampo).focus();\n\t\t\treturn false;\n";
		$this->v_msg.= "\t\t}\n";
		$this->v_msg.= "\t}\n";
		$this->v_msg.= "</script>";
		return($this->v_msg);
	}
}
?>