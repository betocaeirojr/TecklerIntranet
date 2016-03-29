<?php
include "../includes/header.php";
require "conn_rev.php";



date_default_timezone_set("America/Sao_Paulo");

?>
<?php

$sql_num_actual_expected_pv_ggl = 
	"select 
		sum(EXPECTED_VIEWS) as ExpectedPageViews,  
		sum(VIEWS) as ActualPageViews 
	from 
		STATEMENT";

$sql_num_actual_expected_pv_day_ggl = 
	"select 
		sum(EXPECTED_VIEWS) as ExpectedPageViews, 
		sum(VIEWS) as ActualPageViews, 
		date(FROM_UNIXTIME(DAY_SEQ*86400)) as Date 
	from 
		STATEMENT 
	group by 
		date(FROM_UNIXTIME(DAY_SEQ*86400)) 
	order by 
		FROM_UNIXTIME(DAY_SEQ*86400) DESC";

$sql_num_actual_expected_pv_month_ggl = 
	"select 
		sum(EXPECTED_VIEWS) as ExpectedPageViews, 
		sum(VIEWS) as ActualPageViews, 
		date(FROM_UNIXTIME(DAY_SEQ*86400)) as Date 
	from 
		STATEMENT 
	group by 
		month(FROM_UNIXTIME(DAY_SEQ*86400)) 
	order by 
		FROM_UNIXTIME(DAY_SEQ*86400) DESC";

$sql_num_expected_pv_profile_top100 = 
	"select 
		sum(EXPECTED_VIEWS) as ExpectedViews, 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
	from 
		STATEMENT 
	group by 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
	order by 
		sum(EXPECTED_VIEWS) DESC 
	limit 100";

$sql_num_actual_pv_profile_top100 = 
	"select 
		sum(VIEWS) as ActualViews, 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
	from 
		STATEMENT 
	group by 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
	order by 
		sum(VIEWS) DESC 
	limit 100";

$sql_avg_pv_ggl_teck = 
	"select  
		b.NumExpectedPageViews / a.NumPublishedTecks as AvgExpectedPVTecks, 
		b.NumActualPageViews / a.NumPublishedTecks as AvgActualPVTecks 
	from 
		(select count(POST_ID) as NumPublishedTecks from TECKLER.POST) a,
		(select 
			sum(EXPECTED_VIEWS) as NumExpectedPageViews, 
			sum(VIEWS) as NumActualPageViews
		from STATEMENT) b ";

$sql_avg_pv_ggl_profile = 
	"select 
		a.NumExpectedPV / b.NumActiveProfiles as AvgExpectedPVProfile,
		a.NumActualPV / b.NumActiveProfiles as AvgActualPVProfile 
	from 
		(select 
			sum(EXPECTED_VIEWS) as NumExpectedPV, 
			sum(VIEWS) as NumActualPV 
                 from STATEMENT) a, 
		(select count(distinct PROFILE_ID) as NumActiveProfiles from TECKLER.POST) b";

/* ****** Conta de CPM
ganho = CPM * 1000 visualizações
ex: 
	CPM = 0.4 USD
	Visualizações = 2000
	Ganho = 0.4 * 2 (1000) = 0.8 USD

Ou
CPM = ganho (USD) / # Visualizações (/1000) 
************** */

$sql_ecpm_consolidate = 
	"select 
		round((sum(EXPECTED_VALUE)*0.000001) / (sum(EXPECTED_VIEWS)*0.001),3) as ConsExpectedCPM 
	from 
		STATEMENT";
$sql_acpm_consolidate = 
	"select 
		round((sum(VALUE)*0.000001) / (sum(VIEWS)*0.001),3) as ConsActualCPM 
	from 

		STATEMENT"; 

$sql_ecpm_acpm_day =
	"select 
		(sum(EXPECTED_VALUE)*0.000001) / (sum(EXPECTED_VIEWS)*0.001) ExpectedCPM,
		(sum(VALUE)*0.000001) / (sum(VIEWS)*0.001) ActualCPM,
		date(FROM_UNIXTIME(DAY_SEQ*86400)) as Date
	from 
		STATEMENT
	group by 
		date(FROM_UNIXTIME(DAY_SEQ*86400)) 
	order by
		date(FROM_UNIXTIME(DAY_SEQ*86400)) DESC";

$sql_ecpm_acpm_month = 
	"select 
		(sum(EXPECTED_VALUE)*0.000001) / (sum(EXPECTED_VIEWS)*0.001) ExpectedCPM,
		(sum(VALUE)*0.000001) / (sum(VIEWS)*0.001) ActualCPM,
		date(FROM_UNIXTIME(DAY_SEQ*86400)) as Date
	from 
		STATEMENT
	group by 
		month(FROM_UNIXTIME(DAY_SEQ*86400)) 
	order by
		date(FROM_UNIXTIME(DAY_SEQ*86400)) DESC";

