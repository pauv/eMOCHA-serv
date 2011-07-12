


<?php 
if(isset($errors)) { 
	?>
	<div class="alert">
	<?php echo View::factory('alert/errors')->set('errors', $errors)->render(); ?>
	</div>
	<?php
}
?>

<?php echo Form::open('admin/alarm/'.$alarm->id);?>


<table class="form">


    <tbody>
    
    	<?php
    	
    	$conditions = $alarm->alarm_conditions->find_all();
    	
    	foreach ($conditions as $condition) {
    	
    	?>
    	
    	<tr>

            <td>Condition: <?php echo $condition->description; ?></td>

            <td><?php echo Form::input('condition_'.$condition->id, $condition->value); ?></td>

        </tr>
    	<?php
    	
    	}
    	
    	?>
    	
    	
    	<?php
    	
    	$actions = $alarm->alarm_actions->find_all();
    	
    	foreach ($actions as $action) {
    		
    		if($action->type=='email') {
    			$users = Model_User::get_id_email_array();
				?>
				
				<tr>
		
					<td>Action: Email user</td>
		
					<td><?php echo Form::select('action_user_id', $users, $action->user_id); ?></td>
		
				</tr>
				<?php
				
				}
		
		}
    	
    	?>
        

    
 

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', 'save', array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>