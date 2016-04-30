<?php include_once("util/FileUtil.php"); ?>
<?php
	$fileUtil = new FileUtil();
	//echo $fileUtil->getNumberOfUploadFiles()
	//echo realpath('.');
	//print_r($_SERVER);
	//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	//echo $actual_link;
	//echo "$_SERVER[HTTP_HOST]";
	
	$id = $_GET['id'];
	$webUrl = $fileUtil->getWebFolder()."/".urlencode($id).".html";
	$pdfUrl = $fileUtil->getPDFFolder()."/".urlencode($id).".pdf";
	
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="css/dinamo.css">
<link rel="stylesheet" type="text/css" href="css/calendar.css">
<!--<script src="js/angular.min.js"></script>-->
<script src="js/datepickr.js"></script>
<title>Dinamo QA</title>
<body>

<div ng-app="myApp" ng-controller="myCtrl">

   `<div  class="basic-grey" style="background:white;padding:0px;border:0px">
	</div>
   <form action="uploadform.php" id="uploadform" method="post" class="basic-grey" enctype="multipart/form-data">
   <div id="slab-form">
      <h1>
      		<table style="border:0px">
			<tr>
				<td style="vertical-align:top"><img src="img/dinamo_small.png"/></td>
				<td><h1>Upload Success</h1></td>
			</tr>
		</table>
	  </h1>
      <p>
		<table style="border:0px">
		<tr>
		
		</tr>	
		<tr>
			<td>Web Preview</td>
			<td><?php echo "Web prevew is available <a href='".$webUrl."' target='_blank'>here</a>"?></td>
		</tr>	
		<tr>
			<td>PDF Preview</td>
			<td><?php echo "Web prevew is available <a href='".$pdfUrl."' target='_blank'>here</a>"?></td>
		</tr>	
	</table>
	</p>
   </div>
   
</body>

<script>
function switchForms(show,hide)
{
	document.getElementById(show+'-form').style.display='';
	document.getElementById(hide+'-form').style.display='none';
	document.getElementById(show+'-span').className = "button-selected";
	document.getElementById(hide+'-span').className = "button-not-selected";
	document.getElementById(show+'-link').style.color='black';
	document.getElementById(hide+'-link').style.color='#888';
}

new datepickr('pourDate', {
	'dateFormat': 'd-M-Y'
});

function displayPhotos()
{
	if(document.getElementById('photosRequired').checked)
	{
		document.getElementById('photosRow').style.display=''
	}else{
		document.getElementById('photosRow').style.display='none'
	}
}

var photoCount = 3;
var maxPhotos = 12;

function addMorePhotos()
{
	if(photoCount == maxPhotos)
	{
		alert('Only 12 photos can be submitted');
	}
	else{
		photoCount++;
		document.getElementById('photoRow'+photoCount).style.display='';
		photoCount++;
		document.getElementById('photoRow'+photoCount).style.display='';
		photoCount++;
		document.getElementById('photoRow'+photoCount).style.display='';
	}
}


function submitForm()
{
	//do validation here
	document.getElementById('uploadform').submit();
}

/*
var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope) {
    $scope.firstName= "John";
    $scope.lastName= "Doe";
});*/
</script>
</html>
