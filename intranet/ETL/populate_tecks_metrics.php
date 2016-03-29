<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Teck Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$ReferenceDayIs = date("Y-m-d");	
} else {
	$ReferenceDayIs = date("Y-m-d", strtotime($_GET['day']));
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Teck Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

// Connecting to DB Server
require "conn.php";

// Initializing Variables
$NumTotalTecks 				= 0;
$NumTotalPublishedTecks		= 0;
$AvgTecksPerProfile			= 0;
$AvgTecksPerActiveProfile 	= 0;
$NumInactiveProfiles 		= 0;
$rtTeck_PubTeck 			= 0;

$NumTotalTecksDay 			= 0;
$NumTotalPublishedTecksDay 	= 0;
$rtTeck_PubTeckDay 			= 0;
$AvgTecksPerProfileDay 		= 0;
$AvgTecksPerActiveProfileDay = 0;
$NumInactiveProfilesDay 	= 0;

$NumBlogsImportsTotal = 0 ;
$NumBlogsImportsDay = 0 ;

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating USER/Profiles Metrics from DB TECKLER
//
// //////////////////////////////////////////////////////////////////////

echo "[Debug] - Selecting DB Teckler for Consumption...\n";
mysql_select_db(R_DB_T, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for USER and PROFILE information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for TECKS... \n";


	// TECK METRICS
		// Metric - TOTAL USERS
		$sql_total_num_tecks = 
			"select count(POST_ID) as NumTotalTecks, date(CREATION_DATE) as CreationDate from POST where date(CREATION_DATE)<='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_tecks, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Tecks: " . $row['NumTotalTecks']. "\n";
			$NumTotalTecks = $row['NumTotalTecks'];	
		}

		// Metric - TOTAL TECKS OF THE DAY
		$sql_total_num_tecks_day = 
			"select count(POST_ID) as NumTotalTecksDay, date(CREATION_DATE) as CreationDate from POST where date(CREATION_DATE)='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_tecks_day, $conn_r);
		while ($row = mysql_fetch_assoc($result)){
	   		if (is_array($row)){
	        	echo "[Info] - Total Number of Tecks of this day: " . $row['NumTotalTecksDay']. "\n";
				$NumTotalTecksDay = $row['NumTotalTecksDay'];	
			}
		}


	// PUBLISHED TECKS METRICS
		// Metric - TOTAL PUBLISHED TECKS
		$sql_total_published_tecks = 
			"select count(POST_ID) as NumTotalPublishedTecks, date(CREATION_DATE) as CreationDate from POST where STATUS_ID=1 and date(CREATION_DATE)<='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_published_tecks, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Published Tecks: " . $row['NumTotalPublishedTecks']. "\n";
			$NumTotalPublishedTecks = $row['NumTotalPublishedTecks'];
	    }

		// Metric - TOTAL PUBLISHED TECKS OF THE DAY
		$sql_total_published_tecks_day = 
			"select count(POST_ID) as NumTotalPublishedTecksDay, date(CREATION_DATE) as CreationDate from POST where STATUS_ID=1 and date(CREATION_DATE)='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_published_tecks_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Published Tecks of this Day: " . $row['NumTotalPublishedTecksDay']. "\n";
			$NumTotalPublishedTecksDay = $row['NumTotalPublishedTecksDay'];
	    }

	// RATIO : PUBLISHED TECKS / TECKS
		// Metric - RATIO PUBLISHED TECKS / TECKS
		if ($NumTotalTecks!=0) {
			$rtTeck_PubTeck = $NumTotalPublishedTecks / $NumTotalTecks;
		}
		echo "[Info] - Ratio of Published Tecks (Published Tecks / Tecks): " . $rtTeck_PubTeck . "\n";

		// Metric - RATIO PUBLISHED TECKS / TECKS
		if ($NumTotalTecksDay!=0) {
			$rtTeck_PubTeckDay = $NumTotalPublishedTecksDay / $NumTotalTecksDay;
		}
		echo "[Info] - Ratio of Published Tecks (Published Tecks / Tecks) of this Day: " . $rtTeck_PubTeckDay . "\n";


    // TECKS PER PROFILE
		// AVG TECKS PER PROFILE 
		$sql_avg_tecks_profiles =  
		"select a.Tecks/b.Profiles as AvgTecksPerProfile from ". 
			"(select count(POST_ID) as Tecks from POST where date(CREATION_DATE)<='" . $ReferenceDayIs . "') a, ". 
			"(select count(PROFILE_ID) as Profiles from PROFILE where date(PROFILE_CREATION_DATE)<='" . $ReferenceDayIs . "') b"; 
		$result = mysql_query($sql_avg_tecks_profiles, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	    	if (is_array($row)) {
            	echo "[Info] - Average Number of Tecks Per Profile: " . $row['AvgTecksPerProfile']. "\n";
				$AvgTecksPerProfile = $row['AvgTecksPerProfile'];
	    	} 
		} 
	    
		// AVG TECKS PER PROFILE OF THIS DAY
		$sql_avg_tecks_profiles_day =  
		"select a.Tecks/b.Profiles as AvgTecksPerProfileDay from ". 
			"(select count(POST_ID) as Tecks from POST where date(CREATION_DATE)='" . $ReferenceDayIs . "') a, ". 
			"(select count(PROFILE_ID) as Profiles from PROFILE where date(PROFILE_CREATION_DATE)='" . $ReferenceDayIs . "') b"; 
		$result = mysql_query($sql_avg_tecks_profiles_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	    	if (is_array($row)){
	        	echo "[Info] - Average Number of Tecks Per Profile of this day: " . $row['AvgTecksPerProfileDay']. "\n";
				$AvgTecksPerProfileDay = (!empty($row['AvgTecksPerProfileDay']) ? $row['AvgTecksPerProfileDay'] : 0);
	    	}
	    }

 
	// TECKS PER ACTIVE PROFILE
		// AVG TECKS PER ACTIVE PROFILE 
		$sql_avg_tecks_active_profiles =  
		"select a.Tecks/b.ActiveProfiles as AvgTecksPerActiveProfile from ". 
			"(select count(POST_ID) as Tecks from POST where date(CREATION_DATE)<='" . $ReferenceDayIs . "') a, ". 
			"(select count(distinct PROFILE_ID) as ActiveProfiles from POST where date(CREATION_DATE)<='" . $ReferenceDayIs . "') b"; 
		$result = mysql_query($sql_avg_tecks_active_profiles, $conn_r);	
	    while ($row = mysql_fetch_assoc($result)){
            if (is_array($row)) {
            	echo "[Info] - Total Number of Tecks Per Active Profile: " . $row['AvgTecksPerActiveProfile']. "\n";
				$AvgTecksPerActiveProfile = ( !empty($row['AvgTecksPerActiveProfile']) ? $row['AvgTecksPerActiveProfile'] : 0 );
			} 
	    }

	// AVG TECKS PER ACTIVE PROFILE 
		$sql_avg_tecks_active_profiles_day =  
		"select a.Tecks/b.ActiveProfiles as AvgTecksPerActiveProfileDay from ". 
			"(select count(POST_ID) as Tecks from POST where date(CREATION_DATE)='" . $ReferenceDayIs . "') a, ". 
			"(select count(distinct PROFILE_ID) as ActiveProfiles from POST where date(CREATION_DATE)='" . $ReferenceDayIs . "') b"; 
		$result = mysql_query($sql_avg_tecks_active_profiles_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	    	if (is_array($row)) {
                echo "[Info] - Total Number of Tecks Per Active Profile of this day: " . $row['AvgTecksPerActiveProfileDay']. "\n";
				$AvgTecksPerActiveProfileDay = (!empty($row['AvgTecksPerActiveProfileDay']) ?  $row['AvgTecksPerActiveProfileDay'] : 0 );
			}
    	}
			


	// PROFILES WITHOUT TECKS
		// PROFILES WITHOUT TECKS
		$sql_total_profiles_without_teck =  
		"select (a.NumTotalProfiles  - b.ActiveProfiles) as NumInactiveProfiles from ". 
			"(select count(PROFILE_ID) as NumTotalProfiles from PROFILE where date(PROFILE_CREATION_DATE)<='" . $ReferenceDayIs . "') a, ". 
			"(select count(distinct PROFILE_ID) as ActiveProfiles from POST where date(CREATION_DATE)<='" . $ReferenceDayIs . "') b"; 
		$result = mysql_query($sql_total_profiles_without_teck, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
            echo "[Info] - Total Number of Inactive Profiles (Profiles Without Tecks): " . $row['NumInactiveProfiles']. "\n";
			$NumInactiveProfiles = ($row['NumInactiveProfiles']>=0? $row['NumInactiveProfiles']: 0);
	    }


		// PROFILES WITHOUT TECKS OF THIS DAY
		$sql_total_profiles_without_teck_day =  
		"select (a.NumTotalProfiles  - b.ActiveProfiles) as NumInactiveProfilesDay from ". 
			"(select count(PROFILE_ID) as NumTotalProfiles from PROFILE where date(PROFILE_CREATION_DATE)='" . $ReferenceDayIs . "') a, ". 
			"(select count(distinct PROFILE_ID) as ActiveProfiles from POST where date(CREATION_DATE)='" . $ReferenceDayIs . "') b"; 

		$result = mysql_query($sql_total_profiles_without_teck_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result))
	    {
            echo "[Info] - Total Number of Inactive Profiles (Profiles Without Tecks) at this day: " . $row['NumInactiveProfilesDay']. "\n";
			$NumInactiveProfilesDay = ($row['NumInactiveProfilesDay']>=0 ? $row['NumInactiveProfilesDay'] : 0 );
	    }

