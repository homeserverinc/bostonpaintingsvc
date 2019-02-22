/**
 * jqUploader (http://www.pixeline.be/experiments/jqUploader/)
 * A jQuery plugin to replace html-based file upload input fields with richer flash-based upload progress bar UI.
 *
 * Version 1.0.2.2
 * September 2007
 *
 * Copyright (c) 2007 Alexandre Plennevaux (http://www.pixeline.be)
 * Dual licensed under the MIT and GPL licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * using plugin "Flash" by Luke Lutman (http://jquery.lukelutman.com/plugins/flash)
 *
 * IMPORTANT:
 * The packed version of jQuery breaks ActiveX control
 * activation in Internet Explorer. Use JSMin to minifiy
 * jQuery (see: http://jquery.lukelutman.com/plugins/flash#activex).
 *
 **/
 jQuery.fn.jqUploader = function(options) {
    return this.each(function(index) {
        var $this = jQuery(this);
		// fetch label value if any, otherwise set a default one
		var $thisForm =  $this.parents("form");
		var $thisInput = $("input[@type='file']",$this);
		var $thisLabel = $("label",$this);
		var containerId = $this.attr("id") || 'jqUploader-'+index;
		var startMessage = 'Selecione o arquivo para enviar:';
		// get form action attribute value as upload script, appending to it a variable telling the script that this is an upload only functionality
		var actionURL = $thisForm.attr("action");
		// adds a var setting jqUploader to 1, so you can use it for serverside processing
		var prepender = (actionURL.lastIndexOf("?") != -1) ? "&": "?";
		actionURL = actionURL+prepender+'jqUploader=1';
		// check if max file size is set in html form
		var maxFileSize = $("input[@name='MAX_FILE_SIZE']", $(this.form)).val();
		var opts = jQuery.extend({
				width:580,
				height:80,
				version: 8, // version 8+ of flash player required to run jqUploader
				background: '', // background color of flash file
				src:    '../class/automates/jscript/jqUploader/jqUploader.swf',
				uploadScript:     'upload.php',
				afterScript:      'none', // if this is empty, jqUploader will replace the upload swf by a hidden input element
				varName:	        $thisInput.attr("name"),  //this holds the variable name of the file input field in your html form
				allowedExt:	      '*.jpg; *.jpeg; *.png', // allowed extensions
				allowedExtDescr:  'Images (*.jpg; *.jpeg; *.png)',
				params:           {menu:false},
				flashvars:        {},
				hideSubmit:       false,
				barColor:		      'FFCC00',
				maxFileSize:      1048576,
				startMessage:     startMessage,
				errorSizeMessage: ' Arquivo muito grande!',
				validFileMessage: ' Aguarde...',
				progressMessage: ' Por favor aguarde, carregando...',
				endMessage:    'Ok',
				nmArquivo: '',
				nmCaminho: ''
		}, options || {}
		);
		// disable form submit button
		if (opts.hideSubmit==true) {
			$("*[@type='submit']",this.form).hide();
		}
		// THIS WILL BE EXECUTED IN THE USECASE THAT THERE IS NO REDIRECTION TO BE DONE AFTER UPLOAD
		TerminateJQUploader = function(containerId,filename,varname,nmArquivo){
			var nmArquivoExtensao = (filename.substring(filename.lastIndexOf("."))).toLowerCase();
			var valueNmArquivo = nmArquivo + nmArquivoExtensao;
			
			$this= $('#'+containerId).empty();
			
			if ((nmArquivoExtensao == ".jpg") || (nmArquivoExtensao == ".jpeg") || (nmArquivoExtensao == ".gif") || (nmArquivoExtensao == ".png")) $this.text('').append('<span style="color:#008200">Arquivo <strong>'+filename+'</strong> enviado com sucesso!</span><br /><a href="javascript:tb_show(\'\', \''+opts.nmCaminho+valueNmArquivo+'\', false);window.location=\'#\';void(0);"><img src="'+opts.nmCaminho+valueNmArquivo+'" width="120" alt="Ampliar foto" style="border: 3px solid #FFCE09; margin-top: 3px;" /></a> <input type="button" id="bt'+nmArquivo+'" name="bt'+nmArquivo+'" onclick="mDeletaArquivo(\''+nmArquivo+'\',\''+valueNmArquivo+'\',\''+varname+'\');" value="Excluir foto" style="margin-top: -34px;" title="Excluir foto" /><script type="text/javascript">document.getElementById("'+varname+'").value = "'+valueNmArquivo+'";</script>');
			else $this.text('').append('<span style="color:#008200">Arquivo <strong>'+filename+'</strong> enviado com sucesso!</span><br /><a href="'+opts.nmCaminho+valueNmArquivo+'" target="_blank"><img src="../class/automates/images/arquivo.jpg" alt="Abrir arquivo" style="border: 3px solid #FFCE09; margin-top: 3px;" /></a> <input type="button" id="bt'+nmArquivo+'" name="bt'+nmArquivo+'" onclick="mDeletaArquivo(\''+nmArquivo+'\',\''+valueNmArquivo+'\',\''+varname+'\');" value="Excluir arquivo" style="margin-top: -34px;" title="Excluir arquivo" /><script type="text/javascript">document.getElementById("'+varname+'").value = "'+valueNmArquivo+'";</script>');
			
			var myForm = $this.parents("form");
			myForm.submit(function(){return true});
			$("*[@type='submit']",myForm).show();
		}
		var myParams = '';
		for (var p in opts.params){
				myParams += p+'='+opts.params[p]+',';
		}
		myParams = myParams.substring(0, myParams.length-1);
		// this function interfaces with the jquery flash plugin
		jQuery(this).flash(
		{
			src: opts.src,
			width: opts.width,
			height: opts.height,
			id:'movie_player-'+index,
			bgcolor:'#'+opts.background,
			flashvars: {
				containerId: containerId,
				uploadScript: opts.uploadScript+'?nmArquivo='+opts.nmArquivo+'&caminho='+opts.nmCaminho,
				afterScript: opts.afterScript,
				allowedExt: opts.allowedExt,
				allowedExtDescr: opts.allowedExtDescr,
				varName :  opts.varName,
				barColor : opts.barColor,
				maxFileSize :opts.maxFileSize,
				startMessage : opts.startMessage,
				errorSizeMessage : opts.errorSizeMessage,
				validFileMessage : opts.validFileMessage,
				progressMessage : opts.progressMessage,
				endMessage: opts.endMessage,
				nmArquivo: opts.nmArquivo
			},
			params: myParams
		},
		{
			version: opts.version,
			update: false
		},
			function(htmlOptions){
				var $el = $('<div id="'+containerId+'" class="flash-replaced"><div class="alt">'+this.innerHTML+'</div></div>');
					 $el.prepend($.fn.flash.transform(htmlOptions));
					 $('div.alt',$el).remove();
					 $(this).after($el).remove();
			}
		);
	});
};
