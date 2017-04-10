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
$webUtil->srcPage = "save_qa.php";
set_error_handler ( array (
		$webUtil,
		'handleError'
) );



LogUtil::debug ( "save_qa", "Starting save..");

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
$images = new Collection();

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

LogUtil::debug ( "save_qa", "Saving details for project ID = " . $projectID . ", category id = " . $categoryID . ",upload user ID = " . $uploadedUserID );

// get the data
$uploadedUser = $userDelegate->getUser ( $uploadedUserID );
$project = $projectDelegate->getProject ( $projectID );
$currentCategory = $projectDelegate->getCategory ( $projectID, $categoryID );
$categoryOptions = $projectDelegate->getCategoryOptions ( $projectID, $categoryID );
$report->clientID = $uploadedUser->clientID;
LogUtil::debug ( "save_qa", "Saving details for user = " . $uploadedUser->login . ", project = " . $project->projectName . ", category = " . $currentCategory->categoryName . ", num options = " . $categoryOptions->getNumObjects () );

LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", getting date details" );
$dateStr = $dateUtil->getCurrentDateString ();
$dateTimeStr = $dateUtil->getCurrentDateTimeString ();
$pdfName = "Report_" . $project->projectName . "_" . $currentCategory->categoryName . "_" . $dateStr;

$unique_id = $fileUtil->getFilename ( $uploadedUser, $pdfName );

LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", checking for attached images" );

// $count = count($_FILES['files']['tmp_name']);
$idx = 1;
foreach ( $_FILES as $file ) {
	$file_name = $file ['name'];
	$file_type = $file ['type'];
	$file_error = $file ['error'];
	$file_size = $file ['size'];
	$file_tmp_name = $file ['tmp_name'];
	
	LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", checking image = " . $file_name . ", size = " . $file_size . ", type = " . $file_type );
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
			LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", image " . $file_name . " OK, saving to " . $target );
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
			LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", original image width =  " . $image_info [0] . ", original height = " . $image_info [1] . ", modified image width =  " . $pdfImageWidthHeight->width . ", modified height = " . $pdfImageWidthHeight->height );
			
			$images->add ( $uploadedImage );
		} else {
			// file is not an image
			LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", File " . $file_name . " is NOT an image " );
		}
	} else {
		// file is empty
		LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", File " . $file_name . " is EMPTY " );
	}
}

//now process any pre-saved images
if (isset($_POST ["savedImageNames"]) && isset($_POST ["savedImages"]))
{
	$savedImageNames = $_POST ["savedImageNames"];
	$savedImages = $_POST ["savedImages"];
	LogUtil::debug ( "save_qa", "Saved Image Names = ".$savedImageNames);
	LogUtil::debug ( "save_qa", "Saved Images = ".$savedImages);
	if($savedImageNames != "" && $savedImages  != "")
	{
		//ensure same number of values in arrays
		$savedImageNamesArr = explode(',', $savedImageNames);
		$savedImagesArr = explode(',', $savedImages);
		$numSavedImageNames = sizeof($savedImageNamesArr);
		$numSavedImages = sizeof($savedImagesArr);
		LogUtil::debug ( "save_qa", "Num Saved Image Names = ".$numSavedImageNames);
		LogUtil::debug ( "save_qa", "Num Saved Images = ".$numSavedImages);
		if($numSavedImages == $numSavedImageNames)
		{
			for($x = 0; $x < $numSavedImages; $x++) 
			{
		    	$imageName = $savedImageNamesArr[$x];
		    	$image = $savedImagesArr[$x];
		    	$imageFile = $target_dir . "/".$image;
				LogUtil::debug ( "save_qa", "Processing Saved Image: Name = ".$imageName.", location = ".$imageFile);
				$fileExists = file_exists($imageFile);
				LogUtil::debug ( "save_qa", "File exists = ".$fileExists);
				if($fileExists)
				{
					$check = getimagesize ( $imageFile );
					if ($check !== false) {
						$image_info = getimagesize ( $imageFile );
						$image_width = $image_info [0];
						$image_height = $image_info [1];
							
						// echo "File is ok. ".$currentImage;
							
						// lets save to folder
						$uploadOk = 1;
						$info = pathinfo ( $imageName );
						$ext = $info ['extension']; // get the extension of the file
						$idx=$idx+1;
						$metaData[$imageName]=$image;
						// $target = $target_dir."/".$unique_id."/".$newname;
						// mkdir("testing");

						// create image link for web page and PDF
						$uploadedImage = new UploadedImage ();
						$uploadedImage->name = $imageName;
						$uploadedImage->imageURL = $webUtil->getBaseURI () . "/" . $imageFile;
						$uploadedImage->width = $image_info [0];
						$uploadedImage->height = $image_info [1];
							
						// perform ratio calculation if image too big
						$pdfImageWidthHeight = $pdfUtil->getBestPDFWidthHeight ( $uploadedImage );
						$uploadedImage->width = $pdfImageWidthHeight->width;
						$uploadedImage->height = $pdfImageWidthHeight->height;
						LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", original image width =  " . $image_info [0] . ", original height = " . $image_info [1] . ", modified image width =  " . $pdfImageWidthHeight->width . ", modified height = " . $pdfImageWidthHeight->height );
							
						$images->add ( $uploadedImage );
					} else {
						// file is not an image
						LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", File " . $file_name . " is NOT an image " );
					}					
				}
			}
		}
	}
}

