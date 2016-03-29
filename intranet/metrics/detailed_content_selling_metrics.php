<?php
date_default_timezone_set('America/Sao_Paulo');
require_once("conn.php");



// //////////////////////////////////////////////////////
// BUYERS
$sql_total_num_buyers = 
	"select 
		count(USER_ID) as NumBuyers
	from 
		TECKLER.TK_ORDER"; 
$result = mysql_query($sql_total_num_buyers, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TotalNumberOfBuyers = $row['NumBuyers']; 
}

$sql_total_num_buyers_day = 
	"select 
		count(USER_ID) as NumBuyers,
		date(SOLD_DATE) as TransactionDate 
	from 
		TECKLER.TK_ORDER 
	group by 
		date(SOLD_DATE) 
	order by 
		date(SOLD_DATE) DESC";
$result = mysql_query($sql_total_num_buyers_day, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TotalNumberOfBuyers_Date[] = array(
  		"Date" 				=> $row['TransactionDate'], 
  		"Number of Buyers"	=> $row['NumBuyers']);
}


$sql_total_num_buyers_month = 
	"select 
		count(USER_ID) as NumBuyers,
		date(SOLD_DATE) as TransactionDate,
		year(SOLD_DATE) as TransactionYear,
		month(SOLD_DATE) as TransactionMonth 
	from 
		TECKLER.TK_ORDER 
	group by 
		year(SOLD_DATE), month(SOLD_DATE) 
	order by 
		year(SOLD_DATE) DESC, month(SOLD_DATE) DESC";
$result = mysql_query($sql_total_num_buyers_month, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TotalNumberOfBuyers_Month[] = array(
  		"Date" 				=> $row['TransactionDate'], 
  		"Number of Buyers"	=> $row['NumBuyers'],
  		"Transaction Year"	=> $row['TransactionYear'],
  		"Transaction Month"	=> $row['TransactionMonth']);
}

// //////////////////////////////////////////////////////
// SELLERS
$sql_total_num_sellers = 
	"select 
		count(USER_ID) as NumSellers 
	from PAY.PAYMENT";
$result = mysql_query($sql_total_num_sellers, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TotalNumberOfSellers = $row['NumSellers'] ;
}

$sql_total_num_sellers_day = 
	"select 
		count(USER_ID) as NumSellers, 
		date(PAYMENT_DATE) as TransactionDate 
	from 
		PAY.PAYMENT
	group by 
		date(PAYMENT_DATE) 
	order by 
		date(PAYMENT_DATE) DESC";
$result = mysql_query($sql_total_num_sellers_day, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TotalNumberOfSellers_Date[] = array(
  		"Date" 				=> $row['TransactionDate'], 
  		"Number of Sellers"	=> $row['NumSellers']);
}

$sql_total_num_sellers_month = 
	"select 
		count(USER_ID) as NumSellers, 
		date(PAYMENT_DATE) as TransactionDate,
		year(PAYMENT_DATE) as TransactionYear,
		month(PAYMENT_DATE) as TransactionMonth  
	from 
		PAY.PAYMENT
	group by 
		year(PAYMENT_DATE), month(PAYMENT_DATE)  
	order by 
		year(PAYMENT_DATE) DESC, month(PAYMENT_DATE) DESC";
$result = mysql_query($sql_total_num_sellers_month, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TotalNumberOfSellers_Month[] = array(
  		"Date" 				=> $row['TransactionDate'], 
  		"Number of Sellers"	=> $row['NumSellers'],
  		"Transaction Year"	=> $row['TransactionYear'],
  		"Transaction Month"	=> $row['TransactionMonth']);
}


// //////////////////////////////////////////////////////
// PRICE POINT - TICKET
$sql_avg_full_pricing_point = 
	"select 
		avg(VALUE) as AvgPricePoint 
	from PAY.PAYMENT";
$result = mysql_query($sql_avg_full_pricing_point, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $AvgFullPricePoint = ($row['AvgPricePoint'] != "" ? $row['AvgPricePoint'] : 0 ); 
}

