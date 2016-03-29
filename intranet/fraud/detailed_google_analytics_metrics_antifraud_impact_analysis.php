<html>
<HEAD><Title>Metrics Reports from Google Analytics</TITlE></HEAD>
<BODY>
<h1>Metrics Gathered from Google Analytics</h1>	

<P>Information last update at:
<?php

$param = !empty($_GET['sig']) ?  $_GET['sig'] : "";

date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");
echo date('Y-m-d H:i:s');
echo "<BR>";

$gaUsername = 'acaeiro@teckler.com';
$gaPassword = '0ju5td01t0';
$profileId = '71713009';
$dimensions = array('date' /*,'landingPagePath', 'pagePath'*/);
$metrics = array('pageviews','uniquePageviews', /*'bounces' ,*/ 'visits', 'visitors' /*, 'newVisits', 'percentNewVisits','avgTimeOnSite','entranceBounceRate'*/);
$sort = '-date';
$timeInterval = '120';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d');
//$regexp = "(\/".$param."\/(.){1,})$";
//$filter =  !empty($param) ? "pagePath =~" . $regexp . " || landingPagePath=~". $regexp : "";
$regexp = "/".$param."/";
$filter =  !empty($param) ? "pagePath =@" . $regexp . " || landingPagePath=@". $regexp : "";

// Reg Exp Example
// (\/PhillSenters\/(.){1,})$
// retrives all tecks of 'PhillSenters'
echo "[DEBUG] -- Filter Option is: " . $filter;

?>

 </P>

<table border=1>
	<tr><th>Date</th><th>Pageviews</th><th>Unique Pageviews</th><th>Visits</th>	<th>Visitors</th></tr>
<?php
$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, 1, $timeInterval);
foreach($ga->getResults() as $key => $result){
	echo "<tr>\n";
	echo "<td>" . date("Y-m-d",strtotime($result->getDate() ) ) 	. "</td>\n";
	echo "<td>" . $result->getPageviews() 							. "</td>\n";
	echo "<td>" . $result->getUniquePageviews() 					. "</td>\n";
	echo "<td>"	. $result->getVisits() 								. "</td>\n";
	echo "<td>" . $result->getVisitors()							. "</td>\n";
	echo "</tr>\n";
}

?>
</table>
</BODY>
</HTML>