<h1>Edit phone</h1>


<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>

<?php echo Form::open('admin/edit_phone/'.$phone->id);?>


<table>


    <tbody>
    	
    	<tr>

            <td>Imei</td>

            <td><?php echo $phone->imei ?></td>

        </tr>
        
        <tr>

            <td>Activate</td>

            <td><?php echo Form::select('validated', array('0','1'), $phone->validated); ?></td>

        </tr>
    
        <tr>

            <td>Password (leave blank if unchanged)</td>

            <td><?php echo Form::input('password'); ?></td>

        </tr>
        
        <tr>

            <td>Comments</td>

            <td><?php echo Form::textarea('comments', $phone->comments); ?></td>

        </tr>
        
       

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><?php echo Form::submit('submit', 'Save'); ?></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>