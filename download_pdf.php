<?php include_once("util/ConfigUtil.php"); ?><html>
<body>

<?php	if(isset($_GET ["report"]))	{		$fileKey = urldecode($_GET ["report"]);		$pdf_folder = ConfigUtil::getPDFFolder ();		$path = realpath ( '.' );				$file = $path . '/' . $pdf_folder . '/' . $fileKey . '.pdf';				if (file_exists($file)) {		    header('Content-Description: File Transfer');		    header('Content-Type: application/octet-stream');		    header('Content-Disposition: attachment; filename="'.basename($file).'"');		    header('Expires: 0');		    header('Cache-Control: must-revalidate');		    header('Pragma: public');		    header('Content-Length: ' . filesize($file));		    readfile($file);		    exit;		}	}			?>

</body>
</html>