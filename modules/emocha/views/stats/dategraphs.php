<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/plugins/jqplot.ohlcRenderer.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/plugins/jqplot.json2.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/plugins/jqplot.ciParser.js"></script>
   <link rel="stylesheet" type="text/css" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/jquery.jqplot.css" />


<div id="inner_content">

<?php
$count = 0;


foreach ($totals as $chart_name=>$vals) { 
	$count++;
	/*
	An array of points for each symptom:
	This could be coded much more streamlined
	
	$vals[case][date]=num
	*/
?><script class="code" type="text/javascript">$(document).ready(function(){
	points<?php echo $count; ?> = [];
<?php

	foreach($vals as $case=>$dates) {
		// create javascript elements
  		?>
  		points<?php echo $count; ?>['<?php echo $case; ?>'] = [];
  		<?php
  		/*echo Kohana::debug($dates);
  		exit;*/
		foreach($dates as $date=>$total) {
		?>
		points<?php echo $count; ?>['<?php echo $case; ?>'].push(['<?php echo $date; ?>', <?php echo $total; ?>]);
		<?php
		}
  	}
 
?>
  var plot<?php echo $count; ?> = $.jqplot('chart<?php echo $count; ?>', [<?php
  		$str = '';
  		foreach($vals as $case=>$dates) {
  			$str .= "points".$count."['".$case."'],";
  		}
  		$str = substr($str,0,-1);
  		echo $str;
  		?>], {axes:
  											{xaxis:{renderer:$.jqplot.DateAxisRenderer},
  											yaxis: {min: 0,tickInterval: 1}},
  											legend:{
            renderer: $.jqplot.EnhancedLegendRenderer,
            show:true,
            showLabels: true,
            labels:[<?php
				$str = '';
				foreach($vals as $case=>$dates) {
					$str .= "'".$case."',";
				}
				$str = substr($str,0,-1);
				echo $str;
				?>],
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
  
  <br />
<b><?php echo $chart_name; ?></b>
  <div id="chart<?php echo $count; ?>" style="height:400px; width:800px;"></div>
  </div>

<?php
}
?>

</div>