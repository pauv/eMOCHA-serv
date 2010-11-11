<h1>Handset list</h1>

<table>

<tr>
	<th>Name</th>
	<th>IMEI</th>
	<th>Last data transfer</th>
</tr>

<?php 
	foreach ($phones as $phone) {
		if ($phone->last_connect_ts > 0) { 
			$time = date('d-m-Y H:j:s', $phone->last_connect_ts);
		} else {
			$time = 'no data received yet';
		} 
?>
<tr>
	<td><?php echo $phone->comments; ?></td>
	<td><?php echo $phone->imei; ?></td>
	<td><?php echo $time; ?></td>
</tr>

<?php } ?>

</table>
