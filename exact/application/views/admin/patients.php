<table class="list">

<?php
	if (! count($patients)) {
?>
	<tr>
		<td colspan="4">No patients found.
		<br /><br />
		<button onclick="document.location.href='<?php echo Url::site('admin/edit_patient'); ?>';">Add a patient</button>
		</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>Code</th>
	<th>Phone</th>
	<th></th>
	<th></th>

</tr>
<tr>
	<td colspan="4">
<?php if ($action) { ?>

	<div class="st_OK">
	The patient was <?php echo $action; ?>
	</div>
	<br /><br />
	<?php } ?>
	<button onclick="document.location.href='<?php echo Url::site('admin/edit_patient'); ?>';">Add a patient</button>
<br /><br />
	
<?php } ?>
</td>
</tr>



<?php
	$count=1;
		foreach($patients AS $patient) {
			?>
<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $patient->code; ?> </td>
	<td><?php echo $patient->phone->imei; ?>  </td>
	<td><?php echo Html::anchor('admin/edit_patient/'.$patient->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/delete_patient/'.$patient->id, 'delete') ?></td>
</tr>

<?php 	
			$count++;
		}

?>		

</table>
