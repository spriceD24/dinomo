<html>
<body>
<table>
<?php include_once("util/FileUtil.php"); ?>
<?php include_once("util/HTMLUtil.php"); ?>
<?php include_once("util/PDFUtil.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("beans/SelectedOption.php"); ?>
<?php include_once("beans/UploadedImage.php"); ?>
<?php include_once("beans/PDFImageWidthHeight.php"); ?>
<?php require_once('tcpdf/tcpdf.php');?>
<?php require_once('mail/PHPMailer.php');?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("dao/ProjectDAO.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("dao/UserDAO.php"); ?>
<?php include("util/HTMLConst.php"); ?>

<?php

	//print_r($_FILES);
	$pdfUtil = new PDFUtil();
	$fileUtil = new FileUtil();
    $webUtil = new WebUtil();
    $configUtil = new ConfigUtil();
    
	$target_dir = $configUtil->getImageFolder();
	$num_images = $configUtil->getNumberOfUploadFiles();
	
	$setOptionPrefix = HTMLConst::STANDARD_OPT_ID_PREFIX;
	$projectDAO = new ProjectDAO ();
	$userDAO = new UserDAO ();
	
	//add images to collection
	$images = new Collection;
	
	$unique_id = round(microtime(true) * 1000);
	//echo "checking files";
	//loop through possible uploaded files and save
	
	//print_r($_FILES);
	
	//get the uploaded user
	$uploadedUserID = intval($_POST["uploadedBy"]); 
	$projectID = intval($_POST["projectID"]); 
	$categoryID = intval($_POST["categoryID"]); 
	
	//get the data
	$uploadedUser = $userDAO->getUser($uploadedUserID);
	$project = $projectDAO->getProject($projectID);
	$currentCategory = $projectDAO->getCategory($projectID, $categoryID );
	$categoryOptions = $projectDAO->getCategoryOptions($projectID, $categoryID );
	
	//$count = count($_FILES['files']['tmp_name']);
	foreach($_FILES as $file) {
		$file_name = $file['name'];
		$file_type = $file['type'];
		$file_error = $file['error'];
		$file_size = $file['size'];
		$file_tmp_name = $file['tmp_name'];
		
		if($file_size)
		{
			$check = getimagesize($file_tmp_name);
			if($check !== false)
			{
				$image_info = getimagesize($file_tmp_name);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
				
				//echo "File  is ok. ".$currentImage;
				//lets save to folder
				$uploadOk = 1;
				$info = pathinfo($file_name);
				$ext = $info['extension']; // get the extension of the file
				$newname = $file_name."_".$unique_id.".".$ext;
				
				//$target = $target_dir."/".$unique_id."/".$newname;
				//mkdir("testing");
				$target = $target_dir."/".$newname;
				//echo $target_dir;
				move_uploaded_file($file_tmp_name, $target);
				
				//create image link for web page and PDF
				$uploadedImage = new UploadedImage();
				$uploadedImage->name = $newname;
				$uploadedImage->imageURL = $webUtil->getBaseURI()."/".$target;
				$uploadedImage->width = $image_info[0];
				$uploadedImage->height = $image_info[1];
				
				//perform ratio calculation if image too big
				$pdfImageWidthHeight = $pdfUtil->getBestPDFWidthHeight($uploadedImage);
				$uploadedImage->width = $pdfImageWidthHeight->width;
				$uploadedImage->height = $pdfImageWidthHeight->height;
					
				$images->add($uploadedImage);
				
			}else{
				//file is not an image
			}
		}else{
			//file is empty
		}
	}
	
	$options = new Collection;
	
	/*
	//$value =  $_POST['subject'];
    foreach ($_POST as $key => $value) {
		$selectedOption = new SelectedOption();
		$selectedOption->optionFormID = $key;
		$selectedOption->optionValue = $value;
		//TODO get label from Database
		$options->add($selectedOption);
    }
*/

$selectedOption = new SelectedOption();
$selectedOption->valueOnly = false;
$selectedOption->optionFormID = "Project";
$selectedOption->optionValue = $project->projectName;
$options->add($selectedOption);
	
while ( $categoryOption = $categoryOptions->iterate () ) 

{
	if (isset($_POST[$setOptionPrefix.$categoryOption->categoryOptionID]))
	{
		$value = $_POST[$setOptionPrefix.$categoryOption->categoryOptionID];
		//echo $setOptionPrefix.$categoryOption->categoryOptionID.' = '.$value.'<br/>';
		if(!is_null($value))
		{
			$selectedOption = new SelectedOption();
			$label = $categoryOption->title;
			$selectedOption->valueOnly = false;
			$selectedOption->formType = $categoryOption->formType;
			if(!is_null($categoryOption->pdfTitle) && !empty($categoryOption->pdfTitle))
			{
				$label = $categoryOption->pdfTitle;
			}
			$selectedOption->optionFormID = $label;
			$selectedOption->optionValue = $value;
			if ($categoryOption->formType == 'CONFIRM')
			
			{
				$selectedOption->valueOnly = true;
				$selectedOption->optionValue = $label;
			}
			

			$options->add($selectedOption);
		}
	}
}
	
	
    $htmlUtil = new HTMLUtil();
    
    $optionsHTML = $htmlUtil->getOptionsTable($options);
	$imageHTML = $htmlUtil->getImageTable($images);
	$html = "<html><body> ";
	$html = $html.$optionsHTML;
	$html = $html.'<br/><br/><h2>PHOTOS</h2><hr/><br/>';
	$html = $html.$imageHTML;
	$html = $html." </html></body>";
    
    $link = $fileUtil->saveHTMLToWebFile($html, $unique_id);
    echo "<a href='".$link.">".$link."</a>";

	$pdfUtil->generatePDF($optionsHTML,$imageHTML, $unique_id, 'Quality Assurance - '.$currentCategory->categoryName);
	
	//now send the email
	$email = new PHPMailer();

	$webUrl = $configUtil->getWebFolder()."/".urlencode($unique_id).".html";
	$pdfUrl = $configUtil->getPDFFolder()."/".urlencode($unique_id).".pdf";

	$webUrl = $webUtil->getBaseURI()."/".$webUrl;
	$pdfUrl = $webUtil->getBaseURI()."/".$pdfUrl;

	$emailHTML = $htmlUtil->generateUploadEmail($webUrl,$pdfUrl,$project,$currentCategory,$uploadedUser);

	$email->From      = $uploadedUser->email;
	$email->FromName  = $uploadedUser->name;	
	$email->Subject   = 'QA Report - '.$project->projectName.': '.$currentCategory->categoryName;
	$email->Body      = $emailHTML;
	$email->IsHTML(true);

	$email->AddAddress( 'stephen.price@credit-suisse.com' );
	$email->AddAddress( 'sprice_D24@yahoo.com' );
	$email->AddAddress( 'stefdogd24@gmail.com' );

	$pdf_folder = $configUtil->getPDFFolder();
	$path = realpath('.');

	$day = date("j");
	$month = date("M");
	$year = date('Y');
	$pdfName = "Report_".$project->projectName."_".$currentCategory->categoryName."_".$day."_".$month."_".$year;
	
	$file_to_attach = $path.'/'.$pdf_folder.'/'.$unique_id.'.pdf';

	$email->AddAttachment( $file_to_attach , $pdfName.'.pdf' );
	
	$email->Send();

	header("Location: upload_success.php?id=".$unique_id."&projectID=".$projectID."&categoryID=".$categoryID);
	exit;
?>
</body>
</html>
