<div id="inner_content">
<p>
Do you really want to delete the patient '<?php echo $patient->code; ?>'?
</p>

<p>
<?php echo Html::anchor('admin/delete_patient_confirmed/'.$patient->id, 'Yes') ?>
</p>

<p>
<?php echo Html::anchor('admin/patients/', 'No, cancel') ?>
</p>
</div>
