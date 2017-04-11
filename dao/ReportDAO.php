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
	
	function getAllPreliminaryReports($clientID) 
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		
		$sql = "SELECT * from PreliminaryReport where DeleteFlag = 0 and ClientID = ".$clientID." order by ReportID desc";
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
		    	$report->reportKey = $row["ReportKey"];
		    	//$report->reportName = $row["reportName"];
		    	$report->uploadedBy = $row["UploadedBy"];
		    	$report->uploadedForUser = $row["UploadedForUser"];
		    	$report->uploadedDateString = $row["UploadedDateString"];
		    	$report->uploadedDate = $row["UploadedDate"];
		    	$report->metaData = $row["MetaData"];
		    	$report->parentID = $row["ParentID"];
				$reports->add ( $report );
		
		    }
		} 
		$conn->close();

		return $reports;
	}
	
	function getAllPreliminaryReportsForUser($clientID, $userID) 
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		
		$sql = "SELECT * from PreliminaryReport where DeleteFlag = 0 and ClientID = ".$clientID." and UploadedForUser = ".$userID." order by ReportID desc";
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
		    	$report->reportKey = $row["ReportKey"];
		    	//$report->reportName = $row["reportName"];
		    	$report->uploadedBy = $row["UploadedBy"];
		    	$report->uploadedForUser = $row["UploadedForUser"];
		    	$report->uploadedDateString = $row["UploadedDateString"];
		    	$report->uploadedDate = $row["UploadedDate"];
		    	$report->metaData = $row["MetaData"];
		    	$report->parentID = $row["ParentID"];
		    	$reports->add ( $report );
		
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

	function savePreliminaryReport($report)
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
	
		$sql = "insert into PreliminaryReport(UploadedDate,ProjectID,ClientID,CategoryID,ReportKey,UploadedBy,UploadedDateString,UploadedForUser,DeleteFlag,ParentID,MetaData) ";
		$sql = $sql." values (now(),".$report->projectID.",".$report->clientID.",".$report->categoryID.",'".StringUtils::escapeDB($report->reportKey)."',".$report->uploadedBy.",'".$dateUtil->getCurrentDateTimeString()."',".$report->uploadedForUser.",0,".$report->parentID.",'".StringUtils::escapeDB($report->metaData)."')";
	
		if ($conn->query($sql) === TRUE) {
			return "New record created successfully";
		} else {
			return "Error: " . $sql . "<br>" . $conn->error;
		}
	
	}
	
	function removePreliminaryReport($reportID)
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
	
		$sql = " update PreliminaryReport set DeleteFlag = 1 where ReportID = ".$reportID;
	
		if ($conn->query($sql) === TRUE) {
			return "Removed report";
		} else {
			return "Error: " . $sql . "<br>" . $conn->error;
		}
	
	}

	function getPreliminaryReportByID($reportID)
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
	
		$sql = "SELECT * from PreliminaryReport where DeleteFlag = 0 and ReportID = ".$reportID." order by ReportID desc";
		$result = $conn->query($sql);
	
		$report = new Report();

		// replace with call to Database
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$report->reportID = $row["ReportID"];
				$report->categoryID = $row["CategoryID"];
				$report->projectID = $row["ProjectID"];
				$report->reportKey = $row["ReportKey"];
				//$report->reportName = $row["reportName"];
				$report->uploadedBy = $row["UploadedBy"];
				$report->uploadedForUser = $row["UploadedForUser"];
				$report->uploadedDateString = $row["UploadedDateString"];
				$report->uploadedDate = $row["UploadedDate"];
				$report->metaData = $row["MetaData"];
			}
		}
		$conn->close();
	
		return $report;
	}
	
}

?>
