

<table class="list">


<?php
	if (! count($phones)) {
?>
	<tr>
		<td>No phones found.
		<br /><br />
		<button onclick="document.location.href='<?php echo Url::site('admin/edit_phone'); ?>';">Add a phone</button>
		</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>Imei</th>
	<th>Comments</th>
	<th>Created</th>
	<th>Last connected</th>
	<th>C2dm disabled</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>

</tr>
<tr>
	<td colspan="7">
	<?php if ($action) { ?>
	<div class="st_OK">
		The phone was <?php echo $action; ?>
	</div>
	<br /><br />
	<?php } ?>
	<button onclick="document.location.href='<?php echo Url::site('admin/edit_phone'); ?>';">Add a phone</button>
	</td>
</tr>

<?php
		$count=1;
		foreach($phones AS $phone) {
			?>
<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $phone->imei; ?> </td>
	<td><?php echo $phone->comments; ?> </td>
	<td><?php echo date('d-m-Y H:i:s', $phone->creation_ts); ?></td>
	<td><?php if($phone->last_connect_ts!=0) echo date('d-m-Y H:i:s', $phone->last_connect_ts); ?></td>
	<td><?php echo $phone->c2dm_disable; ?> </td>
	<td></td>
	<td><?php $link = $phone->validated ? 'edit':'activate';
			echo Html::anchor('admin/edit_phone/'.$phone->id, $link);
		?></td>
	<td><?php echo Html::anchor('admin/delete_phone/'.$phone->id, 'delete') ?></td>
</tr>

<?php 		$count++;
		}
	} 
?>		

</table>


