<?php 
date_default_timezone_set('America/Sao_Paulo');

require_once("../metrics/conn.php");

$sql_num_tecks = 
	"select 
		a.Date as Date, 
		a.NumTecksPublished as NumTecksPublished, 
		b.NumTecksCreated as NumTecksCreated 
	from 
		(select count(POST_ID) as NumTecksPublished, date(PUBLISH_DATE) as Date 
			from POST group by date(PUBLISH_DATE) order by date(PUBLISH_DATE) DESC limit 30) a, 
		(select count(POST_ID) as NumTecksCreated, date(CREATION_DATE) as Date 
			from POST group by date(CREATION_DATE) order by date(CREATION_DATE) DESC limit 30) b  
	where 
		a.Date = b.Date 
	order by date(a.Date) DESC limit 30";

$result = mysql_query($sql_num_tecks, $conn);
while ($row = mysql_fetch_array($result)) 
{
	$new_tecks[] = array(
		"Date" 					=> $row['Date'], 
		"New Tecks Published" 	=> $row['NumTecksPublished'],
		"New Tecks Created" 	=> $row['NumTecksCreated']);
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
		<th>New Tecks Published <br><center>(Not Necessarily Created at the same day)</center></th>
		<th>New Tecks Created <br><center>(not Necessarily Published at the same day)</center></th>
	</tr>
	<?php
		arsort($new_tecks);
		foreach ($new_tecks as $ekey => $evalue) {
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