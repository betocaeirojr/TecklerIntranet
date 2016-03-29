<?php
include "../includes/header.php";
require "conn_stats.php";

date_default_timezone_set('America/Sao_Paulo');


$day = $_GET['d'];
$type = $_GET['i'];

// Comparing Dates 
// First Day of General Fraudster Info is: 2014-02-26 
$lower_boudery_date = date('U', strtotime('2014-02-27'));
$reference_date = date('U', strtotime($day));
if ($reference_date <= $lower_boudery_date){
	$DateToBeUsedIs = '2014-02-27';
} else {
	$DateToBeUsedIs = "'" . $day . "'";
}

/*
// Start Debuging
	echo "<br>[Debug] - The first day of general fraudster user info is: 2014-02-26, in timestamp it is equal to:  " . $lower_boudery_date . ".<br>";
	echo "<br>[Debug] - The reference day is: $day, in timestamp it is equal to:  " . $reference_date . ".<br>";
	echo "<br>[Debug] - The date to be really used is: $DateToBeUsedIs, in timestamp it is equal to:  " . date('U', strtotime($DateToBeUsedIs)) . ".<br>";
// End Debuging
*/

if ($type=='t'){
	// Info about Tecks	
	$info_about = "Tecks";
	$sql_simple = 
			"select 
				date(DATE) as RefDate, 
				RANKING as PageviewsRanking, 
				TECK_ID as TeckID, 
				TOTAL_PAGEVIEWS as SumViews  
			from 
				TOP_100_TECKS_PER_PAGEVIEW 
			where 
				date(DATE) = '" . $day . "'				
			order by RANKING ASC";

	$result_simple = mysql_query($sql_simple, $conn);
	while ($row_simple = mysql_fetch_array($result_simple)) {
		$simple_info[] = array(
			"Date" 				=> $row_simple['RefDate'],
			"Ranking" 			=> $row_simple['PageviewsRanking'],
			"Teck ID" 			=> $row_simple['TeckID'],
			"Sum of Pageviews" 	=> $row_simple['SumViews']
			);
	}

} elseif ($type == "p") {
	// Info About Profiles
	$info_about = "Profiles";
	$sql_simple = 
			"select 
				date(DATE) as RefDate, 
				RANKING as PageviewsRanking, 
				PROFILE_ID as ProfileID, 
				TOTAL_PAGEVIEWS as SumViews 
			from 
				TOP_100_PROFILES_PER_PAGEVIEW 
			where 
				date(DATE) = '" . $day . "'
			order by RANKING ASC";
	$result_simple = mysql_query($sql_simple, $conn);
	while ($row_simple = mysql_fetch_array($result_simple)) {
		$simple_info[] = array(
			"Date" 				=> $row_simple['RefDate'],
			"Ranking" 			=> $row_simple['PageviewsRanking'],
			"Profile ID" 		=> $row_simple['ProfileID'],
			"Sum of Pageviews" 	=> $row_simple['SumViews']
			);
	}

	$sql_fraudster_profiles = 
		"select 
			a.DATE as RefDate, a.RANKING, a.TOTAL_PAGEVIEWS, b.* 
		from 
			TOP_100_PROFILES_PER_PAGEVIEW a,
			GENERAL_FRAUDSTER_USER_INFO b 
		where 
			a.PROFILE_ID = b.PROFILE_ID and 
			a.DATE = '". $day . "' and b.DATE = '" . $DateToBeUsedIs . "'
		order by a.RANKING ASC";
	$fraudlent_views = 0 ; 
	$result_detailed = mysql_query($sql_fraudster_profiles, $conn);
	while ($row_detailed = mysql_fetch_array($result_detailed)) {
		$detailed_info[] = array(
			"Date" 						=> $row_detailed['RefDate'],
			"Ranking" 					=> $row_detailed['RANKING'],
			"Sum Pageviews" 			=> $row_detailed['TOTAL_PAGEVIEWS'],
			"Profile ID"				=> $row_detailed['PROFILE_ID'],
			"Profile Name" 				=> $row_detailed['PROFILE_SIGNATURE'],
			"Profile Creation Date"		=> $row_detailed['PROFILE_CREATION_DATE'],
			"User ID" 					=> $row_detailed['USER_ID'],
			"User Name" 				=> $row_detailed['USER_NAME'],
			"User Login" 				=> $row_detailed['USER_LOGIN'],
			"User Active Status"		=> $row_detailed['USER_ACTIVE_STATUS'],
			"User Registered Language"	=> $row_detailed['USER_LANG_CODE'],
			"User Creation Date"		=> $row_detailed['USER_CREATION_DATE'],
			"User Default Profile ID"	=> $row_detailed['USER_DEFAULT_PROFILE_ID'],
			"Fraudster Info Ref Date" 	=> $row_detailed['DATE']
			);
		$fraudlent_views = $fraudlent_views + $row_detailed['TOTAL_PAGEVIEWS'];
	}
}


