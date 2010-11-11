<?php defined('SYSPATH') or die('No direct script access.');

class Geo {

	
	public static function get_count_symptoms_by_radius ($form_id, $symptom_xpath, $symptom_result, $radius, $limit) {
		
		
		/*
		SELECT 3963.191 * ACOS( (
SIN( PI( ) * 52.481539 /180 ) * SIN( PI( ) * gps_lat /180 ) ) + (
COS( PI( ) * 52.481539 /180 ) * cos( PI( ) * gps_lat /180 ) * COS( PI( ) * gps_long /180 - PI( ) * 13.443521 /180 )
)
)
FROM households
*/
		/*
		SELECT DISTINCT IF( h1.id < h2.id, h1.id, h2.id ) AS id1, IF( h1.id < h2.id, h2.id, h1.id ) AS id2
FROM households AS h1
INNER JOIN households AS h2 ON ( 3963.191 * ACOS( (
SIN( PI( ) * h2.gps_lat /180 ) * SIN( PI( ) * h1.gps_lat /180 ) ) + ( COS( PI( ) * h2.gps_lat /180 ) * cos( PI( ) * h1.gps_lat /180 ) * COS( PI( ) * h1.gps_long /180 - PI( ) * h2.gps_long /180 ) ) ) <1
)
wHERE h1.id <> h2.id
	*/
	
	/*
		SELECT id
FROM households WHERE ( 3963.191 * ACOS( (
SIN( PI( ) * 52.481539 /180 ) * SIN( PI( ) * gps_lat /180 ) ) + (
COS( PI( ) * 52.481539 /180 ) * cos( PI( ) * gps_lat /180 ) * COS( PI( ) * gps_long /180 - PI( ) * 13.443521 /180 )
)
) < 1
)
	*/
	
	/*
	SELECT id, ExtractValue( xml_content, '/data/post_que/HIV/Status' )
FROM uploaded_data
WHERE form_id =2

	update uploaded_data set xml_content= UpdateXML(xml_content, '/data/post_que/HIV/Status', '1') where id=31
	*/
	
	/*
	1. GET ALL RESULTS WITH THIS SYMPTOM
	*/
		$sql = "SELECT id FROM households WHERE code IN
				(SELECT household_code FROM uploaded_data
				WHERE ExtractValue( xml_content, ".$symptom_xpath." )=".$symptom_result;
		return $count;
	}
	



}