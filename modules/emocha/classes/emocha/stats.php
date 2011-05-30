<?php

    class Emocha_Stats
    {   
    	/*
    	 * Get count new patients by date
    	 * @return array
    	 */
    	public static function get_count_patients_by_date() {
    		
    		$sql = "SELECT date( last_modified ) as date, count(*) as total
					FROM uploaded_data
					where form_id=2
					group by date
					order by date";
			$result = DB::query(Database::SELECT, $sql)->execute();
			return $result->as_array();
			
    	}
    	
    }