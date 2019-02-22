function exibeFlash(url, width, height, id) {
	document.write('<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\" width=\"'+width+'\" height=\"'+height+'\" id=\"'+id+'\">');
	document.write('<param name=\"allowScriptAccess\" value=\"sameDomain\" />');
	document.write('<param name=\"movie\" value=\"'+url+'\" />');
	document.write('<param name=\"quality\" value=\"high\" />');
	document.write('<param name=\"bgcolor\" value=\"#ffffff\" />');
	document.write('<param name=\"wmode\" value=\"transparent\" />');
	document.write('<param name=\"menu\" value=\"false\" />');
	document.write('<embed src=\"'+url+'\" quality=\"high\" menu=\"false\" bgcolor=\"#ffffff\" width=\"'+width+'\" height=\"'+height+'\" allowScriptAccess=\"sameDomain\" wmode=\"transparent\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />');
	document.write('</object>');
}