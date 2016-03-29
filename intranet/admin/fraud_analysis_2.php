<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");


if (isset($_POST['pagepath_select']) and ($_POST['pagepath_select'] != "") ) {

	$postFilter = $_POST['pagepath_select'];	

} elseif (isset($_POST['pagepath_input']) and ($_POST['pagepath_input'] != "") ) {
	
	$postFilter = "PagePath == " . $_POST['pagepath_input'];

} elseif (isset($_POST['referrer_select']) and ($_POST['referrer_select'] != "") ) {
	$postFilter = $_POST['referrer_select'];

} elseif (isset($_POST['referrer_input']) and ($_POST['referrer_input'] != "") ){
	
	$postFilter = "fullReferrer == " . $_POST['pagepath_input'];

} else {
	
	$postFilter = null;
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
$dimensions = array('date','pagePath', 'city', 'networkLocation', 'fullReferrer','landingPagePath', 'pageDepth');
$metrics = array('pageviews','uniquePageviews','entrances', 'bounces', 'entranceRate', 'pageviewsPerVisit', 'avgTimeOnPage','exits', 'exitRate', 'pageValue');
$sort = '-date';

// Time Interval
$timeInterval = '1';
$fromDate = ( isset($_POST['starting_date']) ? $_POST['starting_date']: date('Y-m-d', strtotime('-'. $timeInterval .' days')));
$toDate = ( isset($_POST['finishing_date']) ? $_POST['finishing_date'] : date('Y-m-d', strtotime('-0'.' days'))) ;

?>

<HTML>
<HEAD>
	<Title>Metrics Reports from Google Analytics</TITlE>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>

	<script type='text/javascript'>
		// The active filter is PagePath Select
		function ActiveFilterPPS()
		{
			var PPI=document.getElementById("pagepath_input");
			PPI.value="";

			var RPS=document.getElementById("referrer_select");
			RPS.value="";

			var RPI=document.getElementById("referrer_input");
			RPI.value="";
		}

		// The active filter IS PagePath Input
		function ActiveFilterPPI()
		{
			var PPS=document.getElementById("pagepath_select");
			PPS.value="";

			var RPS=document.getElementById("referrer_select");
			RPS.value="";

			var RPI=document.getElementById("referrer_input");
			RPI.value="";			
		}
		
		// The active filter is Referrer Select
		function ActiveFilterRPS()
		{
			var RPI=document.getElementById("referrer_input");
			RPI.value="";

			var PPS=document.getElementById("pagepath_select");
			PPS.value="";

			var PPI=document.getElementById("pagepath_input");
			PPI.value="";
			
		}

		// The active filter is Referrer Input
		function ActiveFilterRPI()
		{
			var RPS=document.getElementById("referrer_select");
			PPI.value="";

			var PPS=document.getElementById("pagepath_select");
			PPS.value="";

			var PPI=document.getElementById("pagepath_input");
			PPI.value="";		
		}
	</script>
</HEAD>

<BODY>
<h1>Fraud Analysis From Google Analytics Information</h1>	

<p> Please setup your fraud analysis report:</p>

<form method="post" action="fraud_analysis_2.php">
	<table border=1>
		<tr> <!--  Site Pre Selected Pages -->
			<td colspan=2> Please select the specific page to track: </td>
			<td>Page Select:</td>
			<td><select name="pagepath_select" id="pagepath_select" onchange=ActiveFilterPPS()>
					<option value = "null" selected> Overall Info </option>
					<option value = "PagePath == /pt/home">/pt/home</option>
					<option value = "PagePath == /en/home">/en/home</option>
					<option value = "PagePath == /pt/about">/pt/about</option>
					<option value = "PagePath == /en/about">/en/about</option>					
					<option value = "PagePath == /pt/user/new_user">/pt/user/new_user</option>
					<option value = "PagePath == /en/user/new_user">/en/user/new_user</option>
					<option value = "PagePath == /pt/faq">/pt/faq</option>
					<option value = "PagePath == /en/faq">/en/faq</option>
					<option value = "PagePath == /pt/terms">/pt/terms</option>
					<option value = "PagePath == /en/terms">/en/terms</option>
					<option value = "PagePath == /pt/privacy-policy">/pt/privacy-policy</option>
					<option value = "PagePath == /en/privacy-policy">/en/privacy-policy</option>
					<option value = "PagePath == /pt/search/do_search">/pt/search/do_search</option>
					<option value = "PagePath == /en/search/do_search">/en/search/do_search</option>
					<option value = "PagePath != /pt/home && PagePath != /pt/about && 
									 PagePath != /pt/user/new_user && PagePath != /pt/faq && 
									 PagePath != /pt/terms && PagePath != /pt/privacy-policy && 
									 PagePath != /pt/search/do_search && PagePath =~ ^\/pt">
						All Tecks and Profiles in Portuguese
					</option>
					<option value = "PagePath != /en/home && PagePath != /en/about && 
									 PagePath != /en/user/new_user && PagePath != /en/faq && 
									 PagePath != /en/terms && PagePath != /en/privacy-policy && 
									 PagePath != /en/search/do_search && PagePath =~ ^\/en">
						All Tecks and Profies in English
					</option>
					<option value = "PagePath =~ ^\/pt(\/[^\/]+){2,}$ && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$"> All Tecks ONLY - Portuguese </option>
					<option value = "PagePath =~ ^\/en(\/[^\/]+){2,}$ && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$"> All Tecks ONLY -  English </option>
					<option value = "PagePath !@ /home && PagePath !@ /about && 
									 PagePath !@ /user/new_user && PagePath !@ /faq && 
									 PagePath !@ /terms && PagePath !@ /privacy-policy && 
									 PagePath !@ /search/do_search">
						All Tecks and Profiles - All Languages
					</option>
					<option value="PagePath =~ ^(\/[^\/]+){3,} && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$"> 
						All Tecks ONLY
					</option>					
				</select>
			</td>
		</tr>
		<tr> <!--  Any Given page on site -->	
			<td colspan=2> Or enter a page path to query: </td>
			<td>Page Path:</td>
			<td><input type='text' name="pagepath_input" id="pagepath_input" onchange=ActiveFilterPPI() value=
				<?php echo (isset($_POST['pagepath_input']) ? "'" . $_POST['pagepath_input'] . "'" : "" 	); ?>
				></input></td>
		</tr>
		<tr> <!--  Filter by Referrer -->
			<td colspan=2> Or filter by Referrer </td>
			<td>Referrer Select:</td>
			<td><select name="referrer_select" id="referrer_select" onchange=ActiveFilterRPS()>
					<option value = "fullReferrer ==  " selected>Select Below</option>
					<option value = "fullReferrer =@ adf.ly">adf.ly</option>
					<option value = "fullReferrer =@ bloomberg.com">bloomberg.com</option>
					<option value = "fullReferrer =@ droppages.com">droppages.com</option>
					<option value = "fullReferrer =@ hitleap.com">hitleap.com</option>
					<option value = "fullReferrer =@ lolinez.com">lolinez.com</option>
					<option value = "fullReferrer =@ pinterest.com">Pinterest.com</option>
					<option value = "fullReferrer =@ t.com">t.com</option>
					<option value = "fullReferrer =@ tumblr.com">tumblr.com</option>		
					<option value = "fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$">Tumblr Redirect</option>>
					<option value = "fullReferrer =@ winstenseah.me">winstenseah.me</option>
				</select>
			</td>
		</tr>
		<tr> <!--  Any Given referrer -->	
			<td colspan=2> Or enter a specific referrer to filter </td>
			<td>Referrer String:</td>
			<td><input type='text' name="referrer_input" id="referrer_input" onchange=ActiveFilterRPI() value=
				<?php echo (isset($_POST['referrer_input']) ? "'" . $_POST['referrer_input'] . "'" : "" 	); ?>
				></input></td>
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
			<th>Full Referrer </th>
			<th>Landing Page</th>
			<th>Page Path</th>
			<th>Page Depth</th>
			<th>Pageviews</th>
			<th>Unique Pageviews</th>
			<th>Bounces</th>
			<th>Network Location</th>
			<th>City</th>
		</thead>
	</tr>

<?php
// Filter
//$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$';
$filter = 'fullReferrer == (direct)';

// DEBUGGING
//echo "<pre>";
//print_r($_POST);
//echo "From Date is 	: $fromDate <BR>\n";
//echo "To Date is 	: $toDate <BR>\n";
//echo "PostFilter is : $postFilter <BR>\n";
//echo "Filter is 	: $filter <BR>\n";
//echo "</pre>";

$ReturnedCount = 0;
if (isset($_POST['SubmitSpecific']) or isset($_POST['SubmitStarting']) ) {
	$ga = new gapi($gaUsername, $gaPassword);
	//$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, ($postFilter!='null'? $postFilter : $filter ), $fromDate, $toDate, 1, 100000);
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort,  $filter , $fromDate, $toDate, 1, 100000);

	$i=0;
	foreach($ga->getResults() as $result){
		echo "<tr>\n";
		echo "<td>" . date('Y-m-d', strtotime($result->getDate())) . "</td>\n";
		echo "<td>" . $result->getFullReferrer() . "</td>\n";
		echo "<td>" . $result->getLandingPagePath() . "</td>\n";
		echo "<td>" . $result->getPagePath() . "</td>\n";
		echo "<td>" . $result->getPageDepth() . "</td>\n";
		echo "<td>" . $result->getPageviews() . "</td>\n";
		echo "<td>" . $result->getUniquePageviews() . "</td>\n";
		echo "<td>" . $result->getBounces() . "</td>\n";
		echo "<td>" . $result->getNetworkLocation() . "</td>\n";
		echo "<td>" . $result->getCity() . "</td>\n";

		//echo "<td>" . $result->getPreviousPagePath() . "</td>\n";
		//echo "<td>" . $result->getNextPagePath() . "</td>\n";
		//echo "<td>" . $result->getSourceMedium() . "</td>\n";
		//echo "<td>" . $result->getEntrances() . "</td>\n";
		//echo "</tr>\n";
		$i++;
	}
	$ReturnedCount = $i + 1;
}
?>
</table>
<?php echo "<BR><B>Total Number of Returned Itens is: $ReturnedCount !"; ?>

</BODY>
</HTML>
