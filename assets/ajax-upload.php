<?php
if(isset($_FILES["file"]["type"]))
{
	$validextensions = array("jpeg", "jpg", "png", "gif");
	$temporary = explode(".", $_FILES["file"]["name"]);
	$file_extension = end($temporary);
	if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg" || ($_FILES["file"]["type"] == "image/gif")) && ($_FILES["file"]["size"] < 400000)) && in_array($file_extension, $validextensions)) 
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
		}
		else
		{
			if (file_exists($_SERVER['DOCUMENT_ROOT']."/files/forum/" . $_FILES["file"]["name"])) 
			{
				echo $_FILES["file"]["name"] . " <span id='invalid'><b>existiert bereits.</b></span> ";
			}
			else
			{
				$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
				$targetPath = $_SERVER['DOCUMENT_ROOT']."/files/forum/".$_FILES['file']['name']; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
echo "<span id='success'>Das Bild wurde oben angefügt!</span><br/>";
//echo 'Bitte kopieren und oben einfügen:<div id="imagecode">[img]../../files/forum/' . $_FILES["file"]["name"] . '[/img]</div>';
echo '<div id="imagecode" style="display:none;"><img src="../../files/forum/' . $_FILES["file"]["name"] . '"></div>';
//echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
//echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
}
}
}
else
{
echo "<span id='invalid'>***Bild zu groß oder falscher Dateityp***<span>";
}
}
?>
