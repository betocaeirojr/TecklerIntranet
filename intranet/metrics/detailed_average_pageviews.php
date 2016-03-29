<?php
include "../includes/header.php";
require "conn.php";

date_default_timezone_set('America/Sao_Paulo');

$sql_avg_pageviews_per_teck_per_day = 
	"select 
		(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
		(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
		((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal, 
		date(a.RefDate) as ReferenceDate
	from
		(select  
			sum(VIEWS) as SumPageviewsTeckler,  
			sum(DFP_VIEWS) as SumPageviewsDFP,  
			DAY as RefDate,  
			POST_ID as TeckID 
		from  
			DAILY_VIEWS  
		where  
			date(DAY) <> date('0000-00-00')  
		group by 
			POST_ID, date(DAY)  
		order by  
			date(DAY)) a
	group by
		date(a.RefDate) 
	order by 
		date(a.RefDate) DESC";
$sql_avg_pageviews_per_teck_per_week = 
	"select 
		(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
		(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
		((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal, 
		week(a.RefDate, 7) as ReferenceWeek, 
		date(a.RefDate) as ReferenceDate  
	from
		(select  
			sum(VIEWS) as SumPageviewsTeckler,  
			sum(DFP_VIEWS) as SumPageviewsDFP,  
			DAY as RefDate,  
			POST_ID as TeckID 
		from  
			DAILY_VIEWS  
		where  
			date(DAY) <> date('0000-00-00')  
		group by 
			POST_ID, date(DAY)  
		order by  
			date(DAY)) a
	group by
		week(a.RefDate, 7) 
	order by 
		year(a.RefDate) DESC, week(a.RefDate, 7) DESC";
$sql_avg_pageviews_per_teck_per_month = 
	"select 
		(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
		(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
		((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal, 
		date(a.RefDate) as ReferenceDate
	from
		(select  
			sum(VIEWS) as SumPageviewsTeckler,  
			sum(DFP_VIEWS) as SumPageviewsDFP,  
			DAY as RefDate,  
			POST_ID as TeckID 
		from  
			DAILY_VIEWS  
		where  
			month(DAY) <> '00' and 
			year(DAY) >= '2013' 
		group by 
			POST_ID, month(DAY)  
		order by  
			month(DAY)) a 
	group by 
		month(a.RefDate) 
	order by 
		year(a.RefDate) DESC, month(a.RefDate) DESC";


$sql_avg_views_businessplan_day = 
	"select 
		date(a.ReferenceDate) as ReferenceDate, 
		a.SumViewsTeckler as SumViewsTeckler,
		b.NumTecks as NumTotalTecks, 
		(a.SumViewsTeckler / b.NumTecks) as AverageViewsPerTotalTeck
	from
		(select date(DAY) as ReferenceDate, sum(VIEWS) as SumViewsTeckler 
			from DAILY_VIEWS 
			group by date(DAY) order by date(DAY) DESC limit 1) a,
		(select count(POST_ID) as NumTecks, max(date(PUBLISH_DATE)) as LastDay 
			from POST where status_id=1) b
	where a.ReferenceDate = b.LastDay";
$sql_avg_views_businessplan_week =
	"select 
		date(a.ReferenceDate) as ReferenceDate,
		week(a.ReferenceDate) as ReferenceWeek, 
		a.SumViewsTeckler as SumViewsTeckler,
		b.NumTecks as NumTotalTecks, 
		(a.SumViewsTeckler / b.NumTecks) as AverageViewsPerTotalTeck
	from
		(select date(DAY) as ReferenceDate, sum(VIEWS) as SumViewsTeckler from DAILY_VIEWS 
			group by YEAR(DAY), WEEK(DAY) order by YEAR(DAY) DESC ,WEEK(DAY) DESC limit 1) a,
		(select count(POST_ID) as NumTecks, max(date(PUBLISH_DATE)) as LastDay 
			from POST where status_id=1) b
	where WEEK(a.ReferenceDate) = WEEK(b.LastDay) and year(a.ReferenceDate) = year(b.LastDay)";
$sql_avg_views_businessplan_month =
	"select 
		date(a.ReferenceDate) as ReferenceDate, 
		a.SumViewsTeckler as SumViewsTeckler,
		b.NumTecks as NumTotalTecks, 
		(a.SumViewsTeckler / b.NumTecks) as AverageViewsPerTotalTeck
	from
		(select date(DAY) as ReferenceDate, sum(VIEWS) as SumViewsTeckler from DAILY_VIEWS 
			group by YEAR(DAY), MONTH(DAY) order by YEAR(DAY) DESC ,MONTH(DAY) DESC limit 1) a,
		(select count(POST_ID) as NumTecks, max(date(PUBLISH_DATE)) as LastDay 
			from POST where status_id=1) b
	where month(a.ReferenceDate) = month(b.LastDay) and  year(a.ReferenceDate) = year(b.LastDay)" ;


$sql_num_tecks_viewed_day = 
	"select 
		date(DAY) as RefDate, 
		count(POST_ID) as NumTecksViewed  
	from 
		DAILY_VIEWS 
	where 
		date(DAY) <> date('0000-00-00') 
	group by date(DAY) 
	order by date(DAY) DESC" ;
$sql_num_tecks_viewed_month = 
	"select 
		date(DAY) as RefDate, 
		count(POST_ID) as NumTecksViewed  
	from 
		DAILY_VIEWS 
	where 
		date(DAY) <> date('0000-00-00') 
	group by year(DAY), month(DAY)  
	order by year(DAY) DESC, month(DAY) DESC";

$sql_num_tecklers_viewed_day = 
	"select 
		date(DAY) as RefDate, 
		count(PROFILE_ID) as NumTecklersViewed  
	from 
		DAILY_VIEWS 
	where 
		date(DAY) <> date('0000-00-00') 
	group by date(DAY) 
	order by date(DAY) DESC";
$sql_num_tecklers_viewed_month = 
	"select 
		date(DAY) as RefDate, 
		count(PROFILE_ID) as NumTecklersViewed  
	from 
		DAILY_VIEWS 
	where 
		date(DAY) <> date('0000-00-00') 
	group by year(DAY), month(DAY)  
	order by year(DAY) DESC, month(DAY) DESC";



$result_date = mysql_query($sql_avg_pageviews_per_teck_per_day, $conn);
while ($row_date = mysql_fetch_array($result_date)) {
	$avgPageviewsPerTeck_Date[] = array(
		"Date" 								=> $row_date['ReferenceDate'],
		"Avg Pageviews Per Teck - Teckler" 	=> $row_date['AvgPageviewsTecks'],
		"Avg Pageviews Per Teck - DFP" 		=> $row_date['AvgPageviewsDFP'],
		"Avg Pageviews Per Teck - Global" 	=> $row_date['AvgPageviewsGlobal'] 
		);
}

$result_date = mysql_query($sql_avg_pageviews_per_teck_per_week, $conn);
while ($row_date = mysql_fetch_array($result_date)) {
	$avgPageviewsPerTeck_Week[] = array(
		"Week of the Year" 					=> $row_date['ReferenceWeek'],
		"Date"								=> $row_date['ReferenceDate'],
		"Avg Pageviews Per Teck - Teckler" 	=> $row_date['AvgPageviewsTecks'],
		"Avg Pageviews Per Teck - DFP" 		=> $row_date['AvgPageviewsDFP'],
		"Avg Pageviews Per Teck - Global" 	=> $row_date['AvgPageviewsGlobal'] 
		);
}


$result_month = mysql_query($sql_avg_pageviews_per_teck_per_month, $conn);
while ($row_month = mysql_fetch_array($result_month)) {
	$avgPageviewsPerTeck_Month[] = array(
		"Date" 								=> $row_month['ReferenceDate'],
		"AvgPageviewsPerTeck_TecklerCtrl" 	=> $row_month['AvgPageviewsTecks'],
		"AvgPageviewsPerTeck_DFPCtrl" 		=> $row_month['AvgPageviewsDFP'],
		"AvgPageviewsPerTeck_Global" 		=> $row_month['AvgPageviewsGlobal'] 
		);
}


// Average Views Day per Total Tecks - Business Plan Metric
$result = mysql_query($sql_avg_views_businessplan_day, $conn);
while ($row = mysql_fetch_array($result)) {
	$avg_views_per_teck_bp_day     = $row['AverageViewsPerTotalTeck'];
	
}

// Average Views Week per Total Tecks - Business Plan Metric
$result = mysql_query($sql_avg_views_businessplan_week, $conn);
while ($row = mysql_fetch_array($result)) {
	$avg_views_per_teck_bp_week     = $row['AverageViewsPerTotalTeck'];
	
}

// Average Views Month per Total Tecks - Business Plan Metric
$result = mysql_query($sql_avg_views_businessplan_month, $conn);
while ($row = mysql_fetch_array($result)) {
	$avg_views_per_teck_bp_month     = $row['AverageViewsPerTotalTeck'];
	
}

// Number of Tecks Day
$result_day = mysql_query($sql_num_tecks_viewed_day, $conn);
while ($row_month = mysql_fetch_array($result_day)) {
	$numViewedTecks_Day[] = array(
		"Date" 						=> $row_month['RefDate'],
		"Number of Viewed Tecks" 	=> $row_month['NumTecksViewed']
		);
}
// Number of Tecks Month
$result_month = mysql_query($sql_num_tecks_viewed_month, $conn);
while ($row_month = mysql_fetch_array($result_month)) {
	$numViewedTecks_Month[] = array(
		"Date" 						=> $row_month['RefDate'],
		"Number of Viewed Tecks" 	=> $row_month['NumTecksViewed']
		);
}

// Number of Tecklers Day
$result_day = mysql_query($sql_num_tecklers_viewed_day, $conn);
while ($row_month = mysql_fetch_array($result_day)) {
	$numViewedTecklers_Day[] = array(
		"Date" 							=> $row_month['RefDate'],
		"Number of Viewed Tecklers" 	=> $row_month['NumTecklersViewed']
		);
}
// Number of Tecklers Month
$result_month = mysql_query($sql_num_tecklers_viewed_month, $conn);
while ($row_month = mysql_fetch_array($result_month)) {
	$numViewedTecklers_Month[] = array(
		"Date" 							=> $row_month['RefDate'],
		"Number of Viewed Tecklers" 	=> $row_month['NumTecklersViewed']
		);
}


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
                	<h1>Reporting - Info on Average Pageviews per Tecks </h1>
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<div class="clearfix"></div>

             	<h3>Average Views per Total Tecks</h3>
             	<BR>
             	<div class="information-box-3 span4 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Average Views (To Day) Per Total Tecks </div>
		             		<div class="box-title"> <?php echo round($avg_views_per_teck_bp_day,2); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<!-- Average Views (for some period) per Total Tecks -->
             	<div class="information-box-3 span4 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Average Views (Week To Date) Per Total Tecks </div>
		             		<div class="box-title"> <?php echo round($avg_views_per_teck_bp_week,2); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span4 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Average Views (Month To Date) Per Total Tecks </div>
		             		<div class="box-title"> <?php echo round($avg_views_per_teck_bp_month,2); ?></div>
		             	</div>
	             	</div>
             	</div>
           	


             	<h3>Average Pageviews Per Teck - Daily (Considering Only Viewed Tecks at the Period)</h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($avgPageviewsPerTeck_Date[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($avgPageviewsPerTeck_Date);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($avgPageviewsPerTeck_Date as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo date("Y-m-d", strtotime($ivalue)); 
	                                            } else {
	                                              echo number_format($ivalue,4, ".", ",");
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

	              <h3>Average Pageviews Per Teck - Weeky</h3>
	              <h4>Week starting on Monday</h4>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($avgPageviewsPerTeck_Week[0]);
	                                          foreach ($keys as $key => $value) {

	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($avgPageviewsPerTeck_Week);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($avgPageviewsPerTeck_Week as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
												echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo date("Y-m-d", strtotime($ivalue)); 
	                                            } else {
	                                              echo number_format($ivalue,4, ".", ",");
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

	            <h3> Average Pageviews Per Teck - Monthly </h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                  <thead>
	                                    <tr>
	                                      <?php
	                                        $keys = array_keys($avgPageviewsPerTeck_Month[0]);
	                                        foreach ($keys as $key => $value) {
	                                          echo "<th align=center>";
	                                          echo $value;
	                                          echo "</th>";
	                                        }
	                                        reset($avgPageviewsPerTeck_Month);
	                                      ?>
	                                    </tr>
	                                  </thead>
	                                  <tbody>
	                                    <?php 
	                                      foreach ($avgPageviewsPerTeck_Month as $ekey => $evalue) {
	                                        echo "<tr>";    
	                                        foreach ($evalue as $ikey => $ivalue) {
	                                          echo "<TD align=center>";
	                                          if ($ikey == 'Date') {
	                                            echo date("Y-m", strtotime($ivalue)); 
	                                          } else {
	                                            echo number_format($ivalue,4,".",",");
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
	                    </div><!--Striped table END-->
	                </div>

	            <HR>
				<h3> Viewed Tecks - Day </h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                  <thead>
	                                    <tr>
	                                      <?php
	                                        $keys = array_keys($numViewedTecks_Day[0]);
	                                        foreach ($keys as $key => $value) {
	                                          echo "<th align=center>";
	                                          echo $value;
	                                          echo "</th>";
	                                        }
	                                        reset($numViewedTecks_Day);
	                                      ?>
	                                    </tr>
	                                  </thead>
	                                  <tbody>
	                                    <?php 
	                                      foreach ($numViewedTecks_Day as $ekey => $evalue) {
	                                        echo "<tr>";    
	                                        foreach ($evalue as $ikey => $ivalue) {
	                                          echo "<TD align=center>";
	                                          if ($ikey == 'Date') {
	                                            echo date("Y-m-d: l", strtotime($ivalue)); 
	                                          } else {
	                                            echo number_format($ivalue,0,".",",");
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
	                    </div><!--Striped table END-->
	                </div>
				<h3> Viewed Tecks - Month </h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                  <thead>
	                                    <tr>
	                                      <?php
	                                        $keys = array_keys($numViewedTecks_Month[0]);
	                                        foreach ($keys as $key => $value) {
	                                          echo "<th align=center>";
	                                          echo $value;
	                                          echo "</th>";
	                                        }
	                                        reset($numViewedTecks_Month);
	                                      ?>
	                                    </tr>
	                                  </thead>
	                                  <tbody>
	                                    <?php 
	                                      foreach ($numViewedTecks_Month as $ekey => $evalue) {
	                                        echo "<tr>";    
	                                        foreach ($evalue as $ikey => $ivalue) {
	                                          echo "<TD align=center>";
	                                          if ($ikey == 'Date') {
	                                            echo date("Y-m", strtotime($ivalue)); 
	                                          } else {
	                                            echo number_format($ivalue,0,".",",");
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
	                    </div><!--Striped table END-->
	                </div>

				<h3> Viewed Tecklers - Day </h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                  <thead>
	                                    <tr>
	                                      <?php
	                                        $keys = array_keys($numViewedTecklers_Day[0]);
	                                        foreach ($keys as $key => $value) {
	                                          echo "<th align=center>";
	                                          echo $value;
	                                          echo "</th>";
	                                        }
	                                        reset($numViewedTecklers_Day);
	                                      ?>
	                                    </tr>
	                                  </thead>
	                                  <tbody>
	                                    <?php 
	                                      foreach ($numViewedTecklers_Day as $ekey => $evalue) {
	                                        echo "<tr>";    
	                                        foreach ($evalue as $ikey => $ivalue) {
	                                          echo "<TD align=center>";
	                                          if ($ikey == 'Date') {
	                                            echo date("Y-m-d: l", strtotime($ivalue)); 
	                                          } else {
	                                            echo number_format($ivalue,0,".",",");
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
	                    </div><!--Striped table END-->
	                </div>
				<h3> Viewed Tecklers - Month </h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                  <thead>
	                                    <tr>
	                                      <?php
	                                        $keys = array_keys($numViewedTecklers_Month[0]);
	                                        foreach ($keys as $key => $value) {
	                                          echo "<th align=center>";
	                                          echo $value;
	                                          echo "</th>";
	                                        }
	                                        reset($numViewedTecklers_Month);
	                                      ?>
	                                    </tr>
	                                  </thead>
	                                  <tbody>
	                                    <?php 
	                                      foreach ($numViewedTecklers_Month as $ekey => $evalue) {
	                                        echo "<tr>";    
	                                        foreach ($evalue as $ikey => $ivalue) {
	                                          echo "<TD align=center>";
	                                          if ($ikey == 'Date') {
	                                            echo date("Y-m", strtotime($ivalue)); 
	                                          } else {
	                                            echo number_format($ivalue,0,".",",");
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
	                    </div><!--Striped table END-->
	                </div>

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
