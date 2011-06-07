<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sms extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'eMocha - Sms';
		$this->template->nav = View::factory('sms/nav');
		$this->template->curr_menu = 'sms';

	}
	
	public function action_index()
	{
		Request::instance()->redirect('sms/send');
	}
	
	
	public function action_send($recipients='patients')
	{
	
		$content = $this->template->content = View::factory('sms/sms');
		$content->response = false;
		$content->recipients = $recipients;
		$this->template->curr_nav = $recipients;
		if($_POST) {
			$post = Arr::xss($_POST);
			$text = $post['text'];
			$number = $post['number'];
			$url = "https://api.clickatell.com/http/sendmsg?user=".Kohana::config('sms.user').
					"&password=".Kohana::config('sms.password').
					"&api_id=".Kohana::config('sms.api_id').
					"&to=".$number."&text=".urlencode($text);
			
			$ch=curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_HEADER,1);
			// don't enforce ssl cert verification
			// TODO: change this to make more secure?
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);
			
			if (empty($response))
			{
				$response="no response";
			}
			
			$content->response = $response;
			$content->number = $number;
			$content->text = $text;
			$content->error = $error;
		}
		

		
	}
	
	
}