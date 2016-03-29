<?php
include "../includes/header.php";
include "../includes/java_scripts.php";
require "conn_stats.php";

date_default_timezone_set('America/Sao_Paulo');

$sql_perc_top100_total_tecks = 
	"select 
		a.RefDate as ReferenceDate, 
		a.SumViews as TotalPageviewsDay, 
		b.SumViewsTop100 as TotalTop100PageviewsDay,
		(b.SumViewsTop100 / a.SumViews) as PercTop100_Total,
		a.TotalTecks as TotalTecks, 
		100 as Top100Tecks,
		100/a.TotalTecks as Perc100_TotalTecks
	from 
		(select date(DATE) as RefDate, TOTAL_SUM_VIEWS as SumViews, TOTAL_TECKS as TotalTecks 
			from DELTA_METRICS_DAILY_VIEWS order by date(DATE) DESC) a,
		(select date(DATE) as RefDate, sum(TOTAL_PAGEVIEWS) as SumViewsTop100 from 
			TOP_100_TECKS_PER_PAGEVIEW group by date(DATE) order by date(DATE) DESC) b 
	where 
		a.RefDate = b.RefDate
	order by a.RefDate DESC";
$sql_perc_top100_total_profiles = 
	"select 
		a.RefDate as ReferenceDate, 
		a.SumViews as TotalPageviewsDay, 
		b.SumViewsTop100 as TotalTop100PageviewsDay,
		(b.SumViewsTop100 / a.SumViews) as PercTop100_Total,
		a.TotalProfiles as TotalProfiles, 
		100 as Top100Profiles,
		100/a.TotalProfiles as Perc100_TotalProfiles
	from 
		(select date(DATE) as RefDate, TOTAL_SUM_VIEWS as SumViews, TOTAL_PROFILES as TotalProfiles
			from DELTA_METRICS_DAILY_VIEWS order by date(DATE) DESC) a,
		(select date(DATE) as RefDate, sum(TOTAL_PAGEVIEWS) as SumViewsTop100 from 
			TOP_100_PROFILES_PER_PAGEVIEW group by date(DATE) order by date(DATE) DESC) b 
	where 
		a.RefDate = b.RefDate
	order by a.RefDate DESC";

$result_tecks = mysql_query($sql_perc_top100_total_tecks, $conn);

$infoDateTeck 			= "";
$infoPercTop100Teck_PV 	= "";
$infoPercTop100Teck_TK 	= "";
while ($row_date = mysql_fetch_array($result_tecks)) {
	$top100_tecks_pv_Date[] = array(
		"Date" 						=> $row_date['ReferenceDate'],
		"Total Pageviews" 			=> $row_date['TotalPageviewsDay'],
		"Sum Pageviews <BR>Top 100 Tecks" 	=> $row_date['TotalTop100PageviewsDay'],
		"% Top 100 / Total" 		=> $row_date['PercTop100_Total'],
		"Total Tecks Viewed"		=> $row_date['TotalTecks'],
		"% Top 100 / Total Tecks" 	=> $row_date['Perc100_TotalTecks']
		);
	$infoDateTeck 			.= "," . date("Y-m-d",strtotime($row_date['ReferenceDate']));
	$infoPercTop100Teck_PV 	.= "," . $row_date['PercTop100_Total'] * 100;

}

// For Highcharts
	// Date
		$infoDateTeck     = substr($infoDateTeck, 1); 	// Remove the first ','
		$arr_infoDate = explode(",", $infoDateTeck); 	// Transform to an Array to later reverse the arrary
		$arr_infoDate = array_reverse($arr_infoDate); 	// Reverse the Array
		$infoDateTeck = implode(",", $arr_infoDate);	// Implode back to an String to use in Highcharts.

	//%Top100 PV Tecks
		$infoPercTop100Teck_PV = substr($infoPercTop100Teck_PV, 1);
		$arr_1 = explode(",", $infoPercTop100Teck_PV);
		$arr_1 = array_reverse($arr_1);
		$infoPercTop100Teck_PV = implode(",", $arr_1);  


