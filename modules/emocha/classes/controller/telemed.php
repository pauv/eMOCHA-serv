<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Telemed Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Controller_Telemed extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'Telemed';
		$this->template->nav = View::factory('telemed/nav');
		$this->template->curr_menu = 'telemed';

	}

	public function action_index() {
		Request::instance()->redirect('telemed/datasel');
	}
	
	
	
	
	public function action_datasel() {
		$this->template->title = 'Datesel';
		$this->template->content = View::factory('telemed/datasel');
	}
	
	public function action_notes() {
		$this->template->title = 'Notes';
		$this->template->content = View::factory('telemed/notes');
	}
}
