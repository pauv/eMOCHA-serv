<p>
Form: <?php echo $form_data->form->name; ?><br />
<?php if ($form_data->patient_code) { ?>
	Patient: <?php echo $form_data->patient_code; ?><br />
<?php } ?>
<small>(File name: <?php echo $form_data->get_form_name(); ?>)</small>

<?php if($form_data->rejected=='late'){ ?>
<p class="error">Rejected as late</p>
<?php } ?>
<?php if($form_data->rejected=='no_reply'){ ?>
<p class="error">Reminder ignored</p>
<?php } ?>


</p>
<?php
	echo $form_data->display_result();

?>