$sql_avg_full_pricing_point_day = 
	"select 
		avg(VALUE) as AvgPricePoint,
		date(PAYMENT_DATE) as TransactionDate 
	from 
		PAY.PAYMENT 
	group by 
		date(PAYMENT_DATE) 
	order by 
		date(PAYMENT_DATE) DESC";	
$result = mysql_query($sql_avg_full_pricing_point_day, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $AvgFullPricePoint_Date[] = array(
  		"Date" 				=> $row['TransactionDate'], 
  		"Number of Sellers"	=> $row['AvgPricePoint']);
}


$sql_avg_full_pricing_point_month = 
	"select 
		avg(VALUE) as AvgPricePoint,
		date(PAYMENT_DATE) as TransactionDate,
		month(PAYMENT_DATE) as TransactionMonth,
		year(PAYMENT_DATE) as TransactionYear  
	from 
		PAY.PAYMENT 
	group by 
		year(PAYMENT_DATE), month(PAYMENT_DATE) 
	order by 
		year(PAYMENT_DATE) DESC, month(PAYMENT_DATE)";
$result = mysql_query($sql_avg_full_pricing_point_month, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $AvgFullPricePoint_Month[] = array(
  		"Date" 				=> $row['TransactionDate'], 
  		"Number of Sellers"	=> $row['AvgPricePoint'],
  		"Transaction Year"	=> $row['TransactionYear'],
  		"Transaction Month"	=> $row['TransactionMonth']);
}


// //////////////////////////////////////////////////////
// NUM TECKS AVAILABLE
$sql_num_total_tecks_available = 
	"select 
		count(POST_ID) as NumTecksForSale 
	from POST 
	where PRICE > 0 and IS_SOLD<>1";
$result = mysql_query($sql_num_total_tecks_available, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $NumTecksAvailable = $row['NumTecksForSale'] ;
}

$sql_num_total_tecks_available_day =
	"select 
		count(POST_ID) as NumTecksForSale,
		date(PUBLISH_DATE) as PublishDate 
	from 
		POST 
	where 
		PRICE > 0 and IS_SOLD<>1 
	group by 
		date(PUBLISH_DATE) 
	order by date(PUBLISH_DATE) DESC";
$result = mysql_query($sql_num_total_tecks_available_day, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $NumTecksAvailable_Date[] = array(
  		"Date" 						=> $row['PublishDate'], 
  		"Number of Tecks For Sale"	=> $row['NumTecksForSale']);
}


$sql_num_total_tecks_available_month = 
	"select 
		count(POST_ID) as NumTecksForSale,
		date(PUBLISH_DATE) as PublishDate,
		month(PUBLISH_DATE) as PublishMonth,
		year(PUBLISH_DATE) as PuclishYear 
	from 
		POST 
	where 
		PRICE > 0 and IS_SOLD=0 
	group by 
		year(PUBLISH_DATE), month(PUBLISH_DATE) 
	order by 
		year(PUBLISH_DATE) DESC, month(PUBLISH_DATE) DESC";
$result = mysql_query($sql_num_total_tecks_available_month, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $NumTecksAvailable_Month[] = array(
  		"Date" 						=> $row['PublishDate'], 
  		"Number of NumTecksForSale"	=> $row['NumTecksForSale'],
  		"Publication Year"			=> $row['PublishYear'],
  		"Publication Month"			=> $row['PublishMonth']);
}


// //////////////////////////////////////////////////////
// NUM TECKS SOLD
$sql_num_total_tecks_sold = 
	"select 
		count(POST_ID) as NumTecksSold 
	from POST 
	where PRICE > 0 and IS_SOLD=1";
$result = mysql_query($sql_num_total_tecks_sold, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $NumTecksSold = $row['NumTecksSold']; 
}


$sql_num_total_tecks_sold_day =
	"select 
		count(POST_ID) as NumTecksSold,
		date(PUBLISH_DATE) as PublishDate 
	from 
		POST 
	where 
		PRICE > 0 and IS_SOLD=1 
	group by 
		date(PUBLISH_DATE) 
	order by date(PUBLISH_DATE) DESC";
$result = mysql_query($sql_num_total_tecks_sold_day, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $NumTecksSold_Date[] = array(
  		"Date" 					=> $row['PublishDate'], 
  		"Number of Tecks Sold"	=> $row['NumTecksSold']);
}


