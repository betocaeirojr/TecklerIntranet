
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

echo date('Y-m-d H:i:s');

if (isset($_POST['pagepath_select'])) {
	$postFilter = $_POST['pagepath_select'];	
} elseif (isset($_POST['pagepath_input'])) {
	$postFilter = "PagePath == " . $_POST['pagepath_input'];
} else {
	$postFilter = null;
}

if (isset($_POST['SubmitStarting']) and ($_POST['pagepath_input']!="")) {
	$postFilter = "PagePath =@ " . $_POST['pagepath_input'];
}

// DEBUGGING ----------------------------------------------
//echo "<pre>";
//echo "[DEBUG] ---- Showing POST info....\n<br>";
//print_r($_POST); 
//echo "</pre>";
// DEBUGGING ----------------------------------------------
?>

</P>
 
<form method="post" action="detailed_ga_metrics_per_page.php">
	<table border=1>
		<tr>
			<td colspan=2> Please select the specific page to track: </td>
			<td>Page</td>
			<td><select name="pagepath_select" id="pagepath_select" onchange=ActiveFilterPPI()>
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
					<option value = "PagePath =@ /pt/search/do_search">/pt/search/do_search</option>
					<option value = "PagePath =@ /en/search/do_search">/en/search/do_search</option>
					<option value = "PagePath =~ \/..\/search/do_search">All Search Pages</option>
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
		<tr>
			<td colspan=2> Or enter a page path to query: </td>
			<td>Page Path</td>
			<td><input type='text' name="pagepath_input" id="pagepath_input" onchange=ActiveFilterPPS() value=
				<?php echo (isset($_POST['pagepath_input']) ? "'" . $_POST['pagepath_input'] . "'" : "" 	); ?>
				></input></td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type="submit" value="Cancel" name="Cancel"></input></td>
			<td colspan=2 align=center><input type="submit" value="Go and Get It - This specific Page!" name="SubmitSpecifc"></input> &nbsp;
			<input type="submit" value="Go and Get It - Pages Starting with this string!" name="SubmitStarting"></input></td>
		</tr>
	</table>
</form>
<hr>

<?php

if (($postFilter==null) || ($postFilter == 'null')) {
	echo "<p> Showing Consolidated information <B>for all site</B> !</p>\n"; 
} else {
	echo "<p>";
	switch ($postFilter) {
		case 'PagePath != /pt/home && PagePath != /pt/about && 
		PagePath != /pt/user/new_user && PagePath != /pt/faq && 
		PagePath != /pt/terms && PagePath != /pt/privacy-policy && 
		PagePath != /pt/search/do_search && PagePath =~ ^\/pt':
			echo "Showing information for <B>All Tecks and Profiles Pages in Portuguese</B>.<BR>\n";
			break;

		case 'PagePath != /en/home && PagePath != /en/about && 
		PagePath != /en/user/new_user && PagePath != /en/faq && 
		PagePath != /en/terms && PagePath != /en/privacy-policy && 
		PagePath != /en/search/do_search && PagePath =~ ^\/en':
			echo "Showing information for <B>All Tecks and Profiles Pages in English</B>.<BR>\n";
			break;

		case 'PagePath =~ ^\/pt(\/[^\/]+){2,}$ && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$':
			echo "Showing information for <B>All Tecks Pages (ONLY TECKS) in Portuguese</B>.<BR>\n";
			break;

		case 'PagePath =~ ^\/en(\/[^\/]+){2,}$ && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$':
			echo "Showing information for <B>All Tecks Pages (ONLY TECKS) in English</B>.<BR>\n";
			break;

		case 'PagePath =~ ^(\/[^\/]+){3,} && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$':
			echo "Showing information for <B>All Tecks Pages in all languages</B>.<BR>\n";
			break;

		
		default:
			echo "Showing information considering the Page : <B> $postFilter </B>. <BR>\n";
			break;
	}
	echo "</p>\n";
}

?>

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
		<!--th>Average Time On Site</th>
		<th>Average Time On Page</th-->
		<th># Pages / Visits</th>
	</tr>

<?php

