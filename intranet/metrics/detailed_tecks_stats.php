<?php

// include "../includes/header.php";
require "conn.php";

date_default_timezone_set('America/Sao_Paulo');

$sql_tecks_p_day_lang_type = "select count(POST_ID) as NumTecks, CREATION_DATE as PublishedDate, LANGUAGE_CODE as TeckLanguage, TYPE as TeckType from POST group by date(CREATION_DATE), LANGUAGE_CODE, TYPE order by CREATION_DATE DESC, LANGUAGE_CODE ASC, TYPE ASC";

$result = mysql_query($sql_tecks_p_day_lang_type, $conn);
if (mysql_num_rows($result) == 0) {
	echo " <br>\n";
	echo " Opps.. Somethint went wrong. Contact you administrator! \n";
} else {

?>
<HTML>
<HEAD><TITLE>Teck info - Breakdown per Day, Language and TeckType</TITLE></HEAD>
<BODY>
<H1> Detail Teck info </H1>
<H2> Breakdown Per Day/Language/TeckType</H2>
<TABLE border=1>
<TR>
<TH>Date</TH>
<TH>Language</TH>
<TH>Teck Type</TH>
<TH># Tecks</TH>

</TR>

<?php
	//$report_date = "";
	while ($row = mysql_fetch_array($result)) {
		//if ($report_date <> date('Y-m-d',strtotime($row['PublishedDate']))){
			echo "<TR>\n";
			//$report_date = date('Y-m-d',strtotime($row['PublishedDate']));
			echo "<TD>". date('Y-m-d', strtotime($row['PublishedDate'])) . "</TD>\n"; 
			//$teck_langcode = $row['TeckLanguage'];
                        //$teck_type = $row['TeckType'];
                        //$teck_sum = $row['NumTecks'];
			echo "<TD>&nbsp; " . $row['TeckLanguage'] . "</TD>\n";
			echo "<TD>&nbsp; ". $row['TeckType'].  "</TD>\n";
			echo "<TD>&nbsp; " . $row['NumTecks'] . "</TD>\n";
                        //echo $teck_langcode . " : " .  $teck_type . " => " . $teck_sum . " </TD>\n";
			echo "</TR>\n"; 
		//} else{
			//echo "<TD>\n";
			//$teck_langcode = $row['TeckLanguage'];
			//$teck_type = $row['TeckType'];
			//$teck_sum = $row['NumTecks'];
			//echo $teck_langcode . " : " .  $teck_type ." => " . $teck_sum . " </TD>\n";
		//} 
        	echo "</TR>\n"; 
	}
}
 
?>
</TABLE>
<H3>Trying an New Format</H3>
<?php

$lang_options = array(
	0 => "ar", 
	1 => "de", 
	2 => "en", 
	3 => "es", 
	4 => "fr", 
	5 => "he", 
	6 => "hi" ,  
	7 => "it", 
	8 => "jp", 
	9 => "ko", 
	10 => "pt", 
	11 => "ru", 
	11 => "zh");
$type_options = array(
	0 =>"a", 
	1 => "i", 
	2 => "t", 
	3 => "v" );



?>
</BODY>
</HTML>
