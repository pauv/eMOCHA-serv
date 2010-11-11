<link rel="stylesheet" type="text/css" href="/css/stats_export.css">

<div id="datalist">
<h3>This is the list of existing forms. Please click on one to show a table with all received data.</h3>
<?php 

	foreach($forms AS $form) {
		$link = '/stats/export/'.$form->id;
		echo Html::anchor($link, $form->name).', ';
	}	

?>
</div>

<?php

if($selected_form_id) {

	$link = '/stats/export_as_csv/'.$selected_form_id;
	echo Html::anchor($link, 'Download for spreadsheet import (tab separated)');
	
	echo $table; 
	
} ?>
