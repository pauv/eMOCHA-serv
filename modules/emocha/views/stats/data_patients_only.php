
<table>
	<tr>
		<td>
			<div id="datalist">


			<?php
			foreach($patients as $patient) { ?>
				<div class="stats_patient"><img src="<?php echo Kohana::config('assets.images_folder'); ?>/icons/patient_32.png" class="title_icon">
					<?php echo $patient->code;
				?>
				<?php
				$files = $patient->form_datas->order_by('last_modified','ASC')->find_all();
				if (count($files)) {
					print sprintf('<ul id="list_%s">', $patient->id);
					foreach($files AS $file) {
						print sprintf('<li><a href="javascript:;" onClick="display_xml(%s); return false" class="linkToXML">%s</a> %s</li>', $file->id, $file->get_form_name(), $file->last_modified); 
					}
					print '</ul>';
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

