<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("util/DBUtil.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php include_once("dao/model/Report.php"); ?>
<?php

class ReportDAO {
	
	function getAllReports() 
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		
		$sql = "SELECT * from Report";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        echo "id: " . $row["ReportID"]. " - Name: " . $row["ReportKey"]. " " . $row["UploadedDateString"]. "<br>";
		    }
		} else {
		    echo "0 results";
		}
		$conn->close();

	}
	
	function saveReport($report) 
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
		
		$sql = "insert into Report(UploadedDate,ProjectID,CategoryID,ReportKey,UploadedBy,UploadedDateString,UploadedForUser,PDFUrl,WebUrl) ";
		$sql = $sql." values (now(),".$report->projectID.",".$report->categoryID.",'".mysql_real_escape_string($report->reportKey)."',".$report->uploadedBy.",'".$dateUtil->getCurrentDateTimeString()."',".$report->uploadedForUser.",'".mysql_real_escape_string($report->pdfURL)."','".mysql_real_escape_string($report->webURL)."')";
		
		if ($conn->query($sql) === TRUE) {
			return "New record created successfully";
		} else {
			return "Error: " . $sql . "<br>" . $conn->error;
		}
		
	}
}

?>
