<?php

/**
 * Classe para tratar miniaturas: criar, remover, mostrar, verificar
 * Exemplo de uso:
 * $cGd = new cGd;
 * $tpl->Set("exemplo", $cGd->mGeraGd(parametros)); 
 */
class cGd extends cErros {
	
	/**
	 * Largura m�xima da imagem
	 */
	private $v_width_max;
	
	/**
	 * Altura m�xima da imagem
	 */
	private $v_height_max;
	
	/**
	 * Largura original da imagem
	 */
	private $v_width_original;
	
	/**
	 * Altura original da imagem
	 */
	private $v_height_original;
	
	/**
	 * Imagem
	 */
	private $v_image;
	
	/**
	 * Imagem Original (guarda o nome da imagem original)
	 */
	private $v_imageOriginal;
	
	/**
	 * Caminho da imagem
	 */
	private $v_caminho;
	
	/**
	 * Qualidade da imagem que voc� pretende ver
	 */
	private $v_quality;
	
	/**
	 * Tipo de imagem que voc� pretende ver (1 = Normal e 2 = Em p�)
	 */
	/*private $tipo;*/
	
	/**
	 * Miniatura que pretende gerar (1 = min_, 2 = med_ e 3 = max_)
	 */
	private $v_miniatura;
	
	/**
	 * Largura padr�o para se basear nos calculos da foto
	 */
	private $v_width_base;
	
	/**
	 * Altura padr�opara se basear nos calculos da foto
	 */
	private $v_height_base;
	
	/**
	 * C�pia fiel da imagem
	 */
	private $v_src_img_copia;
	
	/**
	 * Armazena a extens�o do arquivo
	 */
	private $v_ext;
	
	/**
	 * Caixa de fundo da miniatura e seta cor
	 */
	public $v_cx_fundo;
	
	private $v_width_img_resize;
	
	private $v_height_img_resize;
	
	/**
	 * Controla para ver se a imagem vai ter borda
	*/
	private $v_borda_imagem;
	
	/**
	 * Seta a cor da borda da imagem
	*/
	private $v_cor_borda_imagem;
	
	/**
	 * Seta a cor fundo da imagem
	*/
	private $v_cor_fundo_imagem;
	
	/**
	 * Gera a Miniatura da imagem
	 * @param string $p_caminho � Caminho da imagem
	 * @param string $p_image � Imagem
	 * @param int $p_width � Largura m�xima da imagem
	 * @param int $p_miniatura � Tipo da miniatura 1 = min_, 2 = med_ e 3 = max_
	 * @param int $p_tp � Posi��o da imagem 1 = De lado e 0 = Em p�
	 * @param int $p_quality � Qualidade da imagem 1 = Boa e 0 = Ruim
	 * @param int $p_borda � Borda na imagem 1 = Sim e 0 = N�o (padr�o)
	 * @param String $p_fundo � Cor de fundo da imagem "255,255,255" (padr�o)
	  * @param String $p_cor_borda � Cor da borda da imagem "0,0,0" (padr�o)
	 */
	function mGeraGd($p_caminho, $p_image, $p_width, $p_miniatura=1, $p_tp=1, $p_quality=1, $p_borda=0, $p_fundo="255,255,255", $p_cor_borda="0,0,0") {
	
		// Caso largura tiver vazio seta padr�o 100
		$this->v_width_max = $p_width == "" ? 100 : $p_width;

		// Caso miniatura tiver vazio seta padr�o 1
		$this->v_miniatura = $p_miniatura;
		
		// Seta a borda
		$this->v_borda_imagem = $p_borda;
		
		// Seta a cor da borda da imagem
		$this->v_cor_borda_imagem = $p_cor_borda;
		
		// Seta a cor de fundo da imagem
		$this->v_cor_fundo_imagem = $p_fundo;
		
		// Trata miniatura
		switch($p_miniatura) {
			case "1": $this->v_miniatura = "min_"; break;
			case "2": $this->v_miniatura = "med_"; break;
			case "3": $this->v_miniatura = "max_"; break;
		}

		// Caso qualidade tiver vazio seta padr�o 1 = Boa
		$this->v_quality = $p_quality;
		
		$this->v_image = $p_caminho.$p_image;
		$this->v_imageOriginal = $p_image;
		$this->v_caminho = $p_caminho;
		
		// Trata tipo de imagem
		if ($p_tp == 1) {
			$this->v_width_base  = 800;
			$this->v_height_base = 600;
		} else {
			$this->v_width_base  = 600;
			$this->v_height_base = 800;
		}
		
		if ($this->mVerificaExiste()) {
			$this->mTipoImagem();
			$this->mValidaImagem();
			$this->mCriaCopiaFielImage();
			
			if ($this->mVerificaTamanhoOriginal($this->v_width_base,$this->v_height_base,$this->v_width_max)) {
				// Salva a imagem
				@imagejpeg($this->v_src_img_copia,$this->v_caminho.$this->v_miniatura.$p_image);
			} else {
				$this->mCriaFundo();
				$this->mRedimensionaImageCopia();
				
				// Salva a imagem
				@imagejpeg($this->v_cx_fundo,$this->v_caminho.$this->v_miniatura.$p_image);
				@imagedestroy($this->v_cx_fundo);
			}
			
			// Verifica se � max_ para excluir a original
			if ($this->v_miniatura == "max_") {
				@unlink($this->v_caminho.$this->v_imageOriginal);
			}
		}

		// Verifica se o arquivo existe
		$caminhoFim = $this->v_caminho.$this->v_miniatura.$this->v_imageOriginal;
		
		if (!file_exists($caminhoFim)) {
			$caminhoFim = "zpainel/class/extensions/miniatura.php?caminho=./&image=nao_disponivel_$p_tp.jpg&width=$p_width&tipo=$p_tp&quality=1";
			$this->v_tipo  = "Erro de imagem";
			$this->v_email = "erros@zaib.com.br";
			$this->mMsgErroImg();
		}
		return $caminhoFim;
	}
	
