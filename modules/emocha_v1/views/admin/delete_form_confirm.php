<h1>Delete file</h1>

<p>
Do you really want to delete the form '<?php echo $form->name; ?>'?
</p>

<p>
<?php echo Html::anchor('admin/delete_form_confirmed/'.$form->id, 'Yes') ?>
</p>

<p>
<?php echo Html::anchor('admin/forms', 'No, cancel') ?>
</p>
