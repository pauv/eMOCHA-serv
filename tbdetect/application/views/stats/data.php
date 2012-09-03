<link rel="stylesheet" type="text/css" href="/css/stats_datasel.css">

&nbsp;
<div class="stats_summary" >
Total users: <b><?php echo ORM::factory('patient')->count_all(); ?></b>,
Total forms: <b><?php echo ORM::factory('form_data')->count_all(); ?></b>,
Total rejected (late) forms: <b><?php echo ORM::factory('form_data')->where('rejected','=','late')->count_all(); ?></b>
</div>

<table>
	<tr>
		<td>
			<div id="datalist">


			<?php
			foreach($patients as $patient) { ?>
				<div class="stats_patient"><img src="/images/icons/patient_32.png" class="title_icon">
				<a href="javascript:;" onclick="$('#dates_<?php echo $patient->id; ?>').toggle();hide_xml();"><?php echo $patient->code; ?></a>
					Total forms: <b><?php echo $patient->form_datas->count_all(); ?></b>,
					Rejected: <b><?php echo $patient->form_datas->where('rejected','=','late')->count_all(); ?></b>
				<div id="dates_<?php echo $patient->id; ?>" style="display:none;margin-left:40px;">
					<?php 
					$dates = $patient->get_form_data_dates();
					foreach($dates as $date) {
						$files = $patient->get_form_data_by_date($date);
						?><a href="javascript:;" onclick="$('#data_<?php echo $patient->id; ?>_<?php echo $date; ?>').toggle();hide_xml();"><?php echo $date; ?></a><br />
						<div id="data_<?php echo $patient->id; ?>_<?php echo $date; ?>"  style="display:none;margin-left:10px;">
						<?php
						if (count($files)) {
							print sprintf('<ul id="list_%s">', $patient->id.'_'.$date);
							foreach($files AS $file) { 
								?><li><a href="javascript:;" onClick="display_xml(<?php echo $file->id; ?>); return false" class="linkToXML"<?php if($file->rejected) echo ' style="color:red;"'; ?>><?php echo $file->get_form_name(); ?></a> <?php echo $file->last_modified; ?></li><?php
							}
							print '</ul>';
						}
						?>
						</div>
					<?php
					}
					?>
				</div>
				
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
	
	function hide_xml() {
		  $('#filecontent').html('&nbsp;');
	}

</script>

