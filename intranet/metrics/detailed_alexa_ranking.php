<?php
include "../includes/header.php";

$AlexaURL = "http://www.alexa.com/siteinfo/teckler.com#trafficstats";
//$AlexaURL = "http://www.alexa.com/siteinfo/parperfeito.com.br#trafficstats";

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

/// Initializing
$GlobalRank = "";
$BrazilRank = "";
$UnitedStatesRank = "";

$arrayTP = file($AlexaURL, FILE_SKIP_EMPTY_LINES );

// Obtaining Key Indexes

$j=0;
foreach ($arrayTP as $key => $value) {
	if ( (strstr($value,"Global rank icon", true)) and (strstr($value,"icon-inline", true)) ){
		$tmp_GlobalRankKey = $j;
	} else {
		if ( (strstr($value,"Brazil Flag", true)) and (strstr($value,"icon-inline", true)) ){
			$tmp_BrazilRankKey = $j;
		} else {
			if ( (strstr($value,"/topsites/countries/", true)) and (strstr($value," Flag", true)) ){
				$tmp_Top1RankKey  = $j;  
				$tmp_Top2RankKey  = $j+2;  
				$tmp_Top3RankKey  = $j+4;  
				$tmp_Top4RankKey  = $j+6;  
				$tmp_Top5RankKey  = $j+8;  
				$tmp_Top6RankKey  = $j+10; 
				$tmp_Top7RankKey  = $j+12; 
				$tmp_Top8RankKey  = $j+14; 
				$tmp_Top9RankKey  = $j+16; 
				$tmp_Top10RankKey = $j+18; 
				$j = $j+18;
				break;
			}		
		}
	}
	$j++;
	echo "<!-- Debugging info : value of J is $j \n-->";
		
}


// Obtaining RAW Information Strings

$str_globalRank 	= $arrayTP[$tmp_GlobalRankKey]; $str_brazilRank 	= $arrayTP[$tmp_BrazilRankKey];
$countryRank_TOP01 	= $arrayTP[$tmp_Top1RankKey]; 	$countryRank_TOP02 	= $arrayTP[$tmp_Top2RankKey];
$countryRank_TOP03 	= $arrayTP[$tmp_Top3RankKey]; 	$countryRank_TOP04 	= $arrayTP[$tmp_Top4RankKey];
$countryRank_TOP05 	= $arrayTP[$tmp_Top5RankKey]; 	$countryRank_TOP06 	= $arrayTP[$tmp_Top6RankKey];
$countryRank_TOP07 	= $arrayTP[$tmp_Top7RankKey]; 	$countryRank_TOP08 	= $arrayTP[$tmp_Top8RankKey];
$countryRank_TOP09 	= $arrayTP[$tmp_Top9RankKey]; 	$countryRank_TOP10 	= $arrayTP[$tmp_Top10RankKey];


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

$docGlobalRank = new DOMDocument();
@$docGlobalRank->loadHTML($str_globalRank);
$bodyGlobalRank = $docGlobalRank->getElementsByTagName('a')->item(0);
$NumGlobalRank =  htmlspecialchars(innerHTML($bodyGlobalRank));


$docBrazilRank = new DOMDocument();
@$docBrazilRank->loadHTML($str_brazilRank);
$bodyBrazilRank = $docBrazilRank->getElementsByTagName('a')->item(0);
$NumBrazilRank =  htmlspecialchars(innerHTML($bodyBrazilRank));

$RankedCountry[0]['Name'] = "Global";
$RankedCountry[0]['Traffic'] = "100%";
$RankedCountry[0]['Ranking'] = $NumGlobalRank;


// Get Info for Global Ranking
$RankedCountry[0]['Name'] = "Global";
$RankedCountry[0]['Traffic'] = "100%";
$RankedCountry[0]['Ranking'] = $NumGlobalRank;

// Get Info for TOP 1 Country
$RankedCountry[1]['Name'] 	= getCountryName($countryRank_TOP01);
$RankedCountry[1]['Traffic'] = getPercTraffic($countryRank_TOP01);
$RankedCountry[1]['Ranking'] = getCountryRanking($countryRank_TOP01);

// Get Info for TOP 2 Country
$RankedCountry[2]['Name'] = getCountryName($countryRank_TOP02);
$RankedCountry[2]['Traffic'] = getPercTraffic($countryRank_TOP02);
$RankedCountry[2]['Ranking'] = getCountryRanking($countryRank_TOP02);

// Get Info for TOP 3 Country
$RankedCountry[3]['Name'] = getCountryName($countryRank_TOP03);
$RankedCountry[3]['Traffic'] = getPercTraffic($countryRank_TOP03);
$RankedCountry[3]['Ranking'] = getCountryRanking($countryRank_TOP03);

// Get Info for TOP 4 Country
$RankedCountry[4]['Name'] = getCountryName($countryRank_TOP04);
$RankedCountry[4]['Traffic'] = getPercTraffic($countryRank_TOP04);
$RankedCountry[4]['Ranking'] = getCountryRanking($countryRank_TOP04);

// Get Info for TOP 5 Country
$RankedCountry[5]['Name'] = getCountryName($countryRank_TOP05);
$RankedCountry[5]['Traffic'] = getPercTraffic($countryRank_TOP05);
$RankedCountry[5]['Ranking'] = getCountryRanking($countryRank_TOP05);

