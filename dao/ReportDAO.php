<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("util/DBUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php include_once("dao/model/Report.php"); ?>
<?php

class ReportDAO {
	
	function getAllReports($clientID) 
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		
		$sql = "SELECT * from Report where DeleteFlag = 0 and ClientID = ".$clientID." order by ReportID desc";
		$result = $conn->query($sql);
		
		$reports = new Collection ();
		
		// replace with call to Database
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	$report = new Report();
		    	$report->reportID = $row["ReportID"];
		    	$report->categoryID = $row["CategoryID"];
		    	$report->projectID = $row["ProjectID"];
		    	$report->pdfURL = $row["PDFUrl"];
		    	$report->webURL = $row["WebUrl"];
		    	$report->reportKey = $row["ReportKey"];
		    	//$report->reportName = $row["reportName"];
		    	$report->uploadedBy = $row["UploadedBy"];
		    	$report->uploadedForUser = $row["UploadedForUser"];
		    	$report->uploadedDateString = $row["UploadedDateString"];
		    	$report->uploadedDate = $row["UploadedDate"];
		    	$report->metaData = $row["MetaData"];
				$reports->add ( $report );
		
		        //echo "id: " . $row["ReportID"]. " - Name: " . $row["ReportKey"]. " " . $row["UploadedDateString"]. "<br>";
		    }
		} 
		$conn->close();

		return $reports;
	}
	
	function saveReport($report) 
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
		
		$sql = "insert into Report(UploadedDate,ProjectID,ClientID,CategoryID,ReportKey,UploadedBy,UploadedDateString,UploadedForUser,PDFUrl,WebUrl,DeleteFlag,MetaData) ";
		$sql = $sql." values (now(),".$report->projectID.",".$report->clientID.",".$report->categoryID.",'".StringUtils::escapeDB($report->reportKey)."',".$report->uploadedBy.",'".$dateUtil->getCurrentDateTimeString()."',".$report->uploadedForUser.",'".StringUtils::escapeDB($report->pdfURL)."','".StringUtils::escapeDB($report->webURL)."',0,'".StringUtils::escapeDB($report->metaData)."')";
		
		if ($conn->query($sql) === TRUE) {
			return "New record created successfully";
		} else {
			return "Error: " . $sql . "<br>" . $conn->error;
		}
		
	}
}

?>
