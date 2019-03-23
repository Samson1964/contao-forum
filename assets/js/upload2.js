$(document).ready(function (e) 
{
	$("#uploadimage").on('submit',(function(e) 
	{
		e.preventDefault();
		$("#message").empty();
		$('#loading').show();
		$.ajax(
		{
			url: "system/modules/forum/assets/ajax-upload.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$('#loading').hide();
				$("#message").html(data);
				var inhalt = $('#imagecode').html(); // Bildlink auslesen
				var alt = tinymce.get('ctrl_text').getContent(); // TinyMCE auslesen, alten Inhalt sichern
				tinyMCE.get('ctrl_text').setContent(alt+inhalt); // Mit hinzugefügtem Inhalt in TinyMCE einsetzen
			}
		});
	}));

	// Function to preview image after validation
	$(function() 
	{
		$("#file").change(function() 
		{
			$("#message").empty(); // To remove the previous error message
			var file = this.files[0];
			var imagefile = file.type;
			var match= ["image/jpeg","image/png","image/jpg","image/gif"];
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3])))
			{
				$('#previewing').attr('src','system/modules/forum/assets/images/noimage.png');
				$("#message").html("<p id='error'>Bitte wähle ein valides Bildformat</p>"+"<h4>Hilfe</h4>"+"<span id='error_message'>Nur jpeg, jpg, png und gif erlaubt</span>");
				return false;
			}
			else
			{
				var reader = new FileReader();
				reader.onload = imageIsLoaded;
				reader.readAsDataURL(this.files[0]);
			}
		});
	});

	function imageIsLoaded(e) 
	{
		$("#file").css("color","green");
		$('#image_preview').css("display", "block");
		$('#previewing').attr('src', e.target.result);
		$('#previewing').attr('width', '250px');
		$('#previewing').attr('height', '230px');
	};
});
