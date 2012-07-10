<table>
	<tr>
		<td>
			<div id="datalist">
Order by:
<?php if ($order_by=='village') { ?>
	<b>Village</b>
<?php } else {
	echo Html::anchor('stats/households', 'Village');
} ?>

<?php if ($order_by=='household') { ?>
	<b>Household</b>
<?php } else {
	echo Html::anchor('stats/households/household', 'Household');
} ?>
<br /><br />
<?php echo Form::open('stats/households/household'); ?>
Household code:<br/>
<?php echo Form::input('household_code', $household_code); ?>
&nbsp;<input type="submit" class="button" value="Search">
<?php echo Form::close(); ?>

<?php 	
	$village = '';
	foreach ($households as $household) {
		if($order_by=='village') {
			$prev_village = $village;
			$village = $household->village_code;
			if($village != $prev_village) {
			?>
			<h2>Village <?php echo $village ?></h2>
			<?php
			}
		}
		?><div class="stats_household">
			<h3><img src="<?php echo Kohana::config('assets.images_folder'); ?>/icons/household_32.png" class="title_icon"><?php echo $household->code; ?></h3>
			<?php
				$files = $household->form_datas->find_all();
				if (count($files)) {
					print sprintf('<ul id="list_%s">', $household->id);
					foreach($files AS $file) {
						// only show non-patient forms
						if(strstr($file->form->group,'household')) {
							print sprintf('<li><a href="javascript:;" onClick="display_xml(%s); return false" class="linkToXML">%s</a></li>', $file->id, $file->get_form_name()); 
						}
					}
					print '</ul>';
				}
			?>
			<?php
			foreach($household->patients->find_all() as $patient) { ?>
				<div class="stats_patient"><img src="<?php echo Kohana::config('assets.images_folder'); ?>/icons/patient_32.png" class="title_icon">
					<?php echo $patient->first_name.' '.$patient->last_name .' <small>('.$patient->code.')</small>';
				?>
				<?php
				$files = $patient->form_datas->find_all();
				if (count($files)) {
					print sprintf('<ul id="list_%s">', $patient->id);
					foreach($files AS $file) {
						print sprintf('<li><a href="javascript:;" onClick="display_xml(%s); return false" class="linkToXML">%s</a></li>', $file->id, $file->get_form_name()); 
					}
					print '</ul>';
				}
				?>
				</div>
				<?php
			}
		?>
		</div>
		<?php
	}
?>

			</div>
		</td>
		<td>
			<div id="filecontent">&nbsp;</div>
		</td>
	</tr>
</table>

<script type="text/javascript">

	function display_xml(id) {
		$.post("<?php echo Url::site('stats/single_form') ?>/" + id, function(data) {
		  $('#filecontent').html(data);
		});
	}

</script>

