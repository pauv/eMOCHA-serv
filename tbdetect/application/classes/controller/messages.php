<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Messages extends Emocha_Controller_Messages {

	/*
	Customisation: filter phones by the language
	*/
	public function action_send()
	{
	
		$content = $this->template->content = View::factory('messages/message');
		$content->response = false;

		if($_POST) {
			$post = Arr::xss($_POST);
			
			if($message = trim($post['message'])) {
				
				// get the auth key
				$auth_key = C2dm::client_auth();
				
				// set collapse key
				$collapse_key = 'ck'.time();
				
				// get the session language
				$language = Session::instance()->get('language');
				
				// iterate phones
				$phones = ORM::factory('phone')
							->where('c2dm_registration_id','!=','')
							->and_where('c2dm_disable', '=', 0)
							->and_where('language', '=', $language)
							->find_all();
				$phone_response = '';
				foreach($phones as $phone) {
					if($phone->send_alert($auth_key, $collapse_key, 'custom_message', '', $message)) {
						$phone_response .= "Message sent to phone id ".$phone->id."<br />";
					}
					else {
						$phone_response .= "Error sending to phone id ".$phone->id."\n";
					}
				}
				
				$content->response = true;
				$content->phone_response = $phone_response;
				$content->message = $message;
			}
		}
	}
}