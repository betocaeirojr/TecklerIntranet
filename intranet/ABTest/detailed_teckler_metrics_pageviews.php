<?php 
date_default_timezone_set('America/Sao_Paulo');

require_once("../metrics/conn.php");

$sql_num_pageviews_day = 
	"select 
		date(a.RefDate) as ReferenceDate,
		(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
		(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
		((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal 
	from 
		(select   
			sum(VIEWS) as SumPageviewsTeckler,  
			sum(DFP_VIEWS) as SumPageviewsDFP,  
			DAY as RefDate,  
			POST_ID as TeckID 
		from  
			TECKLER.DAILY_VIEWS  
		where  
			date(DAY) <> date('0000-00-00')  
		group by 
			POST_ID, date(DAY)  
		order by  
			date(DAY)) a
	group by
		date(a.RefDate)";

$result = mysql_query($sql_num_pageviews_day, $conn);
while ($row = mysql_fetch_array($result)) 
{
	$new_pageviews[] = array(
		"Date" 						=> $row['ReferenceDate'], 
		"Average Pageviews Teckler" => $row['AvgPageviewsTecks'],
		"Average Pageviews DFP" 	=> $row['AvgPageviewsDFP'],
		"Average Pageviews Global" 	=> $row['AvgPageviewsGlobal']
		);
}



?>


<html>
<HEAD><Title>Metrics Reports from Teckler Platform</TITlE></HEAD>
<BODY>
<h1>Metrics Gathered from Teckler Platform</h1>	

<P>Information last update at:
<?php echo date('Y-m-d H:i:s');?>
 </P>

<table border=1>
	<tr>
		<th>Date</th>
		<th>Average Pageviews Teckler</th>
		<th>Average Pageviews DFP</th>
		<th>Average Pageviews Global</th>
	</tr>
	<?php
		arsort($new_pageviews);
		foreach ($new_pageviews as $ekey => $evalue) {
			echo "<tr>";		
			foreach ($evalue as $ikey => $ivalue) {
				if ($ikey == 'Date'){
					echo "<TD align=left>";
					echo date("Y-m-d : l", strtotime($ivalue)); 
				} else {
					echo "<TD align=center>";
					echo $ivalue; 
				}
				echo "</TD>";
			}
			echo "</tr>";
		}
	?>

</table>
</BODY>
</HTML>