// Populate Metrics for TECKS PER LANG 
echo "[Debug] - Preparing SQL Statements to Populate Metrics for TECK PER LANGUAGE... \n";

	$NumTecksPerLang = "";
    $tecks_lang_ar = 0;	$tecks_lang_de = 0; $tecks_lang_en = 0; $tecks_lang_es = 0;
	$tecks_lang_fr = 0; $tecks_lang_he = 0; $tecks_lang_hi = 0; $tecks_lang_it = 0;
    $tecks_lang_jp = 0; $tecks_lang_ko = 0; $tecks_lang_pt = 0; $tecks_lang_ru = 0;
    $tecks_lang_zh = 0;

	$sql_tecks_per_lang = 
		"select count(POST_ID) as NumTecks, LANGUAGE_CODE as Lang from POST where date(CREATION_DATE) <= '" . $ReferenceDayIs . "' group by LANGUAGE_CODE order by LANGUAGE_CODE ASC";	
	$result = mysql_query($sql_tecks_per_lang, $conn_r);
	while ($row_lang = mysql_fetch_assoc($result)){
    	switch ($row_lang['Lang']) {
           case 'ar': $tecks_lang_ar = $row_lang['NumTecks']; break;
           case 'de': $tecks_lang_de = $row_lang['NumTecks']; break;
           case 'en': $tecks_lang_en = $row_lang['NumTecks']; break;
           case 'es': $tecks_lang_es = $row_lang['NumTecks']; break;
           case 'fr': $tecks_lang_fr = $row_lang['NumTecks']; break;
           case 'he': $tecks_lang_he = $row_lang['NumTecks']; break;
           case 'hi': $tecks_lang_hi = $row_lang['NumTecks']; break;
           case 'it': $tecks_lang_it = $row_lang['NumTecks']; break;
           case 'jp': $tecks_lang_jp = $row_lang['NumTecks']; break;
           case 'ko': $tecks_lang_ko = $row_lang['NumTecks']; break;
           case 'pt': $tecks_lang_pt = $row_lang['NumTecks']; break;
           case 'ru': $tecks_lang_ru = $row_lang['NumTecks']; break;
           case 'zh': $tecks_lang_zh = $row_lang['NumTecks']; break;
           default: break;
         }
   	}
    echo "[Info] -- Number of Tecks per Language Code: \n";
    echo "[Info] -- -- Tecks in Arabic (AR): " 		. $tecks_lang_ar . "\n";
    echo "[Info] -- -- Tecks in Deutsche (DE) : " 	. $tecks_lang_de . "\n";
    echo "[Info] -- -- Tecks in English (EN): "		. $tecks_lang_en . "\n";
    echo "[Info] -- -- Tecks in Spanish (ES): " 	. $tecks_lang_es . "\n";
    echo "[Info] -- -- Tecks in French (FR): " 		. $tecks_lang_fr . "\n";
    echo "[Info] -- -- Tecks in Hebrew (He): " 		. $tecks_lang_he . "\n";
    echo "[Info] -- -- Tecks in Hindi (Hi): " 		. $tecks_lang_hi . "\n";
    echo "[Info] -- -- Tecks in Italian (It): " 	. $tecks_lang_it . "\n";
    echo "[Info] -- -- Tecks in Japonese (Jp): " 	. $tecks_lang_jp . "\n";
    echo "[Info] -- -- Tecks in Korean (Ko): " 		. $tecks_lang_ko . "\n";
    echo "[Info] -- -- Tecks in Portuguese (Pt): " 	. $tecks_lang_pt . "\n";
    echo "[Info] -- -- Tecks in Russian (Ru): " 	. $tecks_lang_ru . "\n";
    echo "[Info] -- -- Tecks in Chinese (Zh): " 	. $tecks_lang_zh . "\n";
	
	// Assembling Insert Values Statement
    $NumTecksPerLang = 
    	"('" . 
    		$ReferenceDayIs ."', ".  $tecks_lang_ar . ", " . $tecks_lang_de . ", " .  $tecks_lang_en . ", " . 
    		$tecks_lang_es . ", " .  $tecks_lang_fr . ", " . $tecks_lang_he . ", " .  $tecks_lang_hi . ", " . 
    		$tecks_lang_it . ", " .  $tecks_lang_jp . ", " . $tecks_lang_ko . ", " .  $tecks_lang_pt . ", " . 
    		$tecks_lang_ru . ", " .  $tecks_lang_zh .
    	")";
    echo "[Debug] ---- Language Code => " . $NumTecksPerLang .  " \n";

