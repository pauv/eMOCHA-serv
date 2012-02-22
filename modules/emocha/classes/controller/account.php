<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Account Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */    
class Controller_Account extends Controller_Site {
	
	public function before()
	{
		parent::before();
		
		$this->template->title = 'Account';
		$this->template->nav = View::factory('account/nav');
		$this->template->curr_menu = 'account';

	}
	
	public function action_index()
	{
		// redirect to the login page as default
		Request::instance()->redirect('account/details');
	}
	
	
	public function action_details()
	{	
		$this->template->title = 'Account details';
		//Load the view
		$content = $this->template->content = View::factory('account/details');
		$content->user = $this->user;
 
	}
	
	
	public function action_edit_details()
	{	
		$this->template->title = 'Edit account details';
		//Load the view
		$content = $this->template->content = View::factory('account/edit_details');
		$content->user = $this->user;
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
 			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to form
 			// in case of error
			$content->form_vals = $post;
 			
			//Load the validation rules, filters etc...
			$validation = $this->user->validate_edit($post);	
			
 
			//If the validation data validates using the rules setup in the user model
			if ($validation->check())
			{
 				
				// fill with validated values
				$this->user->values($validation);
				//save the account
				$this->user->save();
				Request::instance()->redirect('account/details');
				
			}
			else
			{
                // Get errors for display in view
				$content->errors = $validation->errors('user');

			}			
		}
		
		else 
		{
			// assign current user data
			$content->form_vals = $this->user->as_array();
			
		}
	}
	
	
	public function action_change_password()
	{	
		$this->template->title = 'Change password';
		//Load the view
		$content = $this->template->content = View::factory('account/change_password');
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
			$validation = $this->user->validate_reset_password($_POST);	
					
			if ($validation->check())
			{
				// save  password (automatically gets re-encrypted)
				$this->user->password = $validation['password'];
				$this->user->save();
				Request::instance()->redirect('account/details');
			} 
			else 
			{
				$content->errors = $validation->errors('user');
			}			
		}
	}
 
}