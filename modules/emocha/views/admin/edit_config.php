
<?php 
if(isset($errors)) { 
	?>
	<div class="alert">
	<?php echo View::factory('alert/errors')->set('errors', $errors)->render(); ?>
	</div>
	<?php
}
?>

<?php echo Form::open('admin/edit_config/'.$type.'/'.$id, array(
													'enctype'=>'multipart/config-data'
													));?>


<table class="form">


    <tbody>
    	
    	<tr>

            <td>Key/label</td>

            <td><?php echo Form::input('label', Arr::get($form_vals, 'label', '')); ?></td>

        </tr>
        
    
        <tr>

            <td>Content</td>
						<td>
<?php
	if ($type == Kohana::config('values.platform') AND Arr::get($form_vals,'label') == Kohana::config('values.application_type'))
	{
		echo Form::select('content', 
											array(Kohana::config('values.app_type_households')=>Kohana::config('values.app_type_households'),
														Kohana::config('values.app_type_patients_only')=>Kohana::config('values.app_type_patients_only'),
														Kohana::config('values.app_type_forms_only')=>Kohana::config('values.app_type_forms_only')),
											Arr::get($form_vals, 'content',''));

	} elseif ($type == Kohana::config('values.platform') AND Arr::get($form_vals,'label') == Kohana::config('values.authentication'))
	{
		echo Form::select('content', 
											array(Kohana::config('values.usr_only')=>Kohana::config('values.usr_only'),
														Kohana::config('values.usr_password')=>Kohana::config('values.usr_password')),
											Arr::get($form_vals, 'content',''));
	} else 
	{
            echo Form::input('content', Arr::get($form_vals, 'content', ''));
	}
?>
						</td>
        </tr>
        
        
        <tr>

            <td>Description</td>

            <td><?php echo Form::textarea('description', Arr::get($form_vals, 'description', '')); ?></td>

        </tr>
        
       
    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', ucfirst($mode), array('class'=>'button')); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>
