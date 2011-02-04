<h1>Patients</h1>

<?php if ($action) { ?>
<div class="st_OK">
	The patient was <?php echo $action; ?>
</div>
<?php } ?>

<p>
<?php echo Html::anchor('admin/edit_patient', 'Add a patient') ?>
</p>




<table>


<?php
	if (! count($patients)) {
?>
	<tr>
		<td>No patients found.</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>Code</th>
	

</tr>

<?php
		foreach($patients AS $patient) {
			?>
<tr>
	<td><?php echo $patient->code; ?> </td>
	<td><?php echo Html::anchor('admin/edit_patient/'.$patient->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/delete_patient/'.$patient->id, 'delete') ?></td>
</tr>

<?php 	
		}
	} 
?>		

</table>
