

<h2>Testing sms</h2>

<?php if($response) { ?>
	Number: <?php echo $number; ?><br />
	Text: <?php echo $text; ?><br />
	<br />
	
	Response: <br /><?php echo nl2br($response); ?><br />
	
	<br />
	<?php if($error) { ?>
	Error: <?php echo $error; ?><br />
	<?php } 
} ?>



<?php echo Form::open('test/sms'); ?>
Number (format: 491712868837):<br /><?php echo Form::input('number'); ?><br /><br />
Text:<br /><?php echo Form::input('text'); ?><br /><br />
<?php echo Form::submit('submit', 'Submit'); ?>
<?php echo Form::close(); ?>


