<?php

class Api
{ 
    
    	public static function get_server_updated_times () {
    	
    		$times = array();
    		
    		
    		// media files
    		$sql = "SELECT MAX(ts) as last_updated FROM media INNER JOIN files
    					ON media.file_id = files.id";
    		$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$times['last_media_upd'] = $row['last_updated']==NULL?0:$row['last_updated'];
			
			// form files
			$sql = "SELECT MAX(ts) as last_updated FROM forms INNER JOIN files
    					ON forms.file_id = files.id";
    		$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$times['last_form_template_upd'] = $row['last_updated']==NULL?0:$row['last_updated'];
			
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