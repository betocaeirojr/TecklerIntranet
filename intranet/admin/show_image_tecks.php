<?php
require "../metrics/conn.php";
//include "header.php";

$sql_get_image_tecks = "select p.USER_ID, p.PROFILE_ID, p.POST_ID  from POST p, USER u where ((u.USER_ID = p.USER_ID) and (TYPE='i') and (STATUS_ID=1) and (u.ACTIVE='a')) order by IS_RESTRICTED DESC";

$sql_get_image_tecks_image_info	= "select p.PROFILE_ID as ProfileID, p.POST_ID as TeckID, p.TITLE as TeckTitle " . 
				"from POST p, USER u " . 
				"where ((u.USER_ID = p.USER_ID) and (TYPE='i') and (STATUS_ID=1) and (u.ACTIVE='a')) order by IS_RESTRICTED DESC";


$sql_get_image_tecks_info = 	"select p.PROFILE_ID as ProfileID, p.POST_ID as TeckID, p.TITLE as TeckTitle, a.URL as TeckImageURL " . 
				"from POST p, USER u, ATTACHMENT a,  POST_ATTACHMENT pa " . 
				"where ((pa.ATTACHMENT_ID = a.ATTACHMENT_ID) and (pa.POST_ID = p.POST_ID) and (u.USER_ID = p.USER_ID) " . 
				"and (STATUS_ID=1) and (u.ACTIVE='a')) order by p.CREATION_DATE DESC";


?>

<h2> Check Bellow all Image Tecks </h2>

<TABLE border=1>
<TR>
<TH>Profile ID</TH> <TH>Teck ID</TH> <TH> Teck Title</TH><TH>Teck Image URL</TH>
</TR>

<?php

$result = mysql_query($sql_get_image_tecks_info, $conn);
while ($row = mysql_fetch_array($result)){
	$teck_id  = $row['TeckID'];
	echo "<TR>\n";
	echo "<TD>" . $row['ProfileID'] . "</TD>\n";
	echo "<TD><a href='../metrics/show_teck_id_content.php?teckid=$teck_id'>" . $teck_id . "</TD>\n";
	echo "<TD>" . substr($row['TeckTitle'],0,60) . "</TD>\n";
	echo "<TD>" . $row['TeckImageURL'] . "</TD>\n";
	echo "</TR>\n";
}

?>



</TABLE>

<?php
include "footer.php";
?>
