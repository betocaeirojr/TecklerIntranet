<?php
include "../includes/header.php";
include "../includes/java_scripts.php";
require "conn_stats.php";

date_default_timezone_set('America/Sao_Paulo');

// Frequency
	$sql_distrib_frequency = 
		"select 
			a.RefDate as ReferenceDate, 
			(b.OneTimeVisitors / a.TotalVisitors) as PercOneTimeVisitors, 
			(c.TwoTimeVisitors / a.TotalVisitors) as PercTwoTimeVisitors, 
			(d.ThreeTimeVisitors / a.TotalVisitors) as PercThreeTimeVisitors, 
			(e.FourTimeVisitors / a.TotalVisitors) as PercFourTimeVisitors 
		from 
			(select date(DATE) as RefDate, sum(INFO_NUMBER_OF_VISITORS) as TotalVisitors 
				from CONS_METRICS_FREQUENCY 
				group by date(DATE) order by date(DATE) desc) a, 
			(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as OneTimeVisitors 
				from CONS_METRICS_FREQUENCY where INFO_NUMBER_OF_VISITS=1 order by date(DATE) desc) b, 
			(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as TwoTimeVisitors 
				from CONS_METRICS_FREQUENCY where INFO_NUMBER_OF_VISITS=2 order by date(DATE) desc) c, 
			(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as ThreeTimeVisitors 
				from CONS_METRICS_FREQUENCY where INFO_NUMBER_OF_VISITS=3 order by date(DATE) desc) d, 
			(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as FourTimeVisitors 
				from CONS_METRICS_FREQUENCY where INFO_NUMBER_OF_VISITS=4 order by date(DATE) desc) e 
		where 
			a.RefDate = b.RefDate and  
			a.RefDate = c.RefDate and 
			a.RefDate = d.RefDate and 
			a.RefDate = e.RefDate";

	$result = mysql_query($sql_distrib_frequency, $conn);

	$infoDateFrequency 			= "";
	$infoPercNumberOfVisits_1 	= "";

	while ($row_date = mysql_fetch_array($result)) {
		$frequency_day[] = array(
			"Date" 							=> $row_date['ReferenceDate'],
			"Percent One Time Visitor" 		=> $row_date['PercOneTimeVisitors'],
			"Percent Two Time Visitor" 		=> $row_date['PercTwoTimeVisitors'],
			"Percent Three Time Visitor" 	=> $row_date['PercThreeTimeVisitors'],
			"Percent Four Time Visitor"		=> $row_date['PercFourTimeVisitors']
			);
		$infoDateFrequency 			.= "," . date("Y-m-d",strtotime($row_date['ReferenceDate']));
		$infoPercNumberOfVisits_1 	.= "," . $row_date['PercOneTimeVisitors'] * 100;

	}

	// For Highcharts
		// Date
			$infoDateFrequency  = substr($infoDateFrequency, 1); 	// Remove the first ','
			$arr_infoDate 		= explode(",", $infoDateFrequency); // Transform to an Array to later reverse the arrary
			$arr_infoDate 		= array_reverse($arr_infoDate); 	// Reverse the Array
			$infoDateFrequency 	= implode(",", $arr_infoDate);		// Implode back to an String to use in Highcharts.

		//%Top100 PV Tecks
			$infoPercNumberOfVisits_1 = substr($infoPercNumberOfVisits_1, 1);
			$arr_1 = explode(",", $infoPercNumberOfVisits_1);
			$arr_1 = array_reverse($arr_1);
			$infoPercNumberOfVisits_1 = implode(",", $arr_1);  

// Recency

