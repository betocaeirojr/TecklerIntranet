<html>
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
echo date('Y-m-d H:i:s');

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
		<th>Average Time On Site (seconds)</th>
		<th># Pages / Visit</th>
		<th>Entrance Bounce Rate</th>
	</tr>

<?php
require ("gapi-1.3/gapi.class.php");

$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date');
$metrics = array('pageviews','uniquePageviews','bounces', 'visits', 'visitors', 'newVisits', 'percentNewVisits','avgTimeOnSite','entranceBounceRate');
$sort = '-date';
$timeInterval = '120';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d');


$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, $timeInterval);

foreach($ga->getResults() as $key => $result){
	echo "<tr>\n";
	$repDate[$key] 					= $result->getDate();
	$repPageview[$key] 				= $result->getPageviews();	
	$repVisits[$key]				= $result->getVisits();
	$repBounce[$key] 				= $result->getBounces();
	$repPercBounce[$key] 			= ( $repVisits[$key] != 0 ? ($repBounce[$key] / $repVisits[$key]): 0);
	$repVisitors[$key] 				= $result->getVisitors();
	$repNewVisits[$key] 			= $result->getNewVisits();
	$repPercentNewVisits[$key] 		= $result->getPercentNewVisits();
	$repPercentReturningVisits[$key] = (100 - $repPercentNewVisits[$key]);
	$repAvgTimeOnSite[$key] 		= $result->getAvgTimeOnSite();
	$repPagesPerVisit[$key] 		= ( $repVisits[$key] != 0 ? $repPageview[$key] / $repVisits[$key] : 0 ) ;
	$repEntranceBounceRate[$key] 	= $result->getEntranceBounceRate();



	echo "<td>" . date("Y-m-d",strtotime($repDate[$key])) 								. "</td>\n";
	echo "<td>" . number_format($repPageview[$key],0,'.', ',') 							. "</td>\n";
	echo "<td>"	. number_format($repVisits[$key],0,'.', ',')	 						. "</td>\n";
	echo "<td>" . number_format($repBounce[$key],0,'.', ',') 	 						. "</td>\n";
	echo "<td>" . number_format((round($repPercBounce[$key],4)*100),2,'.', ',') 		. "% </td>\n";
	echo "<td>" . number_format($repVisitors[$key],0,'.', ',')							. "</td>\n";
	echo "<td>" . number_format($repNewVisits[$key],0,'.', ',')							. "</td>\n";
	echo "<td>" . number_format(round($repPercentNewVisits[$key],2),2,'.', ',')			. "% </td>\n";
	echo "<td>" . number_format(round($repPercentReturningVisits[$key],2),2,'.', ',')	. "% </td>\n";
	echo "<td>" . number_format($repAvgTimeOnSite[$key],2,'.', ',')						. "</td>\n";
	echo "<td>" . number_format(round($repPagesPerVisit[$key],2),2,'.', ',') 			. "</td>";
	echo "<td>" . number_format(round($repEntranceBounceRate[$key],2),2,'.', ',') 		. "</td>";
	echo "</tr>\n";

	if ($key == 0) {
		$infodate 				= date("Y-m-d",strtotime($repDate[$key]));
		$infoPV 				= $repPageview[$key];
		$infoVisits 			= $repVisits[$key];
		$infoVisitors 			= $repVisitors[$key];
		$infoPercBounce 		= round($repPercBounce[$key],4)*100;
		$infoPercNewVisitors 	= round($repPercentNewVisits[$key],2);
		$infoPercRetVisitors 	= round($repPercentReturningVisits[$key],2);
		$infoPagesPerVisit 		= round($repPagesPerVisit[$key],2) * 100;

	} else {
		$infodate 				.= "," . date("Y-m-d",strtotime($repDate[$key]));
		$infoPV 				.= "," . $repPageview[$key];
		$infoVisits 			.= "," . $repVisits[$key];
		$infoVisitors 			.= "," . $repVisitors[$key];
		$infoPercBounce 		.= "," .round($repPercBounce[$key],4)*100;
		$infoPercNewVisitors 	.= "," .round($repPercentNewVisits[$key],2);
		$infoPercRetVisitors 	.= "," .round($repPercentReturningVisits[$key],2);
		$infoPagesPerVisit 		.= "," . round($repPagesPerVisit[$key],2) * 100;
	}


}
?>
</table>

<input type='hidden' id="date-axis-X" value="<?php echo $infodate; ?>">
<input type='hidden' id="pv-axis-Y" value="<?php echo $infoPV; ?>">
<input type='hidden' id="visits-axis-Y" value="<?php echo $infoVisits; ?>">
<input type='hidden' id="visitors-axis-Y" value="<?php echo $infoVisitors; ?>">
<input type='hidden' id="percbounce-axis-Y" value="<?php echo $infoPercBounce; ?>">
<input type='hidden' id="percnewvisitors-axis-Y" value="<?php echo $infoPercNewVisitors; ?>">
<input type='hidden' id="percretvisitors-axis-Y" value="<?php echo $infoPercRetVisitors; ?>">
<input type='hidden' id="pagespervisit-axis-Y" value="<?php echo $infoPagesPerVisit; ?>">

<div id="container_pageviews" style="width:100%; height:400px;"></div>
<div id="container_visits" style="width:100%; height:400px;"></div>
<div id="container_visitors" style="width:100%; height:400px;"></div>
<div id="container_percbounce" style="width:100%; height:400px;"></div>
<div id="container_new-x-ret" style="width:100%; height:400px;"></div>
<div id="container_pagespervisit" style="width:100%; height:400px;"></div>

<script>

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
		
		$(document).ready(function ()
		{
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
		});
	    	

	// Treating Visits Graphic
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

	// Treating Visitors Graphic
		var visitorsValues = $('#visitors-axis-Y').val();
		var xAxis = dateValues.split(",");
		var yAxis = visitorsValues.split(',');
		var merge = [];
		for (var i=0; i < xAxis.length; i++) { 
			var dateparts = xAxis[i].split("-");
			var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
			var visitors = parseInt(yAxis[i], 10);
			merge.push([date, visitors]);
		}
		$('#container_visitors').highcharts({
	        chart: {
	            type: 'line'
	        },
	        title: {
	            text: 'Visitors'
	        },
	        xAxis: {
	        	type : 'datetime'
	        },
	        yAxis: {
	            title: {
	                text: 'Visitors'
	            }
	        },
	        series: [{
	            name: 'Visitors',
	            data: merge
	        }]
	    });

	// Treating Bounce (%) Graphic
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

	// Treating New vs Returning Customers Graphics
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

	// Treating Pages Per Visit Graphic
		var pagesPerVisitValues = $('#pagespervisit-axis-Y').val();
		var xAxis = dateValues.split(",");
		var yAxis = pagesPerVisitValues.split(',');
		var merge = [];
		for (var i=0; i < xAxis.length; i++) { 
			var dateparts = xAxis[i].split("-");
			var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
			var pagespervisit = parseInt(yAxis[i], 10);
			merge.push([date, pagespervisit]);
		}
		$('#container_pagespervisit').highcharts({
	        chart: {
	            type: 'line'
	        },
	        title: {
	            text: '# Pages Per Visit (*100)'
	        },
	        xAxis: {
	        	type : 'datetime'
	        },
	        yAxis: {
	            title: {
	                text: '# Pages Per Visit (*100)'
	            }
	        },
	        series: [{
	            name: '# Pages Per Visit (*100)',
	            data: merge
	        }]
	    });


});
</script>


</BODY>
</HTML>