<h1><?php echo ucfirst($mode) ?> Form File</h1>


<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>

<?php echo Form::open('admin/edit_form_file/'.$form_id.'/'.$id, array(
													'enctype'=>'multipart/form-data'
													));?>


<table>


    <tbody>
        
        <tr>

            <td>Type</td>

            <td><?php echo Form::select('type', array(''=>'','image'=>'image'), Arr::get($form_vals, 'type', '')); ?></td>

        </tr>
    
        
        <tr>

            <td>Label</td>

            <td><?php echo Form::input('label', Arr::get($form_vals, 'label', '')); ?></td>

        </tr>
        
        <tr>

            <td>Config {json}</td>

            <td><?php echo Form::textarea('config', Arr::get($form_vals, 'config', ''), array('rows'=>'10', 'cols'=>'30')); ?></td>

        </tr>
        
        
        <tr>

            <td>File</td>

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