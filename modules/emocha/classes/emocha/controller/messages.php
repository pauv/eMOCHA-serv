<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Messages Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Emocha_Controller_Messages extends Controller_Site {


	/**
	 *  before()
	 *
	 * Run before any action
	 */
	public function before()
	{
		parent::before();
		
		$this->template->title = 'Messages';
		$this->template->nav = View::factory('messages/nav');
		$this->template->curr_menu = 'messages';
		
		if(! $this->enable_alerts) {
			echo "alerts are disabled"; exit;
		}

	}
	
	/**
	 *  index()
	 *
	 * Default action
	 */
	public function action_index()
	{
		Request::instance()->redirect('messages/send');
	}
	
	
	/**
	 *  action_send()
	 *
	 * Send Gcm message to all enabled phones
	 */
	 
	public function action_send()
	{
	
		$content = $this->template->content = View::factory('messages/message');
		$content->response = false;

		if($_POST) {
			$post = Arr::xss($_POST);
			
			if($message = trim($post['message'])) {
			
				// get the auth key
				//$auth_key = C2dm::client_auth();
				$auth_key = Kohana::config('gcm.auth_key');
				//$auth_key = Config::get('platform', 'google_api_auth_key');
				
				// set collapse key
				$collapse_key = 'ck'.time();
				
				// iterate phones
				$phones = ORM::factory('phone')
							->where('alerts_id','!=','')
							->and_where('enable_alerts', '=', 1)
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