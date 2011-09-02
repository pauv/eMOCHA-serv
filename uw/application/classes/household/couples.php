<?php

/*
 * Class used to display missing people
 */
class Household_Couples {



	public static function get_as_table($type='html') {
	
		
		$datas = array();

		$sql = "SELECT id, household_code
				FROM uploaded_data
				WHERE form_id=9 
				AND ExtractValue(AES_DECRYPT(xml_content,'".Encryption::get_key()."'), '//couple_tested')>0
				ORDER BY household_code ASC";
				
		$result = DB::query(Database::SELECT, $sql)->execute();
		
		foreach($result->as_array() as $row) {
			$datas[] = ORM::factory('form_data', $row['id']);
		}
	
		$table = array();
		$count = 0;
		foreach($datas as $data) {
			$obj = @simplexml_load_string($data->xml_content);
			//echo "<pre>"; var_dump($obj->household->missing_people); echo "</pre>";
			foreach($obj->tested_couple as $couple) {
				//echo "<pre>"; var_dump($person); echo "</pre>";
				$table[$count][1] = $data->household_code;
				$table[$count][2] = $couple->closing_study_id_A;
				$table[$count][3] = $couple->closing_study_id_B;
				$table[$count][4] = $couple->members_disclose_results;
				$table[$count][5] = $couple->discordant_results;
				$count++;
			}
			
		}
		
		if($type=='html') {
		
			$html = "<table><tr><td><b>Household code</b></td><td><b>closing_study_id_A</b></td><td><b>closing_study_id_B</b></td>
					<td><b>members_disclose_results</b></td><td><b>discordant_results</b></td></tr>";
    		
    		foreach ($table as $row) {
    			$html .= "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td></tr>";
    		}
    		$html .= "</table>";
    		return $html;
		
		}
		elseif($type=='csv') {
		
			$text = "Household code\tclosing_study_id_A\tclosing_study_id_B\tmembers_disclose_results\tdiscordant_results\n";
    		
    		foreach ($table as $row) {
    			$text .= $row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\n";
    		}
    		return $text;
		
		}
		
	}

}