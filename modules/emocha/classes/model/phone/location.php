<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Phone_Location extends ORM {

	protected $_belongs_to = array (
								'phone' => array(
													'model' => 'phone',
													'foreign_key' => 'phone_id'
												)
							);
							

	public static function get_dates_array() {
		
			$dates = array();
			$sql = "SELECT DISTINCT DATE(ts) as mydate FROM phone_locations";
					$result = DB::query(Database::SELECT, $sql)->execute();
			foreach($result->as_array() as $row) {
				$dates[$row['mydate']] = $row['mydate'];
			}
			return $dates;
	}
	
	
	/*
	 * Format array as text file
	 */
	public static function export_csv() {
	
		$locations = ORM::factory('phone_location')->find_all();
		
		$text = "PATIENT CODE\tPHONE IMEI\tUTC DATE\tUTC TIME\tLOCAL DATE\tLOCAL TIME\tLATITUDE\tLONGITUDE\tALTITUDE\tSPEED\n";
		
		$phones = ORM::factory('phone')->find_all();
		foreach ($phones as $phone) {
			$patient = ORM::factory('patient')->where('phone_id','=', $phone->id)->find();
			foreach ($phone->phone_locations->find_all() as $location) {
				$text.= $patient->code."\t";
				$text.= $phone->imei."\t";
				$localtime = strtotime($location->ts);
				$utc_date = gmdate('Y-m-d', $localtime);
				$utc_time = gmdate('H:i:s', $localtime);
				$local_date = date('Y-m-d', $localtime);
				$local_time = date('H:i:s', $localtime);
				$text.= $utc_date."\t".$utc_time."\t".$local_date."\t".$local_time."\t";
				$text.= $location->gps_lat."\t".$location->gps_long."\t".$location->altitude."\t".$location->speed."\n";
			}
		}
		return $text;
	}
	
}
