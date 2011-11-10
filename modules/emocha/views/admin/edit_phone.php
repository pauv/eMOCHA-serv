<?php 
if(isset($errors)) { 
	?>
	<div class="alert">
	<?php echo View::factory('alert/errors')->set('errors', $errors)->render(); ?>
	</div>
	<?php
}
?>


<?php echo Form::open('admin/edit_phone/'.$phone->id);?>


<table class="form">


    <tbody>
    	
    	<tr>

            <td>Imei</td>

            <td><?php echo Form::input('imei', Arr::get($form_vals, 'imei', '')); ?></td>

        </tr>
        
        <tr>

            <td>Activate</td>

            <td><?php echo Form::select('validated', array('0','1'), Arr::get($form_vals, 'validated', '')); ?></td>

        </tr>
    
        <tr>

            <td>Password (leave blank if unchanged)</td>

            <td><?php echo Form::input('password'); ?></td>

        </tr>
        
        <tr>

            <td>Comments</td>

            <td><?php echo Form::textarea('comments', Arr::get($form_vals, 'comments', '')); ?></td>

        </tr>
        
        <tr>

            <td>Disable c2dm</td>

            <td><?php echo Form::select('c2dm_disable', array('0','1'), Arr::get($form_vals, 'c2dm_disable', '')); ?></td>

        </tr>
       

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', 'Save', array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>