$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date');
$metrics = array('pageviews','uniquePageviews','bounces', 'visits', 'visitors', 'newVisits', 'percentNewVisits',/*'avgTimeOnSite', 'avgTimeOnPage',*/ 'entranceBounceRate');
$sort = 'date';
$timeInterval = '120';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d');
$filter = $postFilter;

$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, ($filter!='null'? $filter : null ), $fromDate, $toDate, 1, $timeInterval);

foreach($ga->getResults() as $key => $result){
	echo "<tr>\n";
	$repDate[$key] 					= $result->getDate();
	$repPageview[$key] 				= $result->getPageviews();	
	$repVisits[$key]				= $result->getVisits();
	$repBounce[$key] 				= $result->getBounces();
	$repPercBounce[$key] 			= ( $repVisits[$key] != 0 ? ($repBounce[$key] / $repVisits[$key]) : 0 );
	$repVisitors[$key] 				= $result->getVisitors();
	$repNewVisits[$key] 			= $result->getNewVisits();
	$repPercentNewVisits[$key] 		= $result->getPercentNewVisits();
	$repPercentReturningVisits[$key] = (100 - $repPercentNewVisits[$key]);
	$repPagesPerVisit[$key] 		= ( $repVisits[$key] != 0 ? $repPageview[$key] / $repVisits[$key] : 0 ) ;


	echo "<td>" . date("Y-m-d",strtotime($repDate[$key])) 	. "</td>\n";
	echo "<td>" . number_format($repPageview[$key],0,'.', ',') 						. "</td>\n";
	echo "<td>"	. number_format($repVisits[$key], 0, '.', ',')	 					. "</td>\n";
	echo "<td>" . number_format($repBounce[$key] ,0, '.', ',') 	 				. "</td>\n";
	echo "<td>" . number_format((round($repPercBounce[$key],4)*100), 2, '.', ',') 	. "% </td>\n";
	echo "<td>" . number_format($repVisitors[$key] ,0, '.', ',')					. "</td>\n";
	echo "<td>" . number_format($repNewVisits[$key] ,0, '.', ',')					. "</td>\n";
	echo "<td>" . number_format(round($repPercentNewVisits[$key],2) ,2, '.', ',')	. "% </td>\n";
	echo "<td>" . number_format(round($repPercentReturningVisits[$key],2),2, '.', ',')	. "% </td>\n";
	//echo "<td>" . $repAvgTimeOnSite[$key]					. "</td>\n";
	//echo "<td>" . $repAvgTimeOnPage[$key]					. "</td>\n";
	echo "<td>" . number_format(round($repPagesPerVisit[$key],2),2, '.', ',') 			. "</td>";
	//echo "<td>" . round($repEntranceBounceRate[$key],2) 	. "</td>";
	echo "</tr>\n";

	if ($key == 0) {
		$infodate 				= date("Y-m-d",strtotime($repDate[$key]));
		$infoPV 				= $repPageview[$key];
		$infoVisits 			= $repVisits[$key];
		$infoPercBounce 		= round($repPercBounce[$key],4)*100;
		$infoPercNewVisitors 	= round($repPercentNewVisits[$key],2);
		$infoPercRetVisitors 	= round($repPercentReturningVisits[$key],2);
	} else {
		$infodate 				.= "," . date("Y-m-d",strtotime($repDate[$key]));
		$infoPV 				.= "," . $repPageview[$key];
		$infoVisits 			.= "," . $repVisits[$key];
		$infoPercBounce 		.= "," .round($repPercBounce[$key],4)*100;
		$infoPercNewVisitors 	.= "," .round($repPercentNewVisits[$key],2);
		$infoPercRetVisitors 	.= "," .round($repPercentReturningVisits[$key],2);
	}
}

?>
</table>


<input type='hidden' id="date-axis-X" value="<?php echo $infodate; ?>">
<input type='hidden' id="pv-axis-Y" value="<?php echo $infoPV; ?>">
<input type='hidden' id="visits-axis-Y" value="<?php echo $infoVisits; ?>">
<input type='hidden' id="percbounce-axis-Y" value="<?php echo $infoPercBounce; ?>">
<input type='hidden' id="percnewvisitors-axis-Y" value="<?php echo $infoPercNewVisitors; ?>">
<input type='hidden' id="percretvisitors-axis-Y" value="<?php echo $infoPercRetVisitors; ?>">

