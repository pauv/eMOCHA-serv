
<h1>New Users</h1>


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

<?php
if(count($users)==0) {
?>
There are no new users
<?php
}
else {
?>

	<table>
		<tr>
			<th>name</th>
			<th>username</th>
			<th>email</th>
		</tr>
		<?php 
		
			// list users
			foreach ($users as $user) {
		?>
			<tr>
				<td><?php echo $user->first_name." ".$user->last_name; ?></td>
				<td><?php echo $user->username; ?></td>
				<td><?php echo $user->email; ?></td>
				<td><?php echo Html::anchor('admin/confirm_user/'.$user->id, 'confirm'); ?></td>
				<td><?php echo Html::anchor('admin/delete_user_confirm/'.$user->id, 'delete'); ?></td>
			</tr>
		<?php 
		} ?>
	
	</table>
	
<?php
}
?>