	/*
	* GERA GD PROPORCIONALMENTE A LARGURA DA IMAGEM
	*/
	function mGeraGdProp($p_caminho, $p_image, $p_width, $p_miniatura=1) {
	
		switch($p_miniatura) {
			case "1": $this->v_miniatura = "min_"; break;
			case "2": $this->v_miniatura = "med_"; break;
			case "3": $this->v_miniatura = "max_"; break;
		}

		$this->v_image = $p_caminho.$p_image;
		$this->v_imageOriginal = $p_image;
		$this->v_caminho = $p_caminho;
		
		if ($this->mVerificaExiste()) {
			$this->mTipoImagem();
			$this->mValidaImagem();
			
			$im       = imagecreatefromjpeg($this->v_image);
			$largurao = imagesx($im);
			$alturao  = imagesy($im);
			$largurad  = $p_width;
			
			if ($largurad < $largurao) {
				$alturad = ($alturao*$largurad)/$largurao;
				
				$nova = imagecreatetruecolor($largurad,$alturad);
				imagecopyresampled($nova,$im,0,0,0,0,$largurad,$alturad,$largurao,$alturao);
				
				@imagejpeg($nova,$this->v_caminho.$this->v_miniatura.$p_image);
				@imagedestroy($im);
				@imagedestroy($nova);
			} else {
				$this->mCriaCopiaFielImage();
				@imagejpeg($this->v_src_img_copia,$this->v_caminho.$this->v_miniatura.$p_image);
			}
			
			if ($this->v_miniatura == "max_") {
				@unlink($this->v_caminho.$this->v_imageOriginal);
			}
		}
		
		$caminhoFim = $this->v_caminho.$this->v_miniatura.$this->v_imageOriginal;
		
		if (!file_exists($caminhoFim)) {
			$caminhoFim = "zpainel/class/extensions/miniatura.php?caminho=./&image=nao_disponivel_$p_tp.jpg&width=$p_width&tipo=$p_tp&quality=1";
			$this->v_tipo  = "Erro de imagem";
			$this->v_email = "erros@zaib.com.br";
			$this->mMsgErroImg();
		}
		return $caminhoFim;
	}
	
