<?php 
date_default_timezone_set('America/Sao_Paulo');

require_once("../metrics/conn.php");

$sql_num_users_profiles = 
	"select 
		a.Date as Date, 
		a.NumUsers as NumUsers, 
		b.NumProfiles as NumProfiles 
	from 
		(select date(USER_CREATION_DATE) as Date, COUNT(USER_ID) as NumUsers from USER 
		group by date(USER_CREATION_DATE) order by date(USER_CREATION_DATE) DESC limit 30) a, 
		(select date(PROFILE_CREATION_DATE) as Date, COUNT(PROFILE_ID) as NumProfiles from PROFILE 
		group by date(PROFILE_CREATION_DATE) order by date(PROFILE_CREATION_DATE) DESC limit 30) b
	where 
		a.Date = b.Date
	order by date(a.Date) DESC 
	limit 30";

$result = mysql_query($sql_num_users_profiles, $conn);
while ($row = mysql_fetch_array($result)) 
{
	$user_profiles[] = array(
		"Date" 			=> $row['Date'], 
		"New Users" 	=> $row['NumUsers'], 
		"New Profiles" 	=> $row['NumProfiles'] );
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
		<th>New Users</th>
		<th>New Profiles</th>
	</tr>
	<?php
		arsort($user_profiles);
		foreach ($user_profiles as $ekey => $evalue) {
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