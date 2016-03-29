<html>
<HEAD><Title>Metrics Reports from Google Analytics</TITlE></HEAD>
<BODY>
<h1>Metrics Gathered from Google Analytics</h1>	

<P>Information last update at:
<?php
date_default_timezone_set('America/Sao_Paulo');
echo date('Y-m-d H:i:s');

?>
 </P>

<table border=1>
	<tr>
		<th>Date</th>
		<th>Source Medium</th>
		<th>Visitors</th>
		<th>Visits</th>
		<th>Pageviews</th>
	</tr>

<?php

require ("gapi-1.3/gapi.class.php");

$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date', 'medium');
$metrics = array('pageviews','uniquePageviews', 'visits', 'visitors');
$sort = '-date';
$timeInterval = '60';
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


	echo "<td>" . date("Y-m-d",strtotime($repDate[$key])) 	. "</td>\n";
	echo "<td>" . $repMedium[$key]					. "</td>\n";
	echo "<td>" . $repVisitors[$key]						. "</td>\n";
	echo "<td>"	. $repVisits[$key]	 						. "</td>\n";
	echo "<td>" . $repPageview[$key] 						. "</td>\n";
	echo "</tr>\n";
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
				if (!isset($visitors[$j-1]['Not Set'])) $visitors[$j-1]['Not Set'] = 0;
				if (!isset($visitors[$j-1]['Direct'])) 	$visitors[$j-1]['Direct'] = 0;
				if (!isset($visitors[$j-1]['Organic'])) $visitors[$j-1]['Organic'] = 0;
				if (!isset($visitors[$j-1]['Referral'])) $visitors[$j-1]['Referral'] = 0;

				// Visits
				if (!isset($visits[$j-1]['Not Set'])) 	$visits[$j-1]['Not Set'] = 0;
				if (!isset($visits[$j-1]['Direct'])) 	$visits[$j-1]['Direct'] = 0;
				if (!isset($visits[$j-1]['Organic'])) 	$visits[$j-1]['Organic'] = 0;
				if (!isset($visits[$j-1]['Referral'])) 	$visits[$j-1]['Referral'] = 0;

				// Pageviews
				if (!isset($pageviews[$j-1]['Not Set'])) 	$pageviews[$j-1]['Not Set'] = 0;
				if (!isset($pageviews[$j-1]['Direct'])) 	$pageviews[$j-1]['Direct'] = 0;
				if (!isset($pageviews[$j-1]['Organic'])) 	$pageviews[$j-1]['Organic'] = 0;
				if (!isset($pageviews[$j-1]['Referral'])) 	$pageviews[$j-1]['Referral'] = 0;

			}
		$date = $repDate[$i];
		$j++;	 

		$visitors[$j]['Date'] = $repDate[$i]; 
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
	$i++;

}



?>
</table>

<?php 

	echo "<PRE>";
	print_r($visitors);
	echo "</PRE>"

?>


</BODY>
</HTML>