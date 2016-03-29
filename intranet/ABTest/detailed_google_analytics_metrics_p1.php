<html>
<HEAD><Title>Metrics Reports from Google Analytics</TITlE></HEAD>
<BODY>
<h1>Metrics Gathered from Google Analytics</h1>	

<P>Information last update at:
<?php
date_default_timezone_set('America/Sao_Paulo');
echo date('Y-m-d H:i:s');

echo "<P> Filtering only PagePath =~ (..\/home)$ </P>"

?>
 </P>

<table border=1>
	<tr>
		<th>Date</th>
		<th>Pageviews</th>
		<th>Pages Visit</th>
		<th>Bounce</th>
		<th>%Bounce</th>
		<th>Visitors</th>
		<th>New Visits</th>
		<th>% New Visits</th>
		<th>% Returning Visits</th>
		<th>Average Time On Site</th>
		<th>Average Time On Page</th>
		<th># Pages / Visits</th>
		<th>Entrance Bounce Rate</th>
	</tr>

<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date');
$metrics = array('pageviews','uniquePageviews','bounces', 'visits', 'visitors', 'newVisits', 'percentNewVisits','avgTimeOnSite', 'avgTimeOnPage', 'entranceBounceRate');
$sort = '-date';
$timeInterval = '60';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d');
$filter = 'PagePath =~ (..\/home)$';


$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, 1, $timeInterval);

foreach($ga->getResults() as $key => $result){
	echo "<tr>\n";
	$repDate[$key] 					= $result->getDate();
	$repPageview[$key] 				= $result->getPageviews();	
	$repVisits[$key]				= $result->getVisits();
	$repBounce[$key] 				= $result->getBounces();
	$repPercBounce[$key] 			= ($repBounce[$key] / $repVisits[$key]);
	$repVisitors[$key] 				= $result->getVisitors();
	$repNewVisits[$key] 			= $result->getNewVisits();
	$repPercentNewVisits[$key] 		= $result->getPercentNewVisits();
	$repPercentReturningVisits[$key] = (100 - $repPercentNewVisits[$key]);
	$repAvgTimeOnSite[$key] 		= $result->getAvgTimeOnSite();
	$repAvgTimeOnPage[$key] 		= $result->getAvgTimeOnPage();
	$repPagesPerVisit[$key] 		= $repPageview[$key] / $repVisits[$key];
	$repEntranceBounceRate[$key] 	= $result->getEntranceBounceRate();



	echo "<td>" . date("Y-m-d : l ",strtotime($repDate[$key])) 	. "</td>\n";
	echo "<td>" . $repPageview[$key] 						. "</td>\n";
	echo "<td>"	. $repVisits[$key]	 						. "</td>\n";
	echo "<td>" . $repBounce[$key] 	 						. "</td>\n";
	echo "<td>" . round($repPercBounce[$key],4)*100 		. "% </td>\n";
	echo "<td>" . $repVisitors[$key]						. "</td>\n";
	echo "<td>" . $repNewVisits[$key]						. "</td>\n";
	echo "<td>" . round($repPercentNewVisits[$key],2)		. "% </td>\n";
	echo "<td>" . round($repPercentReturningVisits[$key],2)	. "% </td>\n";
	echo "<td>" . $repAvgTimeOnSite[$key]					. "</td>\n";
	echo "<td>" . $repAvgTimeOnPage[$key]					. "</td>\n";
	echo "<td>" . round($repPagesPerVisit[$key],2) 			. "</td>";
	echo "<td>" . round($repEntranceBounceRate[$key],2) 	. "</td>";
	echo "</tr>\n";
}
?>
</table>
</BODY>
</HTML>