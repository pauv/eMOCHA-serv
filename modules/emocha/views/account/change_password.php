<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>


<?php echo Form::open('account/change_password'); ?>



<table>


    <tbody>
    	

        <tr>

            <td>New Password</td>

            <td><?php echo Form::password('password'); ?></td>

        </tr>
        
        <tr>

            <td>Confirm New Password</td>

            <td><?php echo Form::password('password_confirm'); ?></td>

        </tr>

 

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', 'Change'); ?></td>

        </tr>

    </tfoot>

</table>



<?php echo Form::close(); ?>