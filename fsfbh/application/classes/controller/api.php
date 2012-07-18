<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Emocha_Controller_Api {


	/*
	 * Upload form data from phone
	 * FSFB: accepts 2 types of household_core forms (hcore and epub)
	 * 
	 */
	function action_upload_form_data() {

		$form = $this->_preprocess_request();	

		if ($form == NULL)
			return;

		//get application_type
  	$app_type = ORM::factory('config')
  	            ->where('label','=',Kohana::config('values.application_type'))
  	            ->and_where('type','=',Kohana::config('values.platform'))
  	            ->find();

		if($app_type->loaded() AND $app_type->content)
		{		
			$patient_code = $this->_get_patient_code($app_type->content);
			$form_data = $this->_get_form_data($app_type->content,$form->id);

			if ($patient_code < 0 OR $form_data == NULL)
				return;

		} else 
		{
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.config_missing')))->render();
			$this->request->response = $json;
			return;
		}

		//common: Update form data values
		$form_data->household_code = Arr::get($_POST,"household_code");
		$form_data->patient_code = $patient_code;
		$form_data->creator_phone_id = $this->phone->id;
		$form_data->form_id = $form->id;
		$form_data->uploader_phone_id = $this->phone->id;
		$form_data->xml_content = Arr::get($_POST,"xml_content");
		$form_data->file_path = Arr::get($_POST,"file_path");
		$form_data->last_modified = Arr::get($_POST,"last_modified");
		
    /*
    Save household and patient data if necessary
    */
		if (($app_type->content == Kohana::config('values.app_type_households')) OR 
				($app_type->content == Kohana::config('values.app_type_patients_only')))
		{
			$this->_process_extra_data($form->code,$form_data);
		}

		//common: Insert or update the form data as the case may be; return form_data_id so devices can use it...
    if ($form_data->save()) {
      $json = View::factory('json/display', Json::response('OK', 'data_uploaded', array('form_data_id'=>$form_data->id)))->render();
    }
    else {
      $json = View::factory('json/display', Json::response('ERR', 'affected=0'))->render();
    }
		$this->request->response = $json;
	}


	private function _preprocess_request() {
    if(! Arr::get($_POST,"xml_content")) {
      $json = View::factory('json/display', Json::response('ERR', 'xml_content is empty'))->render();
      $this->request->response = $json;
      return NULL;
    }

    $form = ORM::factory('form')->where('code','=',Arr::get($_POST,"form_code"))->find();
    if(! $form->loaded()) {
      $json = View::factory('json/display', Json::response('ERR', 'invalid form code'))->render();
      $this->request->response = $json;
      return NULL;
    }
		return $form;
	}

	private function _get_patient_code($app_type='') {

		if (($app_type == Kohana::config('values.app_type_households')) OR
        ($app_type == Kohana::config('values.app_type_patients_only')))
    {
      //1.- get patient_code from request
      $patient_code = Arr::get($_POST,"patient_code");

		} /*TODO be sure patients are created on the back-end prior to upload any data 
//		elseif ($app_type == Kohana::config('values.app_type_forms_only'))
		{
			//1.- get patient_code from phone id
			$patient = ORM::factory('patient')
							->where('register_phone_id','=',$this->phone->id)
							->find();

			if(! $patient->loaded()) 
			{
				$json = View::factory('json/display', Json::response('ERR', 'no corresponding study id found'))->render();
				$this->request->response = $json;
				return NULL;
			} else 
			{
				$patient_code = $patient->code;
			}
		} */
			else
		{
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.wrong_app_type')))->render();
			$this->request->response = $json;
			return -1;
		}

		return $patient_code;
	}


	private function _get_form_data($app_type='',$form_id=-1) {

		if (($app_type == Kohana::config('values.app_type_households')) OR
        ($app_type == Kohana::config('values.app_type_patients_only')))
    {
			$form_data = Model_Form_Data::get_by_key_data(
									Arr::get($_POST,"household_code",''),
									Arr::get($_POST,"patient_code",''),
									$form_id
									);
		}/*TODO be sure patients are created on the back-end prior to upload any data 
		// elseif ($app_type == Kohana::config('values.app_type_forms_only'))
		{
			$form_data = ORM::factory('form_data');
		}*/
		 else
		{
			$json = View::factory('json/display', Json::response('ERR', Kohana::config('errors.wrong_app_type')))->render();
			$this->request->response = $json;
			return NULL;
		}

		return $form_data;
	}

	private function _process_extra_data($form_code='',$form_data) {

    if($form_code==Kohana::config('values.hcore_form_code') OR $form_code==Kohana::config('values.epub_form_code')) {
      $household = Model_Household::save_from_form_data($form_data);
    }

    if($form_code==Kohana::config('values.pcore_form_code')) {
      $patient = Model_Patient::save_from_form_data($form_data);
      /*
      Deal with patient image upload
      TODO: develop a system for dealing with multiple files from any form
      */
      if(isset($_FILES['image'])){
        $validation = Validate::factory($_FILES)
          ->rules('image', array(
                      'upload::valid'=>NULL, 
                      'upload::type'=>array(array('jpg')), 
                      'upload::size'=>array('2M')
                      ));
        if ($validation->check()){
          $patient->save_profile_image($_FILES['image']);
        }
      }
    }
	}
///////////// Upload form data ///////////////////    
}
