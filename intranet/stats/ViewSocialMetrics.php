<?php

require "Social.php";

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
	$yesterday = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$MetricReferenceDate = date('Y-m-d',$yesterday);
}

$ResultReturnType = 'array';
	
// Fetch Metrics Data
$SocialMetric = new Social();

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



?>
<html>
	<head> 
		<title>Social (Shares, Likes, Follows) Key Performance Indicators</title>
	</head>

	<body>
		<h1> <center>Social (Shares, Likes, Follows) Key Performance Indicators </center></h1>
		<hr>
		<!-- Configuration Parameters -->
		<table border=1>
			<form method="post" action="ViewSocialMetrics.php">
				<tr valign=center>
					<!--td> Metric Type : <BR>
						<select name="MetricType">
							<option value="cons" <?//php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
							<option value="day"  <?//php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
						</select>
					</td-->
					<!--td> Aggregation Period: <BR>
						<select name="AggregationPeriod">
							<option value="all" <?//php if ($AggregationPeriod=='all') { echo "selected"; } else { echo "";}  ?>>Overall</option>
							<option value="year">Breakdown by Year</option>
							<option value="semester">Breakdown By Semester</option>
							<option value="quarter">Breakdown By Quarter</option>
							<option value="month" <?//php if ($AggregationPeriod=='month') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Month</option>
							<option value="week">Breakdown By Week</option>
							<option value="day" <?//php if ($AggregationPeriod=='day') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Day</option>
						</select>
					</td-->
					<td>
						&nbsp; Reference Date for Reporting <br>
						&nbsp; <input type="text" name="MetricReferenceDate" value="<?php if (!empty($MetricReferenceDate)) {echo $MetricReferenceDate; }?> "></input><br>
						&nbsp;<sup>In format 'YYYY-mm-dd'</sup>
					</td>
					<td><input type='submit' name="go" value="go"></td>
				</tr>
			</form>
		</table>


		<h2> Aggregated Statistics
		</h2>

		<!-- General Information -->
		<h3> General Statistics </h3>
		<table border=1>
			<!-- Following Info -->
			<tr valign=center>
				<th colspan=6 align=center> Number of People Following Someone </th> 
				<th colspan=6 align=center> Number of People Being Followed by Someone</th> 
			</tr>
			<tr>
				<td colspan=6 align=center> <?php echo $aFollowingCount; ?></td>
				<td colspan=6 align=center> <?php echo $aFollowedCount; ?> </td>
			</tr>
			<tr>
				<th colspan=3 align=center> Average Number of People Following</th>
				<th colspan=3 align=center> Weighted Average of Number People Following</th>
				<th colspan=3 align=center> Average Number of People Being Followed</th>
				<th colspan=3 align=center> Weighted Average Number of People Being Followed</th> 
			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $aAverageFollowing;?> </td>
				<td colspan=3 align=center> <?php echo $aWeightedAverageFollowing;?> </td>
				<td colspan=3 align=center> <?php echo $aAverageFollowed;?> </td>
				<td colspan=3 align=center> <?php echo $aWeightedAverageFollowed;?> </td> 
			</tr>
			<!-- Likes/Dislikes Info -->
			<tr>
				<th colspan=6 align=center> Number of Voted (Likes/Dislikes) Tecks</th>
				<th colspan=6 align=center> Number of Voted (Likes/Dislikes) Profiles</th>
			</tr>
			<tr>
				<td colspan=6 align=center> <?php echo $aVotedTecksCount; ?></td>
				<td colspan=6 align=center> <?php echo $aVotedProfilesCount; ?> </td>
			</tr>
				<tr>
				<th colspan=3 align=center> Average Number of Votes(likes/dislikes) per Teck</th>
				<th colspan=3 align=center> Wighted Average Number of Votes(likes/dislikes) per Teck</th>
				<th colspan=3 align=center> Average Number of Votes(likes/dislikes) per Profile</th>
				<th colspan=3 align=center> Weighted Average Number of Votes(likes/dislikes) per Profile</th>
			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $aAverageVotesPerTeck;				?> </td>
				<td colspan=3 align=center> <?php echo $aWeightedAverageVotesPerTeck; 		?> </td>
				<td colspan=3 align=center> <?php echo $aAverageVotesPerProfile; 			?> </td>
				<td colspan=3 align=center> <?php echo $aWeightedAverageVotesPerProfile; 	?> </td> 
			</tr>

			<!-- Shares Information-->
			<tr>
				<th colspan=3 align=center> Number of Shares (All Social Networks) </th> 
				<th colspan=3 align=center> Average Number of Shares Per Teck </th> 
				<th colspan=3 align=center> Weighted Number of Shates Per Teck </th> 
				<th colspan=3 align=center> Number of Tecks Without Shares</th> 
			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $aSharesCount; ?></td>
				<td colspan=3 align=center> <?php echo $aAverageSharesPerTeck; ?></td>
				<td colspan=3 align=center> <?php echo $aWeightedAverageSharesPerTeck; ?></td>
				<td colspan=3 align=center> <?php echo $aTecksWithoutSharesCount; ?> </td>
			</tr>
			<tr>
				<th colspan=12 align=center>Shares per Social Network</th>
			</tr>
			<tr>
				<th colspan=3 align=center>Facebook</th>
				<th colspan=3 align=center>Twitter</th>
				<th colspan=3 align=center>LinkedIn</th>
				<th colspan=3 align=center>Google+</th>
			</tr>
			<tr>
				<td colspan=3 align=center> 
					<?php 
						echo $aSharesPerSocialNetwork[0]['TOTAL_SHARES_FB'] . "<BR>\n";
						echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_FB']/$aSharesCount,4)*100) . "%)";
					?> 
				</td>
				<td colspan=3 align=center> 
					<?php 
						echo $aSharesPerSocialNetwork[0]['TOTAL_SHARES_TW'] . "<BR>\n"; 
						echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_TW']/$aSharesCount,4)*100) . "%)";
					?> 
				</td>
				<td colspan=3 align=center> 
					<?php 
						echo $aSharesPerSocialNetwork[0]['TOTAL_SHARES_LI'] . "<BR>\n"; 
						echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_LI']/$aSharesCount,4)*100) . "%)";
					?> 
				</td>
				<td colspan=3 align=center> 
					<?php 
						echo $aSharesPerSocialNetwork[0]['TOTAL_SHARES_GP'] . "<BR>\n";
						echo "(" . (round($aSharesPerSocialNetwork[0]['TOTAL_SHARES_GP']/$aSharesCount,4)*100) . "%)";
					?> 
				</td>
			</tr>
		</table>

		<hr>

		<!-- Raw Data -->
		<h3> Following Raw Data - Breakdown by Day - Aggregated Numbers </h3>
		<table border=1>
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
			<?php 
				foreach ($aFollowRawData as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";

					}
					echo "</tr>";
			}
			?>
		</table>

		<!-- Raw Data -->
		<h3> Votes (Likes/Dislikes) Raw Data - Breakdown by Day - Aggregated Numbers</h3>
		<table border=1>
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
			<?php 
				foreach ($aLikesRawData as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";

					}
					echo "</tr>";
			}
			?>
		</table>
		<!-- Raw Data -->
		<h3> Shares Raw Data - Breakdown by Day - Aggregated Numbers</h3>
		<table border=1>
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
			<?php 
				foreach ($aSharesRawData as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";

					}
					echo "</tr>";
			}
			?>
		</table>
		
		<!-- Raw Data -->
		<h3> Shares Per Social Network Raw Data - Breakdown by Day - Aggregated Numbers</h3>
		<table border=1>
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
			<?php 
				foreach ($aSharesPerSocialNetworkData as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "</tr>";
			}
			?>
		</table>


	</body>

</html>
