<?php

date_default_timezone_set('America/Sao_Paulo');
require_once "Connection.php";
require_once "Pageviews.php";
require_once "Tecks.php";
require_once "Users.php";

$Connection = new Connection();
$AudienceMetrics = new Audience($Connection);
$TecksMetrics = new Tecks($Connection);
$UserMetrics = new Users($Connection);
	
$activeProfiles_RawInformation						= $UserMetrics->getActiveProfiles();
?>
<HTML>
	<HEAD>
		<TITLE>Metrics Dashboard</TITLE>
	</HEAD>
	<BODY>
		<H1> Teckler Metrics Dashboard</H1>
		<!-- ------------------------------------------------------------- -->
		<p>Active Profiles</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($activeProfiles_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($activeProfiles_RawInformation);
				?>
			</tr>
			<?php 
				arsort($activeProfiles_RawInformation);
				// We'll show only the first 90 results...
				$counter = 0 ;
				foreach ($activeProfiles_RawInformation as $ekey => $evalue) {
					if ($counter < 90 ){
						echo "<tr>";		
						foreach ($evalue as $ikey => $ivalue) {
							echo "<TD align=center>";
							echo $ivalue; 
							echo "</TD>";
						}
						echo "</tr>";
					}
					$counter++;
				}
			?>
		</table>


	</BODY>
</HTML>