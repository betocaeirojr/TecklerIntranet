<?php
date_default_timezone_set("America/Sao_Paulo");
require_once("../conn.php");

$sql_profiles_higher_pv_per_teck = 
	"select 
		sum(pt.PAGE_VIEWS) as SumPageviews, 
		count(pt.POST_ID) as NumTecks,
		sum(pt.PAGE_VIEWS) / count(pt.POST_ID) as AvgPageviewsPerTeck,
		pt.PROFILE_ID as ProfileID, 
		pr.SIGNATURE as ProfileSignature,
		pt.USER_ID as UserID
	from 
		POST pt, 
		USER_PROFILE up,
		PROFILE pr
	where 
		pt.USER_ID not in 
				(select u.USER_ID from USER u  where u.IS_TRICKSTER = TRUE) and 
		pt.PROFILE_ID = up.PROFILE_ID and 
		pr.PROFILE_ID = up.PROFILE_ID 
	group by 
		pt.PROFILE_ID 
	order by 3 DESC 
	limit 1000";


$result= mysql_query($sql_profiles_higher_pv_per_teck, $conn);
while ($row = mysql_fetch_array($result)) {
	$avgPageviewsPerTeck[] = array(
			"Profile Signature" 	=> $row['ProfileSignature'], 
			"Profile ID"			=> $row['ProfileID'],
			"User ID"				=> $row['UserID'],
			"Number of Tecks"		=> $row['NumTecks'], 
			"Total of Pageviews" 	=> $row['SumPageviews'],
			"Avg Pageviews Per Teck"=> $row['AvgPageviewsPerTeck'] 
		);
}



?>
<html>
	<head>
		<title>
			Fraud Analysis - Profiles with the higher average PV/Teck
		</title>
	</head>
	<body>
		<h1>Profiles with the Higher Average Pageviews Per Teck</h1>
		<h2>On a Daily Basis</h2>
			<table border=1>
				<tr>
					<?php
						$keys = array_keys($avgPageviewsPerTeck[0]);
						foreach ($keys as $key => $value) {
							echo "<th align=center>";
							echo $value;
							echo "</th>";
						}
						reset($avgPageviewsPerTeck);
					?>
				</tr>
				<?php 
					foreach ($avgPageviewsPerTeck as $ekey => $evalue) {
						echo "<tr>";		
						foreach ($evalue as $ikey => $ivalue) {
							echo "<TD align=center>";
							if ($ikey == 'Date') {
								echo date("Y-m-d", strtotime($ivalue)); 
							} else {
								echo $ivalue;
							}
							echo "</TD>";
						}
						echo "</tr>";
					}
				?>
			</table>
	</body>
</html>