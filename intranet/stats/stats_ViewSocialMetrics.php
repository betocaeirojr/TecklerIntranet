<?php
	include "../includes/header.php";

	require_once "Connection.php";
	require_once "Social.php";
	require_once "Users.php";
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
	$SocialMetric = new Social($Connection);

	// Raw Data
	$aFollowRawData 					= $SocialMetric->getFollowRawData($ResultReturnType, $MetricReferenceDate, 'desc');
	$aLikesRawData 						= $SocialMetric->getVotesRawData($ResultReturnType, $MetricReferenceDate, 'desc');
	$aSharesRawData 					= $SocialMetric->getSharesRawData($ResultReturnType, $MetricReferenceDate, 'desc');
	$aSharesPerSocialNetworkData 		= $SocialMetric->getSharesPerSocialNetworkRawData($ResultReturnType, $MetricReferenceDate, 'desc');

	// General Information - Total Count
	$aFollowingCount 			= $SocialMetric->getFollowingCount($ResultReturnType, $MetricReferenceDate);
	$aFollowedCount 			= $SocialMetric->getFollowedCount($ResultReturnType, $MetricReferenceDate);
	$aVotedTecksCount 			= $SocialMetric->getVotedTecksCount($ResultReturnType, $MetricReferenceDate);
	$aVotedProfilesCount 		= $SocialMetric->getVotedProfilesCount($ResultReturnType, $MetricReferenceDate);
	$aSharesCount 				= $SocialMetric->getSharesCount($ResultReturnType, $MetricReferenceDate);
	$aTecksWithoutSharesCount 	= $SocialMetric->getTecksWithoutSharesCount($ResultReturnType, $MetricReferenceDate);
	$aSharesPerSocialNetwork 	= $SocialMetric->getSharesPerSocialNetworkCount($ResultReturnType, $MetricReferenceDate);

	// General Information - Average Info
	$aAverageFollowing 					= $SocialMetric->getAverageFollowingCount($ResultReturnType, $MetricReferenceDate);
	$aAverageFollowed 					= $SocialMetric->getAverageFollowedCount($ResultReturnType, $MetricReferenceDate);
	$aWeightedAverageFollowing 			= $SocialMetric->getWeightedAverageFollowingCount($ResultReturnType, $MetricReferenceDate);
	$aWeightedAverageFollowed 			= $SocialMetric->getWeightedAverageFollowedCount($ResultReturnType, $MetricReferenceDate);
	$aAverageVotesPerTeck 				= $SocialMetric->getAverageVotesPerTeckCount($ResultReturnType, $MetricReferenceDate);
	$aAverageVotesPerProfile 			= $SocialMetric->getAverageVotesPerProfileCount($ResultReturnType, $MetricReferenceDate);
	$aWeightedAverageVotesPerTeck 		= $SocialMetric->getWeightedAverageVotesPerTeckCount($ResultReturnType, $MetricReferenceDate);
	$aWeightedAverageVotesPerProfile 	= $SocialMetric->getWeightedAverageVotesPerProfileCount($ResultReturnType, $MetricReferenceDate);
	$aAverageSharesPerTeck 				= $SocialMetric->getAverageSharesPerTeckCount($ResultReturnType, $MetricReferenceDate);
	$aWeightedAverageSharesPerTeck 		= $SocialMetric->getWeightedAverageSharesPerTeckCount($ResultReturnType, $MetricReferenceDate);


	// Info for derived metrics
	// User and Profile Metric
	$UserMetric = new Users($Connection);
	$UsersRawData 						= $UserMetric->getUsersRawData($ResultReturnType, 'cons', $MetricReferenceDate, 'day' ,  'desc');

	// Teck Metrics
	$TeckMetric = new Tecks($Connection);
	//$TecksCount 						= $TeckMetric->getTecksCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$TeckRawData 						= $TeckMetric->getTecksRawData($ResultReturnType, 'cons', $MetricReferenceDate, 'day' ,  'desc');


	// Combining the Arrays for derived metrics
	$ResultingArray = array();
	foreach ($aSharesRawData as $Sharekey => $Sharevalue) {
		$ResultingArray[$Sharekey] = array_merge($Sharevalue, 
												$aLikesRawData[$Sharekey], 
												$aFollowRawData[$Sharekey], 
												$UsersRawData[$Sharekey], 
												$TeckRawData[$Sharekey] );

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
                <h1>Social (Shares, Likes, Follows) Key Performance Indicators</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
         

             <div class="container_config_param radius">
	             <!-- Configuration Parameters -->
					<table class="select_period">
						<form method="post" action="stats_ViewSocialMetrics.php">
							<tr valign=center>
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
				


				<h2>Aggregated Statistics</h2>

				

				<!-- General Information -->
				<h3 class="clearfix"> General Statistics </h3>

				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content overflow">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
		                <!-- Following Info -->
						<tr valign=center>
							<th class="head_table_gray" colspan=6 align=center> Number of People Following Someone </th> 
							<th class="head_table_gray" colspan=6 align=center> Number of People Being Followed by Someone</th> 
						</tr>
						<tr>
							<td colspan=6 align=center> <?php echo number_format($aFollowingCount, 0, ".", ","); ?></td>
							<td colspan=6 align=center> <?php echo number_format($aFollowedCount, 0, ".", ","); ?> </td>
						</tr>
						<tr>
							<th class="head_table_gray" colspan=3 align=center> Average Number of People Following</th>
							<th class="head_table_gray" colspan=3 align=center> Weighted Average of Number People Following</th>
							<th class="head_table_gray" colspan=3 align=center> Average Number of People Being Followed</th>
							<th class="head_table_gray" colspan=3 align=center> Weighted Average Number of People Being Followed</th> 
						</tr>
						<tr>
							<td colspan=3 align=center> <?php echo number_format($aAverageFollowing , 4, ".", ",");?> </td>
							<td colspan=3 align=center> <?php echo number_format($aWeightedAverageFollowing , 4, ".", ",");?> </td>
							<td colspan=3 align=center> <?php echo number_format($aAverageFollowed , 4, ".", ",");?> </td>
							<td colspan=3 align=center> <?php echo number_format($aWeightedAverageFollowed , 4, ".", ",");?> </td> 
						</tr>
						<!-- Likes/Dislikes Info -->
						<tr>
							<th class="head_table_gray" colspan=6 align=center> Number of Voted (Likes/Dislikes) Tecks</th>
							<th class="head_table_gray" colspan=6 align=center> Number of Voted (Likes/Dislikes) Profiles</th>
						</tr>
						<tr>
							<td colspan=6 align=center> <?php echo number_format($aVotedTecksCount,0,".",","); ?></td>
							<td colspan=6 align=center> <?php echo number_format($aVotedProfilesCount,0, ".", ","); ?> </td>
						</tr>
							<tr>
							<th class="head_table_gray" colspan=3 align=center> Average Number of Votes(likes/dislikes) per Teck</th>
							<th class="head_table_gray" colspan=3 align=center> Wighted Average Number of Votes(likes/dislikes) per Teck</th>
							<th class="head_table_gray" colspan=3 align=center> Average Number of Votes(likes/dislikes) per Profile</th>
							<th class="head_table_gray" colspan=3 align=center> Weighted Average Number of Votes(likes/dislikes) per Profile</th>
						</tr>
						<tr>
							<td colspan=3 align=center> <?php echo number_format($aAverageVotesPerTeck, 4, ".", ",");				?> </td>
							<td colspan=3 align=center> <?php echo number_format($aWeightedAverageVotesPerTeck, 4, ".", ","); 		?> </td>
							<td colspan=3 align=center> <?php echo number_format($aAverageVotesPerProfile, 4, ".", ","); 			?> </td>
							<td colspan=3 align=center> <?php echo number_format($aWeightedAverageVotesPerProfile, 4, ".", ","); 	?> </td> 
						</tr>

						<!-- Shares Information-->
						<tr>
							<th class="head_table_gray" colspan=3 align=center> Number of Shares (All Social Networks) </th> 
							<th class="head_table_gray" colspan=3 align=center> Average Number of Shares Per Teck </th> 
							<th class="head_table_gray" colspan=3 align=center> Weighted Number of Shates Per Teck </th> 
							<th class="head_table_gray" colspan=3 align=center> Number of Tecks Without Shares</th> 
						</tr>
						<tr>
							<td colspan=3 align=center> <?php echo number_format($aSharesCount, 0, ".", ","); ?></td>
							<td colspan=3 align=center> <?php echo number_format($aAverageSharesPerTeck , 4, ".", ","); ?></td>
							<td colspan=3 align=center> <?php echo number_format($aWeightedAverageSharesPerTeck , 4, ".", ","); ?></td>
							<td colspan=3 align=center> <?php echo number_format($aTecksWithoutSharesCount, 0, ".", ","); ?> </td>
						</tr>
						<tr>
							<th class="head_table_gray" colspan=12 align=center>Shares per Social Network</th>
						</tr>
						<tr>
							<th class="head_table_gray" colspan=3 align=center>Facebook</th>
							<th class="head_table_gray" colspan=3 align=center>Twitter</th>
							<th class="head_table_gray" colspan=3 align=center>LinkedIn</th>
							<th class="head_table_gray" colspan=3 align=center>Google+</th>
						</tr>
						<tr>
							<td colspan=3 align=center> 
								<?php 
									echo number_format($aSharesPerSocialNetwork[0]['TOTAL_SHARES_FB'],0, ".", ",") . "<BR>\n";
									echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_FB']/$aSharesCount,4)*100) . "%)";
								?> 
							</td>
							<td colspan=3 align=center> 
								<?php 
									echo number_format($aSharesPerSocialNetwork[0]['TOTAL_SHARES_TW'], 0, ".", ",") . "<BR>\n"; 
									echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_TW']/$aSharesCount,4)*100) . "%)";
								?> 
							</td>
							<td colspan=3 align=center> 
								<?php 
									echo number_format($aSharesPerSocialNetwork[0]['TOTAL_SHARES_LI'], 0, ".", ",") . "<BR>\n"; 
									echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_LI']/$aSharesCount,4)*100) . "%)";
								?> 
							</td>
							<td colspan=3 align=center> 
								<?php 
									echo number_format($aSharesPerSocialNetwork[0]['TOTAL_SHARES_GP'],0, ".", "," ). "<BR>\n";
									echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_GP']/$aSharesCount,4)*100) . "%)";
								?> 
							</td>
						</tr>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->




				<!-- Pageviews Raw Data -->
				<h3> Following Raw Data - Breakdown by Day - Aggregated Numbers</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aFollowRawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($aFollowRawData);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aFollowRawData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 4, ".", ",") : $ivalue) ; 
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
				<h3> Votes (Likes/Dislikes) Raw Data - Breakdown by Day - Aggregated Numbers</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aLikesRawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($aLikesRawData);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aLikesRawData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue,4,".", ",") : $ivalue ); 
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
				<h3> Shares Raw Data - Breakdown by Day - Aggregated Numbers</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aSharesRawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($aSharesRawData);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aSharesRawData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 4, ".", ",") : $ivalue ); 
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
				<h3> Shares Per Social Network Raw Data - Breakdown by Day - Aggregated Numbers</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($aSharesPerSocialNetworkData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
								reset($aSharesPerSocialNetworkData);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($aSharesPerSocialNetworkData as $ekey => $evalue) {
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
	              <!--Striped table END-->


	              <h3> Consolidated Numbers -- Mixing All Social Interactions</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($ResultingArray[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
								reset($ResultingArray);
							?>

						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
							foreach ($ResultingArray as $ekey => $evalue) {
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


