
<h1>Delete User</h1>


<p>Please confirm you want to delete user  <?php echo $user->email; ?></p>

<p><?php echo Html::anchor('admin/delete_user/'.$user->id, 'yes, delete'); ?></p>
<p><?php echo Html::anchor('admin/new_users/'.$user->id, 'no, cancel'); ?></p>



