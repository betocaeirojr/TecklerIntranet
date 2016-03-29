<html>
<HEAD><Title>Metrics Reports from Google Analytics</TITlE></HEAD>
<BODY>
<h1>Metrics Gathered from Google Analytics</h1>	

<P>Information last update at:
<?php
date_default_timezone_set('America/Sao_Paulo');
echo date('Y-m-d H:i:s');

echo "<P> Filtering only previousPagePath =~ (-)[0-9]\d{1,}$ && PagePath =~ (..\/home)$ </P>"

?>
 </P>

<table border=1>
	<tr>
		<th>Date</th>
		<th>Pageviews</th>
		<th>Unique Pageviews</th>
		<th>Visitors</th>
		<th>Average Time On Site</th>
	</tr>

<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date');
$metrics = array('pageviews','uniquePageviews','visitors', 'avgTimeOnSite');
$sort = '-date';
$timeInterval = '60';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d');
$filter = 'previousPagePath =~ (-)[0-9]\d{1,}$ && PagePath =~ (..\/home)$';


$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, 1, $timeInterval);

foreach($ga->getResults() as $key => $result){
	echo "<tr>\n";
	$repDate[$key] 					= $result->getDate();
	$repPageview[$key] 				= $result->getPageviews();
	$repUniquePageviews[$key]		= $result->getUniquePageviews();	
	$repVisitors[$key] 				= $result->getVisitors();
	$repAvgTimeOnSite[$key] 		= $result->getAvgTimeOnSite();

	echo "<td>" . date("Y-m-d : l ",strtotime($repDate[$key])) 	. "</td>\n";
	echo "<td>" . $repPageview[$key] 						. "</td>\n";
	echo "<td>" . $repUniquePageviews[$key] 						. "</td>\n";
	echo "<td>" . $repVisitors[$key]						. "</td>\n";
	echo "<td>" . $repAvgTimeOnSite[$key]					. "</td>\n";
	echo "</tr>\n";
}
?>
</table>
</BODY>
</HTML>