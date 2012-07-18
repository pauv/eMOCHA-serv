
<div id="inner_content">
<b>Send alert to all alert-enabled phones</b>

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

