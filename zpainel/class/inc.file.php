<?php

/**
 * Classe de manipulação de arquivos de qualquer espécie 
 * Requer include da classe tools
 * 
 * <i>Criada em: 04/10/2006 
 * Última Alteração: 05/10/2006 </i>
 * 
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright © 2006, Zaib Tecnologia
 * @package Class
 */
class cFile {
	
	/**
	 * Trata o nome do arquivo, tira espaços em branco, acento e poe em minúsculo.
	 * @param string $p_arquivo » Nome Arquivo
	 * @param string $p_site » Adiciona nome na frente do arquivo
	 * @param int $p_aparece » 1 - Aparece o nome da imagem e 0 não aparece
	 * @param int $p_data » 1 - Aparece a data e 0 não aparece
	 * @return string $arquivo » Arquivo tratado
	 */
	function mTrataArquivo($p_arquivo, $p_site="", $p_aparece=1, $p_data=1){
	 	$p_arquivo  = strtolower($p_arquivo);
		
		// Pega a extensao do arquivo
	 	$s_extensao = explode(".", $p_arquivo);
	 	$s_extensao = ".".array_pop($s_extensao);
		
		// Trata os caracteres especiais e passa para minúsculo
		$p_arquivo  = cTools::mTrataCaracter(substr($p_arquivo, 0, 10));
	 	$p_arquivo  = strtolower($p_arquivo);
		$p_arquivo = str_replace(" ","",$p_arquivo);
		
		if($p_site != "") { $p_site = $p_site."_"; }
		
		// Caso data == 1 coloca a data no arquivo
		$p_data == 1 ? $p_data= date('d_m_y__H_i_s'): $p_data = "";
	
		// Parte randomica do nome para envitar ao máximo a igualdade
		$s_randomico = "__".rand(1000, 9999)."_".rand(1000, 9999);

		// Caso ele quer que apareca o nome do arquivo ou não 
	
		$p_aparece == 1 ? $p_arquivo = $p_site.$p_arquivo.$p_data.$s_randomico.$s_extensao : $p_arquivo = $p_site.$p_data.$s_randomico.$s_extensao;

		return $p_arquivo;	
	 }
	 
	/**
	 * Valida o tipo do arquivo enviado, com possibilidade de encerrar script ou apenas retornar false
	 * @param string $p_type » Tipo do Arquivo enviado $_FILE['img']['type']
	 * @param string $p_typePerm » 'imagem', 'flash'
	 * @param int $p_encerra » 1 - encerra o script e exibe msg de erro, 0 - retorna false
	 * @return bolean » true = sucesso, false ou Mensagem de erro = erro
	 */
	function mValidaTipoArquivo($p_type, $p_typePerm, $p_encerra=1){
		$s_permission = false;		
		switch($p_typePerm){
			case "imagem":
				if(($p_type == "image/jpeg") || ($p_type == "image/pjpeg") || ($p_type == "image/gif")){
					$s_permission = true;
				}
			break;
			
			case "flash":
				if($p_type == "application/x-shockwave-flash"){
					$s_permission = true;
				}
			break;
			
			case "docs":
				if(($p_type == "application/msword") || ($p_type == "text/plain") || ($p_type == "application/pdf") || ($p_type == "text/richtext")){
					$s_permission = true;
				}
			break;
		}
		
		// Caso retorna false é porque houve erro senão retorna true
		if(!$s_permission){

			// Usuário escolhe se exibe erro ou retorna false
			if($p_encerra == 1){
				cTools::mErro("Tipo de arquivo Inválido, é permitido apenas » <strong>".ucfirst($p_typePerm)."</strong>"); 
			}else{
				return false;
			}
		}else{
			return $s_permission;
		}
	}

    /**
	 * Move o arquivo para o caminho desejado com possibilidade de encerrar o script ou apenas retornar false
	 * @param string $p_nome » Nome do arquivo
	 * @param string $p_tmp_nome » Nome Temporário
	 * @param string $p_caminho » Diretório para onde ira move-lo
	 * @param int $p_encerra » 1 - encerra o script e exibe msg de erro, 0 - retorna false
	 * @return string » true - sucesso, false ou msg de erro = erro
	 */
	function mMoveArquivo($p_nome, $p_tmp_nome, $p_caminho, $p_encerra=1){

	 	// Verifica se diretório existe
	 	if(!is_dir($p_caminho)){
	 		
	 		// Usuário escolhe se exibe erro ou retorna false
			if($p_encerra == 1){
				cTools::mErro("Caminho não existe!"); 
			}else{
				return false;
			}	
	 	}else{
		 	
		 	// Caso haja erro ao mover arquivo retorna false
		 	if(!move_uploaded_file($p_tmp_nome, $p_caminho.$p_nome)){
		 		
		 		// Usuário escolhe se exibe erro ou retorna false
				if($p_encerra == 1){
					cTools::mErro("Erro ao mover arquivo!"); 
				}else{
					return false;
				}
		 	} else{
		 		return true;
		 	}
	 	}
	 }
	 
	/**
	 * Remove arquivo com possibilade de encerrar script ou retornar false
	 * @param string $p_arquivo -> Nome do arquivo
	 * @param string $p_caminho -> Diretorio do arquivo
	 * @param int $p_encerra » 1 - encerra o script e exibe msg de erro, 0 - retorna false
	 * @return string » true = sucesso, false ou msg Erro = erro
	 */
	function mRemoveArquivo($p_arquivo, $p_caminho, $p_encerra=1){
  	 
  	 	// Verifica se diretório existe
	 	if(!is_dir($p_caminho)){
	 		
	 		// Usuário escolhe se exibe erro ou retorna false
			if($p_encerra == 1){
				cTools::mErro("Caminho não existe!"); 
			}else{
				return false;
			}	
	 	}else{
	  	 	if(!@unlink($p_caminho.$p_arquivo)){
	  	 		
	  	 		// Usuário escolhe se exibe erro ou retorna false
				if($p_encerra == 1){
					cTools::mErro("Erro ao remover arquivo!"); 
				}else{
					return false;
				}
	  	 	}else{
	  	 		return true;
	  	 	}
	 	}
	 } 
	 
	/**
	 * Valida tamanho máximo permitido em Kb
	 * @param int $p_size » Tamanho em bytes do arquivo
	 * @param int $p_sizePerm » Tamanho máximo permitido em Kb
	 * @param int $p_encerra » 1 - encerra o script e exibe msg de erro, 0 - retorna false
	 * @return string » true = sucesso, false ou msg Erro = erro
	 */
	function mTamanhoMaximoArquivo($p_size, $p_sizePerm, $p_encerra=1) {

		// Passa o tamanho do arquivo de Bytes para Kbytes
		$p_size = $p_size/1024;
		
		settype($p_size, "integer");
		
		if($p_size <= $p_sizePerm) {
			return true;
		}else {
			// Usuário escolhe se exibe erro ou retorna false
			if($p_encerra == 1) {
				cTools::mErro("Tamanho de arquivo Inválido, máximo permitido é de » $p_sizePerm Kb"); 
			}else {
				return false;
			}
		}
	}

}
?>