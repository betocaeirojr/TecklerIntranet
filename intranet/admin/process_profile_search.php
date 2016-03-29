<?php
require "../metrics/conn.php";
include "../includes/header.php";
 
//echo "<PRE>";
//print_r($_POST);
//echo "</PRE>";

if ( ( isset($_POST['profile_id']) and is_numeric($_POST['profile_id'])) or 
     ( isset($_POST['profile_name']) and is_string($_POST['profile_name']))  ){

        if (isset($_POST['profile_id'])){
		$sql_search_profile_info = "select u.EMAIL as ProfileUserEmail, u.USER_ID as ProfileUserID, u.LOGIN as ProfileUserLogin, u.USER_NAME as ProfileUserName, u.ACTIVE as ProfileUserIsActive ". 
				"from USER u, USER_PROFILE up ". 
				"where (u.USER_ID = up.USER_ID) and (up.PROFILE_ID=" . $_POST['profile_id']. ")";
	}
	if (isset($_POST['profile_name'])) {
		$sql_search_profile_info = "select p.PROFILE_ID as ProfileID, u.EMAIL as ProfileUserEmail, u.USER_ID as ProfileUserID, u.LOGIN as ProfileUserLogin, u.USER_NAME as ProfileUserName, u.ACTIVE as ProfileUserIsActive ".
				"from USER u, USER_PROFILE up, PROFILE p " . 
				"where (u.USER_ID = up.USER_ID) and (up.PROFILE_ID = p.PROFILE_ID) and (p.SIGNATURE ='" . trim($_POST['profile_name']) . "')";

	}

	$result = mysql_query($sql_search_profile_info, $conn); 

?>

<div id='wrap'>
	<!--BEGIN SIDEBAR-->
	<div id="menu" role="navigation">
		<?php include "../includes/main_menu.php"; ?>
		<?php include "../includes/submenu_admin.php"; ?>
		<div class="clearfix"></div>
	</div>
	<!--SIDEBAR END-->

	<h1> Profile Information </h1>
	<?php
	echo "<p>";
	echo "<TABLE border=1>\n";
	echo 	"<TR>
				<TH><CENTER> Profile ID &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> User ID &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> User Name &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> User E-mail &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> Is Active (A-active/I-inactive) &nbsp;&nbsp;</CENTER></TH>
			</TR>\n";
	$UserID = "";

	if (mysql_num_rows($result) == 0) {
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) {
			if (isset($_POST['profile_name'])) {
				$_POST['profile_id'] = $row['ProfileID'];
			}
			echo "<TR>\n";
			echo "<TD><CENTER>" . $_POST['profile_id']			. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>" . $row['ProfileUserID']			. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>" . $row['ProfileUserName']		. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>" . $row['ProfileUserEmail']		. "&nbsp;&nbsp;</CENTER></TD>\n"; 
			echo "<TD><CENTER>" . $row['ProfileUserIsActive']	. "&nbsp;&nbsp;</CENTER></TD>\n";
			$UserID = trim($row['ProfileUserID']);
			echo "</TR>";
		}
	}

	
	echo "</TABLE>";
	echo "</p>";


	echo "<HR>\n";
	// Fecth all profiles from this User
	$sql_search_user_profiles = 
                "select p.PROFILE_ID as ProfileID, date(p.PROFILE_CREATION_DATE) as ProfileCreationDate,  p.SIGNATURE as ProfileSignature 
              	from PROFILE p, USER_PROFILE up 
 		where (p.PROFILE_ID = up.PROFILE_ID) and (up.USER_ID=$UserID)";

	echo "<p>Below you can find all the profiles of this User ID $UserID... </p>\n";
	
	//echo $sql_search_user_profiles;

	$result = mysql_query($sql_search_user_profiles, $conn);
	
	echo "<TABLE border=1>\n";
        echo "<TR>
        		<TH><CENTER> Profile ID &nbsp;&nbsp;</CENTER></TH>
        		<TH><CENTER> Profile Signature &nbsp;&nbsp;</CENTER></TH>
        		<TH><CENTER> Profile Creation Date &nbsp;&nbsp;</CENTER></TH>
        	</TR>\n";
        
        if (mysql_num_rows($result) == 0) {
                echo " <br>\n";
               echo " Opps.. Somethint went wrong. Contact you administrator! \n";
        } else {
            while ($row_up = mysql_fetch_array($result)) {
                echo "<TR>\n";
				echo 	"<TD><CENTER>" . $row_up['ProfileID']            	. " &nbsp;&nbsp;</CENTER></TD>\n";
                echo 	"<TD><CENTER>" . $row_up['ProfileSignature']     	. " &nbsp;&nbsp;</CENTER></TD>\n";
                echo 	"<TD><CENTER>" . $row_up['ProfileCreationDate']   	. " &nbsp;&nbsp;</CENTER></TD>\n";
				echo "</TR>\n";
            }
        }
        echo "</TABLE>";

        echo "<HR>";
        echo "<h3> Information Regarding the Tecks of this Profile </h3>\n";
        echo "<p> Profile " . $row_up['ProfileSignature'] . " (ID: ". $_POST['profile_id'] . ") has the following Tecks...</p>\n";
        echo "<p>";
        echo "<table border=1> \n";
	        echo "<tr>\n";	
	        	echo "<th><CENTER> Teck ID &nbsp;&nbsp;</CENTER></th>\n";
	        	echo "<th><CENTER> Teck Title &nbsp;&nbsp;</CENTER></th>\n";
	        	echo "<th><CENTER> Teck Type &nbsp;&nbsp;</CENTER></th>\n";
	        	echo "<th><CENTER> Creation Date &nbsp;&nbsp;</CENTER></th>\n";
	        	echo "<th><CENTER> Published Date &nbsp;&nbsp;</CENTER></th>\n";
				echo "<th><CENTER> Teck Language &nbsp;&nbsp;</CENTER></th>\n";
				echo "<th><CENTER> Pageviews (Teckler) &nbsp;&nbsp;</CENTER></th>\n";
				echo "<th><CENTER> Teck Pageviews (DFP) &nbsp;&nbsp;</CENTER></th>\n";
				echo "<th><CENTER> Teck Status &nbsp;&nbsp;</CENTER></th>\n";
	        	echo "<th> </th>\n";
	        echo "</tr>\n";

	        $sql_info_on_profiles = 
	        	'select 
	        		POST_ID as TeckId, 
	        		TITLE as TeckTitle, 
	        		TYPE as TeckType, 
	        		CREATION_DATE as TeckCreationDate, 
	        		PUBLISH_DATE as TeckPublishDate, 
	        		LANGUAGE_CODE as TeckLanguageCode, 
	        		PAGE_VIEWS as TeckPageviewsTeckler,
	        		DFP_VIEWS as TeckPageviewsDFP, 
	        		STATUS_ID as TeckStatus
	        	from 
	        		POST 
	        	where PROFILE_ID=' . $_POST['profile_id'] . " order by PUBLISH_DATE DESC";
	        $result = mysql_query($sql_info_on_profiles, $conn);
	        while ($row_up = mysql_fetch_array($result)) {
                echo "<TR>\n";
					echo "<TD><CENTER>" . $row_up['TeckId']             	. "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckTitle']      		. "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckType']   			. "&nbsp;&nbsp;</CENTER></TD>\n";
					echo "<TD><CENTER>" . $row_up['TeckCreationDate']       . "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckPublishDate']      	. "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckLanguageCode']   	. "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckPageviewsTeckler']   . "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckPageviewsDFP']      	. "&nbsp;&nbsp;</CENTER></TD>\n";
                	echo "<TD><CENTER>" . $row_up['TeckStatus']   			. "&nbsp;&nbsp;</CENTER></TD>\n";
					echo "</TR>\n";
                }
        echo "</table>\n";
        echo "</p>";
}
?>
</div>

<?php
include "../includes/footer.php";
?>
