<?php if ($action) { ?>
<div class="st_OK">
	The file was <?php echo $action; ?>
</div>
<?php } ?>



<table class="list">


<?php
	if (! count($medias)) {
?>
	<tr>
		<td>No files found.</td>
	</tr>
<?php		
	} else {
	
?>

<tr>
	<th></th>
	<th>Title</th>
	<th>Language</th>
	<th>Filename</th>
	<th>Size</th>
	<th>Date added</th>
	<th></th>
</tr>
<?php
		$count=1;
		foreach($medias AS $media) {
			
			if($media->thumbnail->loaded()) {
				$src = $media->thumbnail->path;
				$img = Html::image($src,
					array(
					'class' => 'thumb'
					));
			}
			else {
				$img = ' ';
			}
			
?>

<tr class="<?php echo ($count%2 ? "odd":"even"); ?>">
	<td><?php echo $img; ?></td>
	<td><?php echo $media->title; ?></td>
	<td><?php echo $media->language; ?></td>
	<td><?php echo $media->file->filename; ?></td>
	<td><?php echo number_format($media->file->size / 1024); ?> Kb</td>
	<td><?php echo date('d-m-Y H:i:s', $media->file->ts); ?></td>
	<td><?php echo Html::anchor('edu/delete/'.$section.'/'.$media->id, 'delete') ?></td>
</tr>

<?php 	$count++;
		}
		
	} 
?>		

</table>



<p>
<button onclick="document.location.href='<?php echo Url::site('edu/form/'.$section); ?>'" class="listbutton">Add a file</button>
</p>
