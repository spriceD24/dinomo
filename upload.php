<?php include("util/FileUtil.php"); ?>
<?php
	$fileUtil = new FileUtil();
	//echo $fileUtil->getNumberOfUploadFiles()
	
	//print_r($_SERVER);
	//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	//echo $actual_link;
	//echo "$_SERVER[HTTP_HOST]";
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
	<span class="button-selected" id="slab-span" name="slab-span"><a href="#" onclick="switchForms('slab','vertical')" id="slab-link" style="color:black;text-decoration: none;">&nbsp;Slab&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span>
	<span class="button-not-selected" id="vertical-span" name="vertical-span"><a href="#" onclick="switchForms('vertical','slab')" id="vertical-link" style="color:#888;text-decoration: none;">&nbsp;Verticals&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></span>
	</div>
   <form action="uploadform.php" id="uploadform" method="post" class="basic-grey" enctype="multipart/form-data">
   <div id="slab-form">
      <h1>
      		<table style="border:0px">
			<tr>
				<td style="vertical-align:top"><img src="img/dinamo_small.png"/></td>
				<td><h1>Quality Assurance<br/>Pre Pour Checklist</h1></td>
			</tr>
		</table>
	  </h1>
      <p>
		<table style="border:0px">
		<tr>
			<td><label><span>Project Name:</span></label></td>
			<td><label><input id="projectName" type="text" name="projectName" placeholder="Project Name" style="width:250px"/></label></td>
		</tr>	
		<tr>
			<td><label><span>Pour No:</span></label></td>
			<td><label><input id="pourNumber" type="text" name="pourNumber" style="width:100px"/></label></td>
		</tr>	
		<tr>
			<td><label><span>Pour Date:</span></label></td>
			<td><input id="pourDate" style="width:100px;" class="dinamo-date"/></span></td>
		</tr>	
		<tr>
			<td><label><span>Temperature:</span></label></td>
			<td><label><input id="temperature" type="text" name="temperature" style="width:100px"/></label></td>
		</tr>	
		<tr>
			<td><label><span>Start Of Pour:</span></label></td>
			<td>
			<label>
				<select name="startOfConcretePour" style="width:100px;height:25px">
					<option value=''></option>
					<option value='05:00'>05:00</option>
					<option value='05:15'>05:15</option>
					<option value='05:30'>05:30</option>
					<option value='05:45'>05:45</option>
					<option value='06:00'>06:00</option>
					<option value='06:15'>06:15</option>
					<option value='06:30'>06:30</option>
					<option value='06:45'>06:45</option>
					<option value='07:00'>07:00</option>
					<option value='07:15'>07:15</option>
					<option value='07:30'>07:30</option>
					<option value='07:45'>07:45</option>
					<option value='08:00'>08:00</option>
					<option value='08:15'>08:15</option>
					<option value='08:30'>08:30</option>
					<option value='08:45'>08:45</option>
					<option value='09:00'>09:00</option>
					<option value='09:15'>09:15</option>
					<option value='09:30'>09:30</option>
					<option value='09:45'>09:45</option>
					<option value='10:00'>10:00</option>
					<option value='10:15'>10:15</option>
					<option value='10:30'>10:30</option>
					<option value='10:45'>10:45</option>
					<option value='11:00'>11:00</option>
					<option value='11:15'>11:15</option>
					<option value='11:30'>11:30</option>
					<option value='11:45'>11:45</option>
					<option value='12:00'>12:00</option>
					<option value='12:15'>12:15</option>
					<option value='12:30'>12:30</option>
					<option value='12:45'>12:45</option>
					<option value='13:00'>13:00</option>
					<option value='13:15'>13:15</option>
					<option value='13:30'>13:30</option>
					<option value='13:45'>13:45</option>
					<option value='14:00'>14:00</option>
					<option value='14:15'>14:15</option>
					<option value='14:30'>14:30</option>
					<option value='14:45'>14:45</option>
					<option value='15:00'>15:00</option>
					<option value='15:15'>15:15</option>
					<option value='15:30'>15:30</option>
					<option value='15:45'>15:45</option>
					<option value='16:00'>16:00</option>
					<option value='16:15'>16:15</option>
					<option value='16:30'>16:30</option>
					<option value='16:45'>16:45</option>
					<option value='17:00'>17:00</option>
					<option value='17:15'>17:15</option>
					<option value='17:30'>17:30</option>
					<option value='17:45'>17:45</option>
					<option value='18:00'>18:00</option>
					<option value='18:15'>18:15</option>
					<option value='18:30'>18:30</option>
					<option value='18:45'>18:45</option>
					<option value='19:00'>19:00</option>
					<option value='19:15'>19:15</option>
					<option value='19:30'>19:30</option>
					<option value='19:45'>19:45</option>
					<option value='20:00'>20:00</option>
					<option value='20:15'>20:15</option>
					<option value='20:30'>20:30</option>
					<option value='20:45'>20:45</option>
					<option value='21:00'>21:00</option>
					<option value='21:15'>21:15</option>
					<option value='21:30'>21:30</option>
					<option value='21:45'>21:45</option>
					<option value='22:00'>22:00</option>
					<option value='22:15'>22:15</option>
					<option value='22:30'>22:30</option>
					<option value='22:45'>22:45</option>
					<option value='23:00'>23:00</option>
				</select>
			</label>
			</td>
		</tr>	
		<tr>
			<td><label><span>Finish Of Pour:</span></label></td>
			<td>
			<label>
				<select name="finishOfConcretePour" style="width:100px;height:25px">
					<option value=''></option>
					<option value='05:00'>05:00</option>
					<option value='05:15'>05:15</option>
					<option value='05:30'>05:30</option>
					<option value='05:45'>05:45</option>
					<option value='06:00'>06:00</option>
					<option value='06:15'>06:15</option>
					<option value='06:30'>06:30</option>
					<option value='06:45'>06:45</option>
					<option value='07:00'>07:00</option>
					<option value='07:15'>07:15</option>
					<option value='07:30'>07:30</option>
					<option value='07:45'>07:45</option>
					<option value='08:00'>08:00</option>
					<option value='08:15'>08:15</option>
					<option value='08:30'>08:30</option>
					<option value='08:45'>08:45</option>
					<option value='09:00'>09:00</option>
					<option value='09:15'>09:15</option>
					<option value='09:30'>09:30</option>
					<option value='09:45'>09:45</option>
					<option value='10:00'>10:00</option>
					<option value='10:15'>10:15</option>
					<option value='10:30'>10:30</option>
					<option value='10:45'>10:45</option>
					<option value='11:00'>11:00</option>
					<option value='11:15'>11:15</option>
					<option value='11:30'>11:30</option>
					<option value='11:45'>11:45</option>
					<option value='12:00'>12:00</option>
					<option value='12:15'>12:15</option>
					<option value='12:30'>12:30</option>
					<option value='12:45'>12:45</option>
					<option value='13:00'>13:00</option>
					<option value='13:15'>13:15</option>
					<option value='13:30'>13:30</option>
					<option value='13:45'>13:45</option>
					<option value='14:00'>14:00</option>
					<option value='14:15'>14:15</option>
					<option value='14:30'>14:30</option>
					<option value='14:45'>14:45</option>
					<option value='15:00'>15:00</option>
					<option value='15:15'>15:15</option>
					<option value='15:30'>15:30</option>
					<option value='15:45'>15:45</option>
					<option value='16:00'>16:00</option>
					<option value='16:15'>16:15</option>
					<option value='16:30'>16:30</option>
					<option value='16:45'>16:45</option>
					<option value='17:00'>17:00</option>
					<option value='17:15'>17:15</option>
					<option value='17:30'>17:30</option>
					<option value='17:45'>17:45</option>
					<option value='18:00'>18:00</option>
					<option value='18:15'>18:15</option>
					<option value='18:30'>18:30</option>
					<option value='18:45'>18:45</option>
					<option value='19:00'>19:00</option>
					<option value='19:15'>19:15</option>
					<option value='19:30'>19:30</option>
					<option value='19:45'>19:45</option>
					<option value='20:00'>20:00</option>
					<option value='20:15'>20:15</option>
					<option value='20:30'>20:30</option>
					<option value='20:45'>20:45</option>
					<option value='21:00'>21:00</option>
					<option value='21:15'>21:15</option>
					<option value='21:30'>21:30</option>
					<option value='21:45'>21:45</option>
					<option value='22:00'>22:00</option>
					<option value='22:15'>22:15</option>
					<option value='22:30'>22:30</option>
					<option value='22:45'>22:45</option>
					<option value='23:00'>23:00</option>
				</select>
			</label>
			</td>
		</tr>	
		<tr>
			<td><label><span style="margin-bottom:0px">Attach Report:</span></label></td>
			<td><input type="file"  name="reportFile" placeholder="Select Report" style="width:250px"/></td>
		</tr>
		<tr>
			<td><label><span style="margin-top:15px">Photos Required:</span></label></td>
			<td>
				<input type="checkbox" name="photosRequired" id="photosRequired" value="Yes" onchange="displayPhotos()">
			</td>
		</tr>
		<tr id="photosRow" style="display:none;vertical-align:top">
			<td><label><span style="margin-top:5px">Photos:</span></label></td>
			<td>
				<table>
					<tr>
						<td>1.</td>
						<td>
							<input type="file"  name="photo1" id="photo1" style="width:250px"/>
						</td>
					</tr>
					<tr>
						<td>2.</td>
						<td>
							<input type="file"  name="photo2"  id="photo2" style="width:250px"/>
						</td>
					</tr>
					<tr>
						<td>3.</td>
						<td>
							<input type="file"  name="photo3"  id="photo3" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow4" style="display:none">
						<td>4.</td>
						<td>
							<input type="file"  name="photo4"  id="photo4" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow5" style="display:none">
						<td>5.</td>
						<td>
							<input type="file"  name="photo5"  id="photo5" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow6" style="display:none">
						<td>6.</td>
						<td>
							<input type="file"  name="photo6"  id="photo6" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow7" style="display:none">
						<td>7.</td>
						<td>
							<input type="file"  name="photo7"  id="photo7" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow8" style="display:none">
						<td>8.</td>
						<td>
							<input type="file"  name="photo8"  id="photo8" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow9" style="display:none">
						<td>9.</td>
						<td>
							<input type="file"  name="photo9"  id="photo9" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow10" style="display:none">
						<td>10.</td>
						<td>
							<input type="file"  name="photo10"  id="photo10" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow11" style="display:none">
						<td>11.</td>
						<td>
							<input type="file"  name="photo11"  id="photo11" style="width:250px"/>
						</td>
					</tr>
					<tr id="photoRow12" style="display:none">
						<td>12.</td>
						<td>
							<input type="file"  name="photo12"  id="photo12" style="width:250px"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td style="font-size:11px">
							<button type="button" onclick="addMorePhotos()">+ Add More Photos</button>
						</td>
					</tr>
					</table>		
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td colspan="2"><i></b>CAST INS</i></b></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		</table>
		<table style="border:0px">
		<tr>
		   <td><label><span style='margin-top:15px'>Hob for post fix spandrals and cast ins:</span></label></td>
		   <td><input type='checkbox' name='Hobforpostfixspandralsandcastins' id='Hobforpostfixspandralsandcastins' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Construction joints / dowels:</span></label></td>
		   <td><input type='checkbox' name='Constructionjoints/dowels' id='Constructionjoints/dowels' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Wet area and balcony stepdowns:</span></label></td>
		   <td><input type='checkbox' name='Wetareaandbalconystepdowns' id='Wetareaandbalconystepdowns' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>RL Deck / Edgeboard:</span></label></td>
		   <td><input type='checkbox' name='RLDeck/Edgeboard' id='RLDeck/Edgeboard' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Overflow blockouts:</span></label></td>
		   <td><input type='checkbox' name='Overflowblockouts' id='Overflowblockouts' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Penetrations for services:</span></label></td>
		   <td><input type='checkbox' name='Penetrationsforservices' id='Penetrationsforservices' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Bond breaker applied:</span></label></td>
		   <td><input type='checkbox' name='Bondbreakerapplied' id='Bondbreakerapplied' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Ableflex fixed:</span></label></td>
		   <td><input type='checkbox' name='Ableflexfixed' id='Ableflexfixed' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Sheer Keys:</span></label></td>
		   <td><input type='checkbox' name='SheerKeys' id='SheerKeys' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Pegs Removed:</span></label></td>
		   <td><input type='checkbox' name='PegsRemoved' id='PegsRemoved' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Drip groove:</span></label></td>
		   <td><input type='checkbox' name='Dripgroove' id='Dripgroove' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Precast fillet:</span></label></td>
		   <td><input type='checkbox' name='Precastfillet' id='Precastfillet' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>CJ fillet:</span></label></td>
		   <td><input type='checkbox' name='CJfillet' id='CJfillet' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Foam filler to CJ:</span></label></td>
		   <td><input type='checkbox' name='FoamfillertoCJ' id='FoamfillertoCJ' value='Yes'></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		</table>
		<table style="border:0px">
		<tr>
		   <td><label style="margin-bottom:0px"><span style="margin-bottom:0px"><b>Pre-Construction</b></span></label></td>
		   <td><label style="margin-bottom:0px"><span style="margin-bottom:0px"><b>Drawing No.</b></span></label></td>
		</tr>
		<tr>
		   <td><label style="margin-bottom:0px"><span style="margin-bottom:0px">Architects GA/RCP</span></label></td>
		   <td><label style="margin-top:5px;margin-bottom:0px"><span style="margin-top:5px;margin-bottom:0px"><input id="temperature" type="text" name="architectsGARCP" style="width:150px"/></span></label></td>
		</tr>
		<tr>
		   <td><label style="margin-bottom:0px"><span style="margin-bottom:0px">Architect's CS/WS</span></label></td>
		   <td><label style="margin-top:5px;margin-bottom:0px"><span style="margin-top:5px;margin-bottom:0px"><input id="temperature" type="text" name="architectsCSWS" style="width:150px"/></span></label></td>
		</tr>
		<tr>
		   <td><label style="margin-bottom:0px"><span style="margin-bottom:0px">Structural Outline</span></label></td>
		   <td><label style="margin-top:5px;margin-bottom:0px"><span style="margin-top:5px;margin-bottom:0px"><input id="structuralOutline" type="text" name="architectsCSWS" style="width:150px"/></span></label></td>
		</tr>
		<tr>
		   <td><label style="margin-bottom:0px"><span style="margin-bottom:0px">Formwork Certificate</span></label></td>
		   <td><label style="margin-top:5px;margin-bottom:0px"><span style="margin-top:5px;margin-bottom:0px"><input id="formworkCertificate" type="text" name="architectsCSWS" style="width:150px"/></span></label></td>
		</tr>
		</table>
	</p>
   </div>
   <div id="vertical-form" style="display:none">
      <h1>
		<table style="border:0px">
			<tr>
				<td style="vertical-align:top"><img src="img/dinamo_small.png"/></td>
				<td><h1>Quality Assurance<br/>Verticals</h1></td>
			</tr>
		</table>
	  </h1>
      <p>
	  <table style="border:0px">
		<tr>
			<td><label><span>Location:</span></label></td>
			<td><label><input id="location" type="text" name="location" placeholder="Enter Location" style="width:250px"/></label></td>
		</tr>	
		<tr>
		   <td colspan="2"><hr/></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Length:</span></label></td>
		   <td><input type='checkbox' name='Length' id='Length' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Width:</span></label></td>
		   <td><input type='checkbox' name='Width' id='Width' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Fillets:</span></label></td>
		   <td><input type='checkbox' name='Fillets' id='Fillets' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Spacers:</span></label></td>
		   <td><input type='checkbox' name='Spacers' id='Spacers' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Nail heights sprayed:</span></label></td>
		   <td><input type='checkbox' name='Nailheightssprayed' id='Nailheightssprayed' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Penetrations for services:</span></label></td>
		   <td><input type='checkbox' name='Penetrationsforservices' id='Penetrationsforservices' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Bond breaker applied:</span></label></td>
		   <td><input type='checkbox' name='Bondbreakerapplied' id='Bondbreakerapplied' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Plumb:</span></label></td>
		   <td><input type='checkbox' name='Plumb' id='Plumb' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Post pour plumb:</span></label></td>
		   <td><input type='checkbox' name='Postpourplumb' id='Postpourplumb' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Rebates:</span></label></td>
		   <td><input type='checkbox' name='Rebates' id='Rebates' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Bracing:</span></label></td>
		   <td><input type='checkbox' name='Bracing' id='Bracing' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Props:</span></label></td>
		   <td><input type='checkbox' name='Props' id='Props' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>She-Bolts tied off:</span></label></td>
		   <td><input type='checkbox' name='She-Boltstiedoff' id='She-Boltstiedoff' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Working platform:</span></label></td>
		   <td><input type='checkbox' name='Workingplatform' id='Workingplatform' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Access Ladder:</span></label></td>
		   <td><input type='checkbox' name='AccessLadder' id='AccessLadder' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Foam filler required:</span></label></td>
		   <td><input type='checkbox' name='Foamfillerrequired' id='Foamfillerrequired' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Z bars tied off:</span></label></td>
		   <td><input type='checkbox' name='Zbarstiedoff' id='Zbarstiedoff' value='Yes'></td>
		</tr>
		<tr>
		   <td><label><span style='margin-top:15px'>Stop end nailed:</span></label></td>
		   <td><input type='checkbox' name='Stopendnailed' id='Stopendnailed' value='Yes'></td>
		</tr>		
		<tr>
		   <td colspan="2"><hr/></td>
		</tr>
		<tr>
		   <td colspan="2"><label><span>Comments:</span></label></td>
		</tr>
		<tr>
		   <td colspan="2"><textarea name="comments" rows=10 style="width:250px"></textarea></td>
		</tr>
	  </table>
   </form>
</div>
<div>
   `<div  class="basic-grey" style="background:white;padding:0px;border:0px">
		<label><input type="button" class="button" value="Submit Details" onclick="submitForm()"></label>
	</div>
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
