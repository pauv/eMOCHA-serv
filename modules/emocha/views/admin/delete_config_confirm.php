<div id="inner_content">
<p>
Do you really want to delete the config '<?php echo $config->label; ?>'?
</p>

<p>
<?php echo Html::anchor('admin/delete_config_confirmed/'.$config->id, 'Yes') ?>
</p>

<p>
<?php echo Html::anchor('admin/configs', 'No, cancel') ?>
</p>
</div>
