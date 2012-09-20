
<h3>Filter</h3>
<form method="post" action="<?php echo Url::site('main/map') ?>">
	Patient<br/>
	<?php echo Form::select('patient_code', $patients, $patient_code, array('id'=>'patient_code','onchange'=>'javascript:display_form();return false;')); ?>
	<br /><br />
	Date<br/>
	<?php echo Form::select('date', array_merge(array('0'=>'All'), $dates), $date); ?>
	<br /><br />
	<input type="submit" value="search" />
</form>
<form method="post" action="<?php echo Url::site('main/map') ?>">
	