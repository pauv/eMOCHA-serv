<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form Data File Model
 *
 * @package    eMOCHA
 * @author     Pau Varela 
 * @copyright  2012 Pau Varela - pau.varela@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 *
 *
 * This class contains form_data_file entries, where files associated to a given form are stored (e.g: pictures, audio files, digital signatures, etc..).
 * DB entries should be populated via upload_form_data_file API call
 */  
class Model_Form_Data_File extends ORM {

  protected $_belongs_to = array (
                'form_data' => array(
                          'model' => 'uploaded_data',
                          'foreign_key' => 'upload_data_id'
                        )
								);

	/*
	 * save_file(): saves provided data to this->filename
	 * 
	 * @param: the data 
	 * @return: true if saved, false otherwise
	 * 
	 */
	public function save_file($data) {
		//get paths ready
		$filename = basename($this->filename);
		$dir = dirname($this->filename);
		if (!is_dir($dir))
		{
			if (!mkdir($dir,0755,TRUE))
				return FALSE;
		}

		//upload the file
		return upload::save($data, $filename, $dir);
	}

	/*
	 * returns 
	 *
	 *
	 */
	public function get_fdf_path($form_data) {
		if ($form_data->patient_code)
			return Kohana::config('values.form_data_file_path').$form_data->patient_code.'/';
		else 
			return Kohana::config('values.form_data_file_path').$form_data->household_code.'/';
	}
}
