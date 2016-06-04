<html>
<body>
<?php include_once("util/FileUtil.php"); ?>
<?php include_once("util/HTMLUtil.php"); ?>
<?php include_once("util/PDFUtil.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("beans/SelectedOption.php"); ?>
<?php include_once("beans/UploadedImage.php"); ?>
<?php include_once("beans/PDFImageWidthHeight.php"); ?>
<?php require_once('tcpdf/tcpdf.php');?>
<?php require_once('mail/PHPMailer.php');?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/HTMLConst.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("dao/model/Report.php"); ?>
<?php include_once("dao/ReportDAO.php"); ?>

<?php

$webUtil = new WebUtil ();
$webUtil->srcPage = "submit_qa.php";
// set_error_handler(array($webUtil, 'handleError'));

$reportDAO = new ReportDAO();
$report = new Report();

// print_r($_FILES);
$pdfUtil = new PDFUtil ();
$fileUtil = new FileUtil ();
$dateUtil = new DateUtil ();

$target_dir = ConfigUtil::getImageFolder ();
$num_images = ConfigUtil::getNumberOfUploadFiles ();

$setOptionPrefix = HTMLConst::STANDARD_OPT_ID_PREFIX;
$projectDelegate = new ProjectDelegate ();
$userDelegate = new UserDelegate ();

// add images to collection
$images = new Collection ();

$metaData = array();
// echo "checking files";
// loop through possible uploaded files and save

// print_r($_FILES);

// get the uploaded user
$uploadedUserID = intval ( $_POST ["uploadedBy"] );
$projectID = intval ( $_POST ["projectID"] );
$categoryID = intval ( $_POST ["categoryID"] );

if(empty($projectID) || empty($categoryID))
{
	header ( "Location: select_qa.php");
}

$report->categoryID = $categoryID;
$report->projectID = $projectID;
$report->uploadedBy= $uploadedUserID;

LogUtil::debug ( "submit_qa", "Uploading details for project ID = " . $projectID . ", category id = " . $categoryID . ",upload user ID = " . $uploadedUserID );

// get the data
$uploadedUser = $userDelegate->getUser ( $uploadedUserID );
$project = $projectDelegate->getProject ( $projectID );
$currentCategory = $projectDelegate->getCategory ( $projectID, $categoryID );
$categoryOptions = $projectDelegate->getCategoryOptions ( $projectID, $categoryID );

LogUtil::debug ( "submit_qa", "Submitting details for user = " . $uploadedUser->login . ", project = " . $project->projectName . ", category = " . $currentCategory->categoryName . ", num options = " . $categoryOptions->getNumObjects () );

LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", getting date details" );
$dateStr = $dateUtil->getCurrentDateString ();
$dateTimeStr = $dateUtil->getCurrentDateTimeString ();
$pdfName = "Report_" . $project->projectName . "_" . $currentCategory->categoryName . "_" . $dateStr;
LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", pdf shall be = " . $pdfName );

$unique_id = $fileUtil->getFilename ( $uploadedUser, $pdfName );

LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", checking for attached images" );

// $count = count($_FILES['files']['tmp_name']);
$idx = 1;
foreach ( $_FILES as $file ) {
	$file_name = $file ['name'];
	$file_type = $file ['type'];
	$file_error = $file ['error'];
	$file_size = $file ['size'];
	$file_tmp_name = $file ['tmp_name'];
	
	LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", checking image = " . $file_name . ", size = " . $file_size . ", type = " . $file_type );
	if ($file_size) {
		$check = getimagesize ( $file_tmp_name );
		if ($check !== false) {
			$image_info = getimagesize ( $file_tmp_name );
			$image_width = $image_info [0];
			$image_height = $image_info [1];
			
			// echo "File is ok. ".$currentImage;
			
			// lets save to folder
			$uploadOk = 1;
			$info = pathinfo ( $file_name );
			$ext = $info ['extension']; // get the extension of the file
			$newname = "photo_" . $idx . "_" . $unique_id . "." . $ext;
			$idx=$idx+1;
			$metaData[$file_name]=$newname;
			// $target = $target_dir."/".$unique_id."/".$newname;
			// mkdir("testing");
			$target = $target_dir . "/" . $newname;
			LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", image " . $file_name . " OK, saving to " . $target );
			// echo $target_dir;
			move_uploaded_file ( $file_tmp_name, $target );
			
			// create image link for web page and PDF
			$uploadedImage = new UploadedImage ();
			$uploadedImage->name = $newname;
			$uploadedImage->imageURL = $webUtil->getBaseURI () . "/" . $target;
			$uploadedImage->width = $image_info [0];
			$uploadedImage->height = $image_info [1];
			
			// perform ratio calculation if image too big
			$pdfImageWidthHeight = $pdfUtil->getBestPDFWidthHeight ( $uploadedImage );
			$uploadedImage->width = $pdfImageWidthHeight->width;
			$uploadedImage->height = $pdfImageWidthHeight->height;
			LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", original image width =  " . $image_info [0] . ", original height = " . $image_info [1] . ", modified image width =  " . $pdfImageWidthHeight->width . ", modified height = " . $pdfImageWidthHeight->height );
			
			$images->add ( $uploadedImage );
		} else {
			// file is not an image
			LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", File " . $file_name . " is NOT an image " );
		}
	} else {
		// file is empty
		LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", File " . $file_name . " is EMPTY " );
	}
}

