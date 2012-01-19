
<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>

<?php echo Form::open('admin/edit_patient/'.$id);?>


<table class="form">


    <tbody>
    	
    	<tr>

            <td>Code</td>

            <td><?php if($mode=='create') { ?><?php echo Form::input('code', Arr::get($form_vals, 'code', '')); ?><?php }
            		else { echo $form_vals['code']; } ?></td>

        </tr>
        
        <tr>

            <td>Phone</td>

            <td><?php echo Form::select('phone_id', $phones, Arr::get($form_vals, 'phone_id', '')); ?></td>

        </tr>
         <tr>

            <td>Email</td>

            <td><?php echo Form::input('email', Arr::get($form_vals, 'email', '')); ?></td>

        </tr>
        
       

    
 

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', ucfirst($mode), array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>