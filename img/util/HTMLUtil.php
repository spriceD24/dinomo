<?php
/**
 * Manages file interactions
 * @author Stef
 *
 */
class HTMLUtil {
	function getOptionsTable($options) {
		$html = "<table>";
		while ( $option = $options->iterate () ) {
			$html = $html . '<tr><td>' . $option->optionFormID . '</td><td>' . $option->optionValue . '</td></tr>';
		}
		$html = $html . "</table>";
		return $html;
	}
	function getImageTable($images) {
		$html = "<table>";
		while ( $image = $images->iterate () ) {
			$html = $html . '<tr><td><img src="' . $image->imageURL . '" style="width:' . $image->width . ';height' . $image->height . '"><td></tr>';
		}
		$html = $html . "</table>";
		return $html;
	}
}
?>