$options = new Collection ();

/*
 * //$value = $_POST['subject'];
 * foreach ($_POST as $key => $value) {
 * $selectedOption = new SelectedOption();
 * $selectedOption->optionFormID = $key;
 * $selectedOption->optionValue = $value;
 * //TODO get label from Database
 * $options->add($selectedOption);
 * }
 */

$selectedOption = new SelectedOption ();
$selectedOption->valueOnly = false;
$selectedOption->optionFormID = "Project";
$selectedOption->optionValue = $project->projectName;
$options->add ( $selectedOption );

$selectedOption = new SelectedOption ();
$selectedOption->valueOnly = false;
$selectedOption->optionFormID = "Submitted By";
$selectedOption->optionValue = $uploadedUser->name;
$options->add ( $selectedOption );

$selectedOption = new SelectedOption ();
$selectedOption->valueOnly = false;
$selectedOption->optionFormID = "Submitted On";
$selectedOption->optionValue = $dateTimeStr;
$options->add ( $selectedOption );

$forUser = "";
$behalfOfUser = $uploadedUser;

while ( $categoryOption = $categoryOptions->iterate () ) 
{
	if (isset ( $_POST [$setOptionPrefix . $categoryOption->categoryOptionID] )) {
		$value = $_POST [$setOptionPrefix . $categoryOption->categoryOptionID];
		// echo $setOptionPrefix.$categoryOption->categoryOptionID.' = '.$value.'<br/>';
		if (! is_null ( $value )) {
			LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Adding value id = " . $setOptionPrefix . $categoryOption->categoryOptionID . ", name =  " . $categoryOption->title . ", value = " . $value );
			
			$selectedOption = new SelectedOption ();
			$label = $categoryOption->title;
			$selectedOption->valueOnly = false;
			$selectedOption->formType = $categoryOption->formType;
			$pdfTitle = $categoryOption->getSetting("pdfTitle");
			if (! is_null ( $pdfTitle ) && ! empty ( $pdfTitle )) {
				$label = $pdfTitle;
			}
			$selectedOption->optionFormID = $label;
			
			$commentOn = $categoryOption->getSetting("commentOn");
	 		if (! empty ( $commentOn ))
	 		{
				LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Checking comment on..." );
				if (isset ( $_POST ["commentOnText_".$setOptionPrefix . $categoryOption->categoryOptionID] ))
				{
					LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Checking comment on val is - ".$_POST ["commentOnText_".$setOptionPrefix . $categoryOption->categoryOptionID] );
					$details = $_POST ["commentOnText_".$setOptionPrefix . $categoryOption->categoryOptionID] ;
					if(!empty($details))
					{
						$value = $value.": ".$details;
					}
				}
			}
			$selectedOption->optionValue = $value;
				
			$metaData[$label]=$value;
			
			if ($categoryOption->formType == 'CONFIRM') 

			{
				$selectedOption->valueOnly = true;
				$selectedOption->optionValue = $label;
			}
			if ($categoryOption->formType == 'USERLIST' && $categoryOption->title == 'Submitted By')
			
			{
				$behalfOfUser = $userDelegate->getUser($value);
				$forUser = $userDelegate->getUser($value)->name;
				LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Setting submitted by = " .$forUser);
			}
			
			$options->add ( $selectedOption );
		}
	}
}

$report->uploadedForUser= $behalfOfUser->userID;
$report->metaData = json_encode ($metaData);

