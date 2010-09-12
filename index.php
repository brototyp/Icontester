<?php
	// uploadpath
	$target_path = 'uploads/';
	
	// values
	$tmplheadericon = '<link rel="%1$s" href="' . $target_path . '%2$s" />';
	$tmpliconselecable = '<a href="index.php?file=%1$s"><img src="' . $target_path . '%1$s" style="height:50px;" /></a>';
	$tmpliconpreview = '<img src="' . $target_path . '%1$s" style="height:50px;" />';
	$tmplshineselection = '<a href="index.php?file=' . $_GET['file'] . '&%1$s">%2$s</a>';
	$tmplUploadSuccess = 'The file %s has been uploaded';
	$tmplUploadFailed = 'There was an error uploading the file, please try again!';
	$tmplUploadForm = '<form enctype="multipart/form-data" action="index.php" method="POST"> Choose an Icon to upload: <input name="uploadedfile" type="file" /><br /> <input type="submit" value="Upload File" /> </form> </body></html>';
	
	// errorhandling
	if(!is_dir($target_path)) die('ERROR! No Such target_path: ' . $target_path);
	
	if(strstr( $_SERVER["HTTP_USER_AGENT"], "iPhone" ))
	{
	// ###########################################
	// online via iPhone
		$content[head] .= "<meta name = \"viewport\" content = \"initial-scale=1, width = 320, user-scalable = no\">";
		if($_GET[file] == '')
		{
			// parse & display all icons
			if($handle = opendir($target_path))
			{
				$icons = array();
				while (false !== ($file = readdir($handle)))
					if ($file != "." && $file != "..") 
						$icons[@filemtime($target_path.$file)] = $file; 
				krsort($icons); 
				foreach ($icons as $icon) 
				$content[body] .= sprintf($tmpliconselecable,$icon);
				closedir($handle);
			}
		} else if($_GET[shine] =='') {
			// select shineoptions
			$content[body] .= sprintf($tmpliconpreview,$_GET['file']);
			$content[body] .= "<p>Do you want to add Shine to the Icon?</p>";
			$content[body] .= sprintf($tmplshineselection,'shine=apple-touch-icon','YES');
			$content[body] .= sprintf($tmplshineselection,'shine=apple-touch-icon-precomposed','NO');
		} else {
			// ready to test
			$content[head] .= sprintf($tmplheadericon,$_GET[shine],$_GET[file]); // set the metatag for the icon
			$content[body] .= sprintf($tmpliconpreview,$_GET['file']);
			$content[body] .= "<p>There you go. Now hit the '+' down there!</p><p><a href='index.php'>start over</a></p>";
		}
	} else {
	// ###########################################
	// online via Computer
		if($_FILES['uploadedfile']['tmp_name'] != '')
		{
			// handle upload
			$filepath = $target_path . basename( $_FILES['uploadedfile']['name']); 
			$content[body] .= move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $filepath) ? sprintf($tmplUploadSuccess,basename( $_FILES['uploadedfile']['name'])) : $tmplUploadFailed;
		}
		// display fomular
		$content[body] .= $tmplUploadForm;
	 } ?>
<html>
	<head>
		<title>Icontester</title>
		<? echo $content[head]; ?>
	</head>
	<body>
		<? echo $content[body]; ?>
	</body>
</html>