<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form helper used to convert an XML tree-like structure into a two dimensional array
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */ 
	class Form_Exporter {
	
		private $form; // form object to be converted
		
		private $def_form_xml;
		private $def_form_path;
		private $phone_id_A;
		private $form_name;
		private $vars_A;
		private $CI;
		
		private $columns = array();
		private $rows =  array();
		
		public $file_found = false;
		

		public $data_as_array = array();
	
	
		/**
		 * _construct()
		 *
		 * Load form
		 *
		 * @param object
		 * @return string
		 */
		function __construct($form) {
			
			$this->form = $form;

			$this->def_form_path = DOCROOT."sdcard/emocha/odk/forms/".$this->form->name;
			
			$file_content = implode('', file($this->def_form_path));
			
			// split up template to handle long files
			// maybe this could be done better with an xml query
			$a = explode('<instance>', $file_content);
			$b = explode('</instance>', $a[1]);
			$this->def_form_xml = @simplexml_load_string($b[0]);
			
			$this->file_found = $this->def_form_xml ? true : false;
		}
		
		
		/**
		 * get_form_path()
		 *
		 * Get disk path to form template
		 *
		 * @return string
		 */
		function get_form_path() {
			return $this->def_form_path;
		}
		
		
		
		

		/**
		 * load_data_as_array()
		 *
		 * Create display independent array representation of data
		 */
		public function load_data_as_array() {            		
											
			$this->data_as_array['columns'] = $this->get_data_columns();
			$this->data_as_array['rows'] = $this->get_data_rows();
	
		}
	
	

    	/**
		 * get_as_html_table()
		 *
		 * Format data array as html table
		 *
		 * @return string
		 */
    	function get_as_html_table() {
    	
    		$this->load_data_as_array();
    		
    		$html = "<table><tr>";
    		foreach ($this->data_as_array['columns'] as $column) {
    			$html .= "<td>";
    			$col_labels = explode(".",$column);
    			$counter = 1;
    			foreach($col_labels as $label) {
    				if($counter==sizeof($col_labels)) {
    					$html .= "<b>$label</b>";
    				}
    				else {
    					$html .= "$label<br />";
    				}
    				$counter++;
    			}
    			$html .= "</td>";
    		}
    		$html .= "</tr>";
    		foreach ($this->data_as_array['rows'] as $row) {
    			$html .= "<tr>";
    			foreach ($this->data_as_array['columns'] as $column) {
    				$val = array_key_exists($column, $row) ? $row[$column] : "";
					$html .= "<td class=\"val\">$val</td>";
				}
				$html .= "</tr>";
    			
    		}
    		$html .= "</table>";
    		return $html;
    		
    	}
    	

    	/**
		 * get_as_csv()
		 *
		 * Format data array as tab separated text file
		 *
		 * @param string
		 * @param string
		 * @return string
		 */
    	function get_as_csv($separator="\t", $line_break="\n") {
    	
    		$this->load_data_as_array();
    		
    		$text = "";
    		foreach ($this->data_as_array['columns'] as $column) {
    			$text .= $column.$separator;
    		}
    		$text .= $line_break;
    		foreach ($this->data_as_array['rows'] as $row) {
    			foreach ($this->data_as_array['columns'] as $column) {
    				$val = array_key_exists($column, $row) ? $row[$column] : "";
					$text .= $val.$separator;
				}
				$text .= $line_break;
    			
    		}
    		return $text;
    		
    	}
		
		

		/**
		 * get_data_columns()
		 *
		 * Read columns from xml template
		 */
		function get_data_columns() {
			if ($this->file_found) {
				return $this->_get_columns($this->def_form_xml, '');
			} 
		}
		/**
		 * get_xml()
		 *
		 * Read  xml template
		 */
		function get_xml() {
			if ($this->file_found) {
				return $this->def_form_xml;
			} 
		}
		

		/**
		 * _get_columns()
		 *
		 * Read columns from xml template
		 *
		 * @param object
		 * @param int
		 * @return array
		 */
		private function _get_columns($obj, $parent_id) {
			if ($this->file_found) {
				// add code as first column
				if(stristr($this->form->group,'household') && empty($this->columns)){
					$this->columns = array('household_code');
				}
				elseif(stristr($this->form->group,'patient') && empty($this->columns)){
					$this->columns = array('patient_code');
				}
				// fill remaining columns by reading xml tree
				foreach($obj as $key => $val) {
					$id = ($parent_id ? "$parent_id." : '').$key; 
					if (count($val) > 0) {
						$this->_get_columns($val, $id);
					} else {
						array_push($this->columns, $id);
					}
				}
				return $this->columns;
			}
		}
		
		
		/**
		 * get_tree()
		 *
		 * Read nodes from xml template
		 *
		 * @param object
		 * @param int
		 * @return array
		 */
		 /*
		public function _get_tree($obj, $parent_id) {
			if ($this->file_found) {
				// fill remaining columns by reading xml tree
				foreach($obj as $key => $val) {
					$id = ($parent_id ? "$parent_id." : '').$key; 
					if (count($val) > 0) {
						$this->_get_columns($val, $id);
					} else {
						array_push($this->columns, $id);
					}
				}
				return $this->columns;
			}
		}
		*/
		

		/**
		 * get_data_rows()
		 *
		 * Read data files into an array or arrays
		 *
		 * @return array
		 */
		function get_data_rows() {
			
			$row_num = 0;
			
			$rows = $this->form->form_datas
					->where('rejected','=', '')
					->find_all();

			foreach($rows AS $row) {	
				if(stristr($this->form->group,'household')){
					$this->rows[$row_num]['household_code'] = $row->household_code;
				}
				elseif(stristr($this->form->group,'patient')){
					$this->rows[$row_num]['patient_code'] = $row->patient_code;
				}
				$row_xml = simplexml_load_string(stripslashes($row->xml_content));
				if ($row_xml) {
					$this->_get_row($row_num, $row_xml, '');	
				}
				$row_num++;
			}
			
			return $this->rows;
		}			


		/**
		 * _get_row()
		 *
		 * Read from data a single instance result file
		 * into one row
		 *
		 * @param int
		 * @param object
		 * @param int
		 */
		private function _get_row($row_num, $obj, $parent_id) {
			foreach($obj as $key => $val) {
				$id = ($parent_id ? "$parent_id." : '').$key; 
				if (count($val) > 0) {
					$this->_get_row($row_num, $val, $id);
				} else {
					$this->rows[$row_num][$id] = trim(str_replace("\n", "", $val));
				}
			}
	
		}
		
	}