// Populate DELTA Metrics for TECKS PER LANG PER DAY 
echo "[Debug] - Preparing SQL Statements to Populate Metrics for TECK PER LANGUAGE of this Day... \n";

	$NumTecksPerLangDay = "";
    $tecks_lang_ar_day = 0; $tecks_lang_de_day = 0; $tecks_lang_en_day = 0; $tecks_lang_es_day = 0;
    $tecks_lang_fr_day = 0; $tecks_lang_he_day = 0; $tecks_lang_hi_day = 0; $tecks_lang_it_day = 0;
    $tecks_lang_jp_day = 0; $tecks_lang_ko_day = 0; $tecks_lang_pt_day = 0; $tecks_lang_ru_day = 0;
    $tecks_lang_zh_day = 0;

	$sql_tecks_per_lang_day = 
		"select count(POST_ID) as NumTecksDay, LANGUAGE_CODE as Lang from POST where date(CREATION_DATE) = '" . $ReferenceDayIs . "' group by LANGUAGE_CODE order by LANGUAGE_CODE ASC";
	$result = mysql_query($sql_tecks_per_lang_day, $conn_r);
    while ($row_lang = mysql_fetch_assoc($result)){
    	switch ($row_lang['Lang']) {
           case 'ar': $tecks_lang_ar_day = $row_lang['NumTecksDay']; break;
           case 'de': $tecks_lang_de_day = $row_lang['NumTecksDay']; break;
           case 'en': $tecks_lang_en_day = $row_lang['NumTecksDay']; break;
           case 'es': $tecks_lang_es_day = $row_lang['NumTecksDay']; break;
           case 'fr': $tecks_lang_fr_day = $row_lang['NumTecksDay']; break;
           case 'he': $tecks_lang_he_day = $row_lang['NumTecksDay']; break;
           case 'hi': $tecks_lang_hi_day = $row_lang['NumTecksDay']; break;
           case 'it': $tecks_lang_it_day = $row_lang['NumTecksDay']; break;
           case 'jp': $tecks_lang_jp_day = $row_lang['NumTecksDay']; break;
           case 'ko': $tecks_lang_ko_day = $row_lang['NumTecksDay']; break;
           case 'pt': $tecks_lang_pt_day = $row_lang['NumTecksDay']; break;
           case 'ru': $tecks_lang_ru_day = $row_lang['NumTecksDay']; break;
           case 'zh': $tecks_lang_zh_day = $row_lang['NumTecksDay']; break;
           default: break;
         }
    }
    echo "[Info] -- Number of Tecks per Language Code of this Day: \n";
    echo "[Info] -- -- Tecks in Arabic (AR): " 		. $tecks_lang_ar_day . "\n";
    echo "[Info] -- -- Tecks in Deutsche (DE) : " 	. $tecks_lang_de_day . "\n";
    echo "[Info] -- -- Tecks in English (EN): " 	. $tecks_lang_en_day . "\n";
    echo "[Info] -- -- Tecks in Spanish (ES): " 	. $tecks_lang_es_day . "\n";
    echo "[Info] -- -- Tecks in French (FR): " 		. $tecks_lang_fr_day . "\n";
    echo "[Info] -- -- Tecks in Hebrew (He): " 		. $tecks_lang_he_day . "\n";
    echo "[Info] -- -- Tecks in Hindi (Hi): " 		. $tecks_lang_hi_day . "\n";
    echo "[Info] -- -- Tecks in Italian (It): " 	. $tecks_lang_it_day . "\n";
    echo "[Info] -- -- Tecks in Japonese (Jp): " 	. $tecks_lang_jp_day . "\n";
    echo "[Info] -- -- Tecks in Korean (Ko): " 		. $tecks_lang_ko_day . "\n";
    echo "[Info] -- -- Tecks in Portuguese (Pt): " 	. $tecks_lang_pt_day . "\n";
    echo "[Info] -- -- Tecks in Russian (Ru): " 	. $tecks_lang_ru_day . "\n";
    echo "[Info] -- -- Tecks in Chinese (Zh): " 	. $tecks_lang_zh_day . "\n";
    
    $NumTecksPerLangDay = 
    	"('" . 
    		$ReferenceDayIs ."'," . 
    		$tecks_lang_ar_day . ", " . $tecks_lang_de_day . ", " . $tecks_lang_en_day . ", " . $tecks_lang_es_day . ", " . 
    		$tecks_lang_fr_day . ", " . $tecks_lang_he_day . ", " . $tecks_lang_hi_day . ", " . $tecks_lang_it_day . ", " . 
    		$tecks_lang_jp_day . ", " . $tecks_lang_ko_day . ", " . $tecks_lang_pt_day . ", " . $tecks_lang_ru_day . ", " . 
    		$tecks_lang_zh_day .
    	")";
    echo "[Debug] ---- Language Code Day => " . $NumTecksPerLangDay .  " \n";

