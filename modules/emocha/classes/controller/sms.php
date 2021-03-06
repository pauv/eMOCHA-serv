<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sms Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Controller_Sms extends Controller_Site {


	/**
	 *  before()
	 *
	 * Run before any action
	 */
	public function before()
	{
		parent::before();
		
		$this->template->title = 'Sms demo';
		$this->template->nav = View::factory('messages/nav');
		$this->template->curr_menu = 'messages';

	}
	
	
	/**
	 *  index()
	 *
	 * Default action
	 */
	public function action_index()
	{
		Request::instance()->redirect('sms/send');
	}
	
	
	/**
	 *  action_send()
	 *
	 * Send sms to a specific telephone number
	 */
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