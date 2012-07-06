<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Config helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Config 
{ 

	/**
	 *  get()
	 *
	 * Get config variable from database
	 *
	 * @param string
	 * @param string
	 *
	 * @return string
	 * NOTE: this is just rough, need to finish config conversation to finalise
	 */
 	public static function get($type='', $label='') {
 	
 		$content = '';
 		if($type && $label) {
			$config = ORM::factory('config')
					->where('type','=',$type)
					->and_where('label','=',$label)
					->find();
			if ($config->loaded()) {
				$content = $config->content;
			}
		}
		return $content;
	
 	}

}