<h1>Phones</h1>

<?php if ($action) { ?>
<div class="st_OK">
	The phone was <?php echo $action; ?>
</div>
<?php } ?>


<table>


<?php
	if (! count($phones)) {
?>
	<tr>
		<td>No phones found.</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th>Imei</th>
	<th>Comments</th>
	<th>Created</th>
	<th>Last connected</th>
	<th></th>

</tr>

<?php
		foreach($phones AS $phone) {
			?>
<tr>
	<td><?php echo $phone->imei; ?> </td>
	<td><?php echo $phone->comments; ?> </td>
	<td><?php echo date('d-m-Y H:i:s', $phone->creation_ts); ?></td>
	<td><?php if($phone->last_connect_ts!=0) echo date('d-m-Y H:i:s', $phone->last_connect_ts); ?></td>
	<td></td>
	<td><?php $link = $phone->validated ? 'edit':'activate';
			echo Html::anchor('admin/edit_phone/'.$phone->id, $link);
		?></td>
</tr>

<?php 	
		}
	} 
?>		

</table>
