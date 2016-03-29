<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

// GA Credentials
$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';

// Dimensions and Metrics Setup
$dimensions = array('country');
$metrics = array('pageviews','uniquePageviews','visitors');
//$sort = '-date';
//$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$';
$filter = 'PagePath =~ ^(\/[^\/]+){3,} && PagePath =~ (-)[0-9]\d{1,}$';

// Time Interval
$timeInterval = '1';
$fromDate 	= date('Y-m-d', strtotime('-30 days'));
$toDate 	= date('Y-m-d', strtotime('-0 days'));

?>

<HTML>
<HEAD>
	<Title>Metrics Reports from Google Analytics</TITlE>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
</HEAD>

<BODY>
	<h1>Origin Access In Tecks Breakdown </h1>	
		<P>Information last update at:<?php echo date('Y-m-d l H:i:s');?></p>
		
	<h2> Fraud Analysis Information;</h2>
	<h4>Info from <?php echo $fromDate ;?> to <?php echo $toDate ;?> <BR>
		Considering the pages that matches the filter: <?php echo $filter ;?></h4>
	<table border=1>
		<tr>
			<thead>
				<th>Pageviews</th>
				<th>Unique Pageviews</th>
				<th>Visitors</th>
				<th>Country</th>
			</thead>
		</tr>

	<?php
	
	$ReturnedCount = 0;
	//if (isset($_POST['SubmitSpecific']) or isset($_POST['SubmitStarting']) ) {
		$ga = new gapi($gaUsername, $gaPassword);
		$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, NULL, $filter, $fromDate, $toDate, 1, 100000);

		$i=0;
		foreach($ga->getResults() as $result){
			echo "<tr>\n";
			echo "<td>" . number_format($result->getPageviews(), 0, ".", "," )		. "</td>\n";
			echo "<td>" . number_format($result->getUniquePageviews(), 0, ".", ",") . "</td>\n";
			echo "<td>" . number_format($result->getVisitors(), 0, ".", ",") 		. "</td>\n";
			echo "<td>" . $result->getCountry() 		. "</td>\n";
			echo "</tr>\n";
			$i++;
		}
		$ReturnedCount = $i + 1;
	//}
	?>
	</table>
	<?php echo "<BR><B>Total Number of Returned Itens is: $ReturnedCount !"; ?>

</BODY>
</HTML>
