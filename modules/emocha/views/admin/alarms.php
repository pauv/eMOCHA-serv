
<table class="list">

<tr>
	<th>Name</th>
	<th>Description</th>
	<th></th>

</tr>

<?php if ($action) { ?>

	<tr>
		<td colspan="3">
			<div class="st_OK">The alarm was <?php echo $action; ?></div>
		</td>
	</tr>

<?php } ?>

<?php
	$count=1;
	foreach ($alarms as $alarm) { 
?>
	<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
		<td><?php echo $alarm->name; ?></td>
		<td><?php echo $alarm->description; ?></td>
		<td><?php echo Html::anchor('admin/alarm/'.$alarm->id, 'edit'); ?></td>
	</tr>
<?php
		$count++;
	}
?>

</table>