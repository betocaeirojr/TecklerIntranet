<?php
	include "../includes/header.php";

	require_once "Connection.php";
	require_once "Tecks.php";

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

	if (isset($_POST['MetricType'])){ $MetricType = $_POST['MetricType']; } else { $MetricType = 'cons'; }

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
	$TeckMetric = new Tecks($Connection);
	// Raw Data
	$aTecksRawData 					= $TeckMetric->getTecksRawData($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod ,  'desc');
	$aTecksPerLanguageData 			= $TeckMetric->getTecksPerLanguage($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod, 'desc');
	$aTecksPerTypeData 				= $TeckMetric->getTecksPerType($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod,'desc');

	// General Information
	$aTecksCount	 				= $TeckMetric->getTecksCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aPublishedTecksCount			= $TeckMetric->getPublishedTecksCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aRatioTecksPubTecks			= $TeckMetric->getRatioTecksByPublishedTecks($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aAverageTecksPerProfile		= $TeckMetric->getAverageTecksPerProfile($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aAverageTecksPerActiveProfile	= $TeckMetric->getAverageTecksPerActiveProfile($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aProfilesWOTecksCount			= $TeckMetric->getProfilesWithoutTeck($ResultReturnType, $MetricType, $MetricReferenceDate);

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
                <h1>Tecks Key Performance Indicators</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
         

             <div class="container_config_param radius">
	             <!-- Configuration Parameters -->
					<table class="select_period">
						<form method="post" action="stats_ViewTecksMetrics.php">
							<tr valign=center>
								<td> Metric Type : <BR>
									<select class="chzn-select chosen_select" name="MetricType">
										<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
										<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
									</select>
								</td>
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
				


				<h2> 
					<?php 
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
							<th class="head_table_gray" colspan=4 align=center> Number of Tecks </th> 
							<th class="head_table_gray" colspan=4 align=center> Number of Published Tecks <sup>2</sup></th> 
							<th class="head_table_gray" colspan=4 align=center> Number of Profiles Without Tecks</th>
						</tr>
						<tr>
							<td colspan=4 align=center> <?php echo number_format($aTecksCount, 0, ".", ",") ; ?> </td> 
							<td colspan=4 align=center> <?php echo number_format($aPublishedTecksCount, 0, ".", ",") ;?></td> 
							<td colspan=4 align=center> <?php echo number_format($aProfilesWOTecksCount, 0, ".", ",") ;?> </td>
						</tr>

						<tr>
							<th class="head_table_gray" colspan=4 align=center> Average Number of Tecks Per Profile</th>
							<th class="head_table_gray" colspan=4 align=center> Average Number of Tecks Per Active Profile<sup>1</sup></th>
							<th class="head_table_gray" colspan=4 align=center> Ratio - Published Tecks / Tecks </th>
						</tr>
						<tr>
							<td colspan=4 align=center> <?php echo number_format($aAverageTecksPerProfile, 4, ".", ",")  ;?> </td>
							<td colspan=4 align=center> <?php echo number_format($aAverageTecksPerActiveProfile, 4, ".", ",")  ;?> </td>
							<td colspan=4 align=center> <?php echo number_format($aRatioTecksPubTecks, 4, ".", ",")  ;?> </td>
						</tr>		                
	              </table>	                
	              <div class="clearfix"></div>
	              <p>1. Active Profiles = Profiles With at Least 1 published teck.<br>
		   2. Published Tecks = Tecks actually published (disconsidering Drafts or Fraud Tecks).<br>
	              </div>
	              </div>
	              <!--Bordered Table END-->

				<!-- Pageviews Raw Data -->
				<h3> Tecks Per Language</h3>
				
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<th>Date </th>
							<th>ar</th><th>de</th><th>en</th><th>es</th>
							<th>fr</th><th>he</th><th>hi</th><th>it</th>
							<th>jp</th><th>ko</th><th>pt</th><th>ru</th>
							<th>zh</th>
						</tr>
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aTecksPerLanguageData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue,0,".",","): $ivalue); 
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


             



             	<!-- Pageviews Raw Data -->
				<h3> Tecks Per Type</h3>
				
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aTecksPerTypeData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
								reset($aTecksPerTypeData);
							?>
							</tr>
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aTecksPerTypeData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 0, ".", ",") : $ivalue ); 
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


	              <!-- Pageviews Raw Data -->
				<h3> Tecks Raw Data - Breakdown by Day</h3>
				
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aTecksRawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($aTecksRawData);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aTecksRawData as $ekey => $evalue) {
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


