<?php
	include "../includes/header.php";

	require_once "Connection.php";
	require_once "Audience.php";
	
	

	/* echo "<PRE>"; print_r($_POST); echo "</PRE>"; */

	// Initialize Key Variables, checking FORM POST
	if (isset($_POST['AggregationPeriod'])) {
		if ( 	($_POST['AggregationPeriod']== 'day') OR 
				($_POST['AggregationPeriod']== 'week' ) OR  
				($_POST['AggregationPeriod']== 'all')
			) {
			$GroupingPeriod = 'day';
		} else {
			$GroupingPeriod = 'month';
		} 	
		$AggregationPeriod = $_POST['AggregationPeriod'];
	} else {
		$AggregationPeriod = 'all'; 
		$GroupingPeriod = 'day';
	}

	if (isset($_POST['MetricType'])){ 
		$MetricType = $_POST['MetricType'];
	} else { 
		$MetricType = 'cons'; 
	}

	if (isset($_POST['MetricReferenceDate'])){
		$MetricReferenceDate = $_POST['MetricReferenceDate'];
		
		// Check to see if $MetricReferenceDate is in the Future
		$givenDate = strtotime($_POST['MetricReferenceDate']);
		$todayDate = strtotime(date('Y-m-d'));

		if ($todayDate <= ($givenDate - 86400)) {
			$yesterday = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
			$MetricReferenceDate = date('Y-m-d',$yesterday);
		}
	} else {
		$yesterday = mktime(0, 0, 0, date("m")  , date("d")-2, date("Y"));
		$MetricReferenceDate = date('Y-m-d',$yesterday);
	}

	$ResultReturnType = 'array';
		

	$Connection = new Connection();

	// Fetch Metrics Data
	$AudienceMetric = new Audience($Connection);

	//Raw Information
	$PageviewsAging_RawInformation 		= 	$AudienceMetric->getPageviewsAging($ResultReturnType, $MetricReferenceDate);
	$PageviewsDecaiRate_RawInformation 	= 	$AudienceMetric->getAgingDecaiRate($ResultReturnType, $MetricReferenceDate);

	

?>

    <div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "../includes/main_menu.php"; ?>
          	<?php include "../includes/submenu_stats.php"; ?>
          <div class="clearfix"></div>
        </div>
        <!--SIDEBAR END-->
    
    	
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
          <div class="block">
   		  <div class="clearfix"></div>
            
             <!--page title-->
             <div class="pagetitle">
                <h1>Pageviews Aging and Decai Rate Indicators </h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
         

             <div class="container_config_param radius">
	             <!-- Configuration Parameters -->
					<table class="select_period">
						<form method="post" action="stats_ViewPageviewsAgingMetrics.php">
							<tr valign=center>
								<!--td> Metric Type : <BR>
									<select name="MetricType">
										<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
										<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
									</select>
								</td-->
								<td> Aggregation Period: <BR>
									<select class="chzn-select chosen_select" name="AggregationPeriod">
										<option value="all" <?php if ($AggregationPeriod=='all') { echo "selected"; } else { echo "";}  ?>>Overall</option>
										<!--option value="year">Breakdown by Year</option-->
										<!--option value="semester">Breakdown By Semester</option-->
										<!--option value="quarter">Breakdown By Quarter</option-->
										<option value="month" <?php if ($AggregationPeriod=='month') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Month</option>
										<!--option value="week">Breakdown By Week</option-->
										<option value="day" <?php if ($AggregationPeriod=='day') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Day</option>
									</select>
								</td>
								<td>
									&nbsp; Reference Date for Reporting <br>
									&nbsp; <input type="text" name="MetricReferenceDate" value="<?php if (!empty($MetricReferenceDate)) {echo $MetricReferenceDate; }?> "></input><br>
									&nbsp;<span class="format">In format 'YYYY-mm-dd'</span>
								</td>
								<td>
									<div class="container_button">
										<input class="button_submit_form green" type='submit' name="go" value="go">
									</div>
								</td>
							</tr>
						</form>
					</table>
				</div>


				<div class="clearfix"></div>
				<h2 class="clearfix"> Aggregated Statistics </h2>

			<!-- General Information -->
			<h3> Accumulated Information - Aging Pageviews in Tecks</h3>
				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
	                <thead>
	                  <tr>
	                    <?php
								$keys = array_keys($PageviewsAging_RawInformation[0]);
								foreach ($keys as $key => $value) {
									echo "<th class=\"head_table_gray\" align=center>";
									echo $value;
									echo "</th>";
								}
							reset($PageviewsAging_RawInformation);
							?>
	                  </tr>
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($PageviewsAging_RawInformation as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 0, ".", ",") : $ivalue);  
									echo "</TD>";

								}
								echo "</tr>";
						}
						?>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->			


			<h3> Accumulated Information - Pageviews Decai Rate in Tecks</h3>
				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
	                <thead>
	                  <tr>
	                    <?php
								$keys = array_keys($PageviewsDecaiRate_RawInformation[0]);
								foreach ($keys as $key => $value) {
									echo "<th class=\"head_table_gray\" align=center>";
									echo $value;
									echo "</th>";
								}
							reset($PageviewsDecaiRate_RawInformation);
							?>
	                  </tr>
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($PageviewsDecaiRate_RawInformation as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 4, ".", ",") : $ivalue); 
									echo "</TD>";

								}
								echo "</tr>";
						}
						?>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->			


              
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->

    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>


