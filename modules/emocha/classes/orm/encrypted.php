<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extends ORM to allow for encrypting and decrypting
 * certain fields to the database
 * BEWARE OF LIMITATIONS
 * SOME ORM FUNCTIONALITY MAY NOT WORK PROPERLY
 * E.G. load_with()
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class ORM_Encrypted extends ORM {

	
	protected $_encrypted   = array();


	/**
	 * Loads a database result, either as a new object for this model, or as
	 * an iterator for multiple rows.
	 *
	 * @chainable
	 * @param   boolean       return an iterator or load a single row
	 * @return  ORM           for single rows
	 * @return  ORM_Iterator  for multiple rows
	 */
	 // OVERRIDDEN:
	protected function _load_result($multiple = FALSE)
	{
		$this->_db_builder->from($this->_table_name);

		if ($multiple === FALSE)
		{
			// Only fetch 1 record
			$this->_db_builder->limit(1);
		}




		// OVERRIDE
		// OLD CODE
		/*
		$this->_db_builder->select($this->_table_name.'.*');
		*/
		// NEW CODE
		// BUILD FULL LIST OF FIELDS
		// WITH DECRYPTION OF ENCRYPTED VALUES
		foreach($this->_table_columns as $column=>$cv) {
			if(in_array($column, $this->_encrypted)) {
				$selected_vals[]=array(DB::expr("AES_DECRYPT(".$this->_table_name.'.'.$column.",'".Encryption::get_key()."')"),$column);
			}
			else {
				$selected_vals[]=$this->_table_name.'.'.$column;
			}
		}
		call_user_func_array(array($this->_db_builder, 'select'),$selected_vals);
		// END OVERRIDE
		



		if ( ! isset($this->_db_applied['order_by']) AND ! empty($this->_sorting))
		{
			foreach ($this->_sorting as $column => $direction)
			{
				if (strpos($column, '.') === FALSE)
				{
					// Sorting column for use in JOINs
					$column = $this->_table_name.'.'.$column;
				}

				$this->_db_builder->order_by($column, $direction);
			}
		}

		if ($multiple === TRUE)
		{
			// Return database iterator casting to this object type
			$result = $this->_db_builder->as_object(get_class($this))->execute($this->_db);

			$this->_reset();

			return $result;
		}
		else
		{
			// Load the result as an associative array
			$result = $this->_db_builder->as_assoc()->execute($this->_db);

			$this->_reset();

			if ($result->count() === 1)
			{
				// Load object values
				$this->_load_values($result->current());
			}
			else
			{
				// Clear the object, nothing was found
				$this->clear();
			}

			return $this;
		}
	}
	
	
	
	
	/**
	 * Saves the current object.
	 *
	 * @chainable
	 * @return  ORM
	 */
	 // OVERRIDDEN:
	public function save()
	{
		if (empty($this->_changed))
			return $this;

		$data = array();
		foreach ($this->_changed as $column)
		{
			// Compile changed data
			$data[$column] = $this->_object[$column];
		}

		if ( ! $this->empty_pk() AND ! isset($this->_changed[$this->_primary_key]))
		{
			// Primary key isn't empty and hasn't been changed so do an update

			if (is_array($this->_updated_column))
			{
				// Fill the updated column
				$column = $this->_updated_column['column'];
				$format = $this->_updated_column['format'];

				$data[$column] = $this->_object[$column] = ($format === TRUE) ? time() : date($format);
			}
			
			// OVERRIDE
			// ENCRYPT CERTAIN FIELDS
			foreach($this->_encrypted as $column) {
				if(array_key_exists($column, $data)) {
					$data[$column]=DB::expr("AES_ENCRYPT(".$this->_db->quote($data[$column]).",'".Encryption::get_key()."')");
				}
			}
			// END OVERRIDE
			
			
			
			$query = DB::update($this->_table_name)
				->set($data)
				->where($this->_primary_key, '=', $this->pk())
				->execute($this->_db);

			// Object has been saved
			$this->_saved = TRUE;
		}
		else
		{
			if (is_array($this->_created_column))
			{
				// Fill the created column
				$column = $this->_created_column['column'];
				$format = $this->_created_column['format'];

				$data[$column] = $this->_object[$column] = ($format === TRUE) ? time() : date($format);
			}
			
			// OVERRIDE
			// ENCRYPT CERTAIN FIELDS
			foreach($this->_encrypted as $column) {
				if(array_key_exists($column, $data)) {
					$data[$column]=DB::expr("AES_ENCRYPT(".$this->_db->quote($data[$column]).",'".Encryption::get_key()."')");
				}
			}
			// END OVERRIDE

			$result = DB::insert($this->_table_name)
				->columns(array_keys($data))
				->values(array_values($data))
				->execute($this->_db);

			if ($result)
			{
				if ($this->empty_pk())
				{
					// Load the insert id as the primary key
					// $result is array(insert_id, total_rows)
					$this->_object[$this->_primary_key] = $result[0];
				}

				// Object is now loaded and saved
				$this->_loaded = $this->_saved = TRUE;
			}
		}

		if ($this->_saved === TRUE)
		{
			// All changes have been saved
			$this->_changed = array();
		}

		return $this;
	}
} 


