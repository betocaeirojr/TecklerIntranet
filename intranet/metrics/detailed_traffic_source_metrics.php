<html>
<HEAD><Title>Metrics Reports from Google Analytics</TITlE></HEAD>
<BODY>
<h1>Metrics Gathered from Google Analytics</h1>	

<P>Information last update at:
<?php
date_default_timezone_set('America/Sao_Paulo');
echo date('Y-m-d H:i:s');

require ("gapi-1.3/gapi.class.php");

$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date', 'medium');
$metrics = array('pageviews','uniquePageviews', 'visits', 'visitors');
$sort = '-date';
$timeInterval = '90';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d');
$maxResults = 10000;


$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, $maxResults);

foreach($ga->getResults() as $key => $result){
	echo "<tr>\n";
	$repDate[$key] 					= $result->getDate();
	$repPageview[$key] 				= $result->getPageviews();	
	$repVisits[$key]				= $result->getVisits();
	$repVisitors[$key] 				= $result->getVisitors();
	$repMedium[$key]				= $result->getMedium();
	$numResults = $key;
}

$i=0;
$j=-1;
$date = "2013-01-01";

while ($i<=$numResults){ 
	if ($date != $repDate[$i]) { 
		// New Date 
			if ($j>0){
				// Visitors
				if (empty($visitors[$j-1]['Not Set'])) $visitors[$j-1]['Not Set'] = 0;
				if (empty($visitors[$j-1]['Direct'])) 	$visitors[$j-1]['Direct'] = 0;
				if (empty($visitors[$j-1]['Organic'])) $visitors[$j-1]['Organic'] = 0;
				if (empty($visitors[$j-1]['Referral'])) $visitors[$j-1]['Referral'] = 0;
				$visitors[$j-1]['Total'] = 
							$visitors[$j-1]['Not Set'] + $visitors[$j-1]['Direct'] + 
							$visitors[$j-1]['Organic'] + $visitors[$j-1]['Referral'];

				// Visits
				if (empty($visits[$j-1]['Not Set'])) 	$visits[$j-1]['Not Set'] = 0;
				if (empty($visits[$j-1]['Direct'])) 	$visits[$j-1]['Direct'] = 0;
				if (empty($visits[$j-1]['Organic'])) 	$visits[$j-1]['Organic'] = 0;
				if (empty($visits[$j-1]['Referral'])) 	$visits[$j-1]['Referral'] = 0;
				$visits[$j-1]['Total'] = 
							$visits[$j-1]['Not Set'] + $visits[$j-1]['Direct'] + 
							$visits[$j-1]['Organic'] + $visits[$j-1]['Referral'];

				// Pageviews
				if (empty($pageviews[$j-1]['Not Set'])) 	$pageviews[$j-1]['Not Set'] = 0;
				if (empty($pageviews[$j-1]['Direct'])) 		$pageviews[$j-1]['Direct'] = 0;
				if (empty($pageviews[$j-1]['Organic'])) 	$pageviews[$j-1]['Organic'] = 0;
				if (empty($pageviews[$j-1]['Referral'])) 	$pageviews[$j-1]['Referral'] = 0;
				$pageviews[$j-1]['Total'] = 
							$pageviews[$j-1]['Not Set'] + $pageviews[$j-1]['Direct'] + 
							$pageviews[$j-1]['Organic'] + $pageviews[$j-1]['Referral'];

			}
		$date = $repDate[$i];
		$j++;	
		$visitors[] = array(
				"Date" 		=> "",
				"Direct"	=> "",
				"Referral"	=> "",
				"Organic"	=> "",
				"Not Set"	=> "",
				"Total"		=> ""
							);
		$visits[] = array(
				"Date" 		=> "",
				"Direct"	=> "",
				"Referral"	=> "",
				"Organic"	=> "",
				"Not Set"	=> "",
				"Total"		=> ""
							);
		$pageviews[] = array(
				"Date" 		=> "",
				"Direct"	=> "",
				"Referral"	=> "",
				"Organic"	=> "",
				"Not Set"	=> "",
				"Total"		=> ""
							);

		$visitors[$j]['Date'] 	= $repDate[$i]; 
		$visits[$j]['Date'] 	= $repDate[$i]; 
		$pageviews[$j]['Date'] 	= $repDate[$i]; 
	}
	switch ($repMedium[$i]) {
		case '(not set)':
			$visitors[$j]['Not Set'] 	= $repVisitors[$i];
			$visits[$j]['Not Set'] 		= $repVisits[$i];
			$pageviews[$j]['Not Set'] 	= $repPageview[$i];
			break;		
		case '(none)':
			$visitors[$j]['Direct'] 	= $repVisitors[$i];
			$visits[$j]['Direct'] 		= $repVisits[$i];
			$pageviews[$j]['Direct'] 	= $repPageview[$i];
			break;
		case 'referral':
			$visitors[$j]['Referral'] 	= $repVisitors[$i]; 
			$visits[$j]['Referral']   	= $repVisits[$i]; 
			$pageviews[$j]['Referral'] 	= $repPageview[$i];
			break;				
		case 'organic':
			$visitors[$j]['Organic'] 	= $repVisitors[$i]; 
			$visits[$j]['Organic'] 		= $repVisits[$i]; 
			$pageviews[$j]['Organic'] 	= $repPageview[$i];
			break;
	}
	//echo "<PRE>";
	//print_r($visitors[$j]);
	//echo "</PRE>";
	$i++;
}

