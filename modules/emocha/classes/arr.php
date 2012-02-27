<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Array helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
 class Arr extends Kohana_Arr{

	/**
	 *  xss()
	 *
	 * catch all xss sanitization for $_POST or $_GET arrays
	 *
	 * @param array
	 * @return array
	 */
	public static function xss(array $array){

		foreach ($array as &$value){
			if(is_array($value)){
				$value = self::xss($value);
			}else{
				$value = Security::xss_clean($value);
			}
		}

		return $array;
	}

}