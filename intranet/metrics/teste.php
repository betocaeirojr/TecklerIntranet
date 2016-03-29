<?php
date_default_timezone_set('America/Sao_Paulo');
require_once "Connection.php";
require_once "Pageviews.php";
require_once "Tecks.php";
require_once "Users.php";

$Connection = new Connection();
$AudienceMetrics = new Audience($Connection);
	
$gaMetrics_RawInfo									= $AudienceMetrics->getGAMetrics();
$gaMetrics_VisitsCount_RawInfo						= $AudienceMetrics->getGAMetrics_VisitsCount();	
$gaMetrics_DaysSinceLastVisit_RawInfo				= $AudienceMetrics->getGAMetrics_DaysSinceLastVisit();	

?>


		<!-- ------------------------------------------------------------- -->
		<p>Google Analytics - Visits Count</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($gaMetrics_VisitsCount_RawInfo[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($gaMetrics_VisitsCount_RawInfo);
				?>
			</tr>
			<?php 
				asort($gaMetrics_VisitsCount_RawInfo);
				// We'll show only the first 90 results...
				
				foreach ($gaMetrics_VisitsCount_RawInfo as $ekey => $evalue) {
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