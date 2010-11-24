<h1>Files for <?php echo $form->name; ?></h1>

<?php if ($action) { ?>
<div class="st_OK">
	The file was <?php echo $action; ?>
</div>
<?php } ?>

<p>
<?php echo Html::anchor('admin/edit_form_file', 'Add a file') ?>
</p>




<table>


<?php
	$form_files = $form->form_files->find_all();
	if (! count($form_files)) {
?>
	<tr>
		<td>No files found.</td>
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

<?php

		foreach($form_files AS $form_file) {
			?>
<tr>
	<td><?php echo $form_file->file->path; ?> </td>
	<td><?php echo $form_file->type; ?> </td>
	<td><?php echo $form_file->label; ?> </td>
	<td><?php echo date('d-m-Y H:j:s', strtotime($form_file->last_modified)); ?></td>
	<td><?php echo Html::anchor('admin/edit_form_file/'.$form_file->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/delete_form_file/'.$form_file->id, 'delete') ?></td>
</tr>

<?php 	
		}
	} 
?>		

</table>