$sql_num_total_tecks_sold_month = 
	"select 
		count(POST_ID) as NumTecksSold,
		date(PUBLISH_DATE) as PublishDate,
		year(PUBLISH_DATE) as PublishYear,
		month(PUBLISH_DATE) as PublishMonth  
	from 
		POST 
	where 
		PRICE > 0 and IS_SOLD=1 
	group by 
		year(PUBLISH_DATE), month(PUBLISH_DATE) 
	order by 
		year(PUBLISH_DATE) DESC, month(PUBLISH_DATE) DESC";
$result = mysql_query($sql_num_total_tecks_sold_month, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $NumTecksSold_Month[] = array(
  		"Date" 						=> $row['PublishDate'], 
  		"Number of NumTecksForSale"	=> $row['NumTecksSold'],
  		"Publication Year"			=> $row['PublishYear'],
  		"Publication Month"			=> $row['PublishMonth']);
}


// //////////////////////////////////////////////////////
// REVENUE GENERATED FROM TECK SELLING
$sql_teckler_revenue_from_content_selling = 
 "select 
 	(VALUE - USER_VALUE - FEE) as TecklerRevenue_USD 
 from 
 	PAY.PAYMENT";
$result = mysql_query($sql_teckler_revenue_from_content_selling, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TecklerRevenueEarnings = $row['TecklerRevenue_USD']; 
}

$sql_teckler_revenue_from_content_selling_day = 
 "select 
 	(VALUE - USER_VALUE - FEE) as TecklerRevenue_USD,
 	date(PAYMENT_DATE) as TransactionDate 
 from 
 	PAY.PAYMENT 
 group by 
 	date(PAYMENT_DATE) 
 order by 
 	DATE(PAYMENT_DATE) DESC";
$result = mysql_query($sql_teckler_revenue_from_content_selling_day, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TecklerRevenueEarnings_Date[] = array(
  		"Date" 					=> $row['TransactionDate'], 
  		"Teckler Revenue USD"	=> $row['TecklerRevenue_USD']);
}


$sql_teckler_revenue_from_content_selling_month = 
 "select 
 	(VALUE - USER_VALUE - FEE) as TecklerRevenue_USD,
 	date(PAYMENT_DATE) as TransactionDate,
 	year(PAYMENT_DATE) as TransactionYear, 
 	month(PAYMENT_DATE) as TransactionMonth  
 from 
 	PAY.PAYMENT 
 group by 
 	year(PAYMENT_DATE), month(PAYMENT_DATE) 
 order by 
 	year(PAYMENT_DATE) DESC, month(PAYMENT_DATE) DESC";

$result = mysql_query($sql_teckler_revenue_from_content_selling_month, $conn);
while ($row = mysql_fetch_assoc($result)) 
{
  $TecklerRevenueEarnings_Month[] = array(
  		"Date" 						=> $row['TransactionDate'], 
  		"Teckler Revenue USD"		=> $row['TecklerRevenue_USD'],
  		"Publication Year"			=> $row['TransactionYear'],
  		"Publication Month"			=> $row['TransactionMonth']);
}

?>

<HTML>
	<HEAD>
		<TITLE>Metrics Dashboard - Content Selling </TITLE>
	</HEAD>
	<BODY>
		<H1> Content Selling Metrics </H1>
		<P>Consolidated Metrics - All Times</P>
		<TABLE border=1>
			<TR>
				<TH>Total Number of Buyers</TH>
				<TH>Total Number of Sellers</TH>
				<TH>Number of Tecks Available</TH>
				<TH>Numbers of Tecks Already Sold</TH>
				<TH>Average Price Point</TH>
			</TR>
			<TR align="center">
				<TD><?php echo $TotalNumberOfBuyers ; ?></TD>
				<TD><?php echo $TotalNumberOfSellers; ?></TD>
				<TD><?php echo $NumTecksAvailable; ?></TD>
				<TD><?php echo $NumTecksSold; ?></TD>
				<TD><?php echo $AvgFullPricePoint; ?></TD>
			</TR>
		</TABLE>
	</BODY>
</HTML>