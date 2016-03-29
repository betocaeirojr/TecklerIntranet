<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

// Filter
$filter = 'PagePath =~ (..\/home)$ && LandingPagePath =~ (..\/home)$';
$postFilter = "";

 
if ( !empty($_POST['pagepath_select']) ) $postFilter = $_POST['pagepath_select'];	

if ( !empty($_POST['landingpagepath_select']) ) {
	if (empty($postFilter)) {
		$postFilter = $_POST['landingpagepath_select'];
	} else {
		$postFilter .= " && " .  $_POST['landingpagepath_select'];
	}	
} 

if ( !empty($_POST['referrer_select']) ) {
	if (empty($postFilter)) {
		$postFilter = $_POST['referrer_select'];
	} else {
		$postFilter .= " && "  . $_POST['referrer_select'];
	}
} 


if (isset($_POST['SubmitStarting']) and ($_POST['pagepath_input']!="")) {
	$postFilter = "PagePath =@ " . $_POST['pagepath_input'];
} elseif (isset($_POST['SubmitStarting']) and ($_POST['referrer_input']!="")) {
	$postFilter = "fullReferrer =@ " . $_POST['referrer_input'];
}


// GA Credentials
$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';

// Dimensions and Metrics Setup
$dimensions = array('date','pagePath', 'country','landingPagePath', 'pageDepth', 'fullReferrer', 'referralPath');
$metrics = array('pageviews','uniquePageviews','entrances', 'bounces', 'entranceRate', 'pageviewsPerVisit', 'avgTimeOnPage','exits', 'exitRate', 'pageValue');
$sort = '-date';


// Time Interval
$timeInterval = '1';
$fromDate = ( isset($_POST['starting_date']) ? $_POST['starting_date']: date('Y-m-d', strtotime('-'. $timeInterval .' days')));
$toDate = ( isset($_POST['finishing_date']) ? $_POST['finishing_date'] : date('Y-m-d', strtotime('-0'.' days'))) ;

// Totals

$totalPageviews 			= 0; 
$totalUniquePageview 		= 0; 
$totalBounce				= 0; 
$averagePageviewsPerVisit	= 0; 
$averageTimeOnPage			= 0; 	 


?>

<HTML>
<HEAD>
	<Title>Metrics Reports from Google Analytics</TITlE>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script type='text/javascript'>
		// The active filter is PagePath Select
		/*
		function ActiveFilterPPS()
		{
			var PPI=document.getElementById("pagepath_input"); 		PPI.value="";
			var RPS=document.getElementById("referrer_select"); 	RPS.value="";
			var RPI=document.getElementById("referrer_input");   	RPI.value="";
		}

		// The active filter IS PagePath Input
		function ActiveFilterPPI()
		{
			var PPS=document.getElementById("pagepath_select");		PPS.value="";
			var RPS=document.getElementById("referrer_select");		RPS.value="";
			var RPI=document.getElementById("referrer_input");		RPI.value="";			
		}
		
		// The active filter is Referrer Select
		function ActiveFilterRPS()
		{
			var RPI=document.getElementById("referrer_input");		RPI.value="";
			var PPS=document.getElementById("pagepath_select");		PPS.value="";
			var PPI=document.getElementById("pagepath_input");		PPI.value="";
		}

		// The active filter is Referrer Input
		function ActiveFilterRPI()
		{
			var RPS=document.getElementById("referrer_select");		PPI.value="";
			var PPS=document.getElementById("pagepath_select");		PPS.value="";
			var PPI=document.getElementById("pagepath_input");		PPI.value="";		
		}*/
	</script>
</HEAD>

<BODY>
<h1>Fraud Analysis From Google Analytics Information</h1>	

<p> Please setup your fraud analysis report:</p>

