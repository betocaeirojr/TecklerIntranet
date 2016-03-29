<HTML>
<HEAD>
	<Title>Metrics Reports from Google Analytics</TITlE>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
</HEAD>
<BODY>
<h1>Metrics Gathered from Google Analytics</h1>	

<P>Information last update at:

<?php
date_default_timezone_set('America/Sao_Paulo');
require ("gapi-1.3/gapi.class.php");

echo date('Y-m-d l H:i:s');



$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date','pagePath', 'previousPagePath', 'nextPagePath', 'fullReferrer','landingPagePath', 'sourceMedium');
$metrics = array('pageviews','uniquePageviews','entrances', 'entranceRate', 'pageviewsPerVisit', 'avgTimeOnPage','exits', 'exitRate', 'pageValue');
$sort = '-date';
$timeInterval = '10';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d', strtotime('-0'.' days'));

?>
<h2> Portuguese Tecks Pages Leading to New User In Portuguese</h2>
<table border=1>
	<tr>
		<thead>
			<th>Date</th>
			<th>Landing Page</th>
			<th>Page Path</th>
			<!--th>Previous Page</th>
			<th>Next Page</th-->
			<th>Full Referrer </th>
			<!--th>Source Medium </th>
			<th># Pageviews </th>
			<th># Unique Pageviews </th>
			<th># Entrances </th-->
		</thead>
	</tr>

<?php
// Filtering New User Other from HOME
$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$';
$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, ($filter!='null'? $filter : null ), $fromDate, $toDate, 1, 10000);

$i=0;
foreach($ga->getResults() as $result){
	echo "<tr>\n";
	echo "<td>" . date('Y-m-d', strtotime($result->getDate())) . "</td>\n";
	echo "<td>" . $result->getLandingPagePath() . "</td>\n";
	echo "<td>" . $result->getPagePath() . "</td>\n";
	//echo "<td>" . $result->getPreviousPagePath() . "</td>\n";
	//echo "<td>" . $result->getNextPagePath() . "</td>\n";
	echo "<td>" . $result->getFullReferrer() . "</td>\n";
	//echo "<td>" . $result->getSourceMedium() . "</td>\n";
	//echo "<td>" . $result->getPageviews() . "</td>\n";
	//echo "<td>" . $result->getUniquePageviews() . "</td>\n";
	//echo "<td>" . $result->getEntrances() . "</td>\n";
	//echo "</tr>\n";
	$i++;
}
$ReturnedCount_1 = $i + 1;

?>
</table>
<?php echo "<BR><B>Total Number of Returned Itens is: </B> $ReturnedCount_1 !"; ?>




</BODY>
</HTML>
