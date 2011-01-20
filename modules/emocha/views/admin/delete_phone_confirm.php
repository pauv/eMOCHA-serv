<h1>Delete phone</h1>

<p>
Do you really want to delete the phone '<?php echo $phone->imei; ?>'?
</p>

<p>
<?php echo Html::anchor('admin/delete_phone_confirmed/'.$phone->id, 'Yes') ?>
</p>

<p>
<?php echo Html::anchor('admin/phones/', 'No, cancel') ?>
</p>
