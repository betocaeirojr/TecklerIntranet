<?php
date_default_timezone_set('America/Sao_Paulo');

require_once("conn_f.php");

mysql_select_db('FRAUD', $conn)
		or die('Could not select database; ' . mysql_error());


$sql_fraudsters_candidate_users = 
	"select 
		sum(a.TECKS_OWN_VIEWS) as SumOwnAccess, 
		a.PROFILE_ID as ProfileID
	from 
		FRAUD.OWN_ACCESS_FRAUD a, 
		TECKLER.PROFILE p 
	where  
		a.PROFILE_ID = p.PROFILE_ID 
	group by a.PROFILE_ID order by sum(a.TECKS_OWN_VIEWS) DESC";

$result= mysql_query($sql_fraudsters_candidate_users, $conn);
while ($row = mysql_fetch_array($result)) {
	$OwnAccessUserInfo[] = array(
			"Profile ID"			=> $row['ProfileID'],
			"Own Access (>10)" 		=> $row['SumOwnAccess'],
		);
}


$sql_users_accessing_own_content = 
	"select 
		date(DATE) as AccessDate, 
		PROFILE_ID as ProfileID,
		TECK_ID as TeckID, 
		TECK_TITLE as TeckTitle, 
		TECK_TOTAL_VIEWS as TotalViews, 
		TECKS_OWN_VIEWS as OwnAccess, 
		TECKS_DISCOUNTED_VIEWS as DiscountedViews  
	from OWN_ACCESS_FRAUD 
	order by date(DATE) DESC";

$result= mysql_query($sql_users_accessing_own_content, $conn);
while ($row = mysql_fetch_array($result)) {
	$OwnAccessInfo[] = array(
			"Teck Access Date"		=> $row['AccessDate'],
			"Profile ID"			=> $row['ProfileID'],
			"Teck ID"				=> $row['TeckID'],
			"Teck Title"			=> $row['TeckTitle'],
			"Teck Total Pageviews"	=> $row['TotalViews'], 
			"Own Access (>10)" 		=> $row['OwnAccess'],
			"Discounted Pageviews"  => $row['DiscountedViews'] 
		);
}

?>

<html>
	<head>
		<title> Fraud Analysis - Users Accessing its own tecks</title>
	</head>
	<body>
		<h1>Teckler Fraud Analysis System</h1>
		<h2>Users Accessing it's own content...</h2>
		<h4>Considering only more then 10 access per day on its own tecks</h4>

		<p> Info from 2013-10-22 up today...</p>
		<table border=1>
			<tr> 
				<th>Profile ID</th>
				<th>Total Own Accesses</th>
			</tr>
				<?php 
					foreach ($OwnAccessUserInfo as $ekey => $evalue) {
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

		<h2>Information about how much access is being generate per the teck author/content</h2>
		<h4>Considering only more then 10 access per day on its own tecks</h4>

		<table border=1>
			<tr> 
				<th>Access Date </th>
				<th>Profile ID</th>
				<th>Teck ID</th>
				<th>Teck Title</th>
				<th>Total Pageviews</th>
				<th>Own Accesses</th>
				<th>Discounted Pageviews</th>
			</tr>
				<?php 
					foreach ($OwnAccessInfo as $ekey => $evalue) {
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