$sql_distrib_recency = 
	"select 
		a.RefDate as ReferenceDate, 
		(b.DaySinceLastVisitEq0 / a.TotalVisitors) as PercVisitorsDaySinceLastVisit0,
		(c.DaySinceLastVisitEq1 / a.TotalVisitors) as PercVisitorsDaySinceLastVisit1, 
		(d.DaySinceLastVisitEq2 / a.TotalVisitors) as PercVisitorsDaySinceLastVisit2, 
		(e.DaySinceLastVisitEq3 / a.TotalVisitors) as PercVisitorsDaySinceLastVisit3, 
		(f.DaySinceLastVisitEq4 / a.TotalVisitors) as PercVisitorsDaySinceLastVisit4 
	from 
		(select date(DATE) as RefDate, sum(INFO_NUMBER_OF_VISITORS) as TotalVisitors 
			from CONS_METRICS_RECENCY 
			group by date(DATE) 
			order by date(DATE) desc) a, 
		(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as DaySinceLastVisitEq0 
			from CONS_METRICS_RECENCY 
			where INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT=0 
			order by date(DATE) desc) b,
		(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as DaySinceLastVisitEq1 
			from CONS_METRICS_RECENCY 
			where INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT=1 
			order by date(DATE) desc) c, 
		(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as DaySinceLastVisitEq2 
			from CONS_METRICS_RECENCY 
			where INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT=2 
			order by date(DATE) desc) d, 
		(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as DaySinceLastVisitEq3 
			from CONS_METRICS_RECENCY 
			where INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT=3 
			order by date(DATE) desc) e, 
		(select date(DATE) as RefDate, INFO_NUMBER_OF_VISITORS as DaySinceLastVisitEq4 
			from CONS_METRICS_RECENCY 
			where INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT=4 
			order by date(DATE) desc) f 
	where 
		a.RefDate = b.RefDate and  
		a.RefDate = c.RefDate and 
		a.RefDate = d.RefDate and 
		a.RefDate = e.RefDate and
		a.RefDate = f.RefDate";
$result = mysql_query($sql_distrib_recency, $conn);
$infoDateRecency 		= "";
$infoDayLastVisitToday 	= "";
while ($row_date = mysql_fetch_array($result)) {
	$recency_date[] = array(
		"Date" 					=> $row_date['ReferenceDate'],
		"% Last Visit D0" 		=> $row_date['PercVisitorsDaySinceLastVisit0'],
		"% Last Visit D-1" 		=> $row_date['PercVisitorsDaySinceLastVisit1'],
		"% Last Visit D-2" 		=> $row_date['PercVisitorsDaySinceLastVisit2'],
		"% Last Visit D-3"		=> $row_date['PercVisitorsDaySinceLastVisit3'],
		"% Last Visit D-4" 		=> $row_date['PercVisitorsDaySinceLastVisit4']
		);
	$infoDateRecency 		.= "," . date("Y-m-d",strtotime($row_date['ReferenceDate']));
	$infoDayLastVisitToday 	.= "," . $row_date['PercVisitorsDaySinceLastVisit0'] * 100;
}
// For Highcharts
	// Date
		$infoDateRecency     = substr($infoDateRecency, 1); 	// Remove the first ','
		$arr_infoDatePr = explode(",", $infoDateRecency); 	// Transform to an Array to later reverse the arrary
		$arr_infoDatePr = array_reverse($arr_infoDatePr); 	// Reverse the Array
		$infoDateRecency = implode(",", $arr_infoDatePr);	// Implode back to an String to use in Highcharts.

	//%Top100 PV Tecks
		$infoDayLastVisitToday = substr($infoDayLastVisitToday, 1);
		$arr_2 = explode(",", $infoDayLastVisitToday);
		$arr_2 = array_reverse($arr_2);
		$infoDayLastVisitToday = implode(",", $arr_2);  

?>


