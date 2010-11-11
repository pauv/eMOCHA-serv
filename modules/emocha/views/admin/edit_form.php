<h1><?php echo ucfirst($mode) ?> Form</h1>


<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>

<?php echo Form::open('admin/edit_form/'.$id, array(
													'enctype'=>'multipart/form-data'
													));?>


<table>


    <tbody>
    	
    	<tr>

            <td>Name</td>

            <td><?php echo Form::input('name', Arr::get($form_vals, 'name', '')); ?></td>

        </tr>
        
        <tr>

            <td>Group</td>

            <td><?php echo Form::select('group', array(''=>'','household'=>'household','patient'=>'patient','training'=>'training'), Arr::get($form_vals, 'group', '')); ?></td>

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

            <td>Parent</td>

            <td><?php echo Form::select('parent_id', $parents, Arr::get($form_vals, 'parent_id', '')); ?></td>
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

            <td colspan="2"><?php echo Form::submit('submit', ucfirst($mode)); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>