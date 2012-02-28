<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Json helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Json 
    { 
    
    	/**
		 * response()
		 *
		 * Create API response array
		 *
		 * @param string
		 * @param string
		 * @param string
		 * @return array
		 */
    	public static function response($status, $msg, $extra = '') {
			$r = array('data' => array(
				'status' => $status,
				'msg' => $msg,
				'ts' => time()
			));
			if (is_array($extra)) {
				$r['data'] = $r['data'] + $extra;
			}
			return $r;
		} 
		
		/**
		 * response_array()
		 *
		 * Create json API response
		 *
		 * @param string
		 * @param string
		 * @param string
		 * @return string
		 */
		public static function response_array($status, $msg, $extra = array(), $name, $sub_name) {
			$r = array(
				'status' => $status,
				'msg' => $msg,
				'ts' => time()
			);
			$r = $r + array($name=>array($sub_name=>$extra));
			
			return json_encode($r);
		} 
		
}