$result_profiles = mysql_query($sql_perc_top100_total_profiles, $conn);
$infoDateProfile 			= "";
$infoPercTop100Profile_PV 	= "";
while ($row_date = mysql_fetch_array($result_profiles)) {
	$top100_profiles_pv_Date[] = array(
		"Date" 								=> $row_date['ReferenceDate'],
		"Total Pageviews" 					=> $row_date['TotalPageviewsDay'],
		"Sum Top 100 Profiles - Pageviews" 	=> $row_date['TotalTop100PageviewsDay'],
		"% Top 100 / Profiles " 			=> $row_date['PercTop100_Total'],
		"Total Profiles Viewed"				=> $row_date['TotalProfiles'],
		"% Top 100 / Total Profiles" 		=> $row_date['Perc100_TotalProfiles']
		);
	$infoDateProfile 			.= "," . date("Y-m-d",strtotime($row_date['ReferenceDate']));
	$infoPercTop100Profile_PV 	.= "," . $row_date['PercTop100_Total'] * 100;
}
// For Highcharts
	// Date
		$infoDateProfile     = substr($infoDateProfile, 1); 	// Remove the first ','
		$arr_infoDatePr = explode(",", $infoDateProfile); 	// Transform to an Array to later reverse the arrary
		$arr_infoDatePr = array_reverse($arr_infoDatePr); 	// Reverse the Array
		$infoDateProfile = implode(",", $arr_infoDatePr);	// Implode back to an String to use in Highcharts.

	//%Top100 PV Tecks
		$infoPercTop100Profile_PV = substr($infoPercTop100Profile_PV, 1);
		$arr_2 = explode(",", $infoPercTop100Profile_PV);
		$arr_2 = array_reverse($arr_2);
		$infoPercTop100Profile_PV = implode(",", $arr_2);  

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
                	<h1>Representativeness of the Top 100 Tecks and Profiles </h1>
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<div class="clearfix"></div>

             	<h3>Tecks</h3>
             	<p>Sum TOP 100 Tecks (order by Pageviews) / Total Pageviews - of the Day</p>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($top100_tecks_pv_Date[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($top100_tecks_pv_Date);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($top100_tecks_pv_Date as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo "<a href=\"detailed_top_100_tecks-profiles_day.php?d=" . date("Y-m-d", strtotime($ivalue)) . "&i=t\">";
	                                              echo date("Y-m-d", strtotime($ivalue)) . "</a>"; 
	                                            } elseif (strstr($ikey, "%") != FALSE) {
	                                              	echo number_format($ivalue,4, ".", ",")*100 . "%";
	                                            } else {
	                                            	echo number_format($ivalue,0, ".", ",");
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
	                <input type='hidden' id="date-axis-X" value="<?php echo $infoDateTeck; ?>">
	                <input type='hidden' id="tecks-axis-Y" value="<?php echo $infoPercTop100Teck_PV; ?>">
	                <div id="container_tecks" style="width:100%; height:300px;"></div>
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
	                          $('#container_tecks').highcharts({
	                                chart: { type: 'line' },
	                                title: { text: '% Top 100 Tecks (PV) / Total Pageviews' },
	                                xAxis: { type : 'datetime' },
	                                yAxis: { title: { text: '% Top 100 Tecks' } , 
	                                          type: 'linear'},
	                                series: [{
	                                    name: '%Top 100 Tecks',
	                                    data: merge
	                                }]
	                            });
	                          });
	                </script>  
	              </div >


             	<h3>Profiles</h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($top100_profiles_pv_Date[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($top100_profiles_pv_Date);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($top100_profiles_pv_Date as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                            	echo "<a href=\"detailed_top_100_tecks-profiles_day.php?d=" . date("Y-m-d", strtotime($ivalue)) . "&i=p\">";
	                                              	echo date("Y-m-d", strtotime($ivalue)) . "</a>"; 
	                                            } elseif (strstr($ikey, "%") != FALSE) {
	                                              	echo number_format($ivalue,4, ".", ",")*100 . "%";
	                                            } else {
	                                            	echo number_format($ivalue,0, ".", ",");
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
	                <input type='hidden' id="date-axis-X" value="<?php echo $infoDateProfile; ?>">
	                <input type='hidden' id="profiles-axis-Y" value="<?php echo $infoPercTop100Profile_PV; ?>">
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
	                                title: { text: '% Top 100 Profiles (PV) / Total Pageviews' },
	                                xAxis: { type : 'datetime' },
	                                yAxis: { title: { text: '% Top 100 Profiles' } , 
	                                          type: 'linear'},
	                                series: [{
	                                    name: '%Top 100 Profiles',
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
