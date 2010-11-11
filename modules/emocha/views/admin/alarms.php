<h1>Alarms</h1>

<?php if ($action) { ?>
<div class="st_OK">
	The alarm was <?php echo $action; ?>
</div>
<?php } ?>

<table>

<tr>
	<th>Name</th>
	<th>Description</th>
	<th></th>

</tr>

<?php
	foreach ($alarms as $alarm) { 
?>
	<tr>
		<td><?php echo $alarm->name; ?></td>
		<td><?php echo $alarm->description; ?></td>
		<td><?php echo Html::anchor('admin/alarm/'.$alarm->id, 'edit'); ?></td>
	</tr>
<?php
	}
?>

</table>