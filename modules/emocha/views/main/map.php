<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $google_maps_key; ?>" type="text/javascript"></script>
<script src="/js/markerclusterer_packed.js" type="text/javascript"></script>
 <script type="text/javascript">
 	

	function createMarker(point, html) {
	
	  // Create our "tiny" marker icon
	var hhIcon = new GIcon(G_DEFAULT_ICON);
	// HOUSEHOLDS
	<?php if ($map_type=='households') { ?>
		hhIcon.image = "/images/icons/household_map.png";
		hhIcon.iconSize = new GSize(25, 23);
	<?php } ?>
	hhIcon.shadow = null;

					
	// Set up our GMarkerOptions object
	markerOptions = { icon:hhIcon };
	  var marker = new GMarker(point, markerOptions);

	  GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml(html);
	  });
	  return marker;
	}
	
	


    function initialize() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng(<?php echo $lat_center; ?>, <?php echo $long_center; ?>), 2);
        map.setUIToDefault();
        bounds = new GLatLngBounds();	

		var marker = null;
		var markers = [];
		
        <?php 
        // HOUSEHOLDS
        if ($map_type=='households') {
        	$households = $items;
			foreach($households as $household) {
				$patients = $household->patients->find_all();
				$html = count($patients).' patients:<br />';
				foreach ($patients as $patient) {
					$html .= Html::anchor('main/map/patients/'.$patient->code, trim($patient->first_name).' '.trim($patient->last_name)) .'<br />';
				}
				
				if($household->gps_lat && $household->gps_long) {
			?>
				var point = new GLatLng(<?php echo $household->gps_lat; ?>, <?php echo $household->gps_long; ?>);
				bounds.extend(point);
			  	marker = createMarker(point, '<?php echo $html; ?>');
			  	markers.push(marker);
			 
			<?php 
			
				}
			}
		}
		// PATIENTS
		elseif ($map_type=='patients') {
			$patients = $items;
			foreach($patients as $patient) {
				if($patient->map_lat && $patient->map_long) {
					$html = View::factory('main/patient_profile')->set('patient', $patient)->render();
					?>
					var point = new GLatLng(<?php echo $patient->map_lat; ?>, <?php echo $patient->map_long; ?>);
					bounds.extend(point);
					marker = createMarker(point, '<?php echo $html; ?>');
					markers.push(marker);
				 <?php 
				}
			}
		}
	
		
		?>
		var markerCluster = new MarkerClusterer(map, markers);

		<?php
		// handle selected patient or household
		if($selected_item && $map_type=='patients') {
		?>
		map.setCenter(new GLatLng(<?php echo $selected_item->household->gps_lat; ?>, <?php echo $selected_item->household->gps_long; ?>), 20);
		<?php
		}
		elseif($selected_item && $map_type=='households') {
		?>
		map.setCenter(new GLatLng(<?php echo $selected_item->gps_lat; ?>, <?php echo $selected_item->gps_long; ?>), 20);
		<?php
		}else {
		?>
			map.setZoom(map.getBoundsZoomLevel(bounds)-1);
		<?php 
		} 
		?>
      }
    }
    
    
 



	$(function() {
		if (initialize) {
			initialize();
		}
		if (GUnload) {
			$(window).bind('unload', GUnload);
		}
	});
</script>








	<div id="map_canvas" class="column"></div>
	<div id="map_filters" class="column">
			<h3>View</h3>
			<?php if ($map_type=='households') { ?>
				<b>Households</b>
			<?php } else {
				echo Html::anchor('main/map/households', 'Households');
			} ?>
			&nbsp;
			&nbsp;
			<?php if ($map_type=='patients') { ?>
				<b>Patients</b>
			<?php } else {
				echo Html::anchor('main/map/patients', 'Patients');
			} ?>
			<br /><br />
			<h3>Filter</h3>
				<?php
				if($map_type=='households') {
				?>
				<form method="post" action="<?php echo Url::site('main/map/households') ?>">
				Number of patients in household<br/>
				<?php 
				echo Form::select('num_patients_operator', 
									array('='=>'equals', '>'=>'more than', '<'=>'less than'),
									Arr::get($form_vals, 'num_patients_operator', '')); 
				echo Form::select('num_patients', 
									array(''=>'','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'),
									Arr::get($form_vals, 'num_patients', '')); 
				?>
				
				
				<br/>
				<?php
				}
				else {
				?>
				<form method="post" action="<?php echo Url::site('main/map/patients') ?>">
				Gender<br/>
				<input type="radio" name="sex" value="m" <?php if (Arr::get($form_vals, 'sex')=='m') echo 'checked'; ?> />Male<br/>
				<input type="radio" name="sex" value="f" <?php if (Arr::get($form_vals, 'sex')=='f') echo 'checked'; ?> />Female<br/>
				<input type="radio" name="sex" value=""  <?php if (! Arr::get($form_vals, 'sex')) echo 'checked'; ?> />Both<br/>
				<br/>
				Age between<br/>
				<input type="text" name="age_min" size="2" class="minmax" value="<?php echo Arr::get($form_vals, 'age_min'); ?>" /> and
				<input type="text" name="age_max" size="2" class="minmax" value="<?php echo Arr::get($form_vals, 'age_max'); ?>" /><br/>
				<br/>
				<?php
				}
				?>
				<br />
				<input type="submit" value="Search" class="button" />
			</form>
	</div>

<br class="clear_float" />