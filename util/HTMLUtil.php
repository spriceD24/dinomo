<?php
	/**
	 * Manages file interactions
	 * @author Stef
	 *
	 */
	class HTMLUtil
	{
		function getOptionsTable($options)
		{
			$html = "<table>";
			while($option = $options->iterate()) {
				$html = $html.'<tr><td>'.$option->optionFormID.'</td><td>'.$option->optionValue.'</td></tr>';
			}
			$html = $html."</table>";
			return $html;			
		}
		
		function getImageTable($images)
		{
			$html = "<table>";
			while($image = $images->iterate()) {
				$html = $html.'<tr><td><img src="'.$image->imageURL.'" style="width:'.$image->width.';height:'.$image->height.'"></td></tr>';
			}
			$html = $html."</table>";
			return $html;
		}


		function generateUploadEmail($webUrl,$pdfUrl)
		{

			$html = "<html><body><table>";
			$html = $html."<tr>";
			$html = $html."<td colspan='2'>";
			$html = $html."<b>QA Report for XX attached</b>";
			$html = $html."</td>";
			$html = $html."</tr>";
			$html = $html."<tr>";
			$html = $html."<td>";
			$html = $html."<b>Uploaded by:</b>";
			$html = $html."</td>";
			$html = $html."<td>";
			$html = $html."Test User";
			$html = $html."</td>";
			$html = $html."</tr>";
			$html = $html."<tr>";
			$html = $html."<td>";
			$html = $html."<b>Web Preview:</b>";
			$html = $html."</td>";
			$html = $html."<td>";
			$html = $html."<a href='".$webUrl."'>".$webUrl."</a>";
			$html = $html."</td>";
			$html = $html."</tr>";
			$html = $html."</table></body></html>";
			return $html;

		}
	}
?>
