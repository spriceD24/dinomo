<?php

	/**
	 * Manages PDF services
	 * @author Stef
	 *
	 */
	class PDFUtil
	{
	
		/**
		 * calculates aspect ratio height
		 */
		function calculateAspectRatio($orig1,$orig2,$newVal)
		{
			return ($orig1 / $orig2) * $newVal;
		}
	
		/**
		 * get best width/height for pdf for an image.
		 * This is to ensure image fits on PDF page
		 */
		function getBestPDFWidthHeight($image)
		{
			//get boundaries for PDF
			$configUtil = new ConfigUtil();
			$maxPDFWidth = $configUtil->getMaxPDFWidth();
			$maxPDFHeight = $configUtil->getMaxPDFHeight();
			$pdfImageWidthHeight = new PDFImageWidthHeight;

			//set the default width/height
			$pdfImageWidthHeight->width = $image->width;
			$pdfImageWidthHeight->height = $image->height;
			
			//check if image is taller than PDF page
			
			if($pdfImageWidthHeight->height > $maxPDFHeight)
			{
				//resize the width
				$pdfImageWidthHeight->width = $this->calculateAspectRatio($image->width, $image->height, $maxPDFHeight);
				//now set the height to be max PDF page size
				$pdfImageWidthHeight->height = $maxPDFHeight;
			}
			
			//check if image is wider than PDF page
			if($pdfImageWidthHeight->width > $maxPDFWidth)
			{
				//resize the height
				$pdfImageWidthHeight->height = $this->calculateAspectRatio($image->height, $image->width, $maxPDFWidth);
				//now set the width to be max PDF page size
				$pdfImageWidthHeight->width = $maxPDFWidth;
			}	
			
			return $pdfImageWidthHeight;
		}
		
		function generatePDF($html, $id)
		{
			$ini_array = parse_ini_file("config/dinamo.ini");
			$pdf_folder = $ini_array["pdf_folder"];
			
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			/*
			$pdf->SetAuthor('Nicola Asuni');
			$pdf->SetTitle('TCPDF Example 002');
			$pdf->SetSubject('TCPDF Tutorial');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
			*/
			// set font
			$pdf->SetFont('dejavusans', '', 10);
			
			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			
			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			
			// ---------------------------------------------------------
					
			// add a page
			$pdf->AddPage();
			
			// set some text to print
			
			// output the HTML content
			//echo "Writing HTML ".$html;
			$pdf->writeHTML($html, true, false, true, false, '');
			$path = realpath('.');
				
			$pdf->Output($path.'/'.$pdf_folder.'/'.$id.'.pdf', 'F');
		}
	}
		
?>