<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");


// GA Credentials
$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';

// Dimensions and Metrics Setup
$dimensions = array('date','pagePath', 'city', 'networkLocation', 'fullReferrer','landingPagePath', 'pageDepth');
$metrics = array('pageviews','uniquePageviews','entrances', 'bounces', 'entranceRate', 'pageviewsPerVisit', 'avgTimeOnPage','exits', 'exitRate', 'pageValue');
$sort = '-date';

// Time Interval
$timeInterval = '1';
//$fromDate = ( isset($_POST['starting_date']) ? $_POST['starting_date']: date('Y-m-d', strtotime('-'. $timeInterval .' days')));
//$toDate = ( isset($_POST['finishing_date']) ? $_POST['finishing_date'] : date('Y-m-d', strtotime('-0'.' days'))) ;

$filter = 'fullReferrer == (direct)';

// $filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ && landingPagePath =@ /en/WaterGod';

?>


<HTML>
<HEAD>
	<Title>Metrics Reports from Google Analytics</TITlE>
</HEAD>

<BODY>
<h1>Fraud Analysis From Google Analytics Information</h1>	
<p> Please setup your fraud analysis report:</p>
<P>Information last update at:
	<?php 
		echo date('Y-m-d l H:i:s');
		$todayIs = time();
		$dayInterval = (1 * 24 * 60 * 60 );
		$firstDayIs = date('U', strtotime('2013-09-24'));
		$EndOfSept = date('U', strtotime('2013-09-30'));
		$BegOfOct = date('U', strtotime('2013-10-01'));
		$FirstWeekOfOct = date('U', strtotime('2013-10-07'));
		$SecondWeekOfOct = date('U', strtotime('2013-09-14'));
		$ThirdWeekOfOct = date('U', strtotime('2013-09-21'));
		$ForthWeekOfOct = date('U', strtotime('2013-09-28'));

		echo "<BR>";
		echo "Today is: " . date('Y-m-d') . " >> In Unix Epoque Time = " . $todayIs . " <BR>\n";
		echo "First day is: " . date('Y-m-d', strtotime('2013-09-24') ) . " >> In Unic Epoque Time = " . $firstDayIs . " <BR>\n";

	?>

</p>
<h2> Fraud Analysis Information;</h2>
<h4>Info Considering the pages that matches the filter: <?php echo $filter ;?></h4>

<?php
$i = 0;
echo "[DEBUG] -- First Day is: " 	. $firstDayIs 	. "<BR>\n";
echo "[DEBUG] -- Today is: " 		. $todayIs 		. "<BR>\n";
echo "[DEBUG] -- Day Interval is: " . $dayInterval  . "<BR>\n";

for($i  = (int)$firstDayIs ; $i <= (int)$EndOfSept ; $i = $i + (int)$dayInterval) {

	echo "<BR> Info for day: " . date("Y-m-d", $i) . "<BR>\n";
	$fromDate = date('Y-m-d', $i);
	echo "From date is " . $fromDate . "<BR>\n";
	$toDate = date('Y-m-d', $i);
	echo "From date is " . $fromDate . "<BR>\n";

	$ReturnedCount = 0;
	$ga = new gapi($gaUsername, $gaPassword);
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, 1, 100000);
	//$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, ($postFilter!='null'? $postFilter : $filter ), $fromDate, $toDate, 1, 100000);

	$j=1;
	foreach($ga->getResults() as $result){
		//echo "<tr>\n";
		//echo "<td>" . date('Y-m-d', strtotime($result->getDate())) . "</td>\n";
		//echo "<td>" . $result->getFullReferrer() . "</td>\n";
		//echo "<td>" . $result->getLandingPagePath() . "</td>\n";
		//echo "<td>" . $result->getPagePath() . "</td>\n";
		//echo "<td>" . $result->getPageDepth() . "</td>\n";
		//echo "<td>" . $result->getPageviews() . "</td>\n";
		//echo "<td>" . $result->getUniquePageviews() . "</td>\n";
		//echo "<td>" . $result->getBounces() . "</td>\n";
		//echo "<td>" . $result->getNetworkLocation() . "</td>\n";
		//echo "<td>" . $result->getCity() . "</td>\n";

		//echo "<td>" . $result->getPreviousPagePath() . "</td>\n";
		//echo "<td>" . $result->getNextPagePath() . "</td>\n";
		//echo "<td>" . $result->getSourceMedium() . "</td>\n";
		//echo "<td>" . $result->getEntrances() . "</td>\n";
		//echo "</tr>\n";
		$j++;
	}
	$ReturnedCount = $j;
	echo "<BR><B>Total Number of Returned Itens is: $ReturnedCount !</b> <BR><BR>";

}

?>

</BODY>
</HTML>
