<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Stats helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
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
    	
    	
    	/*
    	 * Loads result count of a specific form/node into an array
    	 * grouped by result
    	 * @return array
    	 */
    	public static function get_piechart($form_id, $node_name) {
    		
    		$totals = array();
    		$sql = "SELECT ExtractValue( AES_DECRYPT( xml_content, '".Encryption::get_key()."' ) , '//".$node_name."' ) 
    				AS xml_val FROM uploaded_data WHERE form_id=".$form_id;
			$result = DB::query(Database::SELECT, $sql)->execute();
			foreach($result->as_array() as $row) {
				if($val = $row['xml_val']){
					if(isset($totals[$val])) {
						$totals[$val]+=1;
					}
					else {
						$totals[$val] = 1;
					}
				}
			}
			return $totals;
			
    	}
    	
    	/*
    	 * Loads result count of a specific form/node for each day
    	 * @return array
    	 */
    	
    	public static function get_dategraph($form_id, $node_name) {
    		
    		$totals = array();
    		$sql = "SELECT last_modified, ExtractValue( AES_DECRYPT( xml_content, '".Encryption::get_key()."' ) , '//".$node_name."' ) 
    				AS xml_val FROM uploaded_data WHERE form_id=".$form_id;
			$result = DB::query(Database::SELECT, $sql)->execute();
			foreach($result->as_array() as $row) {
				if($val = $row['xml_val']){
					if(isset($totals[$val][$row['last_modified']])) {
						$totals[$val][$row['last_modified']]+=1;
					}
					else {
						$totals[$val][$row['last_modified']]=1;
					}
				}
			}
			return $totals;
			
    	}
    	
    	
    }