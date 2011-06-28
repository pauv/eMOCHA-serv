<script language="JavaScript" src="/js/jqplot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.ohlcRenderer.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.json2.js"></script>
<script language="javascript" type="text/javascript" src="/js/jqplot/plugins/jqplot.ciParser.js"></script>



<script class="code" type="text/javascript">$(document).ready(function(){

  var points = [];
  <?php
  $total = 0;
  foreach($points as $point) { 
  	$total+=$point['total'];
  ?>
     points.push(['<?php echo $point['date']; ?>', <?php echo $total; ?>]);
    <?php
    }
    ?>
  var plot2 = $.jqplot('chart1', [points], {axes:
  											{xaxis:{renderer:$.jqplot.DateAxisRenderer},
  											yaxis: {min: 0, tickInterval: 50}}
  											});
  											
 
  
  });</script>
  
  <h2>Cumulative patients by date</h2>
  <div id="chart1" style="height:400px; width:800px;"></div>
  
