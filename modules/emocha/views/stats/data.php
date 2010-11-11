<?php echo $pagination; ?>
<table>
	<tr>
		<th><a href="?ord=village_code"<?php if($ord=='village_code') echo ' class="selected"'; ?>>Village</a></th>
		<th><a href="?ord=household_code"<?php if($ord=='household_code') echo ' class="selected"'; ?>>Household</a></th>
		<th><a href="?ord=code"<?php if($ord=='code') echo ' class="selected"'; ?>>Patient</a></th>
		<th><a href="?ord=first_name"<?php if($ord=='first_name') echo ' class="selected"'; ?>>First name</a></th>
		<th><a href="?ord=last_name"<?php if($ord=='last_name') echo ' class="selected"'; ?>>Last name</a></th>
		<th><a href="?ord=age"<?php if($ord=='age') echo ' class="selected"'; ?>>Age</a></th>
		<th><a href="?ord=sex"<?php if($ord=='sex') echo ' class="selected"'; ?>>Sex</a></th>
		<th></th>
	</tr>
	<?php foreach($patients as $patient) { ?>
		<tr>
			<td><?php echo $patient->household->village_code; ?>&nbsp;</td>
			<td><?php echo $patient->household_code; ?>&nbsp;</td>
			<td><?php echo $patient->code; ?>&nbsp;</td>
			<td><?php echo $patient->first_name; ?>&nbsp;</td>
			<td><?php echo $patient->last_name; ?>&nbsp;</td>
			<td><?php echo $patient->age; ?>&nbsp;</td>
			<td><?php echo $patient->sex; ?>&nbsp;</td>
			<td><a href="javascript:;" onclick="$('#<?php echo $patient->code; ?>').show();">Details</a></td>
		</tr>
		<tr display="none" id="<?php echo $patient->code; ?>" style="display:none;">
			<td colspan="7"><?php echo View::factory('stats/patient_details')->set('patient', $patient)->render(); ?></td>
			<td><a href="javascript:;" onclick="$('#<?php echo $patient->code; ?>').hide();">Hide</a></td>
		</tr>
	<?php } ?>
</table>
<div id="patient_details"></div>
<?php echo $pagination; ?>


