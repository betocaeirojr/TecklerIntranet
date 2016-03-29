<?php

require "conn_upd.php";
include "../metrics/header.php";

//echo "<PRE>";
//print_r($_POST);
//echo "</PRE>"; 

$update_solr_needed = FALSE;
$userid = $_POST['user_id'];

if (isset($_POST['user_id']) and (is_numeric($_POST['user_id']))){

	switch ($_POST['change']){
	case "lang":
		// SQL Statement for Updating USER CONFIGURATION LANG CODE
		$sql_update_user_info = "update USER_CONFIGURATION " .
					"set VALUE='" . $_POST['user_lang_code']. "' " .  
					"where (USER_ID = $userid) and (CODE='LANG')";

		//echo "DEBUG: ". $sql_update_user_info ."<BR>" ; 
		
		// SQL STATEMENT FOR Updating Tecks from the User, to follow the new lang code
		$sql_update_user_tecks_lang = 	"update POST " . 
						"set LANGUAGE_CODE='" . $_POST['user_lang_code'] . "' " . 
						"where USER_ID=$userid";

		//echo "DEBUG: " . $sql_update_user_tecks_lang . "<BR>\n";		

		break;

	case "status":
		$update_solr_needed = TRUE;
		
		// Query for changing USER Status
		$sql_update_user_info =	"update USER " . 
					"set ACTIVE='" . $_POST['user_status'] . "' " . 
					"where USER_ID=". $userid;
		
		//echo "<BR>DEBUG: ". $sql_update_user_info ."<BR>" ;
		
		// Query for SOLR removal
		$sql_tecks_of_user = "select POST_ID from POST where USER_ID=$userid";
		
		//Update Tecks from user
		$sql_update_tecks_status = "update POST set STATUS_ID=3 where USER_ID=$userid";	

		break;

	default:
		break;
	}
}
	
$result_user = mysql_query($sql_update_user_info, $conn); 
if ($result_user) {

	if ($_POST['change']=="lang"){

		// Now, update all tecks from the user to follow the new lang code
		if (mysql_query($sql_update_user_tecks_lang, $conn)){
			echo "<BR>All tecks were properly updated<BR>\n";
		} else {
			echo "<BR>There was a problem updating the teck code lang for this user. <BR> Please report the incident to our Tech Team... <BR>\n"; 
		}

        } elseif ($_POST['change'] == "status"){

		//Now, update all tecks of this userid to inactivate all tecks
		if (mysql_query($sql_update_tecks_status, $conn)){
			echo "<BR> All tecks were set to BLOCK <BR>";
			echo "<BR> Updating Search results - removing the blocked tecks<BR>";
		
			// Iterating over User Tecks, removing it from SOLR engine/index
			$result_tecks_of_user = mysql_query($sql_tecks_of_user, $conn);

			while ($row = mysql_fetch_array($result_tecks_of_user)){
				$postid = $row['POST_ID'];
				$solr_q="curl http://10.0.0.84:8983/solr/tecks/update/?commit=true -H \"Content-Type: text/xml\" -d \"<delete><query>(POST_ID:$postid)</query></delete>\"";
				//echo "<BR> DEBUG: " . $solr_q;
				exec($solr_q);
				echo "<BR>Removing POST_ID #: $postid from Search Index!\n";
			}
			echo "<BR>Search Index Updated!<BR>\n";
		} else {
			// RETURN FALSE ON UPDATING TECKS INFO
			echo "<BR>Some went wrong when trying to update teck info from User_ID: $user_id <BR>";
			
		}		
	}

	//echo "<br>\n";
	echo "User info has been updated at the Database.<BR> \n";
	echo "Teck info has been removed from the Search Engine.<BR>\n"; 

} else {
	echo "Opps. Something went wrong. Contact our Dev Team for support<BR>\n";
}


include "../metrics/footer.php";
?>
