<?php

function getCountryName($html){
	$docRank = new DOMDocument();
	@$docRank->loadHTML($html);
	$country_name = $docRank->getElementsByTagName('a')->item(0);
	$NameRank = (innerHTML($country_name));
	//DEBUG:: echo "Country Name is:  <B>". $NameRank . "</B> with <B>";
	return $NameRank; 
}

function getPercTraffic($html){
	$docRank = new DOMDocument();
	@$docRank->loadHTML($html);
	$country_traffic = $docRank->getElementsByTagName('span')->item(0);
	$PercTraffic =  (innerHTML($country_traffic));
	//DEBUG:: echo "Percentage of traffic is: " . $PercTraffic . "</B><BR>\n";
	return $PercTraffic;
}

function getCountryRanking($html){
	$docRank = new DOMDocument();
	@$docRank->loadHTML($html);
	$country_rank = $docRank->getElementsByTagName('span')->item(1);
	$NumRank =  (innerHTML($country_rank));
	//DEBUG:: echo "Ranking is: ". $NumRank . "</B><BR>\n";
	return $NumRank;
}


function innerHTML($el) {
  $doc = new DOMDocument();
  $doc->appendChild($doc->importNode(@$el, TRUE));
  $html = trim($doc->saveHTML());
  $tag = $el->nodeName;
  return preg_replace('@^<' . $tag . '[^>]*>|</' . $tag . '>$@', '', $html);
}

date_default_timezone_set("America/Sao_Paulo");

echo "Populating Alexa Ranking Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$InformedDateIs = date("Y-m-d");	
} else {
	$InformedDateIs = date("Y-m-d", strtotime($_GET['day']));
}
$ReferenceDayIs = date("Y-m-d", (time()-86400));

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Alexa Ranking Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Informed Date is: " . $InformedDateIs . "\n";
echo "[Info] - This Script does not used any user informed date... \n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

// Connecting to DB Server
require "conn.php";

// echo "[Debug] - Trying to connect to the Source Database...\n";
// $conn = mysql_connect("localhost","root", "captain") or die("[Error] - Could not connect to Database: " . mysql_error());

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Alexa Ranking Metrics from Alexa WebSite
//
// ////////////////////////////////////////////////////////////////////////

$AlexaURL = "http://www.alexa.com/siteinfo/teckler.com#trafficstats";
//$AlexaURL = "http://www.alexa.com/siteinfo/parperfeito.com.br#trafficstats";

/// Initializing
$GlobalRank = "";
$BrazilRank = "";
$UnitedStatesRank = "";

// Fetching Alexa URL
$arrayTP = file($AlexaURL, FILE_SKIP_EMPTY_LINES );

/* 
echo "----------------- Starting Debugging ----------------\n";
echo "<pre>";
print_r($arrayTP);
echo "</pre>";
*/

// //////////////////////////////////////////////////
// Obtaining Index Keys
$j=0;
foreach ($arrayTP as $key => $value) {
	if ( (strstr($value,"Global rank icon", true)) and (strstr($value,"icon-inline", true)) ){
		$tmp_GlobalRankKey = $j;
	} else {
		if ( (strstr($value,"Brazil Flag", true)) and (strstr($value,"icon-inline", true)) ){
			$tmp_BrazilRankKey = $j;
		} else {
			if ( (strstr($value,"/topsites/countries/", true)) and (strstr($value," Flag", true)) ){
				$tmp_Top1RankKey  = $j;  	$tmp_Top2RankKey  = $j+2;  
				$tmp_Top3RankKey  = $j+4;  	$tmp_Top4RankKey  = $j+6;  
				$tmp_Top5RankKey  = $j+8;  	$tmp_Top6RankKey  = $j+10; 
				$tmp_Top7RankKey  = $j+12; 	$tmp_Top8RankKey  = $j+14; 
				$tmp_Top9RankKey  = $j+16; 	$tmp_Top10RankKey = $j+18; 
				$j = $j+18;
				break;
			}		
		}
	}
	$j++;
	//echo "<!-- Debugging info : value of J is $j \n-->";
		
}

// //////////////////////////////////////////////////////////
// Obtaining RAW Information Strings

// Global Rank
$str_globalRank 	= $arrayTP[$tmp_GlobalRankKey]; $str_brazilRank 	= $arrayTP[$tmp_BrazilRankKey];

