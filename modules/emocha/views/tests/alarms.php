

<h2>Checking Alarms</h2>

<?php

foreach($alarms as $alarm) {

	$alarm->check();
	
	echo "<h3>".$alarm->name."</h3>";
	
	echo "<p>Num alerts:".$alarm->num_alerts."</p>";
	
	echo nl2br($alarm->alert);
	
	echo nl2br($alarm->actions_taken_msg);

}
?>
