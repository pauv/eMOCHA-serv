<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form Data Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Model_Form_Data extends ORM_Encrypted {

	protected $_table_name = 'uploaded_data';
	
	protected $_belongs_to = array('form' => array(), 
									'phone' => array('foreign_key'=>'uploader_phone_id'),
									'household' => array('foreign_key'=>'household_code'),
									'patient' => array('foreign_key'=>'patient_code')
									);
									
	// fields to be stored encrypted in DB
	protected $_encrypted = array('xml_content');
	
	
	public function get_form_name() {
		
		return $this->form->name;
	}
	
	/*
	 * Get existing form data based on code and form_id
	 * Default code values from api.php are '' so this works 
	 * even if household_code is not submitted (in the case of non-household projects)
	 *
	 * param string
	 * param string
	 * param string
	 * return object (loaded or empty)
	 */
	public static function get_by_key_data($household_code, $patient_code, $form_id) {
		
		if($patient_code || $household_code) {
			$form = ORM::factory('form_data')
					->where('household_code','=', $household_code)
					->and_where('patient_code', '=', $patient_code)
					->and_where('form_id', '=', $form_id)
					->find();
		}
		else {
			$form = ORM::factory('form_data'); // empty object
		}
					
		return $form;
	}
	
	
	
	/*
	Find a form data from a referral id
	param string
	return object (loaded or empty)
	*/
	public static function get_by_referral_id($referral_id) {
		$sql = "SELECT id
				FROM uploaded_data
				WHERE ExtractValue(AES_DECRYPT(xml_content,'".Encryption::get_key()."'), '//referral_ID')='".$referral_id."'";
				
		$result = DB::query(Database::SELECT, $sql)->execute();
		
		if($result->count()) {
			$row = $result->current();
			return ORM::factory('form_data', $row['id']);
		}
		else {
			return ORM::factory('form_data');
		}
	
	}
	
	/*
	Update a unique xml node
	param string
	param string
	return bool (node changed)
	*/
	/*
	public function update_xml_node ($node, $value) {
	
		$sql = "UPDATE uploaded_data
		SET xml_content = AES_ENCRYPT(UpdateXML(AES_DECRYPT(xml_content,'".Encryption::get_key()."'), '//".$node."', '<".$node.">".$value."</".$node.">'),'".Encryption::get_key()."')
		WHERE id=".$this->id;
		//echo $sql;
		return DB::query(Database::UPDATE, $sql)->execute();
	}*/
	
	/*
	Update a unique xml node
	param string
	param string
	return bool (node found and updated)
	(USE PHP TO AVOID ENCRYPTION STUFF)
	*/
	public function update_xml_node ($node, $value) {
		$xml = simplexml_load_string($this->xml_content);
		if(is_object($xml) && $ref_node = $xml->xpath('//'.$node)) {
			$ref_node[0][0] = $value;
			$this->xml_content = $xml->asXML();
			if($this->save()) return TRUE;
		}
		return FALSE;
	}
	
	
	/*
	Get xml node value
	param string
	return string
	*/
	public function get_xml_node ($node) {
		$sql = "SELECT ExtractValue(AES_DECRYPT(xml_content,'".Encryption::get_key()."'), '//".$node."') AS xml_val
		FROM uploaded_data WHERE id=".$this->id;
		//echo $sql;
		$result = DB::query(Database::SELECT, $sql)->execute();
		
		if($result->count()) {
			$row = $result->current();
			return $row['xml_val'];
		}
		return '';
	}

	
	/*
	Get xml node value
	param string
	return string
	(USE PHP TO AVOID ENCRYPTION STUFF)
	
	public function get_xml_node ($node) {
		$xml = simplexml_load_string($this->xml_content);
		if(is_object($xml) && $ref_node = $xml->xpath('//'.$node)) {
			return $ref_node[0][0];
		}
		return '';
	}*/
	
	
	
	
	/*
	 * save associated file
	 * @return bool
	 */
	public function save_file($file) {
		$files_folder = 'sdcard/emocha/patient_files';
		$dir = '';
		if($this->patient_code) {
			$dir = $this->patient_code;
		}
		else {
			$dir = $this->household_code;
		}
		// double check folder is not blank
		if(! $dir) return false;
		// write dir
		$file_folder = DOCROOT.$files_folder.'/'.$dir;
		if(! is_dir($file_folder)) {
			mkdir($file_folder);
		}
		// write file
		return upload::save($file, $file['name'], $file_folder);
	}

	
	
	
	##################################
	## OLD STUFF
	## TODO: refactor this in most appropriate way
	##################################

	
	
	public static function get_table_data($gender, $tb, $hiv,
        	$age_min, $age_max, $temp_min, $temp_max) {
        		
            $rows = ORM::factory('form_data')
            			->find_all();

			$a0 = min($age_min,  $age_max); 
			$a1 = max($age_min,  $age_max);
			$t0 = min($temp_min, $temp_max);
			$t1 = max($temp_min, $temp_max);
			
			$resultList = array();
			
			foreach ($rows as $row) {
				$row = $row->as_array();
				
				$xml_obj = simplexml_load_string(stripslashes($row['xml_content']));
	
				$showit = true;

				if ($showit && $gender) {
					$showit = $gender == strtolower($xml_obj->patient_sex);
				} 
				if ($showit && $tb) {
					$showit = $tb == $xml_obj->patient_tbc;
				}
				if ($showit && $hiv) {
					$showit = $hiv == $xml_obj->patient_hiv;
				}
				if ($showit && $age_min) {
					$showit = $a0 <= $xml_obj->patient_age;
				}
				if ($showit && $age_max) {
					$showit = $a1 >= $xml_obj->patient_age;
				}
				if ($showit && $temp_min) {
					$showit = $t0 <= $xml_obj->patient_temp;
				}
				if ($showit && $temp_max) {
					$showit = $t1 >= $xml_obj->patient_temp;
				}
				
				if (strlen($xml_obj->location->gps_coordinates) > 10) {			
					$row['xml_obj'] = $xml_obj;
					$resultList[] = $row;
				} 
			}
            
            
            return $resultList;
        }
        
        
        public function display_result() {
			
			$xml = simplexml_load_string(stripslashes($this->xml_content));
			
			if ($xml) {
				$html = "";
				$html .= '<table>';
				foreach($xml as $key => $val) {
					if(count($val) > 0) {
						$html .= "<tr><td colspan='2'>";				
						$html .= "<h3>$key</h3><table>";
						foreach($val as $k => $v) {
							//$v = $this->_linkify($v, $usrid, $form_name);
							$html .= "<tr><td>$k</td><td class=\"val\">$v</td></tr>";
						}
						$html .= "</table></td></tr>";
					} else {
						//$val = $this->_linkify($val, $usrid, $form_name);
						$html .= "<tr><td>$key</td><td class=\"val\">$val</td></tr>";				
					}
				}
				$html .= '</table>';
			} else {
				$html = "No data found."; 
			}
					
			return $html;
    	}
    	
}