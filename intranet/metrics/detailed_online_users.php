<?php
include "../includes/header.php";
require "conn.php";

date_default_timezone_set('America/Sao_Paulo');

$todayis 	= date('Y-m-d');
$D_Minus_0 	= date('Y-m-d', strtotime('-0 days'));
$D_Minus_1  = date('Y-m-d', strtotime('-1 days'));
$D_Minus_2  = date('Y-m-d', strtotime('-2 days'));
$D_Minus_7  = date('Y-m-d', strtotime('-7 days'));
$D_Minus_15 = date('Y-m-d', strtotime('-15 days'));
$D_Minus_30 = date('Y-m-d', strtotime('-30 days'));

$sql_online_users_today = 
	"select count(USER_ID) as NumUsers from USER_LOGIN_INFO where date(LAST_LOGIN_DATE)='" . $todayis . "'";

$sql_last_login_date_per_date = 
	"select count(USER_ID) as NumUsers, date(LAST_LOGIN_DATE) as LastLogin 
	from USER_LOGIN_INFO group by date(LAST_LOGIN_DATE) order by date(LAST_LOGIN_DATE) DESC";

$sql_numusers_last_login_1week_ago = 
	"select count(USER_ID) as NumUsers from USER_LOGIN_INFO where DATE(LAST_LOGIN_DATE) < CURDATE() - INTERVAL 7 DAY";

$sql_numusers_last_login_1month_ago =
	"select count(USER_ID) as NumUsers from USER_LOGIN_INFO where DATE(LAST_LOGIN_DATE) < CURDATE() - INTERVAL 30 DAY";

$sql_numusers_with_keepmelogged = 
	"select count(TOKEN) as NumUsersKeepMeLogged from USER_LOGIN_INFO where TOKEN<>''";

$sql_users_reconnect_0_1d = 
	"select 
		count(a.NumLoginReconnect1) as NumLoginReconnect  
	from 
		(select  
			a.UserDay1 as NumLoginReconnect1, 
			b.UserDay2 as NumLoginReconnect2 
		from  
			(select distinct USER_ID as UserDay1 
			from USER_LOGIN_HISTORY 
			where date(LOGIN_DATE) = '".$D_Minus_0 . "') a,   
			(select distinct USER_ID as UserDay2 
			from USER_LOGIN_HISTORY 
			where date(LOGIN_DATE) = '".$D_Minus_1 . "') b   
		where  a.UserDay1 = b.UserDay2) a";


$sql_users_reconnect_1_2d = 
	"select 
		count(a.NumLoginReconnect1) as NumLoginReconnect  
	from 
		(select  
			a.UserDay1 as NumLoginReconnect1, 
			b.UserDay2 as NumLoginReconnect2 
		from  
			(select distinct USER_ID as UserDay1 
			from USER_LOGIN_HISTORY 
			where date(LOGIN_DATE) = '".$D_Minus_1 . "') a,   
			(select distinct USER_ID as UserDay2 
			from USER_LOGIN_HISTORY 
			where date(LOGIN_DATE) = '".$D_Minus_2 . "') b   
		where  a.UserDay1 = b.UserDay2) a";

$sql_users_reconnect_0_7d = 
	"select 
		count(a.UserID) as NumLoginReconnect 
	from 
		(select 
			count(USER_ID) as NumLogins, USER_ID as UserID 
		from 
			USER_LOGIN_HISTORY 
		where 
			date(LOGIN_DATE) >= date('" . $D_Minus_7 . "') and 
			date(LOGIN_DATE) <= date('" . $D_Minus_0 . "') 
		group by USER_ID 
		order by count(USER_ID) DESC) a
	where 
		a.NumLogins > 1";

$sql_users_reconnect_0_15d = 
	"select 
		count(a.UserID) as NumLoginReconnect 
	from 
		(select 
			count(USER_ID) as NumLogins, USER_ID as UserID 
		from 
			USER_LOGIN_HISTORY 
		where 
			date(LOGIN_DATE) >= date('" . $D_Minus_15 . "') and 
			date(LOGIN_DATE) <= date('" . $D_Minus_0 . "') 
		group by USER_ID 
		order by count(USER_ID) DESC) a
	where 
		a.NumLogins > 1";


$sql_users_reconnect_0_30d = 
	"select 
		count(a.UserID) as NumLoginReconnect 
	from 
		(select 
			count(USER_ID) as NumLogins, USER_ID as UserID 
		from 
			USER_LOGIN_HISTORY 
		where 
			date(LOGIN_DATE) >= date('" . $D_Minus_30 . "') and 
			date(LOGIN_DATE) <= date('" . $D_Minus_0 . "') 
		group by USER_ID 
		order by count(USER_ID) DESC) a
	where 
		a.NumLogins > 1";


