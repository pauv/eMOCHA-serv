<div id="inner_content">
<p>
Do you really want to delete the file '<?php echo $media->title; ?>'?
</p>

<p>
<?php echo Html::anchor('edu/delete_confirmed/'.$section.'/'.$media->id, 'Yes') ?>
</p>

<p>
<?php echo Html::anchor('edu/'.$section, 'No, cancel') ?>
</p>
</div>