$sql_ecpm_per_profile_top100 = 
	"select 
		(sum(EXPECTED_VALUE)*0.000001) / (sum(EXPECTED_VIEWS)*0.001) as ExpectedCPM, 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
	from 
		STATEMENT 
	group by 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
	order by 
		(sum(EXPECTED_VALUE)*0.000001) / (sum(EXPECTED_VIEWS)*0.001) DESC 
	limit 100";

//$sql_acpm_day = 
//	"select 
//		(sum(VALUE)*0.000001) / (sum(VIEWS)*0.001) ActualCPM,
//		date(FROM_UNIXTIME(SEQ_DAY*86400)) as Date
//	from 
//		STATEMENT
//	group by 
//		date(date(FROM_UNIXTIME(DAY_SEQ*86400)) 
//	order by
//		date(FROM_UNIXTIME(DAY_SEQ*86400)) DESC";
//$sql_acpm_month = 
//	"select 
//		(sum(VALUE)*0.000001) / (sum(VIEWS)*0.001) ActualCPM,
//		date(FROM_UNIXTIME(SEQ_DAY*86400)) as Date
//	from 
//		STATEMENT
//	group by 
//		month(date(FROM_UNIXTIME(DAY_SEQ*86400)) 
//	order by
//		date(FROM_UNIXTIME(DAY_SEQ*86400)) DESC";
//
$sql_acp_profile = 
	"select 
		(sum(VALUE)*0.000001) / (sum(VIEWS)*0.001) as ActualCPM, 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
	from 
		STATEMENT 
	group by 
		SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
	order by 
		(sum(EXPECTED_VALUE)*0.000001) / (sum(EXPECTED_VIEWS)*0.001) DESC 
	limit 100";



/* *******************************************************
** Starting Assembling Page
**********************************************************/

//echo "<HTML>\n";
//echo "<HEAD><TITLE>Metrics - Google Figures</TITLE></HEAD>\n";
//echo "<BODY>\n";


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
                	<h1>Relatórios - PageViews (Based on Google DFP)</h1> 
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
         	
         

<?php

echo "&nbsp;<H2>Consolidated Information - Pageviews and CPM</H2>\n";
echo "&nbsp;<P>Overall Timespan - Since Apr 13th, 2013</P>\n";

echo "<div class=\"grid grid_table\">";
echo "<div class=\"grid-content overflow\">";
echo 	"<TABLE BORDER=1 class=\"table table-striped\">\n";
echo 	"<TR> 
			<TH colspan=2> <center> Overall Expected Pageviews </center></TH>
			<TH colspan=2> <center> Overall Actual Pageviews </center></TH>
		</TR>\n";

// GET Expected and Actual PV information
$result = mysql_query($sql_num_actual_expected_pv_ggl, $conn);
while ($row = mysql_fetch_array($result)) 
	{
		$ExpectedPV     = number_format($row['ExpectedPageViews'],0,".", ",");
		$ActualPV 		= number_format($row['ActualPageViews'],0,".", ",");
	}
	

echo 	"<TR> 
			<TD colspan=2><CENTER> $ExpectedPV</CENTER> </TD>
			<TD colspan=2><CENTER> $ActualPV</CENTER></TD>
		</TR>\n";


echo 	"<TR>
			<TH> Average Expected Pageview Per Teck </TH>
			<TH> Average Actual Pageview Per Teck </TH>
			<TH> Average Expected Pageview Per Profile </TH>
			<TH> Average Actual Pageview Per Profile </TH>
		</TR>\n";

// GET Average PV Per Teck
$result = mysql_query($sql_avg_pv_ggl_teck, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		$AvgExpectedPVTeck 	= number_format($row['AvgExpectedPVTecks'],2,".", ",");
		$AvgActualPVTeck 	= number_format($row['AvgActualPVTecks'],2,".", ",");
	}

// GET Average PV Per Profile
$result = mysql_query($sql_avg_pv_ggl_profile, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		$AvgExpectedPVProfile 	= number_format($row['AvgExpectedPVProfile'],2,".", ",");
		$AvgActualPVProfile 	= number_format($row['AvgActualPVProfile'],2,".", ",");
	}

echo 	"<TR>
			<TD> $AvgExpectedPVTeck </TD>
			<TD> $AvgActualPVTeck </TD>
			<TD> $AvgExpectedPVProfile </TD>
			<TD> $AvgActualPVProfile </TD>
		</TR>\n";


echo 	"<TR>
			<TH colspan=2> Expected CPM </TH>
			<TH colspan=2> Actual CPM </TH>
		</TR>\n";


// GET Expected CPM - Consolidated

$result = mysql_query($sql_ecpm_consolidate, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		$eCPM_Consol 	= number_format($row['ConsExpectedCPM'],6,".", ",");
	}

// GET Actuals CPM - Consolidated 
$result = mysql_query($sql_acpm_consolidate, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		$aCPM_Consol 	= number_format($row['ConsActualCPM'],6,".", ",");
	}

