<html>

<body>


<h2>Check user passes authentication</h2>
<?php echo Form::open('/api/check_user', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>

<h2>Upload file</h2>
<?php echo Form::open('/api/upload_file', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	path0: <input type="text" name="path0" value="/sdcard/test.txt" /><br/>	
	file0: <input type="file" name="file0" /><br/>
	<input type="submit" />
</form>


<h2>Get updated times</h2>
<?php echo Form::open('/api/get_server_updated_times', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>

<h2>Get app config</h2>
<?php echo Form::open('/api/get_app_config', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>

<h2>Get form config</h2>
<?php echo Form::open('/api/get_form_config', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>



<h2>Get media files (old)</h2>
<?php echo Form::open('/api/get_media_files', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>


<h2>Get media</h2>
<?php echo Form::open('/api/get_media', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>


<h2>Activate</h2>
<?php echo Form::open('/api/activate_phone');?>
	imei: <input type="text" name="imei" value="351676030209490" /><br/>
	<input type="submit" />
</form>


<h2>upload_form_data</h2>
<?php echo Form::open('/api/upload_form_data', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="123456" /><br/>
	device_id: <input type="text" name="device_id" value="<?php echo $usr; ?>" /><br/>
	household_code: <input type="text" name="household_code" value="" /><br/>
	patient_code: <input type="text" name="patient_code" value="" /><br/>
	form_code: <input type="text" name="form_code" value="hcore" /><br/>
	xml_content: <textarea name="xml_content"></textarea><br/>
	file_path: <input type="text" name="file_path" value="/sdcard/odk/instances/2010/" /><br/>
	last_modified: <input type="text" name="last_modified" value="<?php echo date('YmdHis'); ?>" /><br/>
	display_label: <input type="text" name="display_label" value="unused" /><br/>	
	image file: <input type="file" name="image" /><br/>
	pn_ts: <input type="text" name="pn_ts" value="<?php echo date('YmdHis'); ?>" /><br/>
	<input type="submit" />
</form>

<h2>upload_form_file</h2>
<?php echo Form::open('/api/upload_form_file', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="123456" /><br/>
	Household code: <input type="text" name="household_code" value="" /><br/>
	Patient code: <input type="text" name="patient_code" value="" /><br/>
	Form code: <input type="text" name="form_code" value="" /><br/>
	File: <input type="file" name="file" /><br/>
	<input type="submit" />
</form>

<h2>upload_phone_locations</h2>
<?php echo Form::open('/api/upload_phone_locations', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="123456" /><br/>
	data file: <input type="file" name="data" /><br/>
	<input type="submit" />
</form>

<h2>get_last_upload_ts</h2>
<?php echo Form::open('/api/get_last_upload_ts');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>


<h2>get_config_by_key</h2>
<?php echo Form::open('/api/get_config_by_key', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="123456" /><br/>
	Key: <input type="text" name="key" value="" /><br/>
	<input type="submit" />
</form>

<h2>get_config_by_keys</h2>
<?php echo Form::open('/api/get_config_by_keys', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="123456" /><br/>
	Keys (comma separated): <input type="text" name="keys" value="" /><br/>
	<input type="submit" />
</form>

<h2>register_c2dm</h2>
<?php echo Form::open('/api/register_c2dm');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	Reg id: <textarea name="registration_id"></textarea>
	<input type="submit" />
</form>


</body>

</html>