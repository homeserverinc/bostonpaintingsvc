<?php

/**
 * Classe que trata os erros ocorridos com o banco de dados
 * 
 * <i>Criada em: 04/10/2006
 * Última Alteração: 05/10/2006</i>
 * 
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright © 2006, Zaib Tecnologia
 * @package Class
 */
class cErros {
	
	/**
	 * Número do erro
	 */
	protected $v_msg_erro;
	
	/**
	 * Tipo de erro
	 */
	protected $v_tipo;
	
	/**
	 * Endereço do erro (www.zaib.com.br/?acao=clientes)
	 */
	protected $v_endereco;
	
	/**
	 * Email para onde sera encaminhado o erro
	 */
	protected $v_email;
	
	/**
	 * Mensagem que sera apresentada ao administrador no WebSite
	 */
	protected $v_msg_admin_site;
	
	/**
	 * Mensagem que sera apresentada ao administrador no Email
	 */
	protected $v_msg_admin_email;
	
	/**
     * Armazena o último SQL feito pelo sistema(usuario) 
     */
	protected $v_ultimo_sql;
	
	/**
	 * Mensagem apresentada ao usuário Final
	 */
	public $v_msg_user;
	
	public function cErros() {
		$this->v_endereco = $_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
	}
	
	/**
	 * Monta mensagem html que irá avisar o usuário que o
	 * servidor está em manutenção
	 */
	private function mMontaMsgUserSite() {
		$this->v_msg_user = "<div style='font: 13px Tahoma, Verdana; text-align: center'>";
		$this->v_msg_user .= "Estamos em manutenção <br/> Tente novamente mais tarde! <br/>Obrigado!";
		$this->v_msg_user .= "</div>";
		
		print $this->v_msg_user;
	}
	
	/**
	 * Monta mensagem html apartir de todos os erros para exibir
	 * ao administrador do site de maneira oculta. (Exibir Códiog-Fonte)
	 */
	private function mMontaMsgAdminSite() {
		$this->v_msg_admin_site  = "<div style='display: none;'>";
		$this->v_msg_admin_site .= "\n\n ----------------------------------- \n\n"; 
		$this->v_msg_admin_site .= "Tipo: ".$this->v_tipo." \n\n";
		$this->v_msg_admin_site .= "Erro: ".$this->v_msg_erro." \n\n";
		$this->v_msg_admin_site .= "Sql:  ".$this->v_ultimo_sql." \n\n";
		$this->v_msg_admin_site .= "\n\n ----------------------------------- \n\n";
		$this->v_msg_admin_site .= "</div>";
		
		print $this->v_msg_admin_site;
	}
	
	/**
	 * Monta mensagem html apartir de todos os erros para exibir
	 * ao administrador do site através de email.
	 */
	private function mMontaMsgAdminEmail() {
		$this->v_msg_admin_email = "<div style='font: 12px Arial, Verdana;'>";
		$this->v_msg_admin_email .= "<h3>Erro em: <span style='color: red;'>$this->v_endereco</span></h3>";
		$this->v_msg_admin_email .= "<p><strong>Erro: </strong>".$this->v_msg_erro."</p>";
		$this->v_msg_admin_email .= "<p><strong>Tipo: </strong>".$this->v_tipo."</p>";
		$this->v_msg_admin_email .= "<p><strong>Sql: </strong>".$this->v_ultimo_sql."</p>";
		$this->v_msg_admin_email .= "</div>";
		
		// Caso a validação do envio retornar TRUE ele faz o enviodo email 
		if($this->mValidaEnvioEmail()) {
			$this->mEnviarEmail();
		}
	}
	
	/**
	 * Exibe as Mensagens de erro
	 */
	public function mExibeMsgErro() {
		$this->mMontaMsgUserSite();
		$this->mMontaMsgAdminSite();
		$this->mMontaMsgAdminEmail();
		exit();
	}
	
	public function mMsgErroImg() {
		$this->mMontaMsgAdminEmail();
	}
	
	/**
	 * Enviara o email com a mensagem de erro ao seu destinatario
	 */
	private function mEnviarEmail() {
		$s_assunto    = "Erro - $this->v_endereco";
		$s_cabecalho  = "MIME-Version: 1.0\r\n";
		$s_cabecalho .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$s_cabecalho .= "From: Erros - Zaib Tecnologia<".$this->v_email.">\r\n";
		
		/* Envia email para destinatário */
		mail($this->v_email, $s_assunto, $this->v_msg_admin_email, $s_cabecalho);
	}
	
	/**
	 * Faz a validação do envio do email, caso ele seje
	 * um email da maquina local (Servidor) não há necessidade
	 * de enviar email pois é montagem do site ou ajuste.
	 * @return boolean $envia » retorna true se deve enviar email senão retorna false
	 */
	private function mValidaEnvioEmail() {
	
		// Array de palavras que deseje encontrar
		$s_dados = array("app/", "localhost/");
		$s_envia = true;
		
		/*
		 * busca pelo palavra contida no array $dados sem diferenciar 
		 * maiusculas e minusculas, caso encontre passa envia para false ou
		 * seja não enviar o email pois encontrou a palavra chave
		 */
		for($i = 0; $i < count($s_dados); $i++) {
			if(stripos($this->v_endereco, $s_dados[$i]) !== false){
				$s_envia = false;
			}
		}
	}
}
?>