<form method="post" action="ga_metrics_p1.php">
	<table border=1>
		<tr> <!--  Site Pre Selected Pages -->
			<td colspan=2> Please select the specific page to track: </td>
			<td>Page Select:</td>
			<td><select name="pagepath_select" id="pagepath_select">
					<option value = "null" selected> Overall Info </option>
					<option value = "PagePath =~ (..\/home)$">Home - All</option>
					<option value = "PagePath == /pt/home">Home - Portugues </option>
					<option value = "PagePath == /en/home">Home - English</option>
				</select>
			</td>
		</tr>
		<tr> <!--  Any Given page on site -->	
			<td colspan=2> Or enter a Landing Page to query: </td>
			<td>Landing Page Select:</td>
			<td><select name="landingpagepath_select" id="landingpagepath_select">
					<option value = "null" selected> Overall Info </option>
					<option value = "PagePath =~ (..\/home)$">Home - All</option>
					<option value = "PagePath == /pt/home">Home - Portugues </option>
					<option value = "PagePath == /en/home">Home - English</option>
					<option value = "PagePath =~ (..\/user\/new_user)$">New User - All </option>
					<option value = "PagePath == /pt/user/new_user">New User - Portugues </option>
					<option value = "PagePath == /en/user/new_user">New User - English </option>
				</select>
			</td>
		</tr>
		<tr> <!--  Filter by Referrer -->
			<td colspan=2> Or filter by Referrer </td>
			<td>Referrer Select:</td>
			<td><select name="referrer_select" id="referrer_select">
					<option value = "null" selected> Overall Info </option>
					<option value = "fullReferrer =@ teckler.com">Teckler</option>
					<option value = "fullReferrer == (direct)">Direct</option>
				</select>
			</td>
		</tr>

		<tr> <!-- Starting Date -->
			<td colspan=2> Starting Date </td>
			<td colspan=2><input type='date' id='starting_date' name='starting_date' value=
				<?php echo (isset($_POST['starting_date']) ? "'" . $_POST['starting_date'] . "'" : "" 	); ?>
				></td>
		</tr>
		<tr> <!-- Finishing Date -->
			<td colspan=2> Finishing Date </td>
			<td colspan=2><input type='date' id='finishing_date' name='finishing_date' value=
				<?php echo (isset($_POST['finishing_date']) ? "'" . $_POST['finishing_date'] . "'" : "" 	); ?>
				></td>
		</tr>


		<tr>
			<td colspan=2 align=center><input type="submit" value="Cancel" name="Cancel"></input></td>
			<td colspan=2 align=center><input type="submit" value="Go and Get It - This specific Page!" name="SubmitSpecific"></input> &nbsp;
			<input type="submit" value="Go and Get It - Pages/Referrers Starting with this string!" name="SubmitStarting"></input></td>
		</tr>
	</table>
</form>
<hr>


<P>Information last update at:
	<?php echo date('Y-m-d l H:i:s'); ?>
</p>
<h2> Fraud Analysis Information;</h2>
<h4>Info from <?php echo $fromDate ;?> to <?php echo $toDate ;?> <BR>
	Considering the pages that matches the filter: <?php echo $postFilter ;?></h4>
<table border=1>
	<tr>
		<thead>
			<th>Date</th>
			<th>Landing Page</th>
			<th>Page Path</th>
			<th>Page Value</th>
			<th>Page Depth</th>
			<th>Pageviews</th>
			<th>Unique Pageviews</th>
			<th>Bounces</th>
			<th>Country</th>
			<th>Full Referrer</th>
			<th>Referral Path</th>
			<th>Pageviews Per Visit</th>
			<th>Average Time On Page</th>
		</thead>
	</tr>

<?php


// DEBUGGING
//echo "<pre>";
//print_r($_POST);
//echo "From Date is 	: $fromDate <BR>\n";
//echo "To Date is 	: $toDate <BR>\n";
//echo "PostFilter is : $postFilter <BR>\n";
//echo "Filter is 	: $filter <BR>\n";
//echo "</pre>";

$ReturnedCount = 0;
$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, ($postFilter!=""? $postFilter : $filter ), $fromDate, $toDate, 1, 100000);

