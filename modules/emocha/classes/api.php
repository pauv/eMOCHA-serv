<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Api helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
 class Api
{ 
    
    	/**
		 *  get_server_updated_times ()
		 *
		 * Get last updated times for key data
		 * so that the client knows whether to download updates
		 *
		 * @return array
		 */
    	public static function get_server_updated_times () {
    	
    		$times = array();
    		
    		
    		// media
			$sql = "SHOW TABLE STATUS LIKE 'media'";
    		$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$times['last_media_upd']=strtotime($row['Update_time']);
			
			// form config
			$sql = "SHOW TABLE STATUS LIKE 'forms'";
    		$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$times['last_form_config_upd']=strtotime($row['Update_time']);

			
			// app config
			$sql = "SELECT UNIX_TIMESTAMP(MAX(last_modified)) as last_updated FROM configs";
					//WHERE label='application'";
    		$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$times['last_app_config_upd'] = $row['last_updated']==NULL?0:$row['last_updated'];
			
			
			return $times;
			
		} 
		
}