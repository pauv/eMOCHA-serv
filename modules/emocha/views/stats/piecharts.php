 <!-- BEGIN: load jqplot -->
  <script language="javascript" type="text/javascript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/jquery.jqplot.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/plugins/jqplot.pieRenderer.js"></script>
   <link rel="stylesheet" type="text/css" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/jquery.jqplot.css" />
<!--  <link rel="stylesheet" type="text/css" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/jqplot/examples/examples.css" />-->

  <!-- END: load jqplot -->
<div id="inner_content">

<? 
$count = 0;
foreach ($totals as $pie_name=>$vals) { 
	$count++;
?>
<script type="text/javascript">$(document).ready(function(){
	jQuery.jqplot.config.enablePlugins = true;
	plot1 = jQuery.jqplot('chart<?php echo $count; ?>', 
		[[<?php
			$js = '';
			foreach($vals as $key=>$val) {
			 	$js .= "['".$key."', ".$val."],";
			 }
			 $js = substr($js, 0, -1);
			echo $js;
			?>]], 
		{
			title: ' ', 
			seriesDefaults: {renderer: jQuery.jqplot.PieRenderer, rendererOptions: { sliceMargin:2 } }, 
			legend: { show:true }
		}
	);
});
</script>
<b><?php echo $pie_name; ?></b>

<div id="chart<?php echo $count; ?>" style="margin-top:20px; margin-left:20px; width:460px; height:300px;"></div>
<?php } ?>





</div>
    
