<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $google_maps_key; ?>" type="text/javascript"></script>
<script src="<?php echo Kohana::config('assets.javascript_folder'); ?>/markerclusterer_packed.js" type="text/javascript"></script>
<script type="text/javascript">

	var pMap;
	var pPatientData = [];

	function showMarkerDetails(tMarker) {
		// send PID to ajax.php, which will return html and image
		tMarker.openInfoWindowHtml(pPatientData[tMarker.PID]);				
	}

    function initialize() {
		if(GBrowserIsCompatible()) {
		    pMap = new GMap2(document.getElementById("map_canvas"));
		    pMap.setCenter(new GLatLng(20, 0), 2);
		    //map.addControl(new GLargeMapControl());
			pMap.setUIToDefault();

			GEvent.addListener(pMap, "click", function(e) {
				if (e instanceof GMarker) {
					showMarkerDetails(e);
				}				
			});

			var recentIcon = new GIcon();
			recentIcon.image = "http://labs.google.com/ridefinder/images/mm_20_green.png";
			recentIcon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
			recentIcon.iconSize = new GSize(12, 20);
			recentIcon.shadowSize = new GSize(22, 20);
			recentIcon.iconAnchor = new GPoint(6, 20);
			recentIcon.infoWindowAnchor = new GPoint(5, 1);

			var oldIcon = new GIcon();
			oldIcon.image = "http://labs.google.com/ridefinder/images/mm_20_orange.png";
			oldIcon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
			oldIcon.iconSize = new GSize(12, 20);
			oldIcon.shadowSize = new GSize(22, 20);
			oldIcon.iconAnchor = new GPoint(6, 20);
			oldIcon.infoWindowAnchor = new GPoint(5, 1);
			
		    var tMarkers = [];
			var tMarker;
			<?php echo $markerDataJS; ?>
		    var markerCluster = new MarkerClusterer(pMap, tMarkers);			    
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