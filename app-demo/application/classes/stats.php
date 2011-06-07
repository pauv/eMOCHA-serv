<?php

    class Stats extends Emocha_Stats
    {   
    	/*
    	 * Get totals for each symptom
    	 * @return array
    	 */
    	 
    	public static $symptom_arr = array('0'=>'None', '1'=>'Current cough', '2'=>'Fever', '3'=>'Weight loss', '4'=>'Night sweats');
    	 
    	public static function get_symptom_totals() {
    		
    		$totals = array();
    		$sql = "SELECT ExtractValue( AES_DECRYPT( xml_content, '".Encryption::get_key()."' ) , '//patient_has_symptoms' ) 
    				AS xml_val FROM uploaded_data";
			$result = DB::query(Database::SELECT, $sql)->execute();
			foreach($result->as_array() as $row) {
				if($row['xml_val']){
					$symptom_keys = explode(' ', $row['xml_val']);
					foreach($symptom_keys as $key) {
						if(isset($totals[self::$symptom_arr[$key]])) {
							$totals[self::$symptom_arr[$key]]+=1;
						}
						else {
							$totals[self::$symptom_arr[$key]]=1;
						}
					}
				}
			}
			return $totals;
			
    	}
    	
    	/*
    	 * Get number of symptoms entered for each day
    	 * @return array
    	 */
    	
    	public static function get_symptom_count_by_date() {
    		
    		$totals = array();
    		$sql = "SELECT last_modified, ExtractValue( AES_DECRYPT( xml_content, '".Encryption::get_key()."' ) , '//patient_has_symptoms' ) 
    				AS xml_val FROM uploaded_data";
			$result = DB::query(Database::SELECT, $sql)->execute();
			foreach($result->as_array() as $row) {
				if($row['xml_val']){
					$symptom_keys = explode(' ', $row['xml_val']);
					foreach($symptom_keys as $key) {
						if(isset($totals[self::$symptom_arr[$key]][$row['last_modified']])) {
							$totals[self::$symptom_arr[$key]][$row['last_modified']]+=1;
						}
						else {
							$totals[self::$symptom_arr[$key]][$row['last_modified']]=1;
						}
					}
				}
			}
			return $totals;
			
    	}
    	
    
    
    
    
    }