$sql_total_num_visitors_last_5_min = 
	"select sum(a.NumIPs) as NumUsersOnline 
	from 
		(select 
			count(distinct CLIENT_IP) as NumIPs, 
			unix_timestamp(ACTION_DATE) as AtSecond
		from  
			FRAUD.IP_ACTION 
		where 
			unix_timestamp(ACTION_DATE) > 
				(select (UNIX_TIMESTAMP(MAX(ACTION_DATE)) - 300) as LowerBoundary from IP_ACTION) 
		group by unix_timestamp(ACTION_DATE)) a";


// GET Number of Online Visitors Total (To be compare to GA real time info)
$result = mysql_query($sql_total_num_visitors_last_5_min, $conn);
while ($row = mysql_fetch_array($result)) {
	$OnlineVisitorsNow     = $row['NumUsers'];
	
}

// GET Number of Online Users Totay
$result = mysql_query($sql_online_users_today, $conn);
while ($row = mysql_fetch_array($result)) {
	$OnlineUsersToday     = $row['NumUsers'];
	
}

// GET Number of Users With Last Login < 1 week
$result = mysql_query($sql_numusers_last_login_1week_ago, $conn);
while ($row = mysql_fetch_array($result)) {
	$UsersLastLogin1Week     = $row['NumUsers'];
	
}

// GET Number of Users With Last Login < 1 Month
$result = mysql_query($sql_numusers_last_login_1month_ago, $conn);
while ($row = mysql_fetch_array($result)) {
	$UsersLastLogin1Month     = $row['NumUsers'];
	
}

// GET Number of Users With Keep Me Logged On
$result = mysql_query($sql_numusers_with_keepmelogged, $conn);
while ($row = mysql_fetch_array($result)) {
	$UsersKeepMeLoggedOn     = $row['NumUsersKeepMeLogged'];
	
}

// GET of Login Reconnect - D0 and D1
$result = mysql_query($sql_users_reconnect_0_1d, $conn);
while ($row = mysql_fetch_array($result)) {
	$NumLoginReconnect_0_1d     = $row['NumLoginReconnect'];
	
}

// GET of Login Reconnect - D1 and D2
$result = mysql_query($sql_users_reconnect_1_2d, $conn);
while ($row = mysql_fetch_array($result)) {
	$NumLoginReconnect_1_2d     = $row['NumLoginReconnect'];
	
}

// GET of Login Reconnect - D0 and D7
$result = mysql_query($sql_users_reconnect_0_7d, $conn);
while ($row = mysql_fetch_array($result)) {
	$NumLoginReconnect_0_7d     = $row['NumLoginReconnect'];
	
}

// GET of Login Reconnect - D0 and D30
$result = mysql_query($sql_users_reconnect_0_15d, $conn);
while ($row = mysql_fetch_array($result)) {
	$NumLoginReconnect_0_15d     = $row['NumLoginReconnect'];
	
}

// GET of Login Reconnect - D0 and D30
$result = mysql_query($sql_users_reconnect_0_30d, $conn);
while ($row = mysql_fetch_array($result)) {
	$NumLoginReconnect_0_30d     = $row['NumLoginReconnect'];
	
}

?>


<div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
          	<?php include "../includes/main_menu.php"; ?>
          	<?php include "../includes/submenu_charts.php"; ?> 
          	<div class="clearfix"></div> 
        </div>
        <!--SIDEBAR END-->
        <div id="main" role="main">
          	<div class="block">
   		  		<div class="clearfix"></div>
            	<!--page title-->
             	<div class="pagetitle">
                	<h1>Reporting - User Login Behavior Information</h1> 
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<P>&nbsp;</P>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Number of Unique Logged Users today: </div>
		             		<div class="box-title"> <?php echo number_format($OnlineUsersToday, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Number of Users With Keep me Logged On: </div>
		             		<div class="box-title"> <?php echo number_format($UsersKeepMeLoggedOn, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Last Login > 1 week: </div>
		             		<div class="box-title"> <?php echo number_format($UsersLastLogin1Week, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Last Login > 1 Month: </div>
		             		<div class="box-title"> <?php echo number_format($UsersLastLogin1Month, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Login Reconnect (Today and Yesterday): </div>
		             		<div class="box-title"> <?php echo number_format($NumLoginReconnect_0_1d, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Login Reconnect (D-1 and D-2): </div>
		             		<div class="box-title"> <?php echo number_format($NumLoginReconnect_1_2d, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Login Reconnect (Within the last 7 Days): </div>
		             		<div class="box-title"> <?php echo number_format($NumLoginReconnect_0_7d, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

				<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Login Reconnect (Within the last 15 Days): </div>
		             		<div class="box-title"> <?php echo number_format($NumLoginReconnect_0_15d, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>          

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Login Reconnect (Within the last 30 Days): </div>
		             		<div class="box-title"> <?php echo number_format($NumLoginReconnect_0_30d, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Online Users/Visitors - Real-time Info: </div>
		             		<div class="box-title"> <?php echo number_format($OnlineVisitorsNow, 0, ".", ","); ?></div>
		             	</div>
	             	</div>
             	</div>
              <?php include "../includes/footer.php"; ?>
               
               
              
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->


    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>