// TOP 10 RANK
$countryRank_TOP01 	= $arrayTP[$tmp_Top1RankKey]; 	$countryRank_TOP02 	= $arrayTP[$tmp_Top2RankKey];
$countryRank_TOP03 	= $arrayTP[$tmp_Top3RankKey]; 	$countryRank_TOP04 	= $arrayTP[$tmp_Top4RankKey];
$countryRank_TOP05 	= $arrayTP[$tmp_Top5RankKey]; 	$countryRank_TOP06 	= $arrayTP[$tmp_Top6RankKey];
$countryRank_TOP07 	= $arrayTP[$tmp_Top7RankKey]; 	$countryRank_TOP08 	= $arrayTP[$tmp_Top8RankKey];
$countryRank_TOP09 	= $arrayTP[$tmp_Top9RankKey]; 	$countryRank_TOP10 	= $arrayTP[$tmp_Top10RankKey];
$MaxAlexaPositions = 10;

// Checking for "empty positions in rankings
if (empty($countryRank_TOP10)){
	echo "[DEBUG] -- The 10th position is empty. \n";
	$isempty_top10 = true;
	$MaxAlexaPositions = 9;

}


// Debuging info
/* 
echo "<BR>"; echo "\n Global Rank: " 				. $str_globalRank;  	
echo "<BR>"; echo "\n Brazil Rank: " 				. $str_brazilRank;
echo "<BR>"; echo "\n TOP 01 Country in Traffic:  " . $countryRank_TOP01; 	
echo "<BR>"; echo "\n TOP 02 Country in Traffic:  " . $countryRank_TOP02;
echo "<BR>"; echo "\n TOP 03 Country in Traffic:  " . $countryRank_TOP03;	
echo "<BR>"; echo "\n TOP 04 Country in Traffic:  " . $countryRank_TOP04;
echo "<BR>"; echo "\n TOP 05 Country in Traffic:  " . $countryRank_TOP05;	
echo "<BR>"; echo "\n TOP 06 Country in Traffic:  " . $countryRank_TOP06;
echo "<BR>"; echo "\n TOP 07 Country in Traffic:  " . $countryRank_TOP07;	
echo "<BR>"; echo "\n TOP 08 Country in Traffic:  " . $countryRank_TOP08;
echo "<BR>"; echo "\n TOP 09 Country in Traffic:  " . $countryRank_TOP09; 	
echo "<BR>"; echo "\n TOP 10 Country in Traffic:  " . $countryRank_TOP10;
echo "<BR>";
*/

// ///////////////////////////////////////
// Get Global Info
echo "[DEBUG] -- Getting Global Ranking:";
$docGlobalRank = new DOMDocument();
@$docGlobalRank->loadHTML($str_globalRank);
$bodyGlobalRank = $docGlobalRank->getElementsByTagName('a')->item(0);
echo $NumGlobalRank =  htmlspecialchars(innerHTML($bodyGlobalRank));
echo "\n";

echo "[DEBUG] -- Getting Brazil Ranking:";
$docBrazilRank = new DOMDocument();
@$docBrazilRank->loadHTML($str_brazilRank);
$bodyBrazilRank = $docBrazilRank->getElementsByTagName('a')->item(0);
echo $NumBrazilRank =  htmlspecialchars(innerHTML($bodyBrazilRank));
echo "\n"; 

// /////////////////////////////////////
// Setting new string for Reference Day, including  ''
$ReferenceDayIs = "'" . $ReferenceDayIs . "'";

// Get Info for Global Ranking
$RankedCountry[0]['Date'] 		= $ReferenceDayIs;
$RankedCountry[0]['Position'] 	= 0;
$RankedCountry[0]['Name'] 		= "Global";
$RankedCountry[0]['Traffic'] 	= "100%";
$RankedCountry[0]['Ranking'] 	= $NumGlobalRank;

// Get Info for TOP 1 Country
$RankedCountry[1]['Date'] 		= $ReferenceDayIs;
$RankedCountry[1]['Position'] 	= 1;
$RankedCountry[1]['Name'] 		= getCountryName($countryRank_TOP01);
$RankedCountry[1]['Traffic']	= getPercTraffic($countryRank_TOP01);
$RankedCountry[1]['Ranking'] 	= getCountryRanking($countryRank_TOP01);

