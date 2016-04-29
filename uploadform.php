<table>
<?php include_once("util/FileUtil.php"); ?>
<?php include_once("util/HTMLUtil.php"); ?>
<?php include_once("util/PDFUtil.php"); ?>
<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("beans/SelectedOption.php"); ?>
<?php include_once("beans/UploadedImage.php"); ?>
<?php include_once("beans/PDFImageWidthHeight.php"); ?>
<?php

	//print_r($_FILES);
	$fileUtil = new FileUtil();
	$target_dir = $fileUtil->getImageFolder();
	$num_images = $fileUtil->getNumberOfUploadFiles();
	
	//add images to collection
	$images = new Collection;
	
	$milliseconds = round(microtime(true) * 1000);
	//echo "checking files";
	//loop through possible uploaded files and save
	for ($x = 1; $x <= $num_images; $x++) {
		$currentImage = "photo".$x;
		if (empty($_FILES[$currentImage]['name'])) {
			// No file was selected for upload
			//echo 'No file for '.$currentImage;
			continue;
		}
		$check = getimagesize($_FILES[$currentImage]["tmp_name"]);
		if($check !== false) {
			$image_info = getimagesize($_FILES[$currentImage]["tmp_name"]);
			$image_width = $image_info[0];
			$image_height = $image_info[1];

			//echo "File  is ok. ".$currentImage;
			//lets save to folder
			$uploadOk = 1;
			$info = pathinfo($_FILES[$currentImage]['name']);
			$ext = $info['extension']; // get the extension of the file
			$newname = $currentImage."_".$milliseconds.".".$ext;
			
			//$target = $target_dir."/".$milliseconds."/".$newname;
			//mkdir("testing");
			$target = $target_dir."/".$newname;
			//echo $target_dir;
			move_uploaded_file( $_FILES[$currentImage]["tmp_name"], $target);
			
			//create image link for web page and PDF		
			$uploadedImage = new UploadedImage();
			$uploadedImage->name = $newname;
			$uploadedImage->imageURL = "../".$target;
			$uploadedImage->width = $image_info[0];
			$uploadedImage->height = $image_info[1];

			//perform ratio calculation if image too big
			$pdfUtil = new PDFUtil();
			$pdfImageWidthHeight = $pdfUtil->getBestPDFWidthHeight($uploadedImage);
			$uploadedImage->width = $pdfImageWidthHeight->width;
			$uploadedImage->height = $pdfImageWidthHeight->height;
				
			$images->add($uploadedImage);
		} else {
			//echo "File is not an image. ".$currentImage;
			$uploadOk = 0;
		}
	}
	
	$options = new Collection;
	
	//$value =  $_POST['subject'];
    foreach ($_POST as $key => $value) {
		$selectedOption = new SelectedOption();
		$selectedOption->optionFormID = $key;
		$selectedOption->optionValue = $value;
		//TODO get label from Database
		$options->add($selectedOption);
    }

    $htmlUtil = new HTMLUtil();
    $html = "<html><body> ";
    $html = $html.$htmlUtil->getOptionsTable($options);
    $html = $html.$htmlUtil->getImageTable($images);
    $html = $html." <html><body>";
    
    $link = $fileUtil->saveHTMLToWebFile($html, $milliseconds);
    echo "<a href='".$link.">".$link."</a>"
?>
</table>