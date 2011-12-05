<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Controller {

	
	public function before() {
		parent::before();
		//make sure this is run from command line
		if(! Kohana::$is_cli){
			echo "Access forbidden";
			exit;
		}
	}
	
	
	 public function action_test()
	{
		echo "testing\n";
	}
	
	
	/*
	 * Handle random form reminders 
	 */
	 public function action_random()
	{
		echo "Timezone is ".date_default_timezone_get()."\n";
		$time = date("H:i:s", time() );
		echo "Time now is $time \n";
		// settings
		$fillout_time_mins = 15;
		
		$current_time = date('G');
		if(!($current_time>=9  && $current_time<21)){
			echo "Night time \n";
			exit;
		}
		else {
			$today = date('Y-m-d');
			// handle scheduling
			$schedules_today = ORM::factory('phone_alert_schedule')
							->where('date','=',DB::expr('CURDATE()'))
							->count_all();
			if(! $schedules_today) {
				// no alerts yet scheduled
				$times = $this->random_times();
				foreach($times as $ts) {
					$schedule = ORM::factory('phone_alert_schedule');
					$schedule->date = $today;
					$schedule->ts = $ts;
					echo "Scheduled ".date('H:i:s', $ts)."\n";
					$schedule->save();
				}
			}
			// check for schedule to send
			$to_send = ORM::factory('phone_alert_schedule')
					->where('date','=',$today)
					->and_where('sent','=',0)
					->and_where('ts','<=',time())
					->find();
			if($to_send->loaded()) {
				// needs sending
				echo "Sending RANDOM alerts at ".date('H:i:s')."\n";
				// get auth key
				$auth_key = C2dm::client_auth();
				// set collapse key
				$collapse_key = 'ck'.time();
				// iterate phones
				$phones = ORM::factory('phone')
							->where('c2dm_registration_id','!=','')
							->and_where('c2dm_disable', '=', 0)
							->find_all();
				foreach($phones as $phone) {
					/*if($response = C2dm::send_message($auth_key, $phone, $collapse_key, 'form_reminder', 'erandom')) {
						$phone->log_alert('form_reminder', 'erandom', '', $response);
						echo "Alert sent to phone id ".$phone->id."\n";
					}
					else {
						echo "Error sending to phone id ".$phone->id."\n";
					}*/
					if($phone->send_alert($auth_key, $collapse_key, 'custom_message', 'form_reminder', 'erandom', '')) {
						echo "Alert sent to phone id ".$phone->id."\n";
					}
					else {
						echo "Error sending to phone id ".$phone->id."\n";
					}
				}
				$to_send->sent = 1;
				$to_send->save();
			}
		}
		echo "\n";
		
	}
	
	/*
	 * get random times
	 */
	function random_times() {
	
		$nine = mktime(9,0,0);
		$times = array();
		// alert 1, (9am-12pm)
		$random = rand(0,10800);
		$times[1] = $nine + $random;
		// alert 2 (12pm-3pm)
		$random = rand(10800,21600);
		$times[2] = $nine + $random;
		// alert 3 (3pm-6pm)
		$random = rand(21600,32400);
		$times[3] = $nine + $random;
		// alert 4 (6pm-8.45pm)
		$random = rand(32400,42300);
		$times[4] = $nine + $random;
		
		// check times are sufficiently spaced
		if($times[2]-$times[1] > 3600 && $times[3]-$times[2] > 3600 && $times[4]-$times[3] > 3600) {
			return $times;
		}
		else {
			return $this->random_times();
		}
	}


	/*
	 * Handle random form reminders 
	 */
	 public function action_end_of_day()
	{
		echo "Timezone is ".date_default_timezone_get()."\n";
		$time = date("H:i:s", time() );
		echo "Time now is $time \n";
		// settings
		$fillout_time_mins = 15;
		
		$current_time = date('G');
		// check after nine and before 10
		// (this script will run hourly)
		if(!($current_time>=21  && $current_time<22)){
			echo "Not right time\n";
			exit;
		}
		else {
			// needs sending
			echo "Sending END OF DAY alerts at ".date('H:i:s')."\n";
			// get auth key
			$auth_key = C2dm::client_auth();
			// set collapse key
			$collapse_key = 'ck'.time();
			// iterate phones
			$phones = ORM::factory('phone')
						->where('c2dm_registration_id','!=','')
						->and_where('c2dm_disable', '=', 0)
						->find_all();
			foreach($phones as $phone) {
				/*if($response = C2dm::send_message($auth_key, $phone, $collapse_key, 'form_reminder', 'edaily')) {
					$phone->log_alert('form_reminder', 'edaily', '', $response);
					echo "Alert sent to phone id ".$phone->id."\n";
				}
				else {
					echo "Error sending to phone id ".$phone->id."\n";
				}*/
				if($phone->send_alert($auth_key, $collapse_key, 'custom_message', 'form_reminder', 'edaily', '')) {
					echo "Alert sent to phone id ".$phone->id."\n";
				}
				else {
					echo "Error sending to phone id ".$phone->id."\n";
				}
			}
		}
		echo "\n";
	}
	
	public function action_cli_test(){
		echo (bool) Kohana::$is_cli;
		echo ":".php_sapi_name()."\n";
	}
	
	
	public function action_end_of_day_force() {
		// needs sending
		echo "Sending END OF DAY alerts at ".date('H:i:s')."\n";
		// get auth key
		$auth_key = C2dm::client_auth();
		// set collapse key
		$collapse_key = 'ck'.time();
		// iterate phones
		$phones = ORM::factory('phone')
					->where('c2dm_registration_id','!=','')
					->and_where('c2dm_disable', '=', 0)
					->find_all();
		foreach($phones as $phone) {
			/*if($response = C2dm::send_message($auth_key, $phone, $collapse_key, 'form_reminder', 'edaily')) {
				$phone->log_alert('form_reminder', 'edaily', '', $response);
				echo "Alert sent to phone id ".$phone->id."\n";
			}
			else {
				echo "Error sending to phone id ".$phone->id."\n";
			}*/
			if($phone->send_alert($auth_key, $collapse_key, 'custom_message', 'form_reminder', 'edaily', '')) {
				echo "Alert sent to phone id ".$phone->id."\n";
			}
			else {
				echo "Error sending to phone id ".$phone->id."\n";
			}
		}
	}
	
	
	public function action_random_force() {
		// needs sending
		echo "Sending RANDOM alerts at ".date('H:i:s')."\n";
		// get auth key
		$auth_key = C2dm::client_auth();
		// set collapse key
		$collapse_key = 'ck'.time();
		// iterate phones
		$phones = ORM::factory('phone')
					->where('c2dm_registration_id','!=','')
					->and_where('c2dm_disable', '=', 0)
					->find_all();
		foreach($phones as $phone) {
			/*if($response = C2dm::send_message($auth_key, $phone, $collapse_key, 'form_reminder', 'erandom')) {
				$phone->log_alert('form_reminder', 'erandom', '', $response);
				echo "Alert sent to phone id ".$phone->id."\n";
			}
			else {
				echo "Error sending to phone id ".$phone->id."\n";
			}*/
			if($phone->send_alert($auth_key, $collapse_key, 'custom_message', 'form_reminder', 'erandom', '')) {
				echo "Alert sent to phone id ".$phone->id."\n";
			}
			else {
				echo "Error sending to phone id ".$phone->id."\n";
			}
		}
	}
	
}