// Populate Metrics for TECKS PER TYPE
echo "[Debug] - Preparing SQL Statements to Populate Metrics for TECK PER TYPE... \n";

	$NumTecksPerType 	  = "";
    $tecks_type_audio 	  = 0; 	$tecks_type_image 	  = 0; $tecks_type_text 	  = 0; 
    $tecks_type_video 	  = 0; 	$tecks_type_document  = 0;

	$sql_tecks_per_type = 
		"select count(POST_ID) as NumTecks, TYPE as TeckType from POST " . 
			"where date(CREATION_DATE) <='" . $ReferenceDayIs . "' group by TYPE order by count(POST_ID) DESC";
	$result = mysql_query($sql_tecks_per_type, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
    	switch ($row['TeckType']) {
           case 'a': $tecks_type_audio 		= $row['NumTecks']; break;
           case 'i': $tecks_type_image 		= $row['NumTecks']; break;
           case 't': $tecks_type_text 		= $row['NumTecks']; break;
           case 'v': $tecks_type_video 		= $row['NumTecks']; break;
           case 'd': $tecks_type_document 	= $row['NumTecks']; break;
           default: break;
         }
    }
    echo "[Info] -- Number of Tecks per Teck Type: \n";
    echo "[Info] -- -- Audio Tecks (a): " 		. $tecks_type_audio . "\n";
    echo "[Info] -- -- Image Tecks (i) : " 		. $tecks_type_image . "\n";
    echo "[Info] -- -- Text Tecks (t): " 		. $tecks_type_text . "\n";
    echo "[Info] -- -- Video Tecks (v): " 		. $tecks_type_video . "\n";
    echo "[Info] -- -- Document Tecks (d) - Not yet Implemented: " 		. $tecks_type_document . "\n";
    
    $NumTecksPerType = 
    	"('" . 
    		$ReferenceDayIs ."'," . 
    		$tecks_type_audio . ", " . $tecks_type_document . ", " . $tecks_type_image . ", " . 
    		$tecks_type_text . ", " . $tecks_type_video .
    	")";
    echo "[Debug] ---- Language Code Day => " . $NumTecksPerType .  " \n";

