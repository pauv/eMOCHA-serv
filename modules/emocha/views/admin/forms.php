<h1>Forms</h1>

<?php if ($action) { ?>
<div class="st_OK">
	The file was <?php echo $action; ?>
</div>
<?php } ?>

<p>
<?php echo Html::anchor('admin/edit_form', 'Add a form') ?>
</p>


<b>Note:</b><br />
Household Core and Patient Core forms need to be given code 'household_core' and 'patient_core' respectively.<br />
Once in the system, form ids need to be preserved, so edit rather than delete a form which is already in use.
<br /><br />

<table>


<?php
	if (! count($forms)) {
?>
	<tr>
		<td>No files found.</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>Name</th>
	<th>Group</th>
	<th>Code</th>
	<th>Last modified</th>
	<th></th>
	<th></th>

</tr>

<?php
		foreach($forms AS $form) {
			?>
<tr>
	<td><?php echo $form->name; ?> </td>
	<td><?php echo $form->group; ?> </td>
	<td><?php echo $form->code; ?> </td>
	<td><?php echo date('d-m-Y H:j:s', strtotime($form->last_modified)); ?></td>
	<td><?php echo Html::anchor('admin/edit_form/'.$form->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/delete_form/'.$form->id, 'delete') ?></td>
</tr>

<?php 	
		}
	} 
?>		

</table>
