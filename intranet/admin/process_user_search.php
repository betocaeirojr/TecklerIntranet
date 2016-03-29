<?php
require "../metrics/conn.php";
include "../includes/header.php";
 
if (isset($_POST['user_id']) and (is_numeric($_POST['user_id']) )){
	$sql_search_user_info = "select * from USER u, USER_PROFILE up ". 
				"where (u.USER_ID = up.USER_ID) ". 
				 "and (u.USER_ID=" . $_POST['user_id']. ")";
}

if (isset($_POST['user_email']) and (is_string($_POST['user_email']) )) {
	$sql_search_user_info = "select * from USER u, USER_PROFILE up ". 
				"where (u.USER_ID = up.USER_ID) ". 
				 "and (u.EMAIL='" . $_POST['user_email']. "')";
}

$result = mysql_query($sql_search_user_info, $conn); 
?>

<div id='wrap'>
	<!--BEGIN SIDEBAR-->
	<div id="menu" role="navigation">
		<?php include "../includes/main_menu.php"; ?>
		<?php include "../includes/submenu_admin.php"; ?>
		<div class="clearfix"></div>
	</div>
	<!--SIDEBAR END-->

	<h1> User Information </h1>
	<?php
	//echo "[Debug] - SQL is: " . $sql_search_user_info . "<BR>\n";
	echo "<p>";
	echo "<TABLE border=\"1\">\n";
	echo "<TR>
			<TH><CENTER> User ID &nbsp;&nbsp; </CENTER></TH>
			<TH><CENTER> Login &nbsp;&nbsp; </CENTER></TH>
			<TH><CENTER> User E-mail &nbsp;&nbsp; </CENTER></TH>
			<TH><CENTER> Is Active (A-active/I-inactive) &nbsp;&nbsp; </CENTER></TH>
			<TH><CENTER> User Name &nbsp;&nbsp; </CENTER></TH>
			<TH><CENTER> User Creation Date &nbsp;&nbsp; </CENTER></TH>
		</TR>\n";

	$UserID = "";
	if (mysql_num_rows($result) == 0) {
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else {
		while ($row = mysql_fetch_assoc($result)) {
			$UserID = trim($row['USER_ID']);
			echo "<TR>\n";
			echo "<TD><CENTER>" . $row['USER_ID']				. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>" . $row['LOGIN']					. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>" . $row['EMAIL']					. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>"	. $row['ACTIVE']				. "&nbsp;&nbsp;</CENTER></TD>\n"; 
			echo "<TD><CENTER>" . $row['USER_NAME']				. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "<TD><CENTER>" . $row['USER_CREATION_DATE'] 	. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "</TR>";
		}
	}
	echo "</TABLE>";
	echo "</p>";

	echo "<HR>\n";
	// Fecth all profiles from this User
	echo "<p>Below you can find all the profiles of this User ID $UserID... </p><BR>\n";
	$sql_search_user_profiles = "select p.PROFILE_ID as ProfileID, p.PROFILE_CREATION_DATE as ProfileCreationDate,  p.SIGNATURE as ProfileSignature from PROFILE p, USER_PROFILE up where (p.PROFILE_ID = up.PROFILE_ID) and (up.USER_ID=$UserID)";
	//echo "[Debug] --" .  $sql_search_user_profiles . "<br>\n";
	$result = mysql_query($sql_search_user_profiles, $conn);
	echo "<TABLE border=\"1\">\n";
	echo 	"<TR>
				<TH><CENTER> Profile ID &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> Profile Signature &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> Profile Creation Date &nbsp;&nbsp;</CENTER></TH>
			</TR>\n";
	if (mysql_num_rows($result) == 0) {
	        echo " <br>\n";
	       echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else
	{
	    while ($row_up = mysql_fetch_array($result)) {
	        echo "<TR>\n";
			echo "<TD><CENTER>" . $row_up['ProfileID']             . "&nbsp;&nbsp;</CENTER></TD>\n";
	        echo "<TD><CENTER>" . $row_up['ProfileSignature']      . "&nbsp;&nbsp;</CENTER></TD>\n";
	        echo "<TD><CENTER>" . $row_up['ProfileCreationDate']   . "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "</TR>\n";
	    }
	}
	echo "</TABLE>";
	?>

	<?php 
	echo "<HR>\n";
	// Fecth all tecks from this User
	echo "<p>Below you can find all the tecks of this User ID $UserID... </p><BR>\n";
	$sql_search_user_tecks = "select TITLE as TeckTitle, TEXT as TeckText from POST where USER_ID=$UserID";
	//echo "[Debug] --" .  $sql_search_user_profiles . "<br>\n";
	$result = mysql_query($sql_search_user_tecks, $conn);
	echo "<TABLE border=\"1\">\n";
	echo 	"<TR>
				<TH><CENTER> Teck Title &nbsp;&nbsp;</CENTER></TH>
				<TH><CENTER> Teck Text &nbsp;&nbsp;</CENTER></TH>
	
			</TR>\n";
	if (mysql_num_rows($result) == 0) {
	        echo " <br>\n";
	       echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else
	{
	    while ($row_up = mysql_fetch_array($result)) {
	        echo "<TR>\n";
			echo "<TD><CENTER>" . $row_up['TeckTitle']  . "&nbsp;&nbsp;</CENTER></TD>\n";
	        echo "<TD><CENTER>" . $row_up['TeckText']  	. "&nbsp;&nbsp;</CENTER></TD>\n";
			echo "</TR>\n";
	    }
	}
	echo "</TABLE>";
	?>

</div>
<?php
include "../includes/footer.php";
?>
