<?php
include "../includes/header.php";
include "../includes/java_scripts.php";
require "../metrics/conn.php";

date_default_timezone_set('America/Sao_Paulo');

$sql_total_and_fraudlent_daily_views = 
	"select 
		a.RefDate as RefDate, a.TotalViewsDay as TotalViewsDay, 
		b.FraudlentViewsDay as FraudlentViewsDay, (b.FraudlentViewsDay / a.TotalViewsDay) as PercFraudlentViews
	from 
		(select date(DAY) as RefDate, sum(VIEWS) as TotalViewsDay 
		from DAILY_VIEWS where date(DAY) <> '0000-00-00' 
		group by date(DAY) order by date(DAY) desc) a,
		(select date(DAY) as RefDate, sum(VIEWS) as FraudlentViewsDay 
		from DAILY_VIEWS 
		where date(DAY) <> '0000-00-00' and  
			PROFILE_ID in 
				(select distinct(AD_UNIT_ID)  from PAY.AD_UNIT_CONFIG 
				where MIN_CPV = 0 
				UNION 
				select distinct(p.PROFILE_ID) from PROFILE p, USER u, USER_PROFILE up 
				where p.PROFILE_ID = up.PROFILE_ID and u.USER_ID = up.USER_ID and  u.IS_TRICKSTER=1 
				) 
		group by date(DAY) order by date(DAY) DESC) b
	where a.RefDate = b.RefDate
	order by a.RefDate DESC";

$result = mysql_query($sql_total_and_fraudlent_daily_views, $conn);

// For High Charts
$infoPercFraudviews = "";
$infoDate 			= "";

while ($row= mysql_fetch_array($result)) {
	$resulting_array[] = 
		array(
			"Date" 					=> $row['RefDate'],
			"Total Daily Views"	 	=> $row['TotalViewsDay'],
			"Total Fraudlent Views" => $row['FraudlentViewsDay'],
			"% Fraudlent Views"		=> $row['PercFraudlentViews']
			);
	$infoDate 				.= "," . date('Y-m-d', strtotime($row['RefDate']));	
	$infoPercFraudviews 	.= "," . $row['PercFraudlentViews'] * 100;
	
}

// Start Debuging
	//	echo "<pre>";
	//	print_r($resulting_array);
	//	echo "</pre>";
// End Debuging

// For Highcharts
	// Raw Info 
		//echo "[Debug] - info date is: " . $infoDate . "<br>\n";
		//echo "[Debug] - % Fraud Views is: " . $infoPercFraudviews . "<br>\n";

	// Date
		$infoDate   	= substr($infoDate, 1); 		// Remove the first ','
		$arr_infoDate 	= explode(",", $infoDate); 		// Transform to an Array to later reverse the arrary
		$arr_infoDate 	= array_reverse($arr_infoDate); // Reverse the Array
		$infoDate 		= implode(",", $arr_infoDate);	// Implode back to an String to use in Highcharts.

	//% Fraudulent Views
		$infoPercFraudviews = substr($infoPercFraudviews, 1);
		$arr_1 = explode(",", $infoPercFraudviews);
		$arr_1 = array_reverse($arr_1);
		$infoPercFraudviews = implode(",", $arr_1);  


?>

<div id="wrap">
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
          	<?php include "../includes/main_menu.php"; ?>
          	<?php include "../includes/submenu_admin.php"; ?> 
          	<div class="clearfix"></div> 
        </div>
        <!--SIDEBAR END-->
        <div id="main" role="main">
          	<div class="block">
   		  		<div class="clearfix"></div>
            	<!--page title-->
             	<div class="pagetitle">
                	<h1>Fraudlent Pageviews</h1>
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<div class="clearfix"></div>

             	<h3> Fraudlent Views vs Total Views</h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($resulting_array[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($resulting_array);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($resulting_array as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo date("Y-m-d", strtotime($ivalue)); 
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
	                <input type='hidden' id="date-axis-X" value="<?php echo $infoDate; ?>">
	                <input type='hidden' id="tecks-axis-Y" value="<?php echo $infoPercFraudviews; ?>">
	                <div id="container_fraud" style="width:100%; height:300px;"></div>
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
	                          $('#container_fraud').highcharts({
	                                chart: { type: 'line' },
	                                title: { text: '% Fraudlent Views' },
	                                xAxis: { type : 'datetime' },
	                                yAxis: { title: { text: '% Fraudlent Views' } , 
	                                          type: 'linear'},
	                                series: [{
	                                    name: '% Fraudlent Views',
	                                    data: merge
	                                }]
	                            });
	                          });
	                </script>  
	              </div >
         	</div>
         </div>


</div>