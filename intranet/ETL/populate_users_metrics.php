<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating User Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$ReferenceDayIs = date("Y-m-d");	
} else {
	$ReferenceDayIs = date("Y-m-d",strtotime($_GET['day']));
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate USER and PROFILE Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

// Connecting to DB Server
require "conn.php";

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating USER/Profiles Metrics from DB TECKLER
//
// //////////////////////////////////////////////////////////////////////

// Initializing Final Variables
$NumTotalUsers 			= 0 ;
$NumTotalProfiles		= 0 ;
$NumTotalActiveProfiles	= 0 ;
$AvgProfilesUser		= 0 ;
$AvgActiveProfilesUser	= 0 ;
$NumUsersWith1Profile	= 0 ;
$WAvgMultipleProfilesPerProfiles = 0 ;
$NumEngagedProfiles 	= 0;

$NumTotalUsersDay			= 0 ;
$NumTotalProfilesDay		= 0 ;
$NumTotalActiveProfilesDay	= 0 ;
$AvgProfilesUserDay			= 0 ;
$AvgActiveProfilesUserDay 	= 0 ;

echo "[Debug] - Selecting DB Teckler for Consumption...\n";
mysql_select_db(R_DB_T, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());


// Populate Consolidated Metrics for USER and PROFILE information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for USER/PROFILES... \n";

	// USER METRICS
		// Metric - TOTAL USERS
		$sql_total_num_users = 
			"select count(USER_ID) as NumTotalUsers from USER where date(USER_CREATION_DATE) <='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_users, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Users: " . $row['NumTotalUsers']. "\n";
			$NumTotalUsers = $row['NumTotalUsers'];	
		}
		
		// Metric - TOTAL USER OF THE DAY
		$sql_total_num_users_day = 
			"select count(USER_ID) as NumTotalUsersDay from USER where date(USER_CREATION_DATE)='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_users_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Users of this day: " . $row['NumTotalUsersDay']. "\n";
			$NumTotalUsersDay = $row['NumTotalUsersDay'];	
		}

	// PROFILE METRICS
		// Metric - TOTAL PROFILES
		$sql_total_num_profiles = 
			"select count(PROFILE_ID) as NumTotalProfiles from PROFILE where date(PROFILE_CREATION_DATE)<='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_profiles, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Profiles: " . $row['NumTotalProfiles']. "\n";
			$NumTotalProfiles = $row['NumTotalProfiles'];
	    }

		// Metric - TOTAL PROFILES OF THE DAY
		$sql_total_num_profiles_day = 
			"select count(PROFILE_ID) as NumTotalProfilesDay from PROFILE where date(PROFILE_CREATION_DATE)='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_profiles_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Profiles of this day: " . $row['NumTotalProfilesDay']. "\n";
			$NumTotalProfilesDay = $row['NumTotalProfilesDay'];
	    }

    // ACTIVE PROFILE METRICS
		// TOTAL ACTIVE PROFILES (ie: profiles with at least on teck)
		$sql_total_num_active_profiles 	= 
			"select count(distinct PROFILE_ID) as NumTotalActiveProfiles from POST where date(CREATION_DATE) <='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_active_profiles, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
            echo "[Info] - Total Number of Active Profiles: " . $row['NumTotalActiveProfiles']. "\n";
			$NumTotalActiveProfiles = $row['NumTotalActiveProfiles'];
	    }


		// TOTAL ACTIVE PROFILES OF THE DAY(ie: profiles with at least on teck at this day)
		$sql_total_num_active_profiles_day 	= 
			"select count(distinct PROFILE_ID) as NumTotalActiveProfilesDay from POST where date(CREATION_DATE)='" . $ReferenceDayIs . "'";
		$result = mysql_query($sql_total_num_active_profiles_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
            echo "[Info] - Total Number of Active Profiles of the Day: " . $row['NumTotalActiveProfilesDay']. "\n";
			$NumTotalActiveProfilesDay = $row['NumTotalActiveProfilesDay'];
	    }

	// Average Profiles per User
		// Average Profiles per User Overall
		if ($NumTotalUsers != 0) {
			$AvgProfilesUser = $NumTotalProfiles / $NumTotalUsers;
		}
		echo "[Info] - Average Number of Profiles per User: " . $AvgProfilesUser . "\n";

		// Average Profiles per User Day
		if ($NumTotalUsersDay != 0) {
			$AvgProfilesUserDay = $NumTotalProfilesDay / $NumTotalUsersDay;
		}
		echo "[Info] - Average Number of Profiles per User of this Day: " . $AvgProfilesUserDay . "\n";

	// Average Active Profiles per User
		// Average Active Profiles per User Overall
		if ($NumTotalUsers!=0){
			$AvgActiveProfilesUser = $NumTotalActiveProfiles / $NumTotalUsers;
		}
		echo "[Info] - Average Number of Active Profiles per User: " . $AvgActiveProfilesUser . "\n";

		// Average Active Profiles per User Day
		if ($NumTotalUsersDay!=0) {
			$AvgActiveProfilesUserDay = $NumTotalActiveProfilesDay / $NumTotalUsersDay;
		}
		echo "[Info] - Average Number of Active Profiles per User of this Day: " . $AvgActiveProfilesUserDay . "\n";

	// Users with only 1 Profile
		$sql_total_num_users_w1_profile  = 
			"select count(a.NumProfiles) as NumUsersWith1Profile from
	            (select COUNT(PROFILE_ID) as NumProfiles, USER_ID as UserID
	            	from USER_PROFILE group by UserID order by 1 ASC) a,
	            USER b 
	           	where a.NumProfiles = 1 and a.UserID = b.USER_ID and
	            date(b.USER_CREATION_DATE) <= date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_num_users_w1_profile, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Profiles with only 1 Profile: " . $row['NumUsersWith1Profile']. "\n";
			$NumUsersWith1Profile = $row['NumUsersWith1Profile'];
	    }

    // For user with more than 1 profile, get the average for profiles per user
		$sql_wavg_active_profiles_user = 
			"select sum(a.NumProfiles)/count(a.UserID) as WAvgProfiles from 
				(select COUNT(PROFILE_ID) as NumProfiles, USER_ID as UserID 
				from USER_PROFILE 
				group by UserID order by 1 ASC) a where a.NumProfiles > 1";
		$result = mysql_query($sql_wavg_active_profiles_user, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Weighted Average Number of Profiles for Users which have more than 1 Profile: " . $row['WAvgProfiles']. "\n";
			$WAvgMultipleProfilesPerProfiles = $row['WAvgProfiles'];
	    }

	// Populate Metrics for USER PER LANG 
		echo "[Debug] - Preparing SQL Statements to Populate Metrics for USER PER LANGUAGE... \n";
		$sql_users_per_lang = 
			"select count(uc.VALUE) as NumUsers, uc.VALUE as Language from USER_CONFIGURATION uc, USER u where ( (uc.USER_ID = u.USER_ID) AND (uc.CODE='LANG') and (date(u.USER_CREATION_DATE)<='".$ReferenceDayIs."')) group by uc.VALUE";
		$NumUsersPerLang = array();
		echo "[Info] - Total Number of Users Per Langage Code...\n";
		$result = mysql_query($sql_users_per_lang, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        $NumUsersPerLang_Date       = $ReferenceDayIs;
	    	$NumUsersPerLang_NumUsers 	= $row['NumUsers'];
	    	$NumUsersPerLang_Lang		= $row['Language'];
	    	$NumUsersPerLang[] = "('" . $NumUsersPerLang_Date ."','" . $NumUsersPerLang_Lang . "'," . $NumUsersPerLang_NumUsers . ")";
	        echo "[Info] ---- Language Code => " . $NumUsersPerLang_Lang . " : " . $NumUsersPerLang_NumUsers . " \n";
	    }

	 // Users Per Lang per Day
		$sql_users_per_lang_day = 
			"select count(uc.VALUE) as NumUsers, uc.VALUE as Language 
			from USER_CONFIGURATION uc, USER u 
			where 
				( (uc.USER_ID = u.USER_ID) AND (uc.CODE='LANG') and (date(u.USER_CREATION_DATE)='".$ReferenceDayIs."')) 
			group by uc.VALUE";
		$NumUsersPerLangDay = array();
		echo "[Info] - Total Number of Users Per Langage Code...\n";
		$result = mysql_query($sql_users_per_lang_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        $NumUsersPerLangDay_Date       = $ReferenceDayIs;
	    	$NumUsersPerLangDay_NumUsers 	= $row['NumUsers'];
	    	$NumUsersPerLangDay_Lang		= $row['Language'];
	    	$NumUsersPerLangDay[] = "('" . $NumUsersPerLangDay_Date ."','" . $NumUsersPerLangDay_Lang . "'," . $NumUsersPerLangDay_NumUsers . ")";
	        echo "[Info] ---- Language Code => " . $NumUsersPerLangDay_Lang . " : " . $NumUsersPerLangDay_NumUsers . " \n";
	    }

	    if (empty($NumUsersPerLangDay)) {
	    	$NumUsersPerLangDay = 	"('". $ReferenceDayIs ."', 0, 'pt')," . 
	    							"('" . $ReferenceDayIs. "',0, 'en')," . 
	    							"('" . $ReferenceDayIs. "',0, 'es')" ;
	    	echo "[Info] Number of Users per Language Code per Day: 0 [" .  $NumUsersPerLangDay . "]\n";
	    }
  	
    // Engaged Profiles
		$sql_num_engaged_profiles = 
			"select count(distinct PROFILE_ID) as NumEngagedProfiles from POST 
			where date(CREATION_DATE) > date('".$ReferenceDayIs."')  - INTERVAL 90 DAY";  
		$result = mysql_query($sql_num_engaged_profiles, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        $NumEngagedProfiles = $row['NumEngagedProfiles'];
	        echo "[Info] - Total Number of Engaged Users (considering Tecks Posted in the prior 90 days): $NumEngagedProfiles ...\n";
	    }

    // Content Creators
	    $sql_num_content_creators_day = 
	    	"select 
	    		date(a.RefDate) as ReferenceDate,
	    		count(a.PROFILE_ID) as NumContentCreators 
	    	 from 
	    	 	(select PROFILE_ID as PROFILE_ID, COUNT(PROFILE_ID) as NUM_TECKS, date(PUBLISH_DATE) as RefDate 
	    	 	from TECKLER.POST where date(PUBLISH_DATE) = date('". $ReferenceDayIs ."') group by date(PUBLISH_DATE), PROFILE_ID) a 
	    	 group by date(RefDate) order by date(RefDate) DESC";

	    #echo "[SQL] SQL from Content Creators is: " . $sql_num_content_creators_day . "\n";
	    $result = mysql_query($sql_num_content_creators_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        $NumContentCreators = $row['NumContentCreators'];
	        echo "[Info] - Total Number of Content Creators of this day: $NumContentCreators ...\n";
	    }


	// Fraudster User Info
	   $sql_fraudester_user_info = 
	   "select 
			p.PROFILE_ID as ProfileID, 
			p.SIGNATURE as ProfileName,
			p.PROFILE_CREATION_DATE as ProfileCreationDate, 
			u.USER_ID as UserID, 
			u.USER_NAME as UserName, 
			u.EMAIL as UserEmail, 
			u.LOGIN as UserLogin, 
			u.ACTIVE as UserActiveStatus, 
			uc.VALUE as UserLangConfig,
			u.USER_CREATION_DATE as UserCreationDate, 
			u.DEFAULT_PROFILE_ID as UserDefaultProfileID
		from
			TECKLER.PROFILE p,
			TECKLER.USER u,
			TECKLER.USER_PROFILE up,
			TECKLER.USER_CONFIGURATION uc
		where
			p.PROFILE_ID = up.PROFILE_ID and
			up.USER_ID = u.USER_ID and 
			u.USER_ID = uc.USER_ID and
			uc.CODE = 'LANG' and
			(p.PROFILE_ID in 
				(select auc.AD_UNIT_ID as AdUnitID 
				from PAY.AD_UNIT_CONFIG auc
				where auc.MIN_CPV = 0) or 
			u.USER_ID in 
				(select uu.USER_ID 
				from USER uu 
				where uu.IS_TRICKSTER=1))";
	    
	    echo "[SQL] SQL for Fraudster User Info is: " . $sql_fraudester_user_info . "\n";
	    $result = mysql_query($sql_fraudester_user_info, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        $fraudster_user_info[] = 
	        "('" . $ReferenceDayIs 			. "'," 	. $row['ProfileID'] 	. ",'" 	. $row['ProfileName'] 		. "','" . $row['ProfileCreationDate'] 	.	"',"   
	        	 . $row['UserID']			. ",'"	. $row['UserName']		. "','" . $row['UserEmail']			. "','" . $row['UserLogin']				.	"','" 
	        	 . $row['UserActiveStatus'] . "','"	. $row['UserLangConfig']. "','" . $row['UserCreationDate']	. "',"	. $row['UserDefaultProfileID']	.
	        ")";

		// START DEBBUGING
		//        echo "[Debug] Info on Fraudster User Info is:\n";
		//        echo "<pre>\n";
		//        print_r($fraudster_user_info);
		//        echo "</pre>\n";
	        // END DEBBUGING
	    }
	    $str_fraudster_user_info = implode(',', $fraudster_user_info);
	    //echo "[Debug] - The insert values for fraudster user info is: " . $str_fraudster_user_info . "\n";


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
	$sql_insert_cons_metrics_users = 
		"insert ignore into CONS_METRICS_PROFILES 
			(DATE, 
			TOTAL_USERS, 
			TOTAL_PROFILES, 
			TOTAL_ACTIVE_PROFILES, 
			AVG_PROFILES_USER,
			AVG_ACTIVE_PROFILES_USER,
			TOTAL_USERS_W1_PROFILES,
			WAVG_PROFILES_USERS,
			TOTAL_ENGAGED_PROFILES)  
		values 
			('". $ReferenceDayIs . "',
			$NumTotalUsers,
			$NumTotalProfiles,
			$NumTotalActiveProfiles,
			$AvgProfilesUser,
			$AvgActiveProfilesUser,
			$NumUsersWith1Profile,
			$WAvgMultipleProfilesPerProfiles,
			$NumEngagedProfiles)";

	echo "[Info] - Starting to insert data about users consolidated information.\n";
	echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_users . "\n";
	$insertion_success = mysql_query($sql_insert_cons_metrics_users, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of Users/Profiles - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of Users/Profiles - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// Delta Metrics - Day
	echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
	$sql_insert_delta_metrics_users_day = 
		"insert ignore into DELTA_METRICS_PROFILES_DAY 
			(DATE, 
			DELTA_USERS_DAY, 
			DELTA_PROFILES_DAY, 
			DELTA_ACTIVE_PROFILES_DAY, 
			AVG_PROFILES_USER_DAY,
			AVG_ACTIVE_PROFILES_USER_DAY) 
		values 
			('". $ReferenceDayIs . "',
			$NumTotalUsersDay,
			$NumTotalProfilesDay,
			$NumTotalActiveProfilesDay,
			$AvgProfilesUserDay,
			$AvgActiveProfilesUserDay)";

	echo "[Info] - Starting to insert data about delta user per day.\n";
	echo "[SQL] - Insert Statement is: " . $sql_insert_delta_metrics_users_day . "\n";
	$insertion_success = mysql_query($sql_insert_delta_metrics_users_day, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Delta Metrics of Users/Profiles Of this day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Delta Metrics of Users/Profiles of this day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// Content Creators - Day
	echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
	$sql_insert_delta_metrics_content_creators_day = 
		"insert ignore into DELTA_METRICS_CONTENT_CREATORS_DAY 
			(DATE, NUM_CONTENT_CREATORS) 
		values 
			('". $ReferenceDayIs . "', $NumContentCreators)";

	echo "[Info] - Starting to insert data about delta content creators per day.\n";
	echo "[SQL] - Insert Statement is: " . $sql_insert_delta_metrics_content_creators_day . "\n";
	$insertion_success = mysql_query($sql_insert_delta_metrics_content_creators_day, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Delta Metrics of Content Creators Of this day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Delta Metrics of Content Creators of this day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// User Lang
	echo "[Debug] - Preparing SQL statement for insertion of user per lang metrics.\n";
	$insertValues = implode("," , $NumUsersPerLang);
	$sql_insert_cons_metrics_users_lang = 
		"insert ignore into CONS_METRICS_USERS_LANG 
			(DATE,
			LANG,
			TOTAL_USERS_LANG) 
		values " . $insertValues;

	echo "[Info] - Starting to insert data about User per Language. \n";
	echo "[SQL] - Insert Statement is : " . $sql_insert_cons_metrics_users_lang . "\n";
	$insertion_success = mysql_query($sql_insert_cons_metrics_users_lang, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of Users Per Language - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of Users per Language - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// User Lang per Day
	echo "[Debug] - Preparing SQL statement for insertion of user per lang per day metrics.\n";
	if (is_string($NumUsersPerLangDay)) {
		$insertValues = $NumUsersPerLangDay;
	} elseif (is_array($NumUsersPerLangDay)) {
		$insertValues = implode("," , $NumUsersPerLangDay);
	} 

	$sql_insert_cons_metrics_users_lang_day = 
		"insert ignore into DELTA_METRICS_USERS_LANG_DAY 
			(DATE,
			LANG,
			DELTA_USERS_LANG_DAY) 
		values " . $insertValues;

	echo "[Info] - Starting to insert data about User per Language per Day. \n";
	echo "[SQL] - Insert Statement is : " . $sql_insert_cons_metrics_users_lang_day . "\n";
	$insertion_success = mysql_query($sql_insert_cons_metrics_users_lang_day, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of Users Per Language Per Day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of Users per Language per Day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}


// Fraudster User Information
	echo "[Debug] - Preparing SQL INSERT STATEMENT for Fraudster info...\n";
	$sql_insert_general_fraudster_user_info = 
		"insert ignore into GENERAL_FRAUDSTER_USER_INFO 
			(DATE, PROFILE_ID, PROFILE_SIGNATURE, PROFILE_CREATION_DATE,
			USER_ID, USER_NAME, USER_EMAIL, USER_LOGIN, USER_ACTIVE_STATUS, USER_LANG_CODE, USER_CREATION_DATE, USER_DEFAULT_PROFILE_ID) 
		values " . $str_fraudster_user_info;

	echo "[Info] - Starting to insert data about Fraudster User Info. \n";
	//echo "[SQL] - Insert Statement is : " . $sql_insert_general_fraudster_user_info . "\n";
	$insertion_success = mysql_query($sql_insert_general_fraudster_user_info, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Fraudster User Info - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Fraudster User Info - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate USER and PROFILE Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";

?>
