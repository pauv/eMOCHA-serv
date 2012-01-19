
<div id="inner_content">
<b>Send C2dm alert to all <?php
	// customisation to auto-select the session language
	$language = Session::instance()->get('language');
	$languages = Kohana::config('language.languages');
	echo $languages[$language]; ?> phones</b>

<?php if($response) { ?>
	<br /><br />
	Message: <br />
	<?php echo nl2br($message); ?><br />
	<br />

	
	Response:<br />
	<?php echo $phone_response; ?>
	<?php 
}
else { ?>



<?php echo Form::open('messages/send/'); ?>
Message:<br /><?php echo Form::textarea('message'); ?><br /><br />
<?php echo Form::submit('submit', 'Submit', array('class'=>'button')); ?>
<?php echo Form::close(); ?>

<?php
}
?>

</div>

