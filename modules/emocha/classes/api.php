<?php

class Api
{ 
    
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
			$sql = "SELECT UNIX_TIMESTAMP(MAX(last_modified)) as last_updated FROM configs
					WHERE label='application'";
    		$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$times['last_app_config_upd'] = $row['last_updated']==NULL?0:$row['last_updated'];
			
			
			return $times;
			
		} 
		
}