//end process any pre-saved images



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

$hasNA = false;

while ( $categoryOption = $categoryOptions->iterate () ) 
{
	if (isset ( $_POST [$setOptionPrefix . $categoryOption->categoryOptionID] )) {
		$value = $_POST [$setOptionPrefix . $categoryOption->categoryOptionID];
		// echo $setOptionPrefix.$categoryOption->categoryOptionID.' = '.$value.'<br/>';
		if (! is_null ( $value )) {
			LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", Adding value id = " . $setOptionPrefix . $categoryOption->categoryOptionID . ", name =  " . $categoryOption->title . ", value = " . $value );
			
			$selectedOption = new SelectedOption ();
			$label = $setOptionPrefix . $categoryOption->categoryOptionID;
			$selectedOption->valueOnly = false;
			$selectedOption->formType = $categoryOption->formType;
			$selectedOption->optionFormID = $label;
			
			if ($categoryOption->formType == 'RADIO')
			{
				$radioOptions = $categoryOption->getSetting("radioOptions");
				if(!empty($radioOptions))
				{
					foreach ( $radioOptions as $radioOption )
					{
						if(StringUtils::equals($radioOption["radioOption"],$value))
						{
							$commentOn = false;
							if(isset($radioOption["commentOn"])
									&& !empty($radioOption["commentOn"])
									&& $radioOption["commentOn"] == true)
							{
								LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", Checking comment on..." );
								if (isset ( $_POST ["commentOnText_".$radioOption["radioOption"].$setOptionPrefix . $categoryOption->categoryOptionID] ))
								{
									LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", Checking comment on val is - ".$_POST ["commentOnText_".$radioOption["radioOption"].$setOptionPrefix . $categoryOption->categoryOptionID] );
									$details = $_POST ["commentOnText_".$radioOption["radioOption"].$setOptionPrefix . $categoryOption->categoryOptionID] ;
									if(!empty($details))
									{
										$selectedOption->comment = $details;
										$metaData["commentOnText_".$radioOption["radioOption"].$setOptionPrefix . $categoryOption->categoryOptionID]=$details;
									}
								}
								
							}
						}
					}
				}				
			}
			
			
			$selectedOption->optionValue = $value;
			if(StringUtils::equals($value, 'N/A'))
			{
				$hasNA = true;
			}
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
				LogUtil::debug ( "save_qa", "user = " . $uploadedUser->login . ", Setting submitted by = " .$forUser);
			}
			
			$options->add ( $selectedOption );
		}
	}
}

$report->uploadedForUser= $behalfOfUser->userID;
$report->metaData = json_encode ($metaData);

$report->reportKey= $unique_id;
$report->reportName= urlencode ( $unique_id ) . ".prelim";

LogUtil::debug ( "save_qa", "Saving preliminary report to DB" );
$reportDAO->savePreliminaryReport($report);

if (isset($_POST ["preliminaryReportID"]))
{
	$preliminaryReportID = intval ( $_POST ["preliminaryReportID"] );
	if(!empty($preliminaryReportID))
	{
		LogUtil::debug ( "save_qa", "Removing previous preliminary report" );
		$reportDAO->removePreliminaryReport($preliminaryReportID);
	}
}
	
header ( "Location: view_preliminary_reports.php?reportKey=" . $unique_id."&message=Report Saved Successfully");
exit ();
?>
</body>
</html>