// Get Info for TOP 2 Country
$RankedCountry[2]['Date'] 		= $ReferenceDayIs;
$RankedCountry[2]['Position'] 	= 2;
$RankedCountry[2]['Name'] 		= getCountryName($countryRank_TOP02);
$RankedCountry[2]['Traffic'] 	= getPercTraffic($countryRank_TOP02);
$RankedCountry[2]['Ranking'] 	= getCountryRanking($countryRank_TOP02);

// Get Info for TOP 3 Country
$RankedCountry[3]['Date'] 		= $ReferenceDayIs;
$RankedCountry[3]['Position'] 	= 3;
$RankedCountry[3]['Name'] 		= getCountryName($countryRank_TOP03);
$RankedCountry[3]['Traffic'] 	= getPercTraffic($countryRank_TOP03);
$RankedCountry[3]['Ranking'] 	= getCountryRanking($countryRank_TOP03);

// Get Info for TOP 4 Country
$RankedCountry[4]['Date'] 		= $ReferenceDayIs;
$RankedCountry[4]['Position'] 	= 4;
$RankedCountry[4]['Name'] 		= getCountryName($countryRank_TOP04);
$RankedCountry[4]['Traffic'] 	= getPercTraffic($countryRank_TOP04);
$RankedCountry[4]['Ranking'] 	= getCountryRanking($countryRank_TOP04);

// Get Info for TOP 5 Country
$RankedCountry[5]['Date'] 		= $ReferenceDayIs;
$RankedCountry[5]['Position'] 	= 5;
$RankedCountry[5]['Name'] 		= getCountryName($countryRank_TOP05);
$RankedCountry[5]['Traffic'] 	= getPercTraffic($countryRank_TOP05);
$RankedCountry[5]['Ranking'] 	= getCountryRanking($countryRank_TOP05);

// Get Info for TOP 6 Country
$RankedCountry[6]['Date'] 		= $ReferenceDayIs;
$RankedCountry[6]['Position'] 	= 6;
$RankedCountry[6]['Name'] 		= getCountryName($countryRank_TOP06);
$RankedCountry[6]['Traffic'] 	= getPercTraffic($countryRank_TOP06);
$RankedCountry[6]['Ranking'] 	= getCountryRanking($countryRank_TOP06);

// Get Info for TOP 7 Country
$RankedCountry[7]['Date'] 		= $ReferenceDayIs;
$RankedCountry[7]['Position'] 	= 7;
$RankedCountry[7]['Name'] 		= getCountryName($countryRank_TOP07);
$RankedCountry[7]['Traffic'] 	= getPercTraffic($countryRank_TOP07);
$RankedCountry[7]['Ranking'] 	= getCountryRanking($countryRank_TOP07);

// Get Info for TOP 8 Country
$RankedCountry[8]['Date'] 		= $ReferenceDayIs;
$RankedCountry[8]['Position'] 	= 8;
$RankedCountry[8]['Name'] 		= getCountryName($countryRank_TOP08);
$RankedCountry[8]['Traffic'] 	= getPercTraffic($countryRank_TOP08);
$RankedCountry[8]['Ranking'] 	= getCountryRanking($countryRank_TOP08);

// Get Info for TOP 9 Country
$RankedCountry[9]['Date'] 		= $ReferenceDayIs;
$RankedCountry[9]['Position'] 	= 9;
$RankedCountry[9]['Name'] 		= getCountryName($countryRank_TOP09);
$RankedCountry[9]['Traffic'] 	= getPercTraffic($countryRank_TOP09);
$RankedCountry[9]['Ranking'] 	= getCountryRanking($countryRank_TOP09);

// Get Info for TOP 10 Country
if (!isempty_top10) {
	$RankedCountry[10]['Date'] 		= $ReferenceDayIs;
	$RankedCountry[10]['Position'] 	= 10;
	$RankedCountry[10]['Name'] 		= getCountryName($countryRank_TOP10);
	$RankedCountry[10]['Traffic'] 	= getPercTraffic($countryRank_TOP10);
	$RankedCountry[10]['Ranking'] 	= getCountryRanking($countryRank_TOP10);
} 

 
echo "----------------- Starting Debugging ----------------\n";
echo "<pre>";
var_dump($RankedCountry);
echo "</pre>";


// ///////////////////////////////////
// Get US and BR Indexes

