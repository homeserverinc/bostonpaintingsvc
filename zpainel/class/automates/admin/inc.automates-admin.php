<?
		switch($_GET['action']) {
			/****************************************************************************
					INSERT
			*****************************************************************************/
			case "insert":				
				include "../class/automates/admin/inc.automates-admin-insert.php";
			break;
			
			/****************************************************************************
					MANAGE
			*****************************************************************************/
			case "manage":
				include "../class/automates/admin/inc.automates-admin-manage.php";
			break;
			
			/****************************************************************************
					UPDATE
			*****************************************************************************/
			case "update":		
				include "../class/automates/admin/inc.automates-admin-update.php";
			break;
			
			/****************************************************************************
					DELETE ARQUIVO
			*****************************************************************************/
			case "deleteArquivo":
				include "../class/automates/admin/inc.automates-admin-delete-arquivo.php";
			break;
			
			/****************************************************************************
					INSERT ARQUIVOS
			*****************************************************************************/
			case "insertArquivos":
				include "../class/automates/admin/inc.automates-admin-insert-arquivos.php";
			break;
			
			/****************************************************************************
					MANAGE ARQUIVOS
			*****************************************************************************/
			case "manageArquivos":
				include "../class/automates/admin/inc.automates-admin-manage-arquivos.php";
			break;
			
			/****************************************************************************
					UPDATE ARQUIVOS
			*****************************************************************************/
			case "updateArquivos":				
				include "../class/automates/admin/inc.automates-admin-update-arquivos.php";
			break;
			
			/****************************************************************************
					DELETE ARQUIVO ARQUIVOS
			*****************************************************************************/
			case "deleteArquivoArquivos":
				include "../class/automates/admin/inc.automates-admin-delete-arquivo-arquivos.php";
			break;
			
			/****************************************************************************
					RESPOSTA RELATION CODIGO
			*****************************************************************************/
			case "resposta-relation-codigo":
				include "../class/automates/admin/inc.automates-admin-resposta-relation-codigo.php";
			break;
			
			/****************************************************************************
					CONSULTA RELATION CODIGO
			*****************************************************************************/
			case "consulta-relation-codigo":
				include "../class/automates/admin/inc.automates-admin-consulta-relation-codigo.php";
			break;
			
			/****************************************************************************
					CONFIG
			*****************************************************************************/
			case "config":
				include "../class/automates/admin/inc.automates-admin-config.php";
			break;
			
			/****************************************************************************
					HOME (DEFAULT)
			*****************************************************************************/
			default:
				include "../class/automates/admin/inc.automates-admin-home.php";
			break;
		}
?>