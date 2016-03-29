<?php
	include "../includes/header.php";

	require_once "Connection.php";
	require_once "Users.php";

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
	$UserMetric = new Users($Connection);
	$aUsersRawData 					= $UserMetric->getUsersRawData($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod ,  'desc');
	$aUsersCount 					= $UserMetric->getUsersCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aProfilesCount 				= $UserMetric->getProfilesCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aActiveProfilesCount 			= $UserMetric->getActiveProfilesCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aAvgProfilesPerUser 			= $UserMetric->getAverageProfilesPerUser($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aAvgActiveProfilesPerUser 		= $UserMetric->getAverageActiveProfilesPerUser($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aUsersWithOnly1ProfileCount 	= $UserMetric->getUsersWithOnly1Profile($ResultReturnType, $MetricReferenceDate);
	$aWavgProfilesPerUser 			= $UserMetric->getWeightedAverageProfilesPerUser($ResultReturnType, $MetricReferenceDate);
	$aUsersPerLanguageData 			= $UserMetric->getUsersPerLanguage($ResultReturnType, $MetricType, $MetricReferenceDate);
	$aEngagedProfilesCount	 		= $UserMetric->getEngagedProfiles($ResultReturnType, $MetricReferenceDate);
	$aLoggedUsersCount 				= $UserMetric->getLoggedUsers($ResultReturnType, $MetricReferenceDate);
	$aUsersWithKeepMeLoggedOnCount = $UserMetric->getUsersWithAutoLogin($ResultReturnType, $MetricReferenceDate);

	// Preparing the info about Users Per Language
	foreach ($aUsersPerLanguageData  as $key => $value) {
		foreach ($value as $ikey => $ivalue) {
			if ($ikey == 'LANG') {
				$LangCode[] = $ivalue;
			} 
			if ( $ikey == 'TOTAL_USERS_LANG' || $ikey == 'DELTA_USERS_LANG_DAY' ) {
				$NumUsersLang[] = $ivalue;
			}
		}
	}

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
                <h1>Users and Profiles Key Performance Indicators</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
         

             <div class="container_config_param radius">
	             <!-- Configuration Parameters -->
					<table class="select_period">
						<form method="post" action="stats_ViewUsersMetrics.php">
							<tr valign=center>
								<td> Metric Type : <BR>
									<select class="chzn-select" name="MetricType">
										<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
										<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
									</select>
								</td>
								<td> Aggregation Period: <BR>
									<select class="chzn-select" name="AggregationPeriod">
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
							<th class="head_table_gray" colspan=6 align=center> Number of Users </th> 
							<th class="head_table_gray" colspan=6 align=center> Number of Profiles </th>
						</tr>
						<tr>
							<td colspan=6 align=center> <?php echo number_format($aUsersCount, 0, ".", ","); ?> </td> 
							<td colspan=6 align=center> <?php echo number_format($aProfilesCount, 0, ".", ",") ;?></td>
						</tr>
						<tr>
							<th class="head_table_gray" colspan=3 align=center> Number of Active Profiles<sup>1</sup></th> 
							<th class="head_table_gray" colspan=3 align=center> Number of Engaged Users <sup>2</sup></th>
							<th class="head_table_gray" colspan=3 align=center> Number of Unique Logged Users<sup>3</sup></th> 
							<th class="head_table_gray" colspan=3 align=center> Number of Users with only 1 Profile</th>
						</tr>
						<tr>
							<td colspan=3 align=center> <?php echo number_format($aActiveProfilesCount, 0, ".", ",") ;?> </td> 
							<td colspan=3 align=center> <?php echo number_format($aEngagedProfilesCount, 0, ".", ",") ;?> </td>
							<td colspan=3 align=center> <?php echo number_format($aLoggedUsersCount, 0, ".", ",");?> </td> 
							<td colspan=3 align=center> <?php echo number_format($aUsersWithOnly1ProfileCount, 0, ".", ",");?> </td>
						</tr>
						<tr>
							<th class="head_table_gray" colspan=4 align=center> Average Number of Profiles Per User</th>
							<th class="head_table_gray" colspan=4 align=center> Average Number of Active Profiles Per User</th>
							<th class="head_table_gray" colspan=4 align=center> Weighted Average Number of Profiles Per User</th>
						</tr>
						<tr>
							<td colspan=4 align=center> <?php echo number_format($aAvgProfilesPerUser, 4, ".", ",") ;?> </td>
							<td colspan=4 align=center> <?php echo number_format($aAvgActiveProfilesPerUser, 4, ".", ",") ;?> </td>
							<td colspan=4 align=center> <?php echo number_format($aWavgProfilesPerUser, 4, ".", ",") ;?> </td>
						</tr>	                
	              </table>	                
	              <div class="clearfix"></div>
	              <p>1. Active Profiles = Profiles With at Least 1 published teck.<br>
				   2. Engaged Profiles = Profiles with published tecks int the previous 90 days.<br>
				   3. Logged Users = Number of Users that logged YESTERDAY</p>
	              </div>
	              </div>
	              <!--Bordered Table END-->



				<!-- Pageviews Raw Data -->
				<h3> Users Per Language</h3>
				
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php foreach ($LangCode as $value) { ?>	
								<th width=3 align=center> <?php echo $value; ?>	 </th>		
							<?php } ?>
						</tr>
	                </thead>
	                <tbody>
	                  <tr>
						<?php foreach ($NumUsersLang as $value) { ?>	
						<td align=center> <?php echo number_format($value, 0, ".", ","); ?>	 </td>		
					<?php } ?>
					</tr>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Striped table END-->


             



             	<!-- Pageviews Raw Data -->
				<h3> User Raw Data - Breakdown by Day</h3>
				<input type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Export to Excel">
				
				
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered" id="testTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" >
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aUsersRawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($aUsersRawData);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aUsersRawData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 4, ".", ","): $ivalue); 
									echo "</TD>";

								}
								echo "</tr>";
						}
						?>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              <p> 1. Weighted Average of Profiles Per Users: Considering ONLY users with more than 1 profile. </p>
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


