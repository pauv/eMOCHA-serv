<h3><?php echo trim($patient->first_name); ?> <?php echo trim($patient->last_name); ?></h3><?php if($profile_image = $patient->get_profile_image()) {
	echo Html::image($profile_image, array('width'=>'100', 'align'=>'left'));
}
				
else {
	echo '<img src="' . Kohana::config('assets.images_folder') . '/icons/patient_100.png" width=100 align="left">';
}
?><br />Sex: <?php echo $patient->sex; ?><br />Age: <?php echo $patient->age; ?><br /><?php echo View::factory('main/symptom_list')->set('patient', $patient)->render(); ?>