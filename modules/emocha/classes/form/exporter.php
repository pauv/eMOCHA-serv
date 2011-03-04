<?php

	/*
     * Class used to convert an XML tree-like structure into a two dimensional
     * array.
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
	
		
		function __construct($form) {
			
			$this->form = $form;

			$this->def_form_path = DOCROOT."sdcard/emocha/odk/forms/".$this->form->name;
			
			$file_content = implode('', file($this->def_form_path));
			preg_match("/<instance>(.*)<\/instance>/ms", $file_content, $foundA);
			$this->def_form_xml = @simplexml_load_string($foundA[1]);
			
			$this->file_found = $this->def_form_xml ? true : false;
		}
		
		function get_form_path() {
			return $this->def_form_path;
		}
		
		
		
		
		
		/*
		 * Get display independent array representation of data
		 */
		public function load_data_as_array() {            		
											
			$this->data_as_array['columns'] = $this->get_data_columns();
			$this->data_as_array['rows'] = $this->get_data_rows();
	
			//var_dump($this->data_as_array);
			//echo "count".count($this->form->data);
			//echo "<pre>";var_dump($this->form->data);echo"</pre>";
		}
	
	
		
		/*
    	 * Format array as html table
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
					$html .= "<td>$val</td>";
				}
				$html .= "</tr>";
    			
    		}
    		$html .= "</table>";
    		return $html;
    		
    	}
    	
    	
    	/*
    	 * Format array as text file
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
		
		
		/*
		 * read columns from xml template
		 */
		function get_data_columns() {
			if ($this->file_found) {
				return $this->_get_columns($this->def_form_xml, '');
			} 
		}
		
		/*
		 * read columns from xml template
		 */
		private function _get_columns($obj, $parent_id) {
			if ($this->file_found) {
				if(stristr($this->form->group,'household')){
					$this->columns = array('household_code');
				}
				elseif(stristr($this->form->group,'patient')){
					$this->columns = array('patient_code');
				}
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
		
		/*
		 * read instance files into an array or arrays
		 */
		function get_data_rows() {
			
			$row_num = 0;

			foreach($this->form->form_datas->find_all() AS $row) {	
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

		
		/*
		 * read from data a single instance result file
		 * into one row
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