<div id="container_pageviews" style="width:100%; height:400px;"></div>
<div id="container_visits" style="width:100%; height:400px;"></div>
<div id="container_percbounce" style="width:100%; height:400px;"></div>
<div id="container_new-x-ret" style="width:100%; height:400px;"></div>



<script>

function ActiveFilterPPS()
{
	var PPS=document.getElementById("pagepath_select");
	PPS.value="";
}

function ActiveFilterPPI()
{
	var PPI=document.getElementById("pagepath_input");
	PPI.value="";
}

$(function () { 

	// Treating Pageviews Graphic
	var dateValues = $('#date-axis-X').val();
	var xAxis = dateValues.split(",");
	
	var pageviewsValues = $('#pv-axis-Y').val();
	var yAxis = pageviewsValues.split(',');
	var merge = [];
	for (var i=0; i < xAxis.length; i++) { 
		var dateparts = xAxis[i].split("-");

		var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
		var pageviews = parseInt(yAxis[i], 10);
		merge.push([date, pageviews]);
	}

    $('#container_pageviews').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Pageviews'
        },
        xAxis: {
        	type : 'datetime'
        },
        yAxis: {
            title: {
                text: 'Pageviews'
            }
        },
        series: [{
            name: 'Pageviews',
            data: merge
        }]
    });	

	
	// visits
	var visitsValues = $('#visits-axis-Y').val();
	var xAxis = dateValues.split(",");
	var yAxis = visitsValues.split(',');
	var merge = [];
	for (var i=0; i < xAxis.length; i++) { 
		var dateparts = xAxis[i].split("-");
		var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
		var visits = parseInt(yAxis[i], 10);
		merge.push([date, visits]);
	}
	

	$('#container_visits').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Visits'
        },
        xAxis: {
        	type : 'datetime'
        },
        yAxis: {
            title: {
                text: 'Visits'
            }
        },
        series: [{
            name: 'Visits',
            data: merge
        }]
    });

	// Bounce (%)
	var percbounceValues = $('#percbounce-axis-Y').val();
	var xAxis = dateValues.split(",");
	var yAxis = percbounceValues.split(',');
	var merge = [];
	for (var i=0; i < xAxis.length; i++) { 
		var dateparts = xAxis[i].split("-");
		var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
		var PercBounce = parseInt(yAxis[i], 10);
		merge.push([date, PercBounce]);
	}
	

	$('#container_percbounce').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Bounce (%)'
        },
        xAxis: {
        	type : 'datetime'
        },
        yAxis: {
            title: {
                text: 'Bounce (%)'
            }
        },
        series: [{
            name: 'Bounce (%)',
            data: merge
        }]
    });

	// New vs Returning Customers
    var percnewvisitorsValues = $('#percnewvisitors-axis-Y').val();
	var xAxis = dateValues.split(",");
	var yAxis = percnewvisitorsValues.split(',');
	var merge_new = [];
	for (var i=0; i < xAxis.length; i++) { 
		var dateparts = xAxis[i].split("-");
		var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
		var PercNewVisitors = parseInt(yAxis[i], 10);
		merge_new.push([date, PercNewVisitors]);
	}

	var percretvisitorsValues = $('#percretvisitors-axis-Y').val();
	var xAxis = dateValues.split(",");
	var yAxis = percretvisitorsValues.split(',');
	var merge_ret = [];
	for (var i=0; i < xAxis.length; i++) { 
		var dateparts = xAxis[i].split("-");
		var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
		var PercRetVisitors = parseInt(yAxis[i], 10);
		merge_ret.push([date, PercRetVisitors]);
	}

	$('#container_new-x-ret').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Visitors - New vs Returning (%)'
        },
        xAxis: {
        	type : 'datetime'
        },
        yAxis: {
            title: {
                text: 'Visitors - New vs Returning (%)'
            }
        },
        series: [{
            name: 'New Visitors (%)',
            data: merge_new}, {
            name: 'Returning Visitors (%)',
            data: merge_ret
        }]
    });



});
</script>

</BODY>
</HTML>