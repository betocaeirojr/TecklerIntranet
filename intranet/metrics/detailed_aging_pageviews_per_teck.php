<?php
include "../includes/header.php";
require "conn.php";
date_default_timezone_set('America/Sao_Paulo');

$teckCreationDate = (isset($_POST['refdate'])? $_POST['refdate'] : '2013-10-08' );
$todayis = date("Y-m-d");
$dayIncreasing = 1; 

for ($i = strtotime('2013-09-19') ; $i <= strtotime($todayis) ; $i = $i + (60 * 60 * 24) ){

	$teckCreationDate = date('Y-m-d', $i);
	$sql_aging_pageviews_per_teck = 
		"select  
			date(dv.DAY) as ReferenceDate, 
			sum(dv.VIEWS) as Pageviews 
		from 
			DAILY_VIEWS dv  
		where 
			date(dv.DAY) > date('0000-00-00') and  
			dv.POST_ID in 
				(select 
					POST_ID 
				from 
					POST 
				where 
					date(PUBLISH_DATE)=date('". date('Y-m-d', $i) . "')
				) 
		group by 
			date(dv.DAY)  
		order 
			by date(dv.DAY)";

	$ReferenceDate = $teckCreationDate;

	// Setting up initial values for Period Variables
	$Pageviews_P1 = 0;
	$Pageviews_P2 = 0;
	$Pageviews_P3 = 0;
	$Pageviews_P4 = 0;
	$Pageviews_P5 = 0;
	$Pageviews_P6 = 0;



	$result = mysql_query($sql_aging_pageviews_per_teck, $conn);
	while ($row = mysql_fetch_assoc($result)){	
		
		// First Period
		$ReferenceDay = strtotime($ReferenceDate);
		if (strtotime($row['ReferenceDate']) == $ReferenceDay) {
			$Pageviews_P1 = $row['Pageviews'];
		}
		// Second Period (D2 to D7)
		$ReferenceDay_Plus1 	= $ReferenceDay + (60*60*24*1);
		$ReferenceDay_Plus7 	= $ReferenceDay + (60*60*24*6);
		if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus1) AND 
				(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus7) ) {
			$Pageviews_P2 = $Pageviews_P2 +  $row['Pageviews']; 
		}

		// Third Period (D8 to D14)
		$ReferenceDay_Plus8 	= $ReferenceDay + (60*60*24*7);
		$ReferenceDay_Plus14 	= $ReferenceDay + (60*60*24*13);
		if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus8) AND 
				(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus14) ) {
			$Pageviews_P3 = $Pageviews_P3 + $row['Pageviews']; 
		}

		// Fourth Period (D15 to D30)
		$ReferenceDay_Plus15	= $ReferenceDay + (60*60*24*14);
		$ReferenceDay_Plus30	= $ReferenceDay + (60*60*24*30);
		if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus15) AND 
				(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus30) ) {
			$Pageviews_P4 = $Pageviews_P4 + $row['Pageviews']; 
		}

		// Fifth Period (D31 to D60)
		$ReferenceDay_Plus31 	= $ReferenceDay + (60*60*24*31);
		$ReferenceDay_Plus60	= $ReferenceDay + (60*60*24*60);
		if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus31) AND 
				(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus60) ) {
			$Pageviews_P5 = $Pageviews_P4 + $row['Pageviews']; 
		}

		// Sixth Period (D61 to D90)
		$ReferenceDay_Plus61 	= $ReferenceDay + (60*60*24*61);
		$ReferenceDay_Plus90	= $ReferenceDay + (60*60*24*90);
		if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus61) AND 
				(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus90) ) {
			$Pageviews_P6 = $Pageviews_P6 + $row['Pageviews']; 
		}

}

	$AgingPVMatrix[] = 
	array(
		"Date"	=> $ReferenceDate,
		"PV-P1"	=> $Pageviews_P1,
		"PV-P2"	=> $Pageviews_P2,
		"PV-P3"	=> $Pageviews_P3,
		"PV-P4"	=> $Pageviews_P4,
		"PV-P5"	=> $Pageviews_P5,
		"PV-P6"	=> $Pageviews_P6,
		);
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
            	<h1>Reporting - Aging of Pageview in Tecks per Month</h1> 
            	<div class="clearfix"></div>
         	</div>
         	<!--page title end-->
         	<P>&nbsp;</P>

         	<div class="information-box-3 span9 box_tecks">
             	<div class="">
             		<div style="padding:0 20px 20px 20px;"  class="">
	             		<!-- <img src="../images/icon/stats_1.png"> -->
	             		<div><h3> Aging of Pageviews:</h3> </div>
	             		<div style="padding:0 20px 0 0;" class="grid grid_table"> 
	             			<div class="overflow">
	             			<table width="80%" class="table table-bordere">	
	             				<thead>	
	             				<tr>
		             				<th>Reference Date</th>
		             				<th>1st Period <BR>(D01)</th>
		             				<th>2nd Period <BR>(D02-07)</th>
		             				<th>3rd Period <BR>(D08-14)</th>
		             				<th>4th Period <BR>(D15-30)</th>
		             				<th>5th Period <BR>(D31-60)</th>
		             				<th>6th Period <BR>(D61-90)</th>
	             				</tr>
	             			</thead>
	             			<?php 
	             				foreach ($AgingPVMatrix as $key => $value) {
	             						echo "<tr>\n";
	             						echo "<TD>" . $value['Date'] . "</TD>\n";
	             						echo "<TD>" . $value['PV-P1']. "</TD>\n";
	             						echo "<TD>" . $value['PV-P2']. "</TD>\n";
	             						echo "<TD>" . $value['PV-P3']. "</TD>\n";
	             						echo "<TD>" . $value['PV-P4']. "</TD>\n";
	             						echo "<TD>" . $value['PV-P5']. "</TD>\n";
	             						echo "<TD>" . $value['PV-P6']. "</TD>\n";
	             						echo "</tr>\n";
	             					}
	             			?>
	             			</table>
	             		</div>
	             	</div>
             	</div>
         	</div>
     	</div>
    </div>
</div>
    <div class="clearfix"></div> 
    <?php include "../includes/footer.php";
    include "../includes/java_scripts.php"; ?>

  </body>
</html>
