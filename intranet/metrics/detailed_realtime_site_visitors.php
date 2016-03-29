<?php
include "../includes/header.php";
require "conn.php";

mysql_select_db("FRAUD", $conn)
		or die('Could not select database; ' . mysql_error());

date_default_timezone_set('America/Sao_Paulo');

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

$sql_total_num_visitors_last_10_min = 
	"select sum(a.NumIPs) as NumUsersOnline 
	from 
		(select 
			count(distinct CLIENT_IP) as NumIPs, 
			unix_timestamp(ACTION_DATE) as AtSecond 
		from  
			FRAUD.IP_ACTION 
		where 
			unix_timestamp(ACTION_DATE) > 
				(select (UNIX_TIMESTAMP(MAX(ACTION_DATE)) - 600) as LowerBoundary from IP_ACTION) 
		group by unix_timestamp(ACTION_DATE)) a";


// GET Number of Online Visitors Total (To be compare to GA real time info)
$result = mysql_query($sql_total_num_visitors_last_5_min, $conn);
while ($row = mysql_fetch_array($result)) {
	$OnlineVisitorsNow_5min     = $row['NumUsersOnline'];
	
}

// GET Number of Online Visitors Total (To be compare to GA real time info)
$result = mysql_query($sql_total_num_visitors_last_10_min, $conn);
while ($row = mysql_fetch_array($result)) {
	$OnlineVisitorsNow_10min     = $row['NumUsersOnline'];
	
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
                	<h1>Reporting - Real Time Visitors</h1> 
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<P>&nbsp;</P>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Number of Active Visitors (last 5 min): </div>
		             		<div class="box-title"> <?php echo $OnlineVisitorsNow_5min; ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> Number of Active Visitors (last 10 min): </div>
		             		<div class="box-title"> <?php echo $OnlineVisitorsNow_10min; ?></div>
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