	/**
	 * Verifica se a miniatura existe
	 */
	function mVerificaExiste() {
		if (!file_exists($this->v_caminho.$this->v_miniatura.$this->v_imageOriginal)) {
			if (file_exists($this->v_caminho.$this->v_imageOriginal)) {
				return true;
			} else {
				if(file_exists($this->v_caminho."max_".$this->v_imageOriginal)) {
					$this->v_image = $this->v_caminho."max_".$this->v_imageOriginal;
					return true;
				}else {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Valida a imagem
	 */
	function mValidaImagem(){
		if ($this->v_ext != "jpg" && $this->v_ext != "jpeg" && $this->v_ext != "gif") {
			exit("Arquivo n�o � uma imagem valida, � v�lido apenas � jpg ou gif!");	
		}
	}
	
	/**
	 * Cria a caixa de fundo para imagem
	 */
	function mCriaFundo(){
		$this->mCalculaQuadrado();
		
		$this->v_cx_fundo = @imagecreatetruecolor($this->v_width_max, $this->v_height_max);
		
		$separaCorFundo = explode(",",$this->v_cor_fundo_imagem);
		$fundo          = @imagecolorallocate($this->v_cx_fundo, $separaCorFundo[0], $separaCorFundo[1], $separaCorFundo[2]);
		@imagefill($this->v_cx_fundo, 0, 0, $fundo);
	}
	
	function mCalculaQuadrado(){
		$this->v_height_max = ($this->v_width_max * $this->v_height_base);
		$this->v_height_max = ($this->v_height_max / $this->v_width_base);
	}
	
	/**
	 * Cria uma c�pia da imagem
	 */
	function mCriaCopiaFielImage(){
		if($this->v_ext == "jpg" || $this->v_ext == "jpeg"){
			$this->v_src_img_copia = @imagecreatefromjpeg($this->v_image);
		} else {
			$this->v_src_img_copia = @imagecreatefromgif($this->v_image);
			if(!$this->v_src_img_copia) {
				$this->v_src_img_copia = @imagecreatefromjpeg($this->v_image);
			}
		}
	}
	
	/**
	 * Verifica se a imagem original j� n�o est� no tamanho desejado
	*/
	function mVerificaTamanhoOriginal($largura,$altura,$largura_desejada) {
	
		$altura_desejada = $largura_desejada * $altura;
		$altura_desejada = $altura_desejada / $largura;
		$altura_desejada = intval($altura_desejada);
		
		if ((imagesx($this->v_src_img_copia) == $largura_desejada) && (imagesy($this->v_src_img_copia) == $altura_desejada)) {
			return true;
		} else {
			return false;
		}
	}
	
	function mRedimensionaImageCopia(){
		$this->v_width_original  = $this->v_width_img_resize = @imagesx($this->v_src_img_copia);
		$this->v_height_original = $this->v_height_img_resize = @imagesy($this->v_src_img_copia);

		// Redimenciona para padr�o do width
		while($this->v_width_img_resize > $this->v_width_max){
			$this->v_width_img_resize = $this->v_width_img_resize/1.001;
			$this->v_height_img_resize = $this->v_height_img_resize/1.001;
		}

		// Redimenciona para padr�o do height
		while($this->v_height_img_resize > $this->v_height_max){
			$this->v_width_img_resize = $this->v_width_img_resize/1.001;
			$this->v_height_img_resize = $this->v_height_img_resize/1.001;
		}
		
		// Adiciona 3px para n�o exibir espaco em baixo
		$this->v_height_img_resize = $this->v_height_img_resize+1;
		$this->v_width_img_resize = $this->v_width_img_resize+1;
		
		// Calcula espacamento da esquerda
		$espaco_esq = ($this->v_width_max - $this->v_width_img_resize);
		$espaco_esq = intval($espaco_esq/2);
		
		// Calcula espacamento do topo
		$espaco_top = ($this->v_height_max - $this->v_height_img_resize);
		$espaco_top = intval($espaco_top/2);
		
		if($this->v_quality == 1) {
			@imagecopyresampled($this->v_cx_fundo, $this->v_src_img_copia, $espaco_esq, $espaco_top, 0, 0, $this->v_width_img_resize, $this->v_height_img_resize, $this->v_width_original, $this->v_height_original);
		} else {
			@imagecopyresized($this->v_cx_fundo, $this->v_src_img_copia, $espaco_esq, $espaco_top, 0, 0, $this->v_width_img_resize, $this->v_height_img_resize, $this->v_width_original, $this->v_height_original);
		}
		
		if ($this->v_borda_imagem == 1) {
			// Adiciona borda na imagem
			$separaCorBorda = explode(",",$this->v_cor_borda_imagem);
			$colorBlack = imagecolorallocate($this->v_cx_fundo, $separaCorBorda[0], $separaCorBorda[1], $separaCorBorda[2]);
			imageline($this->v_cx_fundo, $espaco_esq, $espaco_top, $espaco_esq, ($this->v_height_img_resize-1)+$espaco_top, $colorBlack);
			imageline($this->v_cx_fundo, $espaco_esq, $espaco_top, ($this->v_width_img_resize-1)+$espaco_esq, $espaco_top, $colorBlack);
			imageline($this->v_cx_fundo, ($this->v_width_img_resize-1)+$espaco_esq, $espaco_top, ($this->v_width_img_resize-1)+$espaco_esq, ($this->v_height_img_resize-1)+$espaco_top, $colorBlack);
			
			//Ajusta o border-bottom
			if (intval(($this->v_height_img_resize-1)+$espaco_top) == intval(($this->v_height_img_resize-1))) {
				imageline($this->v_cx_fundo, $espaco_esq, ($this->v_height_img_resize-1)+($espaco_top-1), ($this->v_width_img_resize-1)+$espaco_esq, ($this->v_height_img_resize-1)+($espaco_top-1), $colorBlack);
			} else {
				imageline($this->v_cx_fundo, $espaco_esq, ($this->v_height_img_resize-1)+$espaco_top, ($this->v_width_img_resize-1)+$espaco_esq, ($this->v_height_img_resize-1)+$espaco_top, $colorBlack);
			}
		}
	}
	
	/**
	 * Gera a Miniatura da imagem
	 * @param string $p_caminho � Caminho da imagem
	 * @param string $p_image � Imagem
	 * @param int $p_width � Largura m�xima da imagem
	 * @param int $p_tp � Posi��o da imagem 1 = De lado e 0 = Em p�
	 * @param int $p_quality � Qualidade da imagem 1 = Boa e 0 = Ruim
	 * @param int $p_borda � Borda na imagem 1 = Sim e 0 = N�o (padr�o)
	 * @param String $p_fundo � Cor de fundo da imagem "255,255,255" (padr�o)
	 */
	function mGeraMiniatura($p_caminho, $p_image, $p_width, $p_tp=1, $p_quality=1, $p_borda=0, $p_fundo="255,255,255") {
		// Caso largura tiver vazio seta padr�o 100
		$this->v_width_max = $p_width == "" ? 100 : $p_width;
		
		// Seta a borda
		$this->v_borda_imagem = $p_borda;
		
		// Seta a cor de fundo da imagem
		$this->v_cor_fundo_imagem = $p_fundo;

		// Caso qualidade tiver vazio seta padr�o 1 = Boa
		$this->v_quality = $p_quality;
		
		$this->v_image = $p_caminho.$p_image;
		$this->v_imageOriginal = $p_image;
		$this->v_caminho = $p_caminho;

		// Trata tipo de imagem
		if ($p_tp == 1) {
			$this->v_width_base  = 800;
			$this->v_height_base = 600;
		} else {
			$this->v_width_base  = 600;
			$this->v_height_base = 800;
		}

		if($this->mVerificaMiniaturas()) {
			$this->mTipoImagem();
			$this->mValidaImagem();
			$this->mCriaCopiaFielImage();
			$this->mCriaFundo();
			$this->mRedimensionaImageCopia();
			
			@imagejpeg($this->v_cx_fundo);
			@imagedestroy($this->v_cx_fundo);
		}else {
			$this->v_image = "./nao_disponivel_$p_tp.jpg";
			$this->v_imageOriginal = "nao_disponivel_$p_tp.jpg";
			$this->v_caminho = "./";
		
			$this->mTipoImagem();
			$this->mValidaImagem();
			$this->mCriaCopiaFielImage();
			$this->mCriaFundo();
			$this->mRedimensionaImageCopia();
			
			$this->v_tipo  = "Erro de imagem";
			$this->v_email = "erros@zaib.com.br";
			$this->mMsgErroImg();
			
			@imagejpeg($this->v_cx_fundo);
			@imagedestroy($this->v_cx_fundo);
		}
		return $caminhoFim;
	}
	
	/**
	 * Testa todos os possiveis nomes de miniaturas p/ pode gerar sua
	 * respectiva gd
	 */
	function mVerificaMiniaturas () {
		if(!file_exists($this->v_image)) {
			$this->v_image = $this->v_caminho."min_".$this->v_imageOriginal;
			
			if(!file_exists($this->v_image)) {
				$this->v_image = $this->v_caminho."med_".$this->v_imageOriginal;
				
				if(!file_exists($this->v_image)) {
					$this->v_image = $this->v_caminho."max_".$this->v_imageOriginal;
					
					if(!file_exists($this->v_image)) {
						return false;
					}else {
						return true;
					}
				}else {
					return true;
				}		
			}else {
				return true;
			}
		}else {
			return true;
		}
	}
	
	/**
	 * Seta o tipo da imagem
	 */	
	function mTipoImagem(){
		$type = explode(".", $this->v_image);
		$ext  = array_pop($type);
		
		$this->v_ext = strtolower($ext);
	}
	
	/**
	 * Remove as Gd�s
	 * @param String $caminho � caminho da imagem
	 * @param String $imagem � nome da imagem
	 */	
	function mRemoveGd($p_caminho, $p_image){
		@unlink($p_caminho."min_".$p_image);
		@unlink($p_caminho."med_".$p_image);
		@unlink($p_caminho."max_".$p_image);
		@unlink($p_caminho.$p_image);
	}
}
?>