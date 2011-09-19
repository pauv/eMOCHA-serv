<h1><?php echo ucfirst($section) ?></h1>

<?php if ($action) { ?>
<div class="st_OK">
	The file was <?php echo $action; ?>
</div>
<?php } ?>


<p>
<button onclick="document.location.href='<?php echo Url::site('edu/form/'.$section); ?>'">Add a file</button>
</p>


<table>


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
	<th>Filename</th>
	<th>Size</th>
	<th>Date added</th>
	<th></th>
</tr>
<?php
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

<tr>
	<td><?php echo $img; ?></td>
	<td><?php echo $media->title; ?></td>
	<td><?php echo $media->file->filename; ?></td>
	<td><?php echo number_format($media->file->size / 1024); ?> Kb</td>
	<td><?php echo date('d-m-Y H:i:s', $media->file->ts); ?></td>
	<td><?php echo Html::anchor('edu/delete/'.$section.'/'.$media->id, 'delete') ?></td>
</tr>

<?php 	
		}
	} 
?>		

</table>