// Totaling the last item
$visitors[$j]['Total'] = 
		$visitors[$j]['Not Set'] + $visitors[$j]['Direct'] + 
		$visitors[$j]['Organic'] + $visitors[$j]['Referral'];

$visits[$j]['Total'] = 
		$visits[$j]['Not Set'] + $visits[$j]['Direct'] + 
		$visits[$j]['Organic'] + $visits[$j]['Referral'];

$pageviews[$j]['Total'] = 
		$pageviews[$j]['Not Set'] + $pageviews[$j]['Direct'] + 
		$pageviews[$j]['Organic'] + $pageviews[$j]['Referral'];

?>

 </P>

<h2> Visitors - Breakdown by Traffic Source</h2>
<table border=1>
	<tr>
		<?php
			$keys = array_keys($visitors[0]);
			foreach ($keys as $key => $value) {
				echo "<th align=center>";
				echo $value;
				echo "</th>";
			}
			reset($visitors);
		?>
	</tr>
	<?php 
		//arsort($visitors);
		foreach ($visitors as $ekey => $evalue) {
			echo "<tr>";		
			foreach ($evalue as $ikey => $ivalue) {
				echo "<TD align=center>";
				if ($ikey == 'Date') {
					echo date("Y-m-d", strtotime($ivalue)); 
				} else {
					echo $ivalue;
				}

				echo "</TD>";
			}
			echo "</tr>";
		}
	?>

</table>

<!-- -------------------------------------- -->
<h2> Visits - Breakdown by Traffic Source</h2>
<table border=1>
	<tr>
		<?php
			$keys = array_keys($visits[0]);
			foreach ($keys as $key => $value) {
				echo "<th align=center>";
				echo $value;
				echo "</th>";
			}
			reset($visits);
		?>
	</tr>
	<?php 
		//arsort($visitors);
		foreach ($visits as $ekey => $evalue) {
			echo "<tr>";		
			foreach ($evalue as $ikey => $ivalue) {
				echo "<TD align=center>";
				if ($ikey == 'Date') {
					echo date("Y-m-d", strtotime($ivalue)); 
				} else {
					echo $ivalue;
				}

				echo "</TD>";
			}
			echo "</tr>";
		}
	?>

</table>

<!-- -------------------------------------- -->
<h2> Pageviews - Breakdown by Traffic Source</h2>
<table border=1>
	<tr>
		<?php
			$keys = array_keys($pageviews[0]);
			foreach ($keys as $key => $value) {
				echo "<th align=center>";
				echo $value;
				echo "</th>";
			}
			reset($pageviews);
		?>
	</tr>
	<?php 
		//arsort($visitors);
		foreach ($pageviews as $ekey => $evalue) {
			echo "<tr>";		
			foreach ($evalue as $ikey => $ivalue) {
				echo "<TD align=center>";
				if ($ikey == 'Date') {
					echo date("Y-m-d", strtotime($ivalue)); 
				} else {
					echo $ivalue;
				}

				echo "</TD>";
			}
			echo "</tr>";
		}
	?>

</table>

</BODY>
</HTML>