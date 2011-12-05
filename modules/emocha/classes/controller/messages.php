<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Messages extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'Messages';
		$this->template->nav = View::factory('messages/nav');
		$this->template->curr_menu = 'messages';

	}
	
	public function action_index()
	{
		Request::instance()->redirect('messages/send');
	}
	
	
	public function action_send()
	{
	
		$content = $this->template->content = View::factory('messages/message');
		$content->response = false;
		//$this->template->curr_nav = $recipients;
		//$post = Arr::xss($_POST);
		if($_POST) {
			$post = Arr::xss($_POST);
			
			if($message = trim($post['message'])) {
			
				//$message  = urlencode($message);
				
				$auth_key = C2dm::client_auth();
				// set collapse key
				$collapse_key = 'ck'.time();
				// iterate phones
				$phones = ORM::factory('phone')
							->where('c2dm_registration_id','!=','')
							->and_where('c2dm_disable', '=', 0)
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
				//$content->error = $error;
			}
		}
		

		
	}
	
	
}