/*
// Start Debuging
	echo "<br> [Debug / VAR] - Day is: " . $day . "<br>\n";
	echo "<br> [Debug / VAR] - Formatted Day is: " . date("Y-m-d", strtotime($day)) . "<br>\n";
	echo "<br> [Debug / SQL] - Fraudster Profiles Information SQL Statement is: " . $sql_fraudster_profiles . "<br>\n";
// End Debuging
*/


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
                	<h1>Detailed Information on TOP 100 <?php echo $info_about;?> Regarding Pageviews </h1>
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<div class="clearfix"></div>

             	<h3><?php echo $info_about;?></h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($simple_info[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($simple_info);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($simple_info as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            if ($ikey == 'Date') {
	                                              echo date("Y-m-d", strtotime($ivalue)); 
	                                            } elseif (strstr($ikey, "%") != FALSE) {
	                                              	echo number_format($ivalue,4, ".", ",")*100 . "%";
	                                            } else {
	                                            	echo number_format($ivalue,0, ".", ",");
	                                            }
	                                            echo "</TD>";
	                                          }
	                                          echo "</tr>";
	                                        }
	                                    ?>
	                                </tbody>
	                            </table>
	                        <div class="clearfix"></div>
	                    </div>
	                  </div>     <!--Striped table END-->
	                </div>

	        <?php 
	        if ($info_about == "Profiles") {
	        ?>
             	<h3>Fraudlent Profiles</h3>
	                <div class="row-fluid">
	                <!--Striped table-->
	                    <div class="grid span12 grid_table">
	                        <div class="grid-content overflow tabela_scroll">
	                            <table class="table table-striped">
	                                <thead>
	                                    <tr>
	                                      <?php
	                                          $keys = array_keys($detailed_info[0]);
	                                          foreach ($keys as $key => $value) {
	                                            echo "<th align=center>";
	                                            echo $value;
	                                            echo "</th>";
	                                          }
	                                          reset($detailed_info);
	                                      ?>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <?php 
	                                        foreach ($detailed_info as $ekey => $evalue) {
	                                          echo "<tr>";    
	                                          foreach ($evalue as $ikey => $ivalue) {
	                                            echo "<TD align=center>";
	                                            // Formating the outupt
	                                            if ($ikey == 'Date' || $ikey == 'Profile Creation Date' || $ikey == 'User Creation Date' || $ikey == 'Fraudster Info Ref Date') {
	                                              echo date("Y-m-d l H:i:s", strtotime($ivalue)); 
	                                            } elseif ($ikey == 'Ranking' || $ikey == 'Sum Pageviews' || $ikey == 'Profile ID' || $ikey == 'User ID' || $ikey == 'User Default Profile ID') {
	                                              	echo number_format($ivalue,0, ".", ",") ;
	                                            } elseif ($ikey == 'Profile Name' || $ikey == 'User Name' || $ikey == 'User Login' || $ikey == 'User Active Status' || $ikey == 'User Registered Language'){
	                                            	echo $ivalue;
	                                            }
	                                            echo "</TD>";
	                                          }
	                                          echo "</tr>";
	                                        }
	                                    ?>
	                                </tbody>
	                            </table>
	                        <div class="clearfix"></div>
	                    </div>
	                  </div>     <!--Striped table END-->
	                  <h3> Total of Fraudlent Views : <?php echo number_format($fraudlent_views, 0, ".", ",");?></h3>
	                </div>
	        <?php
	        }
	        ?>
         	</div>
         </div>


</div>
