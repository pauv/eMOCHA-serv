<table class="list">

<?php
	if (! count($forms)) {
?>
	<tr>
		<td colspan="7">No files found.
		<br /><br />
		<button onclick="document.location.href='<?php echo Url::site('admin/edit_form'); ?>';">Add a form</button>
		</td>
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
	<th></th>

</tr>
<tr>
	<td colspan="7">
<?php if ($action) { ?>

	<div class="st_OK">
	The file was <?php echo $action; ?>
	</div>
	<br /><br />
	<?php } ?>
	<button onclick="document.location.href='<?php echo Url::site('admin/edit_form'); ?>';">Add a form</button>
	<br /><b>Note:</b>
Household Core and Patient Core forms need to be given code 'hcore' and 'pcore' respectively.<br />
Once in the system, form ids need to be preserved, so edit rather than delete a form which is already in use.
<br /><br />
	
<?php } ?>
</td>
</tr>


<?php 
	$count=1;
	foreach($forms as $form) { ?>
<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $form->name; ?> </td>
	<td><?php echo $form->group; ?> </td>
	<td><?php echo $form->code; ?> </td>
	<td><?php echo date('d-m-Y H:j:s', strtotime($form->last_modified)); ?></td>
	<td><?php echo Html::anchor('admin/edit_form/'.$form->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/form_files/'.$form->id, 'edit files') ?></td>
	<td><?php echo Html::anchor('admin/delete_form/'.$form->id, 'delete') ?></td>
</tr>

<?php 	
		$count++;
	}

?>		

</table>