echo 	"<TR>
			<TD colspan=2><CENTER> $eCPM_Consol</CENTER> </TD>
			<TD colspan=2><CENTER> $aCPM_Consol</CENTER> </TD>
		</TR>\n";

echo "</TABLE>\n";
echo "</div></div>";
echo "<HR>";



// //////////////////////////////////////
// //////////////////////////////////////

 
echo "<H2>Detailed Info - Page Views</H2>\n";
echo "<H3> Expected and Actuals Page Views - By Period</H3>\n";
echo "<P>Per Day - last 90 days</P>\n";
echo "<div class=\"grid grid_table\">";
echo "<div class=\"grid-content overflow\">";
echo "<table border=1 class=\"table table-striped\">\n";
echo 	"<TR>
			<TH>Date</TH>
			<TH>Expected Page Views</TH>
			<TH>Actual Page Views</TH>
		</TR>\n";

// GET Expected and Actual PV per Day
 
$result = mysql_query($sql_num_actual_expected_pv_day_ggl, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		echo "<TR>\n";
		echo "<TD>" . date('Y-m-d',strtotime($row['Date'])) . "&nbsp;</TD>\n";
		echo "<TD>" . number_format($row['ExpectedPageViews'],0,".", ",") . "&nbsp;</TD>\n";
		echo "<TD>" . number_format($row['ActualPageViews'],0,".", ",") . "&nbsp;</TD>\n";
		echo "</TR>\n";
	}

echo "</table>\n";
echo "</div></div>";

echo "<P>Per Month - last 24 Months</P>\n";
echo "<div class=\"grid grid_table\">";
echo "<div class=\"grid-content overflow\">";

echo "<table border=1 class=\"table table-striped\">\n";
echo 	"<TR>
			<TH>Date</TH>
			<TH>Expected Page Views</TH>
			<TH>Actual Page Views</TH>
		</TR>\n";

// GET Expected and Actual PV per Month

$result = mysql_query($sql_num_actual_expected_pv_month_ggl, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		echo "<TR>\n";
		echo "<TD>" . date('Y-m',strtotime($row['Date'])) . "</TD>\n";
		echo "<TD>" . number_format($row['ExpectedPageViews'],0,".", ",") . "</TD>\n";
		echo "<TD>" . number_format($row['ActualPageViews'],0,".", ",") . "</TD>\n";
		echo "</TR>\n";
	}

echo "</table>\n";
echo "</div></div>";

echo "<HR>\n";
echo "<H2>Detailed Info - CPMs</H2>\n";
echo "<H3> Expected and Actuals CPM - By Period</H3>\n";
echo "<P>Per Day - last 90 days</P>\n";
echo "<div class=\"grid grid_table\">";
echo "<div class=\"grid-content overflow\">";

echo "<table border=1 class=\"table table-striped\">\n";
echo 	"<TR>
			<TH>Date</TH>
			<TH>eCPM</TH>
			<TH>aCPM</TH>
		</TR>\n";

// GET Expected and Actual PV per Month
 
$result = mysql_query($sql_ecpm_acpm_day, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		echo "<TR>\n";
		echo "<TD>" . date('Y-m-d',strtotime($row['Date'])) . "</TD>\n";
		echo "<TD>" . number_format($row['ExpectedCPM'],6,".", ",") . "</TD>\n";
		echo "<TD>" . number_format($row['ActualCPM'],6,".", ",") . "</TD>\n";
		echo "</TR>\n";
	}
echo "</table>\n";
echo "</div></div>";

echo "<P>Legend : <br>
		eCPM : Expected CPM <BR>
		aCPM : Actual CPM</P>\n";


echo "<P>Per Month - last 24 months </P>\n";
echo "<div class=\"grid grid_table\">";
echo "<div class=\"grid-content overflow\">";
echo "<table border=1 class=\"table table-striped\">\n";
echo 	"<TR>
			<TH>Date</TH>
			<TH>eCPM</TH>
			<TH>aCPM</TH>
		</TR>\n";

// GET Expected and Actual PV per Month
$result = mysql_query($sql_ecpm_acpm_month, $conn);
	while ($row = mysql_fetch_array($result)) 
	{
		echo "<TR>\n";
		echo "<TD>" . date('Y-m',strtotime($row['Date'])) . "</TD>\n";
		echo "<TD>" . number_format($row['ExpectedCPM'],6,".", ",") . "</TD>\n";
		echo "<TD>" . number_format($row['ActualCPM'],6,".", ",") . "</TD>\n";
		echo "</TR>\n";
	}

echo "</table>\n";
echo "</div></div>";


echo "<P>Legend : <br>
		eCPM : Expected CPM <BR>
		aCPM : Actual CPM</P>\n";
echo "</P>";


echo "<HR>\n";

?>
			</div>
         </div>
	</div>
</div>