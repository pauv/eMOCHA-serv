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
    	
    	
    
	public function action_times () {
			$str = '';
    		$str .= "Timezone is ".date_default_timezone_get()."<br />";
			$str .= "Time is ".date("G:i:s A", time() )."<br />";
			$str .= "Time offset is ".date('P', time() )."<br />";
			$sql = "SELECT @@global.time_zone as global_time, @@session.time_zone as session_time, NOW() as cur_time";
			$result = DB::query(Database::SELECT, $sql)->execute();
			$row = $result->current();
			$str .= "DB global timezone is ".$row['global_time']."<br />";
			$str .= "DB session timezone is ".$row['session_time']."<br />";
			$str .= "DB time is ".$row['cur_time']."<br />";
			$this->template->content = $str;
    	}
	
	
	public function action_google_client_login() {
	
		// client login
		/*
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$data = array('accountType' => 'GOOGLE',
		'Email' => 'emocha.exact@gmail.com',
		'Passwd' => 'jooth7eed:iu2Ah',
		'source'=>'PHI-cUrl-Example',
		'service'=>'ac2dm');
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$hasil = curl_exec($ch);
		
		//$this->template->content = nl2br($hasil);
		
		$tokens = explode('=', $hasil);
		//echo Kohana::debug($tokens);
		//$this->template->content = $tokens[3];
		$this->template->content =  "Auth token: ".$tokens[3];
		
		// c2dm call
	
		$ch = curl_init();
		
		$header[] = 'Authorization: GoogleLogin auth='.trim($tokens[3]);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_URL, "https://android.apis.google.com/c2dm/send");
		
		$data = array('registration_id' => 'APA91bGJh3UtZHYL-cSdGxNjmyNVyRYcUKGKgonS_aAZP6_f3liX8fe758W7-hrMapJphbLuZuphhxriBfDeKcEKbiXWENXsgXJbB3WLZlJ0gc_TDPgPBK4K3SiHpwUV7btEcfEVg-FnN65-NPTXgXDYQkOcRYbD2g',
		'collapse_key' => 'rtert45767',
		'data.message_type' => 'form_reminder_c',
		'data.form_code' => 'erandom');
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$hasil = curl_exec($ch);
		
		$info = Kohana::debug(curl_getinfo($ch));
		
		$this->template->content .= '<br /><br />Registration id:<br />APA91bGJh3UtZHYL-cSdGxNjmyNVyRYcUKGKgonS_aAZP6_f3liX8fe758W7-hrMapJphbLuZuphhxriBfDeKcEKbiXWENXsgXJbB3WLZlJ0gc_TDPgPBK4K3SiHpwUV7btEcfEVg-FnN65-NPTXgXDYQkOcRYbD2g';
		
		$this->template->content .= '<br /><br />Curl info:<br />'.$info;
		
		$this->template->content .= '<br /><br />Response including headers:<br />'.$hasil;
		

	*/
		$auth_key = C2dm::client_auth();
		echo 'Auth<br />'.Kohana::debug($auth_key);
		if($auth_key) {
			$reg_id = 'PA91bGJh3UtZHYL-cSdGxNjmyNVyRYcUKGKgonS_aAZP6_f3liX8fe758W7-hrMapJphbLuZuphhxriBfDeKcEKbiXWENXsgXJbB3WLZlJ0gc_TDPgPBK4K3SiHpwUV7btEcfEVg-FnN65-NPTXgXDYQkOcRYbD2g';
			$response = C2dm::send_message($auth_key, $reg_id, 'dsfsdf', 'form_reminder', 'erandom');
			echo 'Msg<br />'.Kohana::debug($response);
		}
	}


}
