<div id="inner_content"><?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>


<?php echo Form::open('auth/reset'); ?>

<?php if( ! $code) { ?>
You've been sent an email with a verification code. Please enter the code below:
<?php } ?>
</div>
<table class="form">


    <tbody>

        <tr>

            <td>Verification Code</td>

            <td><?php echo Form::input('code', $code); ?></td>

        </tr>
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

            <td colspan="2"><?php echo Form::submit('submit', 'Reset Password', array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>



<?php echo Form::close(''); ?>