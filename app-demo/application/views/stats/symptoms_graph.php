<script language="JavaScript" src="/js/jqplot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.ohlcRenderer.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.json2.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.ciParser.js"></script>
   <link rel="stylesheet" type="text/css" href="/js/jqplot/jquery.jqplot.css" />



<script class="code" type="text/javascript">$(document).ready(function(){

	/*
	An array of points for each symptom:
	This could be coded much more streamlined
	*/
	var cough = [];
	<?php
	if(isset($points["Current cough"])) {
		foreach($points["Current cough"] as $date=>$total) { 
		  ?>
			 cough.push(['<?php echo $date; ?>', <?php echo $total; ?>]);
		<?php
		}
	}
    ?>
    var fever = [];
	<?php
	if(isset($points["Fever"])) {
		foreach($points["Fever"] as $date=>$total) { 
		  ?>
			 fever.push(['<?php echo $date; ?>', <?php echo $total; ?>]);
		<?php
		}
	}
    ?>
    var weight = [];
	<?php
	if(isset($points["Weight loss"])) {
		foreach($points["Weight loss"] as $date=>$total) { 
		  ?>
			 weight.push(['<?php echo $date; ?>', <?php echo $total; ?>]);
		<?php
		}
	}
    ?>
    var sweats = [];
	<?php
	if(isset($points["Night sweats"])) {
		foreach($points["Night sweats"] as $date=>$total) { 
		  ?>
			 sweats.push(['<?php echo $date; ?>', <?php echo $total; ?>]);
		<?php
		}
	}
    ?>
    
  <?php
  /*foreach(Stats::$symptom_arr as $symptom) {
  	if(isset($points[$symptom])) {
  		echo Kohana::debug($points[$symptom]);
  		?>points['<?php echo $symptom; ?>'] = [];
  		<?php
  		foreach($points[$symptom] as $date->$total) {
  			?>
     			points[<?php echo $symptom; ?>].push(['<?php echo $date; ?>', <?php echo $total; ?>]);
     		<?php
  		}
  	}
 }*/
 ?>

  var plot2 = $.jqplot('chart2', [cough,fever,weight,sweats], {axes:
  											{xaxis:{renderer:$.jqplot.DateAxisRenderer},
  											yaxis: {min: 0,tickInterval: 1}},
  											legend:{
            renderer: $.jqplot.EnhancedLegendRenderer,
            show:true,
            showLabels: true,
            labels:['Current cough', 'Fever', 'Weight loss', 'Night sweats'],
            rendererOptions:{
                numberColumns:1,
                seriesToggle: 900,
                disableIEFading: false
            },
            placement:'outside',
            shrinkGrid: true
        }
  											});

  
  });</script>
  

  
<h2>Symptoms entered by date</h2>
  <div id="chart2" style="height:400px; width:800px;"></div>

