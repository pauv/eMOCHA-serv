
<?php 
if(isset($errors)) { 
	?>
	<div class="alert">
	<?php echo View::factory('alert/errors')->set('errors', $errors)->render(); ?>
	</div>
	<?php
}
?>

<?php echo Form::open('admin/edit_config/'.$id, array(
													'enctype'=>'multipart/config-data'
													));?>


<table class="config">


    <tbody>
    	
    	<tr>

            <td>Key/label</td>

            <td><?php echo Form::input('label', Arr::get($form_vals, 'label', '')); ?></td>

        </tr>
        
    
        <tr>

            <td>Content</td>

            <td><?php echo Form::input('content', Arr::get($form_vals, 'content', '')); ?></td>

        </tr>
        
       
    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', ucfirst($mode), array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>