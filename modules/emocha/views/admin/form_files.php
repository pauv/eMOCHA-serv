
<table class="list">


<?php
	$form_files = $form->form_files->find_all();
	if (! count($form_files)) {
?>
	<tr>
		<td>No files found.
		<br /><br />
		<button onclick="document.location.href='<?php echo Url::site('admin/edit_form_file/'.$form->id); ?>';">Add a file</button>
		</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>File</th>
	<th>Type</th>
	<th>Label</th>
	<th>Last modified</th>
	<th></th>
	<th></th>

</tr>


<tr>
<td colspan="6">
<?php if ($action) { ?>
<div class="st_OK">
	The file was <?php echo $action; ?>
</div><br /><br />
<?php } ?>
	<button onclick="document.location.href='<?php echo Url::site('admin/edit_form_file/'.$form->id); ?>';">Add a file</button>
</td>
</tr>

<?php
		$count=1;
		foreach($form_files AS $form_file) {
			?>
<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $form_file->file->path; ?> </td>
	<td><?php echo $form_file->type; ?> </td>
	<td><?php echo $form_file->label; ?> </td>
	<td><?php echo date('d-m-Y H:i:s', strtotime($form_file->last_modified)); ?></td>
	<td><?php echo Html::anchor('admin/edit_form_file/'.$form->id.'/'.$form_file->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/delete_form_file/'.$form->id.'/'.$form_file->id, 'delete') ?></td>
</tr>

<?php 	
			$count++;
		}
	} 
?>		

</table>