// Populate Metrics for TECKS PER TYPE PER DAY
echo "[Debug] - Preparing SQL Statements to Populate Metrics for TECK PER TYPE of this Day... \n";
	
	$NumTecksPerTypeDay 	  = "";
    $tecks_type_audio_day 	  = 0; 	$tecks_type_image_day 	  = 0; 	$tecks_type_text_day 	  = 0; 
    $tecks_type_video_day 	  = 0; 	$tecks_type_document_day  = 0;
    
	$sql_tecks_per_type_day = 
		"select count(POST_ID) as NumTecksDay, TYPE as TeckType from POST " . 
			"where date(CREATION_DATE) ='" . $ReferenceDayIs . "' group by TYPE order by count(POST_ID) DESC";
	$result = mysql_query($sql_tecks_per_type_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
    	switch ($row['TeckType']) {
           case 'a': $tecks_type_audio_day 		= $row['NumTecksDay']; break;
           case 'i': $tecks_type_image_day 		= $row['NumTecksDay']; break;
           case 't': $tecks_type_text_day 		= $row['NumTecksDay']; break;
           case 'v': $tecks_type_video_day 		= $row['NumTecksDay']; break;
           case 'd': $tecks_type_document_day 	= $row['NumTecksDay']; break;
           default: break;
         }
    }
    echo "[Info] -- Number of Tecks per Teck Type: \n";
    echo "[Info] -- -- Audio Tecks (a): " 		. $tecks_type_audio_day . "\n";
    echo "[Info] -- -- Image Tecks (i) : " 		. $tecks_type_image_day . "\n";
    echo "[Info] -- -- Text Tecks (t): " 		. $tecks_type_text_day . "\n";
    echo "[Info] -- -- Video Tecks (v): " 		. $tecks_type_video_day . "\n";
    echo "[Info] -- -- Document Tecks (d) - Not yet Implemented: " 		. $tecks_type_document_day . "\n";
    
    $NumTecksPerTypeDay = 
    	"('" . 
    		$ReferenceDayIs ."'," . 
    		$tecks_type_audio_day . ", " . $tecks_type_document_day . ", " . $tecks_type_image_day . ", " . 
    		$tecks_type_text_day . ", " . $tecks_type_video_day .
    	")";
    echo "[Debug] ---- Language Code Day => " . $NumTecksPerTypeDay .  " \n";

    // Populating Blog Imports
    $sql_total_blogs_imports = 
    	"select count(profile_id) as NumImportsTotal from BLOG_IMPORT";

    $result = mysql_query($sql_total_blogs_imports, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Blogs Imported: " . $row['NumImportsTotal']. "\n";
			$NumBlogsImportsTotal = $row['NumImportsTotal'];
	    }

    $sql_delta_blogs_import_day = 
    	"select 
    		count(PROFILE_ID) as NumImportsDay
    	from 
    		BLOG_IMPORT  
    	where 
    		date(CREATION_DATE) = '" . $ReferenceDayIs . "'";

    $result = mysql_query($sql_delta_blogs_import_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Blogs Imported: " . $row['NumImportsDay']. "\n";
		$NumBlogsImportsDay = $row['NumImportsDay'];
    }



