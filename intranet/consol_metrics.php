<?php
	include "header.php";
	require "conn.php";
?>
<H2>Consolidated Metrics</H2>
</H4>This metrics considers the overall timespan of the site!</H4>
<?php
$sql_num_total_users 		= "select count(USER_ID) as NumTotalUsers from USER";
$sql_num_total_profiles 	= "select count(PROFILE_ID) as NumTotalProfiles from PROFILE;";
$sql_num_total_tecks 		= "select count(POST_ID) as NumTotalTecks from POST";
$sql_num_total_shares 		= "select sum(FACEBOOK+GOOGLE_PLUS+TWITTER+LINKEDIN) as NumTotalShares from POST_SHARE";
$sql_num_total_pageviews 	= "select sum(PAGE_VIEWS) as NumTotalPageviews from POST";

// Starting processing
// Total Users
$result = mysql_query($sql_num_total_users, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_users = $row['NumTotalUsers'];
		}
	}

// Total Profiles
$result = mysql_query($sql_num_total_profiles, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_profiles = $row['NumTotalProfiles'];
		}
	}


// Total Tecks
$result = mysql_query($sql_num_total_tecks, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_tecks = $row['NumTotalTecks'];
		}
	}

// Total Shares
$result = mysql_query($sql_num_total_shares, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_shares = $row['NumTotalShares'];
		}
	}

// Total PageViews
$result = mysql_query($sql_num_total_pageviews, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_pageviews = $row['NumTotalPageviews'];
		}
	}



?>


<div>
	<tr>
	<td>Total Number of Registered Users:</td>
	<td><?php echo $res_num_total_users;?></td>
	</tr>
</div>

<div>
	<tr>
	<td>Total Number of Created Profiles:</td>
	<td><?php echo $res_num_total_profiles;?></td>
	</tr>
</div>

<div>
	<tr>
	<td>Total Number of Published Tecks:</td>
	<td><?php echo $res_num_total_tecks;?></td>
	</tr>
</div>

<div>
	<tr>
	<td>Total Number of Shares - considering all social networks :</td>
	<td><?php echo $res_num_total_shares;?></td>
	</tr>
</div>

<div>
	<tr>
	<td>Total Number of Teckler Page Views:</td>
	<td><?php echo $res_num_total_pageviews;?></td>
	</tr>
</div>

<?php
	echo "<p><a href=\"index.php\">Back to Main Page</a></p>";
	include "footer.php";
?>
