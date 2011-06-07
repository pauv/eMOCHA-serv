<?php 
$symptom_arr = array('0'=>'None', '1'=>'Current cough', '2'=>'Fever', '3'=>'Weight loss', '4'=>'Night sweats');
$symptom_list = trim($patient->get_core_form_val('patient_has_symptoms'));
if($symptom_list) {
	echo "Symptoms:<br />";
	$symptom_keys = explode(' ', $symptom_list);
	$symptoms = '';
	foreach($symptom_keys as $key) {
		if(isset($symptom_arr[$key])){
			$symptoms .= $symptom_arr[$key].', ';
		}
	}
	$symptoms = substr($symptoms, 0, -2);
	echo $symptoms;
}
else {
	echo "No symptoms";
}

?>