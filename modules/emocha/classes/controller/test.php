<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Test extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'eMocha - Test api';

	}
	
	
	public function action_index()
	{
		$content = $this->template->content = View::factory('tests/index');
		
	}
	
	public function action_api()
	{
		$content = $this->template->content = View::factory('tests/api');
		$phone = ORM::factory('phone')->where('validated', '=', 1)->find();
		if($phone->loaded()){
			$content->usr = $phone->imei_md5;
		}
		else {
			$content->usr = '';
		}
	}
	
	
	public function action_alarms()
	{
		$content = $this->template->content = View::factory('tests/alarms');
		$content->alarms = ORM::factory('alarm')->find_all();
		
	}
	
	
	
	public function action_sms()
	{
	
		$content = $this->template->content = View::factory('tests/sms');
		$content->response = false;
	
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
	
	
	
	
	public function action_cdma () {
    		$r = Phone::is_cdma_valid('123345efa5223');
    		echo Kohana::debug($r);
    	}
	


}