$htmlUtil = new HTMLUtil ();

LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Generating HTML, id = " . $unique_id );
$optionsHTML = $htmlUtil->getOptionsTable ( $options );
$imageHTML = $htmlUtil->getImageTable ( $images );
$html = "<html><body> ";
$html = $html . $optionsHTML;
$html = $html . '<br/><br/><h2>PHOTOS</h2><hr/><br/>';
$html = $html . $imageHTML;
$html = $html . " </html></body>";

$link = $fileUtil->saveHTMLToWebFile ( $html, $unique_id );
echo "<a href='" . $link . ">" . $link . "</a>";
LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Generating PDF, id = " . $unique_id );

$pdfUtil->generatePDF ( $optionsHTML, $imageHTML, $unique_id, 'Quality Assurance - ' . $currentCategory->categoryName );

// now send the email
$email = new PHPMailer ();

$webUrl = ConfigUtil::getWebFolder () . "/" . urlencode ( $unique_id ) . ".html";
$pdfUrl = ConfigUtil::getPDFFolder () . "/" . urlencode ( $unique_id ) . ".pdf";

$webUrl = $webUtil->getBaseURI () . "/" . $webUrl;
$pdfUrl = $webUtil->getBaseURI () . "/" . $pdfUrl;

$report->pdfURL= $pdfUrl;
$report->webURL= $webUrl;
$report->reportKey= $unique_id;
$report->reportName= urlencode ( $unique_id ) . ".pdf";

LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Generating Email, Web URL = " . $webUtil->getBaseURI () . "/" . $webUrl );
$emailHTML = $htmlUtil->generateUploadEmail ( $webUrl, $pdfUrl, $project, $currentCategory, $uploadedUser, $forUser );

$email->From = $uploadedUser->email;
$email->FromName = $uploadedUser->name;
$email->Subject = 'QA Report - ' . $project->projectName . ': ' . $currentCategory->categoryName;
$email->Body = $emailHTML;
$email->IsHTML ( true );

$recipients = "";
$allUsers = $userDelegate->getAllUsers();
$emailRecipients = array();

while ( $user = $allUsers->iterate () )
{
	if($user->hasRole('recipient'))
	{
		LogUtil::debug ( "submit_qa", "Adding email recipient = " . $user->login . " [".$user->email."], user has role 'recipient'");
		$email->AddAddress ($user->email);
		array_push($emailRecipients,$user->email);
		if($recipients != '')
		{
			$recipients = $recipients.", ";
		}
		$recipients = $recipients . $user->email;
	}
}

$email->addBCC(ConfigUtil::getDinamoSupportEmail());

//check if we need to CC uploaded
if (!in_array($uploadedUser->email, $emailRecipients)) 
{
	LogUtil::debug ( "submit_qa", "Adding email CC recipient = " . $uploadedUser->login . " [".$uploadedUser->email."], user uploaded report");
	$email->addCC($uploadedUser->email);
}
if (!empty($behalfOfUser) && $behalfOfUser->userID != $uploadedUser->userID
		&& !in_array($behalfOfUser->email, $emailRecipients))
{
	LogUtil::debug ( "submit_qa", "Adding email CC recipient = " . $behalfOfUser->login . " [".$behalfOfUser->email."], user is listed as submitter of report");
	$email->addCC($behalfOfUser->email);
}

$pdf_folder = ConfigUtil::getPDFFolder ();
$path = realpath ( '.' );

$file_to_attach = $path . '/' . $pdf_folder . '/' . $unique_id . '.pdf';

LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Generating Email, Add attachment = " . $file_to_attach );
$email->AddAttachment ( $file_to_attach, $pdfName . '.pdf' );

LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Sending Email to " . $recipients );

if ($webUtil->isProduction ()& !empty($project->projectName)) {
	$email->Send ();
} else {
	LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Not Sending Email to " . $recipients . " as not in PRODUCTION" );
}
LogUtil::debug ( "submit_qa", "user = " . $uploadedUser->login . ", Sent Email OK" );

LogUtil::debug ( "submit_qa", "Saving report to DB" );
$reportDAO->saveReport($report);
LogUtil::debug ( "submit_qa", "Saved report to DB" );

header ( "Location: upload_success.php?id=" . $unique_id . "&projectID=" . $projectID . "&categoryID=" . $categoryID );
exit ();
?>
</body>
</html>