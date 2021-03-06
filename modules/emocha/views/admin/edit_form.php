
<?php 
if(isset($errors)) { 
	?>
	<div class="alert">
	<?php echo View::factory('alert/errors')->set('errors', $errors)->render(); ?>
	</div>
	<?php
}
?>

<?php echo Form::open('admin/edit_form/'.$id, array(
													'enctype'=>'multipart/form-data'
													));?>


<table class="form">


    <tbody>
    	
    	<tr>

            <td>Name</td>

            <td><?php echo Form::input('name', Arr::get($form_vals, 'name', '')); ?></td>

        </tr>
        
        <tr>

            <td>Group</td>

            <td><?php echo Form::select('group', array(''=>'','household_core'=>'household_core','household_data'=>'household_data','patient_core'=>'patient_core','patient_data'=>'patient_data','training'=>'training','training_data'=>'training_data'), Arr::get($form_vals, 'group', '')); ?></td>

        </tr>
        
        <tr>

            <td>Code</td>

            <td><?php echo Form::input('code', Arr::get($form_vals, 'code', '')); ?></td>

        </tr>
    
        <tr>

            <td>Description</td>

            <td><?php echo Form::input('description', Arr::get($form_vals, 'description', '')); ?></td>

        </tr>
        
        

        <tr>

            <td>Conditions {json}</td>

            <td><?php echo Form::textarea('conditions', Arr::get($form_vals, 'conditions', ''), array('rows'=>'10', 'cols'=>'30')); ?></td>

        </tr>
        
        <tr>

            <td>Label</td>

            <td><?php echo Form::input('label', Arr::get($form_vals, 'label', '')); ?></td>

        </tr>
        
        
        <tr>

            <td>Template File</td>

            <td><?php echo Form::file('newfile'); ?>
            <?php if($file_path) { ?>
            	<br />Current: <?php echo $file_path; ?>
            <?php } ?>
            </td>
        </tr>

    
 

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', ucfirst($mode), array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>