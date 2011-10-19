<?php

    class C2dm 
    { 
    
    	/*
    	 * Authorize with google
    	 * return Auth key or false
    	 */
    	public static function client_auth() {
    	
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			
			$data = array('accountType' => 'GOOGLE',
			'Email' => Kohana::config('c2dm.user'),
			'Passwd' => Kohana::config('c2dm.password'),
			'source'=>'PHI-cUrl-Example',
			'service'=>'ac2dm');
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			$response = curl_exec($ch);
			if(!stristr($response, 'error')) {
				$tokens = explode('=', $response);
				if (isset($tokens[3])) {
					return trim($tokens[3]);
				}
			}
			return FALSE;
		} 
		
		/*
    	 * Send c2dm message
    	 * return response with message id or false
    	 */
		public static function send_message($auth_key, $reg_id, $collapse_key, $message_type, $form_code) {
			$ch = curl_init();
		
			$header[] = 'Authorization: GoogleLogin auth='.$auth_key;
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_URL, "https://android.apis.google.com/c2dm/send");
			
			$data = array('registration_id' => $reg_id,
			'collapse_key' => $collapse_key,
			'data.message_type' => $message_type,
			'data.form_code' => $form_code);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			$response = curl_exec($ch);
			$info = Kohana::debug(curl_getinfo($ch));
			if($info['http_code']==200 && stristr($response, 'id')) {
				return $response;
			}
			return FALSE;
			
		} 
		
}