// ///////////////////////////////////////////////////////////////////////
//
// Saving Consolidated Metrics into INTRANET DB
//
// //////////////////////////////////////////////////////////////////////

// Preparing for Insertion
echo "[Debug] - Selecting DB Intranet for Insertion ...\n";
mysql_select_db(W_DB, $conn_w) or die('[Error] - Could not select database; ' . mysql_error());

// Consolidated Metrics - TOTAL
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_cons_metrics_tecks = 
	"insert ignore into CONS_METRICS_TECKS 
		(DATE, TOTAL_TECKS, TOTAL_PUBLISHED_TECKS, AVG_TECKS_PROFILE, AVG_TECKS_ACTIVE_PROFILE, TOTAL_PROFILES_WO_TECKS, RT_TECKS_PUBTECKS, TOTAL_BLOG_IMPORTS)  
	values 
		('". $ReferenceDayIs . "', $NumTotalTecks, $NumTotalPublishedTecks, $AvgTecksPerProfile, $AvgTecksPerActiveProfile, $NumInactiveProfiles,$rtTeck_PubTeck, $NumBlogsImportsTotal)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_tecks . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_tecks, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Tecks - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Tecks - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// Delta Metrics - Day
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_delta_metrics_tecks_day = 
	"insert ignore into DELTA_METRICS_TECKS_DAY 
		(DATE, DELTA_TECKS_DAY, DELTA_PUBLISHED_TECKS_DAY, RT_TECKS_PUBTECKS_DAY, AVG_TECKS_PROFILE_DAY, AVG_TECKS_ACTIVE_PROFILES_DAY, DELTA_PROFILE_WO_TECKS_DAY, DELTA_BLOGS_IMPORT_DAY) 
	values 
		('". $ReferenceDayIs . "', $NumTotalTecksDay, $NumTotalPublishedTecksDay, $rtTeck_PubTeckDay, $AvgTecksPerProfileDay, $AvgTecksPerActiveProfileDay, $NumInactiveProfilesDay, $NumBlogsImportsDay)";

