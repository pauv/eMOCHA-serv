<?php

/*
 * Class used to display missing people
 */
class Household_Missing {



	public static function get_as_table($type='html') {
	
		
		$datas = array();

		$sql = "SELECT id, household_code
				FROM uploaded_data
				WHERE form_id=1 
				AND ExtractValue(AES_DECRYPT(xml_content,'".Encryption::get_key()."'), '//adults_missing')>0
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
			foreach($obj->household->missing_people->children() as $person) {
				//echo "<pre>"; var_dump($person); echo "</pre>";
				$table[$count][1] = $data->household_code;
				$table[$count][2] = $person->person_id;
				$table[$count][3] = $person->age;
				$table[$count][4] = $person->sex;
				$count++;
			}
			
		}
		
		if($type=='html') {
		
			$html = "<table><tr><td><b>Household code</b></td><td><b>Person id</b></td><td><b>Age</b></td><td><b>Sex</b></td></tr>";
    		
    		foreach ($table as $row) {
    			$html .= "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
    		}
    		$html .= "</table>";
    		return $html;
		
		}
		elseif($type=='csv') {
		
			$text = "Household code\tPerson\tAge\tSex\n";
    		
    		foreach ($table as $row) {
    			$text .= $row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\n";
    		}
    		return $text;
		
		}
		
	}

}