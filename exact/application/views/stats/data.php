<link rel="stylesheet" type="text/css" href="/css/stats_datasel.css">

<table>
	<tr>
		<td>
			<div id="datalist">


			<?php
			foreach($patients as $patient) { ?>
				<div class="stats_patient"><img src="/images/icons/patient_32.png" class="title_icon">
					<?php echo $patient->code;
				?>
				<?php
				$files = $patient->form_datas->order_by('last_modified','ASC')->find_all();
				if (count($files)) {
					print sprintf('<ul id="list_%s">', $patient->id);
					foreach($files AS $file) { 
						?><li><a href="javascript:;" onClick="display_xml(<?php echo $file->id; ?>); return false" class="linkToXML"<?php if($file->rejected) echo ' style="color:red;"'; ?>><?php echo $file->get_form_name(); ?></a> <?php echo $file->last_modified; ?></li><?
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

