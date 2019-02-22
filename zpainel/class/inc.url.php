<?php
/**
 * Classe de controle de dados via URL
 */
class cUrl {

	//variavel privada para guardar a array gerada na url
	private $urlArray;
	
	/**
	 * M�todo construtor para pegar a url e separar em barras para salvar em um array
	 */
	public function __construct() {
		//Pega a url do browser
		$url = $_SERVER['REQUEST_URI'];
		
		//separa a url em barras /
		$separa = explode("/", $url);
		
		//salva a array em uma variavel privada
		$this->urlArray = $separa;
	}
	
	/**
	 * M�todo para retornar o nome da variavel solicitada
	 */
	public function mGetParam($numero) {
		return $this->urlArray[$numero];
	}
	
	/**
	 * M�todo para retornar uma string tratada para mostra na url
	 */
	public function mTrataString($texto) {
	
		$texto = html_entity_decode($texto);
		
		//remove acentos
		$texto = preg_match('[a�����]','a',$texto);
		$texto = preg_match('[e����]','e',$texto);
		$texto = preg_match('[i����]','i',$texto);
		$texto = preg_match('[o�����]','o',$texto);
		$texto = preg_match('[u����]','u',$texto);
		
		//trata cedilha � e �
		$texto = preg_match('[�]','c',$texto);
		$texto = preg_match('[�]','n',$texto);
		
		//substitui os espa�os em branco por hifen
		$texto = preg_match('( )','-',$texto);
		
		//trata outros caracteres
		$texto = preg_match('[^a-z0-9\-]','',$texto);
		
		//trata duplo espa�o de hifen para um hifen apenas
		$texto = preg_match('--','-',$texto);
		
		return strtolower($texto);
	}
}
?>