echo "[SQL] - Insert Statement is: " . $sql_insert_delta_metrics_tecks_day . "\n";
$insertion_success = mysql_query($sql_insert_delta_metrics_tecks_day, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Delta Metrics of Users/Profiles Of this day - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Delta Metrics of Users/Profiles of this day - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// Tecks per Lang
echo "[Debug] - Preparing SQL statement for insertion of tecks per lang metrics... \n";
$insertValues = $NumTecksPerLang;
$sql_insert_cons_metrics_tecks_lang = 
	"insert ignore into CONS_METRICS_TECKS_LANG 
		(DATE,
		TOTAL_TECKS_AR, TOTAL_TECKS_DE, TOTAL_TECKS_EN, TOTAL_TECKS_ES, TOTAL_TECKS_FR,
		TOTAL_TECKS_HE, TOTAL_TECKS_HI, TOTAL_TECKS_IT, TOTAL_TECKS_JP, TOTAL_TECKS_KO, 
		TOTAL_TECKS_PT, TOTAL_TECKS_RU, TOTAL_TECKS_ZH) 
	values " . $insertValues;

echo "[SQL] - Insert Statement is : " . $sql_insert_cons_metrics_tecks_lang . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_tecks_lang, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Tecks Per Language - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Tecks per Language - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// Tecks per Lang per Day
echo "[Debug] - Preparing SQL statement for insertion of tecks per lang of this Day metrics... \n";
$insertValues = $NumTecksPerLangDay;
$sql_insert_cons_metrics_tecks_lang_day = 
	"insert ignore into DELTA_METRICS_TECKS_LANG_DAY 
		(DATE,
		DELTA_TECKS_AR_DAY, DELTA_TECKS_DE_DAY, DELTA_TECKS_EN_DAY, DELTA_TECKS_ES_DAY, DELTA_TECKS_FR_DAY,
		DELTA_TECKS_HE_DAY, DELTA_TECKS_HI_DAY, DELTA_TECKS_IT_DAY, DELTA_TECKS_JP_DAY, DELTA_TECKS_KO_DAY, 
		DELTA_TECKS_PT_DAY, DELTA_TECKS_RU_DAY, DELTA_TECKS_ZH_DAY) 
	values " . $insertValues;

echo "[SQL] - Insert Statement is : " . $sql_insert_cons_metrics_tecks_lang_day . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_tecks_lang_day, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Delta Metrics of Tecks Per Language of this Day - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Delta Metrics of Tecks per Language of this Day - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// Tecks per Type
echo "[Debug] - Preparing SQL statement for insertion of tecks per type metrics... \n";
$insertValues = $NumTecksPerType;
$sql_insert_cons_metrics_tecks_type = 
	"insert ignore into CONS_METRICS_TECKS_TYPE 
		(DATE, TOTAL_TECKS_AUDIO, TOTAL_TECKS_DOCUMENT, TOTAL_TECKS_IMAGE, TOTAL_TECKS_TEXT, TOTAL_TECKS_VIDEO) 
	values " . $insertValues;

echo "[SQL] - Insert Statement is : " . $sql_insert_cons_metrics_tecks_type . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_tecks_type, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Tecks Per Type - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Tecks per Type - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// Tecks per Type per Day
echo "[Debug] - Preparing SQL statement for insertion of tecks per type of this Day metrics... \n";
$insertValues = $NumTecksPerTypeDay;
$sql_insert_cons_metrics_tecks_type_day = 
	"insert ignore into DELTA_METRICS_TECKS_TYPE_DAY 
		(DATE,
		DELTA_TECKS_AUDIO_DAY, DELTA_TECKS_DOCUMENT_DAY, DELTA_TECKS_IMAGE_DAY, DELTA_TECKS_TEXT_DAY, DELTA_TECKS_VIDEO_DAY) 
	values " . $insertValues;

echo "[SQL] - Insert Statement is : " . $sql_insert_cons_metrics_tecks_type_day . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_tecks_type_day, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Delta Metrics of Tecks Per Type of this Day - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Delta Metrics of Tecks per Type of this Day - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}



echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Teck Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>