$i=0;
foreach($ga->getResults() as $result){
	echo "<tr>\n";
	echo "<td>" . date('Y-m-d', strtotime($result->getDate())) 	. "</td>\n";
	echo "<td>" . $result->getLandingPagePath() 				. "</td>\n";
	echo "<td>" . $result->getPagePath() 						. "</td>\n";
	echo "<td>" . $result->getPageValue() 						. "</td>\n";
	echo "<td>" . $result->getPageDepth() 						. "</td>\n";
	echo "<td>" . $result->getPageviews() 						. "</td>\n";
	echo "<td>" . $result->getUniquePageviews() 				. "</td>\n";
	echo "<td>" . $result->getBounces() 						. "</td>\n";
	echo "<td>" . $result->getCountry() 						. "</td>\n";
	echo "<td>" . $result->getFullReferrer() 					. "</td>\n";
	echo "<td>" . $result->getReferralPath() 					. "</td>\n";
	echo "<td>" . $result->getPageviewsPerVisit() 				. "</td>\n";
	echo "<td>" . $result->getAvgTimeOnPage() 					. "</td>\n";
	
	$totalPageviews 			= $totalPageviews + $result->getPageviews();
	$totalUniquePageview 		= $totalUniquePageview + $result->getUniquePageviews(); 	
	$totalBounce				= $totalBounce + $result->getBounces(); 
	$averagePageviewsPerVisit	= $averagePageviewsPerVisit + $result->getPageviewsPerVisit(); 
	$averageTimeOnPage			= $averageTimeOnPage + $result->getAvgTimeOnPage();

	echo "</tr>\n";
	$i++;
}
$ReturnedCount = $i + 1;

if ($ReturnedCount == 10001) {
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, ($postFilter!=""? $postFilter : $filter ), $fromDate, $toDate, 10001, 10000);

	foreach($ga->getResults() as $result){
		echo "<tr>\n";
		echo "<td>" . date('Y-m-d', strtotime($result->getDate())) . "</td>\n";
		echo "<td>" . $result->getLandingPagePath() . "</td>\n";
		echo "<td>" . $result->getPagePath() . "</td>\n";
		echo "<td>" . $result->getPageValue() . "</td>\n";
		echo "<td>" . $result->getPageDepth() . "</td>\n";
		echo "<td>" . $result->getPageviews() . "</td>\n";
		echo "<td>" . $result->getUniquePageviews() . "</td>\n";
		echo "<td>" . $result->getBounces() . "</td>\n";
		echo "<td>" . $result->getCountry() . "</td>\n";
		echo "<td>" . $result->getFullReferrer() . "</td>\n";
		echo "<td>" . $result->getReferralPath() . "</td>\n";
		echo "<td>" . $result->getPageviewsPerVisit() . "</td>\n";
		echo "<td>" . $result->getAvgTimeOnPage() . "</td>\n";

		$totalPageviews 			= $totalPageviews + $result->getPageviews();
		$totalUniquePageview 		= $totalUniquePageview + $result->getUniquePageviews(); 	
		$totalBounce				= $totalBounce + $result->getBounces(); 
		$averagePageviewsPerVisit	= $averagePageviewsPerVisit + $result->getPageviewsPerVisit(); 
		$averageTimeOnPage			= $averageTimeOnPage + $result->getAvgTimeOnPage();

		echo "</tr>\n";
		$i++;
	}
	$ReturnedCount = $i + 1;


}

echo "<tr>";
echo "<td>" . "Totals" 										. "</td>\n";
echo "<td>" . " "											. "</td>\n";
echo "<td>" . " "											. "</td>\n";
echo "<td>" . " "											. "</td>\n";
echo "<td>" . " "											. "</td>\n";
echo "<td>" . $totalPageviews								. "</td>\n";
echo "<td>" . $totalUniquePageview							. "</td>\n";
echo "<td>" . $totalBounce / $ReturnedCount					. "</td>\n";
echo "<td>" . " " 											. "</td>\n";
echo "<td>" . " " 											. "</td>\n";
echo "<td>" . " " 											. "</td>\n";
echo "<td>" . $averagePageviewsPerVisit / $ReturnedCount 	. "</td>\n";
echo "<td>" . $averageTimeOnPage / $ReturnedCount 			. "</td>\n";
echo "</tr>";



?>
</table>
<?php echo "<BR><B>Total Number of Returned Itens is: $ReturnedCount !"; ?>

</BODY>
</HTML>
