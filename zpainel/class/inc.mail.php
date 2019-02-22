<?

class cMail {
	private $v_cont;
	private $v_img_header;
	private $v_img_rodape;
	private $v_header;
	private $v_rodape;
	private $v_msg;
	private $v_titulo_mail;
	private $v_dt_envio;

	public  $v_email;
	public  $v_mail_from;
	public  $v_nm_from;
	public  $v_colspan;
	public  $v_caminho_imgs;
	public  $v_font;
	public  $v_size;
	public  $v_cd_excluir;
	public  $v_link_excluir;
	public  $v_cor;
	
	/**
	 * Monta estrutura do envio do email
	 */
	public function __construct () {
		$this->v_header = "<html>";
		$this->v_header .= "<body style=\"margin:0; padding:0;\">";
		$this->v_header .= "<table align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">";
		$this->v_cont   = "";
		$this->v_rodape = "</table>";
		$this->v_rodape .= "</body>";
		$this->v_rodape .= "</html>";
		
		$this->v_font    = "Tahoma";
		$this->v_size    = "2";
		$this->v_colspan = "2";
		$this->v_cor     = "#000";
	}

	/**
	 * Seta imagem de cabeçalho <b>Obs*</b>: Não esquecer de setar o caminho antes
	 */
	public function mImgHeader ($p_img) {
		$this->v_img_header = "<tr><td colspan=\"".$this->v_colspan."\">";
		$this->v_img_header .= "<img src=\"".$this->v_caminho_imgs.$p_img."\" alt=\"Cabeçalho\"/>";
		$this->v_img_header .= "</td></tr>";
		$this->v_img_header .= "<tr><td><font size=\"10px\"></font>";
		$this->v_img_header .= "</td></tr>";
	}
	
	/**
	 * Seta imagem de rodapé <b>Obs*</b>: Não esquecer de setar o caminho antes (height 39px)
	 */
	public function mImgRodape ($p_img) {
		$this->v_img_header .= "<tr><td style=\"font: 5px Tahoma;\" colspan=\"2\">&nbsp;</td></tr>";
		$this->v_img_header .= "<tr><td><font size=\"10px\"></font>";
		$this->v_img_header .= "</td></tr>";
		$this->v_img_rodape .= "<tr><td style=\"font: 5px Tahoma;\" colspan=\"2\">&nbsp;</td></tr>";
		$this->v_img_rodape .= "<tr><td height=\"39\" colspan=\"".$this->v_colspan."\" background=\"".$this->v_caminho_imgs.$p_img."\">";

		if($this->v_cd_excluir != "") $this->v_img_rodape .= "<font face=\"".$this->v_font."\" size=\"".$this->v_size."\" color=\"".$this->v_cor."\">&nbsp;&nbsp;Caso não queira mais receber esse email click e <a href=\"".$this->v_link_excluir."\" link=\"#FFFFFF\"><font color=\"".$this->v_cor."\">remova seu email</font></a></font>";

		$this->v_img_rodape .= "</td></tr>"; 
	}
	
	/**
	 * Seta variveis com parametro Ex: Nome: Zaib, etc..
	 * 
	 * @param string » Nome do Enunciado
	 * @param string » Conteudo desse Enunciado
	 */
	public function mSetParam($p_var, $p_val) {
		$this->v_cont .= "<tr>";
		$this->v_cont .= "<td width=\"20%\" valign=\"top\" style=\"padding-left: 10px;\"><font face=\"".$this->v_font."\" size=\"".$this->v_size."\"><strong>".$p_var."</strong>:</td>";
		$this->v_cont .= "<td width=\"80%\" valign=\"top\" style=\"padding-left: 10px;\"><font face=\"".$this->v_font."\" size=\"".$this->v_size."\">".$p_val."</td>";
		$this->v_cont .= "</tr>";
	}
	
	/**
	 * Monta o título do email
	 * 
	 * @param string » Título do email
	 */
	public function mSetTitulo($p_titulo) {
		$this->v_titulo_mail .= "<tr height=\"20\"><td colspan=\"".$this->v_colspan."\" style=\"padding-left: 10px;\">";
		$this->v_titulo_mail .= "<font face=\"".$this->v_font."\" size=\"".$this->v_size."\"><strong>".$p_titulo."</strong></font>";
		$this->v_titulo_mail .= "<tr><td colspan=\"".$this->v_colspan."\">";
	}
	
	/**
	 * Monta a mensagem
	 */
	private function mMontaMsg() {
		$this->v_msg = $this->v_header;
		$this->v_msg .= $this->v_img_header;
		$this->v_msg .= $this->v_titulo_mail;
		$this->v_msg .= $this->v_dt_envio;
		$this->v_msg .= $this->v_cont;
		$this->v_msg .= $this->v_img_rodape;
		$this->v_msg .= $this->v_rodape;
	}
	
	/**
	 * Envia o email
	 */
	private function mEnviaEmail() {		
		mail($this->v_email, html_entity_decode(strip_tags($this->v_titulo_mail)), $this->v_msg, "Date: ".date("r")."\nX-Mailer: PHP v".phpversion()."\nMIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1\nFrom: ".$this->v_nm_from." <".$this->v_mail_from.">\nReturn-Path: <".$this->v_mail_from.">"); 
	}
	
	/**
	 * Seta a data atual e hora junto c/ email
	 */
	public function mSetDtEnvio () {
		$this->v_dt_envio  = "<tr hieght=\"20\"><td colspan=\"".$this->v_colspan."\" style=\"padding-left: 10px;\">";
		$this->v_dt_envio .= "<font face=\"".$this->v_font."\" size=\"".$this->v_size."\">Formulário enviado dia <strong>".date("d/m/Y")."</strong> às <strong>".date("H:i")."</strong> horas.</font>";
		$this->v_dt_envio .= "</td></tr>";
		$this->v_dt_envio .= "<tr><td style=\"font: 5px Tahoma;\" colspan=\"2\">&nbsp;</td></tr>";
	}

	/**
	 * Executa o envio do email, montagem do email
	 */
	public function mShowMail($p_email) {
		$this->mMontaMsg();
		$this->v_email = $p_email;
		$this->mEnviaEmail();
	}
	
	public function mSetText ($p_msg) {
		$this->v_cont = "<tr><td style=\"padding-left: 10px;\">";
		$this->v_cont .= "<font face=\"".$this->v_font."\" size=\"".$this->v_size."\">".$p_msg."</font>";
		$this->v_cont .= "</td></tr>";
	}
	
	public function mEnvioMsgResp() {
		$this->mMontaMsg();
		$this->mEnviaEmail();
	}
}
?>