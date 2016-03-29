<?php
	include "../includes/header.php";

	require_once "Connection.php";
	require_once "Revenue.php";

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

	// $MetricReferenceDate = date('2013-08-05');

	$ResultReturnType = 'array';

	$Connection = new Connection();
		
	// Fetch Metrics Data
	$RevenueMetric = new Revenue($Connection);
	// Raw Data
	$aRevenueRawDataInfo 					= $RevenueMetric->getRevenueRawData($ResultReturnType, $MetricType, $MetricReferenceDate,'desc');

	// Revenue Breakdown ( Expected / Actual)
	$aExpectedRevenueAmmount 			= $RevenueMetric->getExpectedRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aActualRevenueAmmount 				= $RevenueMetric->getActualRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);

	// Revenue Breakdown By Type - Payment Lifecycle
	$aPendingRevenueAmmount 			= $RevenueMetric->getPendingRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aVerifiedRevenueAmmount 			= $RevenueMetric->getVerifiedRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aRequestedRevenueAmmount 			= $RevenueMetric->getRequestedRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aWithdrawnRevenueAmmount 			= $RevenueMetric->getWithdrawnRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aErrorRevenueAmmount 				= $RevenueMetric->getErrorRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);

	// Average Revenue
	$aAverageRevenuePerProfile 			= $RevenueMetric->getAverageRevenue($ResultReturnType, $MetricReferenceDate, 'profile');
	$aAverageRevenuePerTeck 			= $RevenueMetric->getAverageRevenue($ResultReturnType, $MetricReferenceDate, 'teck');
	$aWeightedAverageRevenuePerProfile 	= $RevenueMetric->getWeightedAverageRevenue($ResultReturnType, $MetricReferenceDate, 'profile');
	$aWeightedAverageRevenuePerTeck 	= $RevenueMetric->getWeightedAverageRevenue($ResultReturnType, $MetricReferenceDate, 'teck');

	$aTransferRequestedDayCount			= $RevenueMetric->getNumberOfTransferRequested($ResultReturnType, $MetricReferenceDate);

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
                <h1>Revenue Key Performance Indicators</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
         

             <div class="container_config_param radius">
	             <!-- Configuration Parameters -->
					<table class="select_period">
						<form method="post" action="stats_ViewRevenueMetrics.php">
							<tr valign=center>
								<!--td> Metric Type : <BR>
									<select name="MetricType">
										<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
										<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
									</select>
								</td-->
								<td> Aggregation Period: <BR>
									<select class="chzn-select chosen_select" name="AggregationPeriod">
										<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
										<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
									</select>
								</td>
								<td>
									&nbsp; Reference Date for Reporting <br>
									&nbsp; <input type="text" name="MetricReferenceDate" value="<?php if (!empty($MetricReferenceDate)) {echo $MetricReferenceDate; }?> "></input><br>
									&nbsp;<sup>In format 'YYYY-mm-dd'</sup>
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
				


				<h2> <?php 
					if ($MetricType=='cons') { 
						echo "Aggregate Statistics";
					} elseif ($MetricType=='day') {  
						echo "Daily Statistics";
					} 
					?>
				</h2>

				

				<!-- General Information -->
				<h3 class="clearfix"> General Statistics</h3>

				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content overflow">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
		                <tr valign=center>
							<th class="head_table_gray" colspan=10 align=center> Expected Revenue </th> 
							<th class="head_table_gray" colspan=10 align=center> Actual Revenue <sup>1</sup></th> 
						</tr>
						<tr>
							<td colspan=10 align=center> <?php echo number_format($aExpectedRevenueAmmount, 2, ".", ","); ?> </td> 
							<td colspan=10 align=center> <?php echo number_format($aActualRevenueAmmount, 2, ".", ",");?></td> 
						</tr>

						<!-- Revenue by Payment LifeCycle-->
						<tr>
							<th class="head_table_gray" colspan=4 align=center> Pending Revenue (US$)				</th>
							<th class="head_table_gray" colspan=4 align=center> Verified Revenue (US$)	<sup>2</sup></th>
							<th class="head_table_gray" colspan=4 align=center> Requested Revenue (US$)	<sup>2</sup></th>
							<th class="head_table_gray" colspan=4 align=center> Withdrawn Revenue (US$)	<sup>2</sup></th>
							<th class="head_table_gray" colspan=4 align=center> Error Revenue (US$)		<sup>2</sup></th>
						</tr>
						<tr>
							<td colspan=4 align=center> <?php echo number_format($aPendingRevenueAmmount, 2, ".", ",") ;?> 		</td>
							<td colspan=4 align=center> <?php echo number_format($aVerifiedRevenueAmmount, 2, ".", ",") ;?>  	</td>
							<td colspan=4 align=center> <?php echo number_format($aRequestedRevenueAmmount, 2, ".", ",") ;?> 	</td>
							<td colspan=4 align=center> <?php echo number_format($aWithdrawnRevenueAmmount, 2, ".", ",") ;?> 	</td>
							<td colspan=4 align=center> <?php echo number_format($aErrorRevenueAmmount, 2, ".", ",") ;?>  		</td>
						</tr>
						<!-- Average and Weighted Average Per Teck and Per Profile-->
						<tr>
							<th class="sup_head_table_gray" colspan=10 align=center> Tecks</th>
							<th class="sup_head_table_gray" colspan=10 align=center> Profiles</th>
						</tr>
						<tr>
							<th class="head_table_gray" colspan=5 align=center> Average Revenue Per Teck</th>
							<th class="head_table_gray" colspan=5 align=center> Weighted Average Revenue Per Viewed Teck</th>
							<th class="head_table_gray" colspan=5 align=center> Average Revenue per Profile</th>
							<th class="head_table_gray" colspan=5 align=center> Weighted Average Revenue Per Viewed Profile</th>
						</tr>
						<tr>
							<td colspan=5 align=center> <?php echo number_format($aAverageRevenuePerTeck, 6, ".", ",") ;?> 			</td>
							<td colspan=5 align=center> <?php echo number_format($aWeightedAverageRevenuePerTeck, 6, ".", ",") ;?>  </td>
							<td colspan=5 align=center> <?php echo number_format($aAverageRevenuePerProfile, 6, ".", ",") ;?> 		</td>
							<td colspan=5 align=center> <?php echo number_format($aWeightedAverageRevenuePerProfile, 6, ".", ",") ;?> 	</td>
						</tr>
	              </table>	                
	              <div class="clearfix"></div>
	              <p>1. Actual Revenue = Revenue already verified by Google, after a 32 days period.<br>
			   2. Actual Revenue = Verified + Requested + Withdrawn + Error<br>
	              </div>
	              </div>
	              <!--Bordered Table END-->

	              



	              <!-- Actual vs Expected Revenue-->
	              <div class="grid grid_table">
	              <div class="grid-content overflow">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
		                <tr>
							<th class="head_table_gray" colspan=20 align=center> Total Number of Fund Transfers Requested </th>
						</tr>
						<tr>
							<td colspan=20 align=center> <?php echo number_format($aTransferRequestedDayCount, 0, ".", ",") ;?> </td>
						</tr>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->

			






				<!-- Pageviews Raw Data -->
				<h3> Tecks Raw Data - Breakdown by Day</h3>
				
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aRevenueRawDataInfo[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($aRevenueRawDataInfo);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aRevenueRawDataInfo as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 2, ".", ","): $ivalue); 
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
	              <!--Striped table END-->


             
             
               
               <?php include "../includes/footer.php"; ?>
              
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->


    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>


