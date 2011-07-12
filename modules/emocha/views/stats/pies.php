 <!-- BEGIN: load jqplot -->
  <script language="javascript" type="text/javascript" src="/js/jqplot/jquery.jqplot.js"></script>
  <script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.pieRenderer.js"></script>
   <link rel="stylesheet" type="text/css" href="/js/jqplot/jquery.jqplot.css" />
<!--  <link rel="stylesheet" type="text/css" href="/js/jqplot/examples/examples.css" />-->

  <!-- END: load jqplot -->


<script id="example_1" type="text/javascript">$(document).ready(function(){
	jQuery.jqplot.config.enablePlugins = true;
	plot1 = jQuery.jqplot('chart1', 
		[[<?php
			$js = '';
			foreach($totals as $key=>$val) {
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


<div id="inner_content">
<b><?php echo $title; ?></b>

<div id="chart1" style="margin-top:20px; margin-left:20px; width:460px; height:300px;"></div>

</div>
    
