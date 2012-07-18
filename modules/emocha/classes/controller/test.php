<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Controller_Test extends Controller_Site {


	/**
	 *  before()
	 *
	 * Run before any action
	 */
	public function before()
	{
		parent::before();
		
		$this->template->title = 'eMocha - Test api';

	}
	
	
	/**
	 *  index()
	 *
	 * Default action
	 */
	public function action_index()
	{
		$content = $this->template->content = View::factory('tests/index');
		
	}
	
	
	/**
	 *  action_api()
	 *
	 * Forms for making test api calls
	 */
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
	
	
	/**
	 *  action_alarms()
	 *
	 * List alarms
	 */
	public function action_alarms()
	{
		$content = $this->template->content = View::factory('tests/alarms');
		$content->alarms = ORM::factory('alarm')->find_all();
		
	}
	
	
	/**
	 *  action_sms()
	 *
	 * Send an sms
	 */
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
	
	

    	
    /**
	 *  action_times()
	 *
	 * Test server times compared to database times
	 */
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
	


		/**
	 *  action_api()
	 *
	 * Forms for making test api calls
	 */
	public function action_xform()
	{
		$this->auto_render = FALSE;
		$content = View::factory('tests/xform')->render();
		echo $content;
	}
}
