<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Alarm extends ORM {

	protected $_has_many = array (
								'alarm_conditions'=>array(),
								'alarm_actions'=>array()
							);
	
	public $alert = '';
	public $num_alerts = 0;
	public $actions_taken_msg = '';

	public function check() {
		foreach ($this->alarm_conditions->find_all() as $condition) {
			
			if( $condition->check() ) {
				$this->num_alerts++;
				$this->alert .= $condition->description.": (alarm value=".$condition->value.", value found=".$condition->value_found.")\n";
			}
		}
		if ($this->num_alerts) {
			foreach ($this->alarm_actions->find_all() as $action) {
				$result = $action->execute($this->alert);
				if ($result) $this->actions_taken_msg .= $result."\n";
			}
		}
		
	}
}