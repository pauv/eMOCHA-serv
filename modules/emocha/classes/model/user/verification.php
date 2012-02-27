<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User Verification Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Model_User_Verification extends ORM {

	// sorting date descending, means we can easily
	// select the most recent one
	protected $_sorting = array('date_created'=>'desc');
	
    
    public function generate_code() {
    
		while (TRUE)
		{
			// Create a random token
			$token = text::random('alnum', 24);

			// Make sure the token does not already exist
			$count = DB::select('id')
				->where('code', '=', $token)
				->from($this->_table_name)
				->execute($this->_db)
				->count();
			if ($count === 0)
			{
				// A unique token has been found
				$this->code = $token;
				return TRUE;
			}
		}
	}
    
    
    /**
	 * send_email()
	 * @return bool
	 */
    public function send_email(){
    	
    	$to = $this->email;
		$from = Kohana::config('email.options.username');
		
		switch($this->type) {
		
			case 'activation':
				$user = ORM::factory('user', $this->user_id);
				$subject = "eMocha Sign-up";
				$message = "Thanks for signing up\n"
							."Your username is: ".$user->username."\n\n"
							."Your account confirmation code is: ".$this->code."\n\n"
							."Enter the code into the box provided, or click on the following link to confirm your account in a new window:\n"
							.Url::site('auth/conf/'.$this->code);
			break;
			
			case 'email_change':
				$subject = "eMocha Email Change";
				$message = "Your email change confirmation code is: ".$this->code."\n\n"
							."Enter the code into the box provided, or click on the following link to confirm your email change in a new window:\n"
							.Url::site('auth/email/'.$this->code);
			break;
			
			case 'password':
				$subject = "eMocha Login Help";
				$message = "Your password change confirmation code is: ".$this->code."\n\n"
							."Enter the code into the box provided, or click on the following link to change your password in a new window:\n"
							.Url::site('auth/reset/'.$this->code);
			break;
			
				
		}
 
		if (Email::send($to, $from, $subject, $message)) {
    		return TRUE;
    	}
    	return FALSE;
    }
    
    
	/**
	 * @param  array: array containing encrypted code
	 * @param string:	type of verification
	 * Both must be correct and 
	 * the verification must be unused
	 * to return the verification
	 * @return mixed: user_verification object or false
	 * + sends any errors back to the controller
	 */
   public static function get_from_request(array & $validation, $type) {
   
   		$validation = Validate::factory($validation)
			->filter(TRUE, 'trim')
			->rules('code',  array(
								'not_empty'=>NULL
								));
		
		if ($validation->check()) {
			$verification = ORM::factory('user_verification')
									->where('code', '=', $validation['code'])
									->and_where('type', '=', $type)
									->and_where('verified', '=', 0)
									->find();
									
			if ($verification->loaded()) {
				return $verification;
			}
			else {
				$validation->error('code', 'invalid');
			}
		}
		
		return FALSE;
		
    }
    
    
    // mark verification as used
    public function mark_verified() {
  
		$this->verified = 1;
		$this->save();
		
    }
    

}