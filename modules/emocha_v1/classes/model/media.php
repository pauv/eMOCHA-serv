<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Media extends ORM {

	/*
	ORM vars
	*/
	protected $_table_name = 'media';
	protected $_sorting = array('date_created'=>'DESC');
	
	/*
	ORM Relationships
	*/
	// this relationship is really a multiple 'has_one' relationship
	// but I use belongs_to because the keys reside in the media table
	protected $_belongs_to = array (
								'file' => array(
													'model' => 'file',
													'foreign_key' => 'file_id'
												),
								'thumbnail' => array(
													'model' => 'file',
													'foreign_key' => 'thumbnail_file_id'
												)
							);
	// load these relationships by default
	protected $_load_with = array ('file', 'thumbnail');
					
							
							
							
	/*
	Other vars
	*/
	 protected $file_types = array (
							'courses'=>array('mp4'),
							'lectures'=>array('mp4'),
							'library'=>array('html','pdf')
							);
	protected $mime_types = array (
							'mp4'=>'video/mp4',
							'html'=>'html'
							);
	protected $thumbnail_allowed = array (
							'courses',
							'lectures'
							);



	
	
	/* get allowed file extension for this type of file
	 * return string or false
	 */
	public function get_allowed_file_type() {
		if(array_key_exists($this->type, $this->file_types)){
			return $this->file_types[$this->type];
		}
		return false;
	}
	
	/* get whether this file type takes a thumbnail
	 * return bool
	 */
	public function get_thumbnail_allowed() {
		if(in_array($this->type, $this->thumbnail_allowed)){
			return true;
		}
		return false;
	}
	
	
	/* get unique filename
	 * recursively checks for a valid unique name
	 * return string
	 */
	public function get_unique_file_name($folder, $name) {
	
		if(is_file($folder.$name)) {
			
			$parts = explode('.', $name);
			$basename = $parts[0];
			$extension = array_pop($parts);
			$new_name = $basename.'_1.'.$extension;
			return $this->get_unique_file_name($folder, $new_name);
				
		}
		return $name;
	}
	
	
	// override delete method to handle files
	public function delete($id = NULL) {
	
		if($this->file->loaded()){
			$this->file->delete();
		}
		if($this->thumbnail->loaded()){
			$this->thumbnail->delete();
		}
		
		parent::delete();
	}
	
}