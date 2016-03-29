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
	
$pageviewsAging_RawInformation						= $AudienceMetrics->getPageviewsAging();

?>
<HTML>
	<HEAD>
		<TITLE>Metrics Dashboard</TITLE>
	</HEAD>
	<BODY>
		<H1> Teckler Metrics Dashboard</H1>
		<p> Checking Audience Metrics </p>
		<p>Pageviews Aging Information</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($pageviewsAging_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($pageviewsAging_RawInformation);
				?>
			</tr>
			<?php 
				arsort($pageviewsAging_RawInformation);
				foreach ($pageviewsAging_RawInformation as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "</tr>";
				}
			?>
		</table>


	</BODY>
</HTML>