<?php
date_default_timezone_set("America/Sao_Paulo");
echo "Populating Contacts Importing and Invitations Sent at Metrics Database.\n";

if (!isset($_GET['day'])) {
	$ReferenceDayIs = date("Y-m-d", (time()-86400));	
} else {
	$ReferenceDayIs = date("Y-m-d", strtotime($_GET['day']));
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Contact and Invitations Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

// Connecting to DB Server
require "conn.php";

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Contact Importing Metrics
//
// //////////////////////////////////////////////////////////////////////


echo "[Debug] - Selecting DB Teckler for Consumption...\n";
mysql_select_db(R_DB_T, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Contact Importing... \n";

// CONTACT IMPORTING
	// Metric - Number of Contacts Imported Per Day
	$sql_total_num_contacts_imported_day = 
		"select count(CONTACT_ID) as NumContactsImported from TECKLER.USER_EXTERNAL_CONTACT where date(CREATION_DATE)='" . $ReferenceDayIs . "'";
	echo "[Debug] - SQL statement is: " . $sql_total_num_contacts_imported_day . "\n";

	$result = mysql_query($sql_total_num_contacts_imported_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Contacts Imported at this Day: " . $row['NumContactsImported']. "\n";
		$NumDeltaContactsImportedDay = (!empty($row['NumContactsImported']) ? $row['NumContactsImported'] : 0 );	
	}


// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Contact Importing Metrics
//
// //////////////////////////////////////////////////////////////////////

 
require_once "../stats/MailTrackingDynamoDbStats.php";
$DynamoDB = new MailTrackingDynamoDbStats();
$contactSentInvitations_RawInformation = $DynamoDB->getInvitationSentInfo();
arsort($contactSentInvitations_RawInformation);

//echo "\n[Debug] --- Start Debuging Session ---- \n";
//print_r($contactSentInvitations_RawInformation);
//echo "\n[Debug] --- Ending Debuging Session ---- \n";

$InvitationsKeyDates = array_keys($contactSentInvitations_RawInformation);
if ($InvitationsKeyDates[0] == date("Y-m-d")){
	echo "[Debug] - Always considering from yesterday backwards...\n";
	echo "[Debug] - Unsetting first key.\n";
	unset($contactSentInvitations_RawInformation[$InvitationsKeyDates[0]]);
}

// ///////////////////////////////////////////////////////////////////////
//
// Saving Consolidated Metrics into INTRANET DB
//
// //////////////////////////////////////////////////////////////////////

// Preparing for Insertion
echo "[Debug] - Selecting DB Intranet for Insertion ...\n";
mysql_select_db(W_DB, $conn_w) or die('[Error] - Could not select database; ' . mysql_error());

// Consolidated Metrics - Number of Contacts Imported at a single Day
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_delta_contacts_imported_metric = 
	"insert ignore into DELTA_METRICS_CONTACTS_INVITATIONS_DAY 
		(DATE, DELTA_IMPORTED_CONTACTS_DAY) 
	values 
		('". $ReferenceDayIs . "', $NumDeltaContactsImportedDay)";

echo "[SQL] - Insert Statement is: " . $sql_insert_delta_contacts_imported_metric . "\n";
$insertion_success = mysql_query($sql_insert_delta_contacts_imported_metric, $conn_w);
if ($insertion_success) {
	echo "[Success] - Delta Metrics of Contacts Imported at this Day - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Delta Metrics of Contacts Imported at this Day - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}


// Consolidated Metrics - Number of Invitations Sent, Read, Clicked
foreach ($contactSentInvitations_RawInformation as $key => $value) {
	$update_sql_statement = 
		"update ignore DELTA_METRICS_CONTACTS_INVITATIONS_DAY
			set DELTA_INVITATIONS_SENT_DAY = " 	. $value['EmailSent'] . ", 
				DELTA_INVITATIONS_READ_DAY = " 	. $value['EmailReads'] . ", 
				DELTA_INVITATIONS_CLICK_DAY = " . $value['EmailClicks'] . " 
			where date(DATE) = '" . date("Y-m-d",strtotime($value['EmailDate'])).  "'";	
	
	echo "[SQL] - Update Statement is: " . $update_sql_statement . "\n";
	
	$insertion_success = mysql_query($update_sql_statement, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Delta Metrics of Invitations Sent at this Day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Delta Metrics of Invitations Sent at this Day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}
	
}



?>