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
	Language: <?php echo Form::select('language', array('en'=>'en','es'=>'es')); ?><br />
	<input type="submit" />
</form>


<h2>Activate</h2>
<?php echo Form::open('/api/activate_phone');?>
	imei: <input type="text" name="imei" value="351676030209490" /><br/>
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

<h2>register_language</h2>
<?php echo Form::open('/api/register_language');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	Language: <input type="text" name="language" value="" />
	<input type="submit" />
</form>


<h2>confirm_alert</h2>
<?php echo Form::open('/api/confirm_alert');?>
	Usr: <input type="text" name="usr" value="<?php echo $usr; ?>" /><br/>
	Pwd: <input type="text" name="pwd" value="" /><br/>
	Alert id: <input type="text" name="alert_id" value="" />
	<input type="submit" />
</form>


</body>

</html>