echo "[Debug] - Starting to find US and BR Index Keys.\n";
echo "[Debug] - Meanwhile, removing ',' thousand separator and performing html entities into Name field...\n";
$i=0;
foreach ($RankedCountry as $RankKey) {
	//echo "Rank is: " . $i . "<BR>\n";
	foreach ($RankKey as $key => $value) {
		//echo "Key is: " . $key . " || \n";
		//echo "Value is: " . $value . "<BR>\n";
		if (strstr($value,"Brazil", true)) {
				$BrazilRankKey = $i;
			} else {
			if (strstr($value, "United States", true) ){
				$UnitedStatesRankKey = $i;
			} 
		}
		if ($key=="Ranking") 
			$value = str_replace(",", "", $value);

		if ($key=="Name") 
			$value = htmlentities($value);

	}
	$i++;
}

// Fetching BR and US Values, using the keys just discovered 
$Rank_GlobalRanking     = $RankedCountry[0]['Ranking'];
$Rank_BRARanking 	= $RankedCountry[$BrazilRankKey]['Ranking'];
$Rank_USARanking	= $RankedCountry[$UnitedStatesRankKey]['Ranking'];

// Ranking Info from Alexa Comes with ',' (comma).
// Using str_replace to remove ',' thousand separator.
$Rank_GlobalRanking = str_replace(",", "", $Rank_GlobalRanking);
$Rank_BRARanking 	= str_replace(",", "", $Rank_BRARanking);
$Rank_USARanking 	= str_replace(",", "", $Rank_USARanking);

echo "[Info] - Teckler Global Rank is: " . $Rank_GlobalRanking 	. "\n";
echo "[Info] - Teckler Brazil Rank is: " . $Rank_BRARanking 	. "\n";
echo "[Info] - Teckler USA Rank is: " . $Rank_USARanking . "\n";

echo "[Debug] - Cleaning Up html tags and special characters for each of the top 10 info...\n";
for ($j=1; $j<$MaxAlexaPositions; $j++){
	$RankedCountry[$j]['Name'] 		= "'" . trim(str_replace("&nbsp;", "", strip_tags($RankedCountry[$j]['Name']))) . "'";
	$RankedCountry[$j]['Traffic'] 	= str_replace("%", "", $RankedCountry[$j]['Traffic']);
	$RankedCountry[$j]['Ranking'] 	= str_replace(",", "", $RankedCountry[$j]['Ranking']);
	echo "[Info] - TOP $j Country info is: ". 
							"Country Name:" 		. $RankedCountry[$j]['Name'] . 
						" || Traffic Percentage: " 	. $RankedCountry[$j]['Traffic'] . 
						" || Ranking: " 			. $RankedCountry[$j]['Ranking']	. "\n";

	$insert_value[$j] = "(" . implode(",", $RankedCountry[$j]) . ")";
	//echo $insert_value[$j];
}
$insert_value_statement = implode(",", $insert_value);
echo "[Debug] - The final information about the TOP 10 countries are: " . $insert_value_statement . "\n";

/*
// START DEBUGING
echo "<pre>";
print_r($RankedCountry);
echo "</pre>";
// END DEBUGING
*/

// ///////////////////////////////////////////////////////////////////////
//
// Saving Consolidated Metrics into INTRANET DB
//
// //////////////////////////////////////////////////////////////////////

// Preparing for Insertion
echo "[Debug] - Selecting DB Intranet for Insertion ...\n";
mysql_select_db(W_DB, $conn_w) or die('[Error] - Could not select database; ' . mysql_error());

// Consolidated Metrics - GLOBAL INFO TOTAL
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_cons_metrics_alexa_global = 
	"insert ignore into CONS_METRICS_ALEXA_GLOBAL_DAY 
		(DATE, GLOBAL_RANK, BRA_RANK, USA_RANK)  
	values 
		(". $ReferenceDayIs . ", $Rank_GlobalRanking, $Rank_BRARanking, $Rank_USARanking)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_alexa_global . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_alexa_global, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Alexa Ranking - Global Ranking - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Alexa Ranking - Global Ranking - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}


// ////////////////////////////////////////
// Consolidated Metrics - TOP 10 RANK 
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_cons_metrics_alexa_top10 = 
	"insert ignore into CONS_METRICS_ALEXA_TOP10RANK_DAY 
		(DATE, RANK_POSITION_NUMBER, RANK_POSITION_COUNTRY, RANK_POSITION_TRAFFIC_PERCENT, RANK_POSITION_RANKING)  
	values " . $insert_value_statement;

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_alexa_top10 . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_alexa_top10, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Alexa Ranking - TOP 10 Ranking - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Alexa Ranking - TOP 10 Ranking - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Alexa Ranking Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>
