

<table class="list">
<?php if ($action_taken) { ?>
<tr><td>
<?php

	// display message for action taken
	switch($action_taken) {
	
		case 'confirmed':
			?><p class="st_OK">The user was confirmed</p><?php
		break;
		
		case 'deleted':
			?><p class="st_OK">The user was deleted</p><?php
		break;
		
		case 'error':
			?><p class="st_ERR">Error - please try again</p><?php
		break;
	
	}
?>
</td></tr>
<?php
}
?>


<?php
if(count($users)==0) {
?>
<tr><td>There are no new users</td></tr>
<?php
}
else {
?>

	
		<tr>
			<th>name</th>
			<th>username</th>
			<th>email</th>
			<th></th>
			<th></th>
		</tr>
		<?php 
			$count=1;
			// list users
			foreach ($users as $user) {
		?>
			<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
				<td><?php echo $user->first_name." ".$user->last_name; ?></td>
				<td><?php echo $user->username; ?></td>
				<td><?php echo $user->email; ?></td>
				<td><?php echo Html::anchor('admin/confirm_user/'.$user->id, 'confirm'); ?></td>
				<td><?php echo Html::anchor('admin/delete_user_confirm/'.$user->id, 'delete'); ?></td>
			</tr>
		<?php 
			$count++;
		} ?>
	
	</table>
	
<?php
}
?>