<div id="wrap">
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
          	<?php include "../includes/main_menu.php"; ?>
          	<?php include "../includes/submenu_charts.php"; ?> 
          	<div class="clearfix"></div> 
        </div>
        <!--SIDEBAR END-->
        <div id="main" role="main">
          	<div class="block">
   		  		<div class="clearfix"></div>
            	<!--page title-->
             	<div class="pagetitle">
                	<h1>Frequency and Recency Information </h1>
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<div class="clearfix"></div>

             	<h3>Frequency</h3>
             	<p>Number of Visits one visitors have</p>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($frequency_day[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($frequency_day);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($frequency_day as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo date("Y-m-d", strtotime($ivalue)); 
	                                            } else {
	                                            	echo number_format($ivalue,4, ".", ",") * 100 . "%";
	                                            }
	                                            echo "</TD>";
	                                          }
	                                          echo "</tr>";
	                                        }
	                                    ?>
	                                </tbody>
	                            </table>
	                        <div class="clearfix"></div>
	                    </div>
	                  </div>     <!--Striped table END-->
	                </div>
              		<!--  Users per Day Charts -->
		              <br>
		              <div class='clearfix'>
		                <input type='hidden' id="date-axis-X" value="<?php echo $infoDateFrequency; ?>">
		                <input type='hidden' id="tecks-axis-Y" value="<?php echo $infoPercNumberOfVisits_1; ?>">
		                <div id="container_frequency" style="width:100%; height:300px;"></div>
		                <script>
		                  $(function () { 
		                      // Treating Pageviews Graphic
		                          var dateValues = $('#date-axis-X').val();
		                          var xAxis = dateValues.split(",");
		                          var tecksValues = $('#tecks-axis-Y').val();
		                          var yAxis = tecksValues.split(',');
		                          var merge = [];
		                          for (var i=0; i < xAxis.length; i++) { 
		                            var dateparts = xAxis[i].split("-");
		                            var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
		                            var tecks = parseFloat(yAxis[i], 10);
		                            merge.push([date, tecks]);
		                          }
		                          $('#container_frequency').highcharts({
		                                chart: { type: 'line' },
		                                title: { text: '% One Time Visitors' },
		                                xAxis: { type : 'datetime' },
		                                yAxis: { title: { text: '% One Time Visitors' } , 
		                                         type: 'linear', 
		                                      	 allowDecimals : 'true',
												},
		                                series: [{
		                                    name: '% One Time Visitors',
		                                    data: merge
		                                }]
		                            });
		                          });
		                </script>  
		              </div >


             	<h3>Recency</h3>
             	<p>Days Since Last Visit</p>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($recency_date[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($recency_date);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($recency_date as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo date("Y-m-d", strtotime($ivalue)); 
	                                            } else {
	                                            	echo number_format($ivalue,4, ".", ",") * 100;
	                                            }
	                                            echo "</TD>";
	                                          }
	                                          echo "</tr>";
	                                        }
	                                    ?>
	                                </tbody>
	                            </table>
	                        <div class="clearfix"></div>
	                    </div>
	                  </div>     <!--Striped table END-->
	                </div>
             
              <!--  Users per Day Charts -->
	              <br>
	              <div class='clearfix'>
	                <input type='hidden' id="date-axis-X" value="<?php echo $infoDateRecency; ?>">
	                <input type='hidden' id="profiles-axis-Y" value="<?php echo $infoDayLastVisitToday; ?>">
	                <div id="container_profiles" style="width:100%; height:300px;"></div>
	                <script>
	                  $(function () { 
	                      // Treating Pageviews Graphic
	                          var dateValues = $('#date-axis-X').val();
	                          var xAxis = dateValues.split(",");
	                          var profilesValues = $('#profiles-axis-Y').val();
	                          var yAxis = profilesValues.split(',');
	                          var merge = [];
	                          for (var i=0; i < xAxis.length; i++) { 
	                            var dateparts = xAxis[i].split("-");
	                            var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
	                            var profiles = parseFloat(yAxis[i], 10);
	                            merge.push([date, profiles]);
	                          }
	                          $('#container_profiles').highcharts({
	                                chart: { type: 'line' },
	                                title: { text: '% Days Since Last Visit Equals 0' },
	                                xAxis: { type : 'datetime' },
	                                yAxis: { title: { text: '% Days Since Last Visit Equals 0' } , 
	                                          type: 'linear'},
	                                series: [{
	                                    name: '% Days Since Last Visit Equals 0',
	                                    data: merge
	                                }]
	                            });
	                          });
	                </script>  
	              </div >


         	</div>
         </div>


</div>
<?php

// GET Number of Users With Keep Me Logged On
//$result = mysql_query($sql_numusers_with_keepmelogged, $conn);
//while ($row = mysql_fetch_array($result)) {
//	$OnlineUsersToday     = $row['NumUsers'];/
//	
//}

?>
