<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Auth Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */   
class Controller_Auth extends Controller_Site {

	// login not required for this controller
	public $login_required = FALSE;
	
	
	/**
	 *  before()
	 *
	 * Run before any action
	 * Checks user authentication
	 */
	public function before()
	{
		parent::before();

		if(Auth::instance()->logged_in()!= 0 && Request::instance()->action!='logout'){
			// apart from logout action
			// redirect to the main page if already logged in
			Request::instance()->redirect('main');		
		}
	
	}
	
	/**
	 *  index()
	 *
	 * Default action
	 */
	public function action_index()
	{
		// redirect to the login page as default
		Request::instance()->redirect('auth/login');
	}
	
	
	/**
	 *  action_login()
	 *
	 * Handle user login
	 */
	public function action_login()
	{
 		$this->template->title = 'Login';
		$content = $this->template->content = View::factory('auth/login');	
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
			//Instantiate a new user
			$user = ORM::factory('user');
 
			//Check Auth
			$status = $user->login($_POST);
 
			//If the post data validates using the rules setup in the user model
			if ($status)
			{		
				//redirect to the user account
				Request::instance()->redirect('');
			}else
			{
                //Get errors for display in view
                //var_dump($_POST->errors());
				$content->errors = $_POST->errors('login');
			}
 
		}
	}
 
 
 
 	/**
	 *  action_logout()
	 *
	 * Logout user
	 */
	public function action_logout()
	{
		//Sign out the user
		Auth::instance()->logout();
 
		//redirect to the user account and then the signin page if logout worked as expected
		Request::instance()->redirect('auth/login');		
	}
	
	
	
	
	
	/**
	 *  actionregister()
	 *
	 * Register user, display form or post form results
	 */
	public function action_register()
	{	
		$this->template->title = 'Register';
		//Load the view
		$content = $this->template->content = View::factory('auth/register');
		// assign empty vals
		$content->form_vals = array();
		
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
 			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to form
 			// in case of error
			$content->form_vals = $post;
 			
 			//Instantiate a user
			$user = ORM::factory('user');
 			
			//Load the validation rules, filters etc...
			$validation = $user->validate_create($post);	
			
 
			//If the validation data validates using the rules setup in the user model
			if ($validation->check())
			{
 				
				// fill with validated values
				$user->values($validation);
				//create the account
				$user->save();
 
				//Add the login role to the user
				$login_role = new Model_Role(array('name' =>'login'));
				$user->add('roles',$login_role);
				
				
				// create and send out 
				// email verification code
				// create and send out 
				// email verification code
				if ( ! $user->send_verification_email('activation'))
				{
					// email sent, redirect to activation page
					Request::instance()->redirect('auth/email_error');
				}
				else
				{
				
					// email sent, redirect to activation page
					Request::instance()->redirect('auth/conf');
					
				}
 				
				
			}
			else
			{
                // Get errors for display in view
				$content->errors = $validation->errors('user');

			}			
		}		
	}
 
 
 
	/**
	 *  action_conf()
	 *
	 * Checks email verification
	 * activates user account
	 *
	 * @param string
	 */
	public function action_conf($code = FALSE) 
	{
		$this->template->title = 'Register';
		// load default activation form
		$content = $this->template->content = View::factory('auth/activate');
		
		// check to see if code was submit via url or $_POST
		if ($validation = ($code ? array('code'=>$code) : $_POST)) {
			
			// check code valid
			if ( ! $verification = Model_User_Verification::get_from_request($validation, 'activation')) 
			{
				// display invalid code error on failure
				$content->errors = $validation->errors('user_verification');
			}
			
			// validation ok
			else 
			{
				// load user
				$user = ORM::factory('user', $verification->user_id);
				
				// mark verification as used
				$verification->mark_verified();
				
				// activate user account
				$user->activate();
	 
				// display activated message
				Request::instance()->redirect('auth/activated');
			}
		}		
	}
	
	

	/**
	 *  action_email_error()
	 *
	 * Email couldn't be sent, display special page
	 */
	public function action_email_error() 
	{
		// load view
		$this->template->title = 'Register';
		$content = $this->template->content = View::factory('auth/error');
		$content->errors = array(Kohana::message('user_verification', 'email.send_failure'));
	
	}
	
	

	/**
	 *  action_activated()
	 *
	 * Display activated message after successful verification
	 */
	public function action_activated() 
	{
		// load view
		$this->template->title = 'Register';
		$content = $this->template->content = View::factory('auth/activated');
	
	}
	
	
	
	/**
	 *  action_forgot_password()
	 *
	 * Forgot password, handle password re-request
	 */
	public function action_forgot_password() 
	{
		$this->template->title = 'Forgot password';
		//default view
		$content = $this->template->content = View::factory('auth/forgot_password');
		// assign empty vals
		$content->form_vals = array();
		
		if ($_POST) 
		{
			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to form
 			// in case of error
			$content->form_vals = $post;
			
			$validation = Validate::factory($post)
						->filter(TRUE, 'trim')
						->rules('email', array(
								'not_empty'		=> NULL,
								'validate::email'	=> NULL,
							));
						
			if ($validation->check()) 
			{
				$user = ORM::factory('user')->where('email', '=', $validation['email'])->find();
					
				if ( ! $user->loaded()) 
				{
					$validation->error('email', 'unknown');
				}
				else 
				{
					// generate and send out
					// email verification code
					$email_verification = ORM::factory('user_verification');
					$email_verification->type = 'password';
					$email_verification->user_id = $user->id;
					$email_verification->email = $user->email;
					$email_verification->generate_code();
					
					if ( ! $email_verification->send_email())
					{
						$validation->error('email', 'send_failure');
					}
					else 
					{
						$email_verification->save();
						Request::instance()->redirect('auth/reset');
					} 
				} 
			}
			
			$content->errors = $validation->errors('user_verification');
		} 
	}
	
	

    /**
	 *  action_reset()
	 *
	 * Checks password verification
	 *
	 * @param string
	 */
	public function action_reset($code = FALSE) 
	{
		$this->template->title = 'Password';
		// load view
		$content = $this->template->content = View::factory('auth/reset_password');	
	
	
		// check to see if code was submit via url or $_POST
		if ( ! $code_validation = ($code ? array('code'=>$code) : $_POST)) 
		{
			// no code submitted just show initial form
			$content->code = '';
		}
		
		// code submitted
		else 
		{
			
			// check code valid
			if ( ! $verification = Model_User_Verification::get_from_request($code_validation, 'password')) 
			{
				// display invalid code error on failure
				$content->errors = $code_validation->errors('user_verification');
				$content->code = $code_validation['code'];
				
			} 
			else 
			{
				// check to see if password was also submitted
				if (Arr::get($_POST, 'password')) 
				{
					$user = ORM::factory('user', $verification->user_id);
					
					//Load the validation rules, filters etc...
					$pass_validation = $user->validate_reset_password($_POST);	
					
					//If the validation data validates using the rules setup in the user model
					if ( ! $pass_validation->check())
					{
						// password validation failed
						// display form again with errors
						$content->code = $code_validation['code'];
						$content->errors = $pass_validation->errors('user');
					} 
					else 
					{
					
						// all validation successful
						// save  password (automatically gets re-encrypted)
						$user->password = $pass_validation['password'];
						$user->save();
						
						// mark verification as used
						$verification->mark_verified();
						
						// display confirmation message
						Request::instance()->redirect('auth/reset_ok');
					
					}
				} 
				else 
				{
					// display form for entering new password
					// with code already confirmed
					$content->code = $code_validation['code'];
				}
			} 
		}
	}
 
 

	/**
	 *  action_reset_ok()
	 *
	 * Display activated message after successful password reset
	 */
	public function action_reset_ok() 
	{
		$this->template->title = 'Password';
		// load view
		$content = $this->template->content = View::factory('auth/reset_password_ok');
	
	}
	
	
	

	/**
	 *  action_access()
	 *
	 * Display access barred message when user has tried to view a forbidden page
	 */
	public function action_access() {

		$this->template->title = 'Error';
		$content = $this->template->content = View::factory('auth/access');
		
	}
		
}