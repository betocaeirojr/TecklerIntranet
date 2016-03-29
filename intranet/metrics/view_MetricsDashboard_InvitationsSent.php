<?php

date_default_timezone_set('America/Sao_Paulo');
require_once "Connection.php";
require_once "Users.php";
require_once "../stats/MailTrackingDynamoDbStats.php";

$Connection = new Connection();
$UserMetrics = new Users($Connection);
$DynamoDB = new MailTrackingDynamoDbStats();
	
$contactImporting_RawInformation	= $UserMetrics->getContactImportingPerDay();
$contactSentInvitations_RawInformation = $DynamoDB->getInvitationSentInfo();


?>
<HTML>
	<HEAD>
		<TITLE>Metrics Dashboard</TITLE>
	</HEAD>
	<BODY>
		<H1> Teckler Metrics Dashboard</H1>
		<p> Checking Audience Metrics </p>
		<!-- ------------------------------------------------------------- -->

		<p>Contacts Imported per Day </p>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($contactImporting_RawInformation[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
					reset($contactImporting_RawInformation);
				?>
			</tr>
			<?php 
				foreach ($contactImporting_RawInformation as $ekey => $evalue) {
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


		<!-- ------------------------------------------------------------- -->

		<p>Contact Invitations Sent per Day </p>
		<table border=1>
			<tr>
				<th>Date</th>
				<th>Invitations Sent</th>
				<th>Invitations Reads</th>
				<th>Invitations Clicks</th>
			</tr>
			<?php 
				foreach ($contactSentInvitations_RawInformation as $ekey => $evalue) {
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



	</BODY>
</HTML>