<div id="inner_content">
<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>

<?php 
if($referral_visit_logged) { 
?>
<p>
<h2>Visit logged</h2>
Patient: <?php echo $patient->first_name.' '.$patient->last_name; ?><br />
Referred from form: <?php echo $form->name; ?>
</p>
<?php 
} 
?>


<?php echo Form::open('stats/referrals');?>


<table>


    <tbody>
    	
    	<tr>

            <td>Referral Id</td>

            <td><?php echo Form::input('referral_id', Arr::get($form_vals, 'referral_id', '')); ?></td>

        </tr>
    
 

    </tbody>

    <tfoot>

        <tr>

            <td colspan="2"><input type="submit" class="button" value="Submit"></td>

        </tr>

    </tfoot>

</table>

<?php echo Form::close(); ?>
</div>