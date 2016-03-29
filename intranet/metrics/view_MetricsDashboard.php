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
	
$pageviewsPerDay_RawInformation 					= $AudienceMetrics->getPageviewsPerTeckPerDay();
$pageviewsPerMonth_RawInformation 					= $AudienceMetrics->getPageviewsPerTeckPerMonth();
$pageviewsPerTeckAccumulatedGlobal_RawInformation 	= $AudienceMetrics->getPageviewsPerTeckAccumulatedGlobal();
$pageviewsAging_RawInformation						= $AudienceMetrics->getPageviewsAging();
$gaMetrics_RawInfo									= $AudienceMetrics->getGAMetrics();
$gaMetrics_VisitsCount_RawInfo						= $AudienceMetrics->getGAMetrics_VisitsCount();	
$gaMetrics_DaysSinceLastVisit_RawInfo				= $AudienceMetrics->getGAMetrics_DaysSinceLastVisit();	

$newTecksPerDay_RawInformation						= $TecksMetrics->getNewTecksPerDay();
$newTecksPerMonth_RawInformation					= $TecksMetrics->getNewTecksPerMonth();

$newUsersPerDay_RawInformation						= $UserMetrics->getNewUsersPerDay();
$newUsersPerMonth_RawInformation					= $UserMetrics->getNewUsersPerMonth();
$newProfilesPerDay_RawInformation					= $UserMetrics->getNewProfilesPerDay();
$newProfilesPerMonth_RawInformation					= $UserMetrics->getNewProfilesPerMonth();
$activeProfiles_RawInformation						= $UserMetrics->getActiveProfiles();
$loggedUsersPerDay_RawInformation					= $UserMetrics->getLoggedUsersPerDay();
$totalVisitorsPerDay_RawInformation					= $UserMetrics->getTotalVisitors();
$totalVisitorsNewUserPerDay_RawInformation			= $UserMetrics->getTotalVisitors('NewUser');
$totalVisitorsPerDay_RawInformation					= $UserMetrics->getTotalVisitors('');
$percLoggedPerVisitors_Visits_RawInformation		= $UserMetrics->getPercLoggedPerTotalVisitors();
$bounceRateNewUser_RawInformation					= $UserMetrics->getGAMetricsNewUserPageBounceRate();
$conversRateNewUser_RawInformation					= $UserMetrics->getNewUserPageConversionRate();
?>
<HTML>
	<HEAD>
		<TITLE>Metrics Dashboard</TITLE>
	</HEAD>
	<BODY>
		<H1> Teckler Metrics Dashboard</H1>
		<p> Checking Audience Metrics </p>
		<p> Pageviews Per Teck per Day</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($pageviewsPerDay_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($pageviewsPerDay_RawInformation);
				?>
			</tr>
			<?php 
				arsort($pageviewsPerDay_RawInformation);
				foreach ($pageviewsPerDay_RawInformation as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->

		<p> Pageviews Per Teck per Month</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($pageviewsPerMonth_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($pageviewsPerMonth_RawInformation);
				?>
			</tr>
			<?php 
				arsort($pageviewsPerMonth_RawInformation);
				foreach ($pageviewsPerMonth_RawInformation as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo ($ikey == 'ReferenceDate' ? date("Y-m", strtotime($ivalue)) : $ivalue); 
						echo "</TD>";
					}
					echo "</tr>";
				}
			?>
		</table>

		<!-- ------------------------------------------------------------- -->

		<p>Overall Accumulated Pageviews Per Teck</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($pageviewsPerTeckAccumulatedGlobal_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($pageviewsPerTeckAccumulatedGlobal_RawInformation);
				?>
			</tr>
			<?php 
				arsort($pageviewsPerTeckAccumulatedGlobal_RawInformation);
				foreach ($pageviewsPerTeckAccumulatedGlobal_RawInformation as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->

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

		<!-- ------------------------------------------------------------- -->

		<p>Google Analytics - New vs Returning and Pages Per Visit</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($gaMetrics_RawInfo[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($gaMetrics_RawInfo);
				?>
			</tr>
			<?php 
				arsort($gaMetrics_RawInfo);
				foreach ($gaMetrics_RawInfo as $ekey => $evalue) {
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
				$counter = 0 ;
				foreach ($gaMetrics_VisitsCount_RawInfo as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->
		<p>Google Analytics - Days Since Last Visit</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($gaMetrics_DaysSinceLastVisit_RawInfo[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($gaMetrics_DaysSinceLastVisit_RawInfo);
				?>
			</tr>
			<?php 
				asort($gaMetrics_DaysSinceLastVisit_RawInfo);
				// We'll show only the first 90 results...
				$counter = 0 ;
				foreach ($gaMetrics_DaysSinceLastVisit_RawInfo as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->
		<p>New Tecks Per Day</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($newTecksPerDay_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($newTecksPerDay_RawInformation);
				?>
			</tr>
			<?php 
				arsort($newTecksPerDay_RawInformation);
				foreach ($newTecksPerDay_RawInformation as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->
		<p>New Tecks Per Month</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($newTecksPerMonth_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($newTecksPerMonth_RawInformation);
				?>
			</tr>
			<?php 
				arsort($newTecksPerMonth_RawInformation);
				foreach ($newTecksPerMonth_RawInformation as $ekey => $evalue) {
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


		<!-- ------------------------------------------------------------- -->
		<p>New Profiles Per Day</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($newProfilesPerDay_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($newProfilesPerDay_RawInformation);
				?>
			</tr>
			<?php 
				arsort($newProfilesPerDay_RawInformation);
				foreach ($newProfilesPerDay_RawInformation as $ekey => $evalue) {
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


		<!-- ------------------------------------------------------------- -->
		<p>New Profiles Per Month</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($newProfilesPerMonth_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($newProfilesPerMonth_RawInformation);
				?>
			</tr>
			<?php 
				arsort($newProfilesPerMonth_RawInformation);
				// We'll show only the first 90 results...
				$counter = 0 ;
				foreach ($newProfilesPerMonth_RawInformation as $ekey => $evalue) {
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


		<!-- ------------------------------------------------------------- -->
		<p>Logged Users Per Day</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($loggedUsersPerDay_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($loggedUsersPerDay_RawInformation);
				?>
			</tr>
			<?php 
				arsort($loggedUsersPerDay_RawInformation);
				foreach ($loggedUsersPerDay_RawInformation as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->
		<p>Total Visitors Per Day</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($totalVisitorsPerDay_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($totalVisitorsPerDay_RawInformation);
				?>
			</tr>
			<?php 
				arsort($totalVisitorsPerDay_RawInformation);
				foreach ($totalVisitorsPerDay_RawInformation as $ekey => $evalue) {
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


		<!-- ------------------------------------------------------------- -->
		<p>Total Visitors at New User Pages Per Day</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($totalVisitorsNewUserPerDay_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($totalVisitorsNewUserPerDay_RawInformation);
				?>
			</tr>
			<?php 
				arsort($totalVisitorsNewUserPerDay_RawInformation);
				foreach ($totalVisitorsNewUserPerDay_RawInformation as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->
		<p>% Logged Users vs Visitors and Visits</p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($percLoggedPerVisitors_Visits_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($percLoggedPerVisitors_Visits_RawInformation);
				?>
			</tr>
			<?php 
				arsort($percLoggedPerVisitors_Visits_RawInformation);
				foreach ($percLoggedPerVisitors_Visits_RawInformation as $ekey => $evalue) {
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

		<!-- ------------------------------------------------------------- -->
		<p>% Bounce Rate - New User Page </p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($bounceRateNewUser_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($bounceRateNewUser_RawInformation);
				?>
			</tr>
			<?php 
				arsort($bounceRateNewUser_RawInformation);
				foreach ($bounceRateNewUser_RawInformation as $ekey => $evalue) {
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


		<!-- ------------------------------------------------------------- -->
		<p>% Conversion rate - New User Page </p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($conversRateNewUser_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($conversRateNewUser_RawInformation);
				?>
			</tr>
			<?php 
				arsort($conversRateNewUser_RawInformation);
				foreach ($conversRateNewUser_RawInformation as $ekey => $evalue) {
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