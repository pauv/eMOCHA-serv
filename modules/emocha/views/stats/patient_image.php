<div style="background:#FFF;padding:10px">
<?php if($profile_image = $patient->get_profile_image()) {
	echo Html::image($profile_image, array('width'=>'100', 'align'=>'left'));
}
				
else {
	echo '<img src="/images/icons/patient_100.png" width=100 align="left">';
}
?>
<br class="clear_float" />
<!--<a href="javascript:;" onclick="$('#patient_image').hide()">X</a>-->
</div>