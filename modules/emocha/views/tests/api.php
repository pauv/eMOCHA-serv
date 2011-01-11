<html>

<body>

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


<h2>Get form templates</h2>
<?php echo Form::open('/api/get_form_templates', array(
													'enctype'=>'multipart/form-data'
													));?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>

<h2>Get media files</h2>
<?php echo Form::open('/api/get_media_files', array(
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


<h2>Check user</h2>
<?php echo Form::open('/api/check_user');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	gps: <input type="text" name="gps" value="39.296502470970154:-76.59163177013397" /><br />
	<input type="submit" />
</form>

<h2>get_sdcard_file_list</h2>
<?php echo Form::open('/api/get_sdcard_file_list');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	last_server_upd: <input type="text" name="last_server_upd" value="<?php print time(); ?>" /><br/>
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
	last_modified: <input type="text" name="last_modified" value="<?php echo time(); ?>" /><br/>
	display_label: <input type="text" name="display_label" value="unused" /><br/>	
	image file: <input type="file" name="image" /><br/>
	<input type="submit" />
</form>

<h2>get_last_upload_ts</h2>
<?php echo Form::open('/api/get_last_upload_ts');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	<input type="submit" />
</form>


</body>

</html>