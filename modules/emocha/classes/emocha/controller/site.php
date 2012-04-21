<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Site Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @author     Pau Varela
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @copyright  2012 Pau Varela- pau.varela@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  

// Global Site template controller
// handles access permissions and loads default display elements

class Emocha_Controller_Site extends Controller_Template 
{

	public $template = 'site_template';
  
	public $login_required = TRUE;
	
	// set this to an array of roles e.g. ('admin', 'moderator')
	// to do additional role checks
	public $roles_required = FALSE;
	
	public $user = FALSE;
	
	/**
	* The before() method is called before your controller action.
	* In our template controller we override this method so that we can
	* set up default values. These variables are then available to our
	* controllers if they need to be modified.
	*/
	public function before()
	{
		parent::before();
		  
		// Open session
		$this->session= Session::instance();
		
		// if logged in, check the login is for the current app
		// to prevent login leaking between apps
		if(Auth::instance()->logged_in()) {
			if ($this->session->get('base_url', NULL) != Kohana::$base_url) {
				Auth::instance()->logout();
				$this->session->destroy();
			}
		}
		
		//get version_name
		$version_name = ORM::factory('config')
										->where('label','=',Kohana::config('values.version_name'))
										->and_where('type','=',Kohana::config('values.server'))
										->find();
		if ($version_name->loaded() AND $version_name->content)
		{
			$this->template->version_name = $version_name->content;
		} else 
		{
			$this->template->version_name = Kohana::config('values.version_name');
		}
		
		// Check user auth
		if ($this->login_required) {
			if(Auth::instance()->logged_in() === FALSE) {
				Request::instance()->redirect('auth/login');
			}
			else {
				// assign user object to controller
				// for ease of use
				$this->user = Auth::instance()->get_user();
				
				// check in case of role limits
				if (is_array($this->roles_required)) {
					if ( ! Auth::instance()->logged_in($this->roles_required)) {
						Request::instance()->redirect('auth/access');
					}
				}			
			}
		}
		

		
		
		// display stuff
		if ($this->auto_render)
		{
			// Initialize empty values
			$this->template->title   = '';
			$this->template->content = '';
			$this->template->curr_nav = $this->request->action;
			
			// login and user details
			if($this->user) 
			{
				$this->template->logged_in = TRUE;
				$this->template->user = $this->user;
				$this->template->is_admin_user = Auth::instance()->logged_in('admin');
			}
			else 
			{
				$this->template->logged_in = FALSE;
			}
			
			$this->template->styles = array();
			$this->template->scripts = array();
					
		}
	}
	
	

	/**
	* The after() method is called after your controller action.
	* In our template controller we override this method so that we can
	* make any last minute modifications to the template before anything
	* is rendered.
	*/
	public function after()
	{
		if ($this->auto_render)
		{
		/*
		$styles = array(
			'media/css/screen.css' => 'screen, projection',
			'media/css/print.css' => 'print',
			'media/css/style.css' => 'screen',
		);
		
		$scripts = array(
			'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',
		);
		
		$this->template->styles = array_merge( $this->template->styles, $styles );
		$this->template->scripts = array_merge( $this->template->scripts, $scripts );
		*/
		}
		parent::after();
	}
}
