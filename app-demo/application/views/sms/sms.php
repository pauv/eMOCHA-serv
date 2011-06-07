

<h2>Sms to all <?php echo $recipients; ?></h2>

<?php if($response) { ?>
	Text: <?php echo $text; ?><br />
	<br />

	<?php if($error) { ?>
	Error: <?php echo $error; ?><br />
	<?php } 
	else { ?>
	Message successfully sent.<br />
	<?php 
	}
}
else { ?>



<?php echo Form::open('sms/send/'.$recipients); ?>
<?php echo Form::hidden('number', '34646728955'); ?><br />
Text:<br /><?php echo Form::textarea('text'); ?><br /><br />
<?php echo Form::submit('submit', 'Submit'); ?>
<?php echo Form::close(); ?>

<?php
}
?>


