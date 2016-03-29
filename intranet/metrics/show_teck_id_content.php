<?php
	require "conn.php";
	include "../includes/header.php";
		
	$teck_id = $_GET['teckid'];
?>
<H2>Contents for Teck ID : <?php $teck_id;?> </H2>

<?php

$sql_get_teck_content = "select * from POST where POST_ID=$teck_id"; 
$sql_get_teck_attachments = "select A.URL, A.ADITIONAL_TEXT from POST_ATTACHMENT PA, ATTACHMENT A where(PA.ATTACHMENT_ID = A.ATTACHMENT_ID) and PA.POST_ID=$teck_id"; 

//echo $sql_get_teck_content;

// Starting processing

// ------------------------------------------------------
// User Information
// ------------------------------------------------------

echo "<table border=1>\n";
echo "<tr><th>Content Key</th><th>Content Value</tr></tr>\n";

$result = mysql_query($sql_get_teck_content, $conn);

$result_att = mysql_query($sql_get_teck_attachments, $conn);

if (mysql_num_rows($result) == 0) {
	echo " <br>\n";
	echo " Opps.. Something went wrong. Contact you administrator! \n";
} else 
{
	while ($row = mysql_fetch_array($result)) {
		$langcode = $row['LANGUAGE_CODE'];
		echo "<TR>\n <TD>User ID</TD>\n<TD> &nbsp" . $row['USER_ID'] . "</TD>\n</TR>\n"; 
		echo "<TR>\n <TD>Profile ID</TD>\n<TD> &nbsp" . $row['PROFILE_ID'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Title</TD>\n<TD> &nbsp" . $row['TITLE'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Teck Content</TD>\n<TD> &nbsp" . $row['TEXT'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Teck Attachments </TD>\n<TD>\n";
		if (mysql_num_rows($result_att) == 0){
			echo "&nbsp - ";
		} else {
			while ($row_att = mysql_fetch_array($result_att)){
				$att_url = $row_att['URL'];
				$att_txt = $row_att['ADITIONAL_TEXT'];
				echo "<CENTER><img src='$att_url'/> <BR> $att_txt </CENTER><BR>\n";
			}

		}
		echo "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Creation Date</TD>\n<TD> &nbsp" . $row['CREATION_DATE'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Page Views</TD>\n<TD> &nbsp" . $row['PAGE_VIEWS'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Publish Date</TD>\n<TD> &nbsp" . $row['PUBLISH_DATE'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Status ID</TD>\n<TD> &nbsp" . $row['STATUS_ID'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Is Restricted</TD>\n<TD> &nbsp" . $row['IS_RESTRICTED'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Type</TD>\n<TD> &nbsp" . $row['TYPE'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Language Code</TD>\n<TD> &nbsp" . $row['LANGUAGE_CODE'] . "</TD>\n</TR>\n";
		echo "<TR>\n <TD>Teck Portal URL</TD><TD><a href='http://www.teckler.com/index.php/$langcode/post/open_post/$teck_id' target=_blank>Check Here</a></TD>\n";
	}
}
echo "</table>\n";
echo "<P>Legend: <BR>";
echo ":: Is Restricted: 0 - Not Restricted;  1 - Restricted. <BR> "; 
echo ":: Type: i - Image; a - Audio; v - Video; t - Text <BR> ";
echo ":: Status ID: 1 - Publishe; 2 - Draft; 3 - Fraud <BR>";
echo "</p>";


echo "<HR>";

include "../includes/footer.php";
?>
