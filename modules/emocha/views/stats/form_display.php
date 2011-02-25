<p>
Form: <?php echo $form_data->form->name; ?><br />
Household: <?php echo $form_data->household_code; ?> (<?php echo Html::anchor('main/map/households/'.$form_data->household_code, 'view on map') ?>)<br />
<?php if ($form_data->patient_code) { ?>
	Patient: <?php echo $form_data->patient->first_name.' '.$form_data->patient->last_name; ?> (<?php echo $form_data->patient_code; ?>)<br />
<?php } ?>
<small>(File name: <?php echo $form_data->get_form_name(); ?>)</small>

</p>
<?php
	echo $form_data->display_result();

?>