<?php

	/**
	 * Manages PDF services
	 * @author Stef
	 *
	 */
	class PDFUtil
	{
		/*
		 * returns max recommended width for PDF
		 */
		function getMaxPDFWidth()
		{
			$ini_array = parse_ini_file("config/dinamo.ini");
			$max_pdf_width = $ini_array["pdf_max_width"];
			return $max_pdf_width;
		}

		/*
		 * returns max recommended height for PDF
		 */
		function getMaxPDFHeight()
		{
			$ini_array = parse_ini_file("config/dinamo.ini");
			$max_pdf_height = $ini_array["pdf_max_height"];
			return $max_pdf_height;
		}
		
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
			$maxPDFWidth = $this->getMaxPDFWidth();
			$maxPDFHeight = $this->getMaxPDFHeight();
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
		
	}
?>