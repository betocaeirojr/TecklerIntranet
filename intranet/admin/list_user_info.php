<?php
require_once "../metrics/conn.php";
require_once "../includes/header.php";

date_default_timezone_set('America/Sao_Paulo');

$sql_user_info = "select 
					u.USER_ID as UserID, u.USER_NAME as Username, 
					u.EMAIL as UserEmail, date(u.USER_CREATION_DATE) as UserCreationDate,
					p.PROFILE_ID as ProfileID, p.SIGNATURE as ProfileName, b.EMAIL as EMailPaypal, 
					date(p.PROFILE_CREATION_DATE) as ProfileCreationDate
				from USER u, PROFILE p, USER_PROFILE up, BILLING b
				where u.USER_ID = up.USER_ID and p.PROFILE_ID = up.PROFILE_ID and u.USER_ID = b.USER_ID
				order by lower(u.USER_NAME) ASC";
?>

<div id="wrap">    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "../includes/main_menu.php"; ?>
          
          <?php include "../includes/submenu_admin.php"; ?>
          
          
          <div class="clearfix"></div>
          
          
        </div>
        <!--SIDEBAR END-->
 

<div id="main" role="main">
	<h1>User and Profile Complete Listing - For Finance Only</h1>	
	<p> Information updated at: <?php echo date("Y-m-d H:i:s"); ?> </p>

	<div class="grid span10 grid_table"> <div class="grid-content tabela_scroll">
		<table class="table table-striped">
			<tr>
				<th>User ID </th>
				<th>User Name </th>
				<th>User E-Mail</th>
				<th>User Paypal Email</th>
				<th>User Creation Date</th>
				<th>Profile ID</th>
				<th>Profile Name</th>
				<th>Profile Creation Date</th>
			</tr>

<?php
$result = mysql_query($sql_user_info, $conn);
while ($row = mysql_fetch_array($result)){
	echo "<TR>\n";
	echo "<TD>" . $row['UserID'] 				. "</TD>\n";
	echo "<TD>" . $row['Username'] 				. "</TD>\n";
	echo "<TD>" . $row['UserEmail'] 			. "</TD>\n";
	echo "<TD>" . $row['EMailPaypal'] 			. "</TD>\n";
	echo "<TD>" . $row['UserCreationDate'] 		. "</TD>\n";
	echo "<TD>" . $row['ProfileID'] 			. "</TD>\n";
	echo "<TD>" . $row['ProfileName'] 			. "</TD>\n";
	echo "<TD>" . $row['ProfileCreationDate'] 	. "</TD>\n";
	echo "</TR>\n";
}

echo "</table>";
echo "</div></div></div>";
echo "</div>";
require_once "../includes/footer.php";

?>




