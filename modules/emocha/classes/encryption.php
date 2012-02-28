<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Encryption helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Encryption {


	/**
	 * get_key()
	 *
	 * Get AES encryption key for database encryption
	 *
	 * @return string
	 */
	public static function get_key() {
		return Kohana::config('encryption.key');
	}

	
}