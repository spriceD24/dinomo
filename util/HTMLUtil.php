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
		
		function getStandardLabel($label,$labelClass)
		{
			$html = '<span class="';
			if(!is_null($labelClass) && $labelClass != '')
			{
				$html =$html.$labelClass.'">';
			}else{
				$html =$html.HTMLConst::STANDARD_LABEL.'">';
			}
			$html = $html.$label."</span>";
			return $html;
		}
		
		function getStandardRadio($label,$id,$name,$styleClass)
		{
			$html = '<input type="radio" id="'.$id.'" name="'.$name.'" class="';
			if(!is_null($styleClass) && $styleClass != '')
			{
				$html =$html.$styleClass.'"/>';
			}else{
				$html =$html.HTMLConst::STANDARD_RADIO.'"/>';
			}
			$html = $html.$label;
			return $html;
			
		}
		
		function getTimeDropdown($id)
		{
			$html="<select name='".$id."' id='".$id."' onchange='setDate(\"'.$id.'\")' style='width:100px;height:25px'>";
			$html = $html."<option value=''></option>";
			$html = $html."<option value='05:00'>05:00</option>";
			$html = $html."<option value='05:15'>05:15</option>";
			$html = $html."<option value='05:30'>05:30</option>";
			$html = $html."<option value='05:45'>05:45</option>";
			$html = $html."<option value='06:00'>06:00</option>";
			$html = $html."<option value='06:15'>06:15</option>";
			$html = $html."<option value='06:30'>06:30</option>";
			$html = $html."<option value='06:45'>06:45</option>";
			$html = $html."<option value='07:00'>07:00</option>";
			$html = $html."<option value='07:15'>07:15</option>";
			$html = $html."<option value='07:30'>07:30</option>";
			$html = $html."<option value='07:45'>07:45</option>";
			$html = $html."<option value='08:00'>08:00</option>";
			$html = $html."<option value='08:15'>08:15</option>";
			$html = $html."<option value='08:30'>08:30</option>";
			$html = $html."<option value='08:45'>08:45</option>";
			$html = $html."<option value='09:00'>09:00</option>";
			$html = $html."<option value='09:15'>09:15</option>";
			$html = $html."<option value='09:30'>09:30</option>";
			$html = $html."<option value='09:45'>09:45</option>";
			$html = $html."<option value='10:00'>10:00</option>";
			$html = $html."<option value='10:15'>10:15</option>";
			$html = $html."<option value='10:30'>10:30</option>";
			$html = $html."<option value='10:45'>10:45</option>";
			$html = $html."<option value='11:00'>11:00</option>";
			$html = $html."<option value='11:15'>11:15</option>";
			$html = $html."<option value='11:30'>11:30</option>";
			$html = $html."<option value='11:45'>11:45</option>";
			$html = $html."<option value='12:00'>12:00</option>";
			$html = $html."<option value='12:15'>12:15</option>";
			$html = $html."<option value='12:30'>12:30</option>";
			$html = $html."<option value='12:45'>12:45</option>";
			$html = $html."<option value='13:00'>13:00</option>";
			$html = $html."<option value='13:15'>13:15</option>";
			$html = $html."<option value='13:30'>13:30</option>";
			$html = $html."<option value='13:45'>13:45</option>";
			$html = $html."<option value='14:00'>14:00</option>";
			$html = $html."<option value='14:15'>14:15</option>";
			$html = $html."<option value='14:30'>14:30</option>";
			$html = $html."<option value='14:45'>14:45</option>";
			$html = $html."<option value='15:00'>15:00</option>";
			$html = $html."<option value='15:15'>15:15</option>";
			$html = $html."<option value='15:30'>15:30</option>";
			$html = $html."<option value='15:45'>15:45</option>";
			$html = $html."<option value='16:00'>16:00</option>";
			$html = $html."<option value='16:15'>16:15</option>";
			$html = $html."<option value='16:30'>16:30</option>";
			$html = $html."<option value='16:45'>16:45</option>";
			$html = $html."<option value='17:00'>17:00</option>";
			$html = $html."<option value='17:15'>17:15</option>";
			$html = $html."<option value='17:30'>17:30</option>";
			$html = $html."<option value='17:45'>17:45</option>";
			$html = $html."<option value='18:00'>18:00</option>";
			$html = $html."<option value='18:15'>18:15</option>";
			$html = $html."<option value='18:30'>18:30</option>";
			$html = $html."<option value='18:45'>18:45</option>";
			$html = $html."<option value='19:00'>19:00</option>";
			$html = $html."<option value='19:15'>19:15</option>";
			$html = $html."<option value='19:30'>19:30</option>";
			$html = $html."<option value='19:45'>19:45</option>";
			$html = $html."<option value='20:00'>20:00</option>";
			$html = $html."<option value='20:15'>20:15</option>";
			$html = $html."<option value='20:30'>20:30</option>";
			$html = $html."<option value='20:45'>20:45</option>";
			$html = $html."<option value='21:00'>21:00</option>";
			$html = $html."<option value='21:15'>21:15</option>";
			$html = $html."<option value='21:30'>21:30</option>";
			$html = $html."<option value='21:45'>21:45</option>";
			$html = $html."<option value='22:00'>22:00</option>";
			$html = $html."<option value='22:15'>22:15</option>";
			$html = $html."<option value='22:30'>22:30</option>";
			$html = $html."<option value='22:45'>22:45</option>";
			$html = $html."<option value='23:00'>23:00</option>";
			$html = $html."</select>";	
			return $html;	
		}

		function getDropdownElement($id,$today)
		{
			if($today == $id)
			{
				return "<option value='".$id."' selected>".$id."</option>";
			}
			return "<option value='".$id."'>".$id."</option>";
		}
		
		function getDayDropdown($id)
		{
			$dayNumber = date("j");
			$html="<select name='".$id."' id='".$id."' onchange=\"setDate(\\\''.$id.'\\\')\"  style='width:50px'>";
			for($x = 1; $x <= 31; $x ++)
			{
				$html = $html.$this->getDropdownElement($x,$dayNumber);
			}
			$html = $html."</select>";
			return $html;
		}
	
		function getMonthDropdown($id)
		{
			$month = date("M");
			$html="<select name='".$id."' id='".$id."'  style='width:75px'>";
			$html = $html.$this->getDropdownElement('Jan',$month);
			$html = $html.$this->getDropdownElement('Feb',$month);
			$html = $html.$this->getDropdownElement('Mar',$month);
			$html = $html.$this->getDropdownElement('Apr',$month);
			$html = $html.$this->getDropdownElement('May',$month);
			$html = $html.$this->getDropdownElement('Jun',$month);
			$html = $html.$this->getDropdownElement('Jul',$month);
			$html = $html.$this->getDropdownElement('Aug',$month);
			$html = $html.$this->getDropdownElement('Sep',$month);
			$html = $html.$this->getDropdownElement('Oct',$month);
			$html = $html.$this->getDropdownElement('Nov',$month);
			$html = $html.$this->getDropdownElement('Dec',$month);
			$html = $html."</select>";
			return $html;
		}

		function getYearDropdown($id)
		{
			$year = date('Y');
			
			$html="<select name='".$id."' id='".$id."' onchange='setDate(\"'.$id.'\")' style='width:100px'>";
			$html = $html."<option value='".($year-1)."'>".($year-1)."</option>";
			$html = $html."<option value='".$year."' selected>".$year."</option>";
			$html = $html."</select>";
			return $html;
		}
	}
?>
