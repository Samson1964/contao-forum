tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	skin: "default",
	height: "400",
	language : "en",
	valid_elements: "*",
	extended_valid_elements : 'img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]',
	
	plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,jbimages,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,emotions,cleanup,help,code",
	theme_advanced_buttons3 : "insertdate,inserttime,preview,|,forecolor,backcolor",
	//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Example content CSS (should be your site CSS)
	content_css : "/system/modules/forum/assets/tinymce/css/content.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "/system/modules/forum/assets/tinymce/lists/template_list.js",
	external_link_list_url : "/system/modules/forum/assets/tinymce/lists/link_list.js",
	external_image_list_url : "/system/modules/forum/assets/tinymce/lists/image_list.js",
	media_external_list_url : "/system/modules/forum/assets/tinymce/lists/media_list.js",

	// Style formats
	style_formats : [
		{title : 'Bold text', inline : 'b'},
		{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
		{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
		{title : 'Example 1', inline : 'span', classes : 'example1'},
		{title : 'Example 2', inline : 'span', classes : 'example2'},
		{title : 'Table styles'},
		{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
	],

	//relative_urls : false,
	
	setup: function(editor) 
	{
		editor.getElement().removeAttribute('required');
	}

});
