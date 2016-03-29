<?php
include "../includes/header.php";
require "conn.php";

mysql_select_db('INTRANET', $conn)
		or die('Could not select database; ' . mysql_error());

date_default_timezone_set('America/Sao_Paulo');

// Recurring Writers - 2 Months
$sql_num_recurring_writers_2_months = 
	"select 
		count(*) NumRecurringWriters 
	from 
		CONS_METRICS_RECURRING_WRITERS
    where 
    	(NUM_TECKS_MAY2013 > 0 and NUM_TECKS_JUN2013 > 0) or
    	(NUM_TECKS_JUN2013 > 0 and NUM_TECKS_JUL2013 > 0) or 
    	(NUM_TECKS_JUL2013 > 0 and NUM_TECKS_AUG2013 > 0) or
    	(NUM_TECKS_AUG2013 > 0 and NUM_TECKS_SEP2013 > 0) or
    	(NUM_TECKS_SEP2013 > 0 and NUM_TECKS_OCT2013 > 0) or 
    	(NUM_TECKS_OCT2013 > 0 and NUM_TECKS_NOV2013 > 0)";
$result = mysql_query($sql_num_recurring_writers_2_months, $conn);
while ($row = mysql_fetch_array($result)) {
	$totalRecurringWriters_2Months     = $row['NumRecurringWriters'];
	
}

// Recurring Writers - 3 Months
$sql_num_recurring_writers_3_months = 
	"select 
		count(*) NumRecurringWriters 
	from 
		CONS_METRICS_RECURRING_WRITERS
    where 
    	(NUM_TECKS_MAY2013 > 0 and NUM_TECKS_JUN2013 > 0 and NUM_TECKS_JUL2013 > 0) or
    	(NUM_TECKS_JUN2013 > 0 and NUM_TECKS_JUL2013 > 0 and NUM_TECKS_AUG2013 > 0) or 
    	(NUM_TECKS_JUL2013 > 0 and NUM_TECKS_AUG2013 > 0 and NUM_TECKS_SEP2013 > 0) or
    	(NUM_TECKS_AUG2013 > 0 and NUM_TECKS_SEP2013 > 0 and NUM_TECKS_OCT2013 > 0) or
    	(NUM_TECKS_SEP2013 > 0 and NUM_TECKS_OCT2013 > 0 and NUM_TECKS_NOV2013 > 0)";
$result = mysql_query($sql_num_recurring_writers_3_months, $conn);
while ($row = mysql_fetch_array($result)) {
	$totalRecurringWriters_3Months     = $row['NumRecurringWriters'];
	
}


// Recurring Writers - 4 Months
$sql_num_recurring_writers_4_months = 
	"select 
		count(*) NumRecurringWriters 
	from 
		CONS_METRICS_RECURRING_WRITERS
    where 
    	(NUM_TECKS_MAY2013 > 0 and NUM_TECKS_JUN2013 > 0 and NUM_TECKS_JUL2013 > 0 and NUM_TECKS_AUG2013 > 0) or
    	(NUM_TECKS_JUN2013 > 0 and NUM_TECKS_JUL2013 > 0 and NUM_TECKS_AUG2013 > 0 and NUM_TECKS_SEP2013 > 0) or 
    	(NUM_TECKS_JUL2013 > 0 and NUM_TECKS_AUG2013 > 0 and NUM_TECKS_SEP2013 > 0 and NUM_TECKS_OCT2013 > 0) or 
    	(NUM_TECKS_AUG2013 > 0 and NUM_TECKS_SEP2013 > 0 and NUM_TECKS_OCT2013 > 0 and NUM_TECKS_NOV2013 > 0) ";
$result = mysql_query($sql_num_recurring_writers_4_months, $conn);
while ($row = mysql_fetch_array($result)) {
	$totalRecurringWriters_4Months     = $row['NumRecurringWriters'];
	
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
                	<h1>Reporting - Recurring Writers - Consecutive Months </h1>
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->


             	<div class="clearfix"></div>
             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> 2 Months Consecutives </div>
		             		<div class="box-title"> <?php echo $totalRecurringWriters_2Months; ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> 3 Months Consecutives </div>
		             		<div class="box-title"> <?php echo $totalRecurringWriters_3Months; ?></div>
		             	</div>
	             	</div>
             	</div>

             	<div class="information-box-3 span6 box_tecks">
	             	<div class="item">
	             		<div class="box-info">
		             		<img src="../images/icon/stats_1.png">
		             		<div class="box-figures"> 4 Months Consecutives </div>
		             		<div class="box-title"> <?php echo $totalRecurringWriters_4Months; ?></div>
		             	</div>
	             	</div>
             	</div>
         	</div>
         </div>	
	</div>
    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>
