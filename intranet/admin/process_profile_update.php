<?php
require "../metrics/conn.php";
include "../metrics/header.php";
 
if (isset($_POST['profile_id']) and (is_numeric($_POST['profile_id']))){

	$sql_search_profile_info = "select u.EMAIL as ProfileUserEmail, u.USER_ID as ProfileUserID, u.LOGIN as ProfileUserLogin, u.USER_NAME as ProfileUserName, u.ACTIVE as ProfileUserIsActive ". 
				"from USER u, USER_PROFILE up ". 
				"where (u.USER_ID = up.USER_ID) and (up.PROFILE_ID=" . $_POST['profile_id']. ")";

	$result = mysql_query($sql_search_profile_info, $conn); 

	echo "<TABLE border=1>\n";
	echo "<TR><TH>Profile ID</TH><TH>User ID</TH><TH>User Name</TH><TH>User E-mail</TH><TH>Is Active (A-active/I-inactive)</TH></TR>\n";
	echo "<TR>\n";
	
	$UserID = "";

	if (mysql_num_rows($result) == 0) {
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) {
			echo "<TD>" . $_POST['profile_id']		. "</TD>\n";
			echo "<TD>" . $row['ProfileUserID']		. "</TD>\n";
			echo "<TD>" . $row['ProfileUserName']		. "</TD>\n";
			echo "<TD>" . $row['ProfileUserEmail']		. "</TD>\n"; 
			echo "<TD><CENTER>" . $row['ProfileUserIsActive']	. "</CENTER></TD>\n";
			$UserID = trim($row['ProfileUserID']);
		}
	}

	echo "</TR>";
	echo "</TABLE>";

	// Fecth all profiles from this User
	$sql_search_user_profiles = "select p.PROFILE_ID as ProfileID, date(p.PROFILE_CREATION_DATE) as ProfileCreationDate,  p.SIGNATURE as ProfileSignature from PROFILE p, USER_PROFILE up where (p.PROFILE_ID = up.PROFILE_ID) and (up.USER_ID=$UserID)";

	echo "<p>Below you can find all the profiles of this User ID $UserID... </p>\n";
	
	//echo $sql_search_user_profiles;

	$result = mysql_query($sql_search_user_profiles, $conn);
	
	echo "<TABLE border=1>\n";
        echo "<TR><TH>Profile ID</TH><TH>Profile Signature </TH><TH>Profile Creation Date</TH></TR>\n";
        echo "<TR>\n";
        if (mysql_num_rows($result) == 0) {
                echo " <br>\n";
               echo " Opps.. Somethint went wrong. Contact you administrator! \n";
        } else
        {
                while ($row_up = mysql_fetch_array($result)) {
                        echo "<TR>\n";
			echo "<TD>" . $row_up['ProfileID']             . "</TD>\n";
                        echo "<TD>" . $row_up['ProfileSignature']      . "</TD>\n";
                        echo "<TD>" . $row_up['ProfileCreationDate']   . "</TD>\n";
			echo "</TR>\n";
                }
        }

        echo "</TR>";
        echo "</TABLE>";











}
include "../metrics/footer.php";
?>