// Get Info for TOP 6 Country
//$RankedCountry[6]['Name'] = getCountryName($countryRank_TOP06);
//$RankedCountry[6]['Traffic'] = getPercTraffic($countryRank_TOP06);
//$RankedCountry[6]['Ranking'] = getCountryRanking($countryRank_TOP06);

// Get Info for TOP 7 Country
//$RankedCountry[7]['Name'] = getCountryName($countryRank_TOP07);
//$RankedCountry[7]['Traffic'] = getPercTraffic($countryRank_TOP07);
//$RankedCountry[7]['Ranking'] = getCountryRanking($countryRank_TOP07);

// Get Info for TOP 8 Country
//$RankedCountry[8]['Name'] = getCountryName($countryRank_TOP08);
//$RankedCountry[8]['Traffic'] = getPercTraffic($countryRank_TOP08);
//$RankedCountry[8]['Ranking'] = getCountryRanking($countryRank_TOP08);

// Get Info for TOP 9 Country
//$RankedCountry[9]['Name'] = getCountryName($countryRank_TOP09);
//$RankedCountry[9]['Traffic'] = getPercTraffic($countryRank_TOP09);
//$RankedCountry[9]['Ranking'] = getCountryRanking($countryRank_TOP09);

// Get Info for TOP 10 Country
//$RankedCountry[10]['Name'] = getCountryName($countryRank_TOP10);
//$RankedCountry[10]['Traffic'] = getPercTraffic($countryRank_TOP10);
//$RankedCountry[10]['Ranking'] = getCountryRanking($countryRank_TOP10);


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
                	<h1>Relatórios - Alexa Ranking</h1> 
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
         	</div>
         </div>

  

<?php

echo "<h1>Alexa Ranking Info</h1>\n";
echo "<h4> Obtaining info from Alexa web site<br>\n";
echo "using URL: $AlexaURL </h4>\n";

echo "";


// START DEBUG
/* 
echo "<PRE>";
print_r($arrayTP);
echo "</PRE>";

echo "<PRE>";
print_r($RankedCountry);
echo "</PRE>";
*/ // END DEBUG

/* 
echo "The Global Rank Key is:". $tmp_GlobalRankKey . " <BR>\n";
echo "The Brazil Rank Key is:". $tmp_BrazilRankKey . " <BR>\n";
echo "TOP 1 Rank Key is: "  	. $tmp_Top1RankKey . "<br>\n";
echo "TOP 2 Rank Key is: "  	. $tmp_Top2RankKey . "<br>\n";
echo "TOP 3 Rank Key is: "  	. $tmp_Top3RankKey . "<br>\n";
echo "TOP 4 Rank Key is: "  	. $tmp_Top4RankKey . "<br>\n";
echo "TOP 5 Rank Key is: "  	. $tmp_Top5RankKey . "<br>\n";
echo "TOP 6 Rank Key is: "  	. $tmp_Top6RankKey . "<br>\n";
echo "TOP 7 Rank Key is: "  	. $tmp_Top7RankKey . "<br>\n";
echo "TOP 8 Rank Key is: "  	. $tmp_Top8RankKey . "<br>\n";
echo "TOP 9 Rank Key is: "  	. $tmp_Top9RankKey . "<br>\n";
echo "TOP 10 Rank Key is: " 	. $tmp_Top10RankKey . "<br>\n";
*/

$i=0;
foreach ($RankedCountry as $RankKey) {
	//print_r($RankKey);
	//echo "Rank is: " . $i . "<BR>\n";
	foreach ($RankKey as $key => $value) {
		//echo "Key is: " . $key . " || \n";
		//echo "Value is: " . $value . "<BR>\n";
		if (strstr($value,"Brazil", true)) {
				$BrazilRank = $i;
			} else {
			if (strstr($value, "United States", true) ){
				$UnitedStatesRank = $i;
			} 
		}


	}
	$i++;
}

?>
	<!-- Alexa Key Rankings -->
	<div class="grid grid_table">
		<div class="grid-content">
			<table class="table table-stripped">
				<tr>
					<th>Global Rank</th> <th>Brazil Rank</th> <th>United States Rank</th>
				</tr>
				<tr>
					<td><?php echo $RankedCountry[0]['Ranking'];?></td>
					<td><?php echo $RankedCountry[$BrazilRank]['Ranking'];?></td>
					<td><?php echo $RankedCountry[$UnitedStatesRank]['Ranking'];?></td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Alexa Top 10 Ranked Countries -->
	<div class="grid grid_table">
		<div class="grid-content">
			<table class="table table-stripped">
				<tr>
					<th>Rank</th> <th>Country</th> <th>Traffic Percentage</th> <th>Ranking Number</th>
				</tr>
				<?php
				for ($k=1; $k<=5; $k++ ){
					echo "<tr> <!-- TOP $k -->";
					echo 	"<td>$k</td>";
					echo 	"<td>" . $RankedCountry[$k]['Name']. "</td>";
					echo 	"<td>" . $RankedCountry[$k]['Traffic']. "</td>";
					echo 	"<td>" . $RankedCountry[$k]['Ranking']. "</td>\n";
					echo "</tr> <!-- End of TOP $k -->";
				}
				?>
			</table>
		</div>
	</div>
</div>
<?php include "../includes/footer.php"; ?>
