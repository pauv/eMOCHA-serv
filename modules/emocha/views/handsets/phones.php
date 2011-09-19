<table class="list">

<tr>
	<th>Name</th>
	<th>IMEI</th>
	<th>Last data transfer</th>
</tr>

<?php 
	$count=1;
	foreach ($phones as $phone) {
		if ($phone->last_connect_ts > 0) { 
			$time = date('d-m-Y H:i:s', $phone->last_connect_ts);
		} else {
			$time = 'no data received yet';
		} 
?>
<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $phone->comments; ?></td>
	<td><?php echo $phone->imei; ?></td>
	<td><?php echo $time; ?></td>
</tr>

<?php 
	$count++;
} ?>

</table>
