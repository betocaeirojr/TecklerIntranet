<?php

include("MailTrackingDynamoDbStats.php");

$EmailTrackingMetrics = new MailTrackingDynamoDbStats();
$EMT_Details = $EmailTrackingMetrics->getEmailTrackingInfo();
arsort($EMT_Details);

//echo "<PRE>";
//print_r($EmailTrackingMetrics->getEmailTrackingInfo());
//echo "</PRE>";

?>
<HTML>
	<HEAD>
		<TITLE>Email Tracking Metrics</TITLE>
	</HEAD>
	<BODY>
		<H1> Email Tracking Metrics</H1>
		<P>
			<TABLE BORDER=1>
				<TR>
					<?php 
						$keys = array_keys($EMT_Details[0]);
						foreach ($keys as $key => $value) {
							echo "<th align=center>";
							echo $value;
							echo "</th>";
						}
						reset($EMT_Details);
					?>	
					<th> Last Update at </th>
			</TR>
			<?php 
				foreach ($EMT_Details as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "<TD> " . date('Y-m-d H:i:s') . " </TD>\n";
					echo "</tr>";
			}
			?>
			
			</TABLE>
		</P>
	</BODY>
</HTML>
