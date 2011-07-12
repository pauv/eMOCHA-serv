<div id="inner_content"><p>
Do you really want to delete the file '<?php echo $form_file->file->filename; ?>'?
</p>

<p>
<?php echo Html::anchor('admin/delete_form_file_confirmed/'.$form_file->form->id.'/'.$form_file->id, 'Yes') ?>
</p>

<p>
<?php echo Html::anchor('admin/form_files/'.$form_file->form->id, 'No, cancel') ?>
</p>
</div>
