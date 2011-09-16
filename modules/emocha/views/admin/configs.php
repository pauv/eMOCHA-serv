<table class="list">

<?php
	if (! count($configs)) {
?>
	<tr>
		<td colspan="7">No configs found.
		<br /><br />
		<button onclick="document.location.href='<?php echo Url::site('admin/edit_config'); ?>';">Add a config</button>
		</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>Key/label</th>
	<th>Content</th>
	<th>Last modified</th>
	<th></th>
	<th></th>

</tr>
<tr>
	<td colspan="5">
<?php if ($action) { ?>

	<div class="st_OK">
	The config was <?php echo $action; ?>
	</div>
	<br /><br />
	<?php } ?>
	<button onclick="document.location.href='<?php echo Url::site('admin/edit_config'); ?>';">Add a config</button>
<br /><br />
	
<?php } ?>
</td>
</tr>


<?php 
	$count=1;
	foreach($configs as $config) { ?>
<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $config->label; ?> </td>
	<td><?php echo $config->content; ?> </td>
	<td><?php echo date('d-m-Y H:j:s', strtotime($config->last_modified)); ?></td>
	<td><?php echo Html::anchor('admin/edit_config/'.$config->id, 'edit') ?></td>
	<td><?php echo Html::anchor('admin/delete_config/'.$config->id, 'delete') ?></td>
</tr>

<?php 	
		$count++;
	}

?>		

</table>
