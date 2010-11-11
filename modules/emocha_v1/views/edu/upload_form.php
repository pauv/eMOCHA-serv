<h1>Add a file to '<?php echo $section ?>'</h1>


<?php 
if(isset($errors)) { 
	echo View::factory('alert/errors')->set('errors', $errors)->render();
}
?>

<?php echo Form::open('edu/upload/'.$section, array(
													'enctype'=>'multipart/form-data'
													));?>


<p>
Title/label:<br />
<input type="text" name="title" size="20" />
</p>

<p>
Select from Ftp folder:<br />
<?php if(is_array($ftp_files) && count($ftp_files)) { ?>
<select name="ftp_file">
	<option value=""></option>
	<?php foreach($ftp_files as $ftp_file) { ?>
		<option value="<?php echo basename($ftp_file) ?>"><?php echo basename($ftp_file) ?></option>
	<?php } ?>
</select>
<?php } else { ?>
	No files uploaded
<?php } ?>
 <br />
 (to use this functionality, upload files by sftp to sdcard/upload)
</p>


<?php 
if ($section=='library') { 
?>
<p>
or Upload a file directly (
	<?php 
		$ext_str = '';
		foreach($allowed_file_types as $type){ 
			$ext_str .= $type.', ';
		}
		$ext_str = substr($ext_str, 0,-2);
		echo $ext_str;
		?>, max 2mb):<br />
<input type="file" name="userfile" size="20" />
</p>
<?php } ?>

<?php if ($thumbnail_allowed) { ?>
<p>
Thumbnail (optional, jpg):<br />
<input type="file" name="thumbnail" size="20" /><br />
</p>
<?php } ?>

<p>
<input type="submit" value="upload" />
</p>

</form>