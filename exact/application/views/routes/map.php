<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

  function initialize() {
    var myLatLng = new google.maps.LatLng(52.5152,13.4701);
    var myOptions = {

      mapTypeId: google.maps.MapTypeId.TERRAIN
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    var flightPlanCoordinates = [
    	<?php
    	foreach($locations as $location) {
    		if(isset($last_location) && $last_location->gps==$location->gps) {
    			$last_location = $location;
    			continue;
    		}
    		else {
    		?>
        		new google.maps.LatLng(<?php echo $location->gps_lat; ?>,<?php echo $location->gps_long; ?>),
       		<?php 
       			$last_location = $location;
       		}
       	}
        ?>
    ];
    
    
   var bounds = new google.maps.LatLngBounds();
    <?php
    	$count = 1;
    	foreach($locations as $location) {
    ?>
      var myLatLng = new google.maps.LatLng(<?php echo $location->gps_lat; ?>,<?php echo $location->gps_long; ?>);
      <?php
      if($count==1) { ?>
      	var marker = new google.maps.Marker({
        	position: myLatLng,
        	map: map,
        	title: 'Start',
        	icon:'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|00CD00|000000'
    	});
    <?php
    	}
    elseif($count==count($locations)) { ?>
    	var marker = new google.maps.Marker({
        	position: myLatLng,
        	map: map,
        	title: 'End',
        	icon:'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=B|FF0000|000000'
    	});
    <?php
    	}
    	?>

      bounds.extend(myLatLng);
      map.fitBounds(bounds);
     <?php
     	$count++;
     }
     ?>


    var flightPath = new google.maps.Polyline({
      path: flightPlanCoordinates,
      strokeColor: "#FF8000",
      strokeOpacity: 1.0,
      strokeWeight: 2
    });

   flightPath.setMap(map);
  }
  
  
	$(function() {
		if (initialize) {
			initialize();
		}
	});
</script>

<div id="inner_content">

<div id="map_canvas" class="column"></div>
<script type="text/javascript">

	function display_form() {
		patient_code = $('#patient_code').val();
		url = '<?php echo Url::site('main/map_form') ?>/'+patient_code;
		$.post(url, function(data) {
		  $('#map_filters').html(data);
		});

	}
</script>
<div id="map_filters" class="column" id="map_form">
			
			
			<?php echo View::factory('routes/form')
				->set('patients', $patients)
				->set('patient_code', $patient_code)
				->set('dates', $dates)
				->set('date', $date)
				->render(); ?>
				
			
</div>


<br class="clear_float" />
</div>