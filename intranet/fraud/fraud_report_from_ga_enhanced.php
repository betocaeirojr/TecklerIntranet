<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

//print_r($_GET);

if (isset($_GET['params'])) {
	if (strrpos($_GET['params'], 'd') != FALSE) {
		$prGet = explode("d", $_GET['params']);
		$day = $prGet[0];
		$filterOption = $prGet[1];	
	} else {
		$day = $_GET['params'];
	}
	
} else {
	$day = date('Y-m-d');
}

//print_r($prGet);


// GA Credentials
$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';

// Dimensions and Metrics Setup
$dimensions = array('date','pagePath', 'city', 'networkLocation', 'fullReferrer','landingPagePath', 'pageDepth');
$metrics = array('pageviews','uniquePageviews','entrances', 'bounces', 'entranceRate', 'pageviewsPerVisit', 'avgTimeOnPage','exits', 'exitRate', 'pageValue');
$sort = '-date';


// Time Interval
$timeInterval = '1';

$fromDate 	= $day;
$toDate 	= $day;

//Max Results
$maxResults = 10000;

// Offset
$startOffset = 1;


switch ($filterOption) {
	case '1':
		// WaterGod
		$filter = 	'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/WaterGod\/)(\S{1,})$ || PagePath =~ (\w\w)(\/WaterGod\/)(\S{1,})$'; 
		$profile = "WaterGod";
		break;
	case '2':
		// Joker
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/Joker\/)(\S{1,})$ || PagePath =~ (\w\w)(\/Joker\/)(\S{1,})$'; 
		$profile = "Joker";
		break;
	case '3':
		// AskerWisky
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/Askerwisky\/)(\S{1,})$ || PagePath =~ (\w\w)(\/Askerwisky\/)(\S{1,})$'	; 
		$profile = "Askerwisky";
		break;
	case '4':
		// AskerWiskier
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/&& landingPagePath =~ (\w\w)(\/Askerwiskier\/)(\S{1,})$ || PagePath =~ (\w\w)(\/Askerwiskier\/)(\S{1,})$'	; 
		$profile = "Askerwiskier";
		break;
	case '5';
		// Agusdiazz
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/agusdiazz\/)(\S{1,})$ || PagePath =~ (\w\w)(\/agusdiazz\/)(\S{1,})$';	
		$profile = "agusdiazz";
		break;
	case '6':
		// Babibu
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/Babibu\/)(\S{1,})$ || PagePath =~ (\w\w)(\/Babibu\/)(\S{1,})$';	
		$profile = "Babibu";
		break;
	case '7': 
		// ZAZ Portal
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/ZaZPortal\/)(\S{1,})$ || PagePath =~ (\w\w)(\/ZaZPortal\/)(\S{1,})$'; 
		$profile = "ZaZPortal";
		break;
	case '8':
		// Portal ZAZ
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$  || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/PortalZaZ\/)(\S{1,})$	|| PagePath =~ (\w\w)(\/PortalZaZ\/)(\S{1,})$'; 
		$profile = "PortalZaZ";
		break;
	case '9':
		// Eagle Eyes
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/EagleEyes\/)(\S{1,})$ || PagePath =~ (\w\w)(\/EagleEyes\/)(\S{1,})$'; 
		$profile = "EagleEyes";
		break;
	case '10':
		// DeSoLaTe
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/DeSoLaTe\/)(\S{1,})$ || PagePath =~ (\w\w)(\/DeSoLaTe\/)(\S{1,})$'; 
		$profile = "DeSoLaTe";
		break;
	case '11':
		// DeSoLaDo
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$  || fullReferrer == pinterest.com/pin/240661173810527605/&& landingPagePath =~ (\w\w)(\/DeSoLaDo\/)(\S{1,})$ || PagePath =~ (\w\w)(\/DeSoLaDo\/)(\S{1,})$' ; 
		$profile = "DeSoLaDo";
		break;
	case '12':
		// Ciencia Aqui
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/Ciencia_Aqui\/)(\S{1,})$ || PagePath =~ (\w\w)(\/Ciencia_Aqui\/)(\S{1,})$'; 
		$profile = "Ciencia_Aqui";
		break;
	case '13':
		// Sky Blye
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/skyblue\/)(\S{1,})$ || PagePath =~ (\w\w)(\/skyblue\/)(\S{1,})$'	; 
		$profile = "skyblue";
		break; 
	case '14':
		// Maiconassis
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/maiconassis\/)(\S{1,})$	|| PagePath =~ (\w\w)(\/maiconassis\/)(\S{1,})$'; 
		$profile = "maiconassis";
		break;
	case '15':
		// Daniel Bohnrs
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/danielbohnrs\/)(\S{1,})$ || PagePath =~ (\w\w)(\/danielbohnrs\/)(\S{1,})$'; 
		$profile = "danielbohnrs";
		break	;
	case '16':
		// Jefferson_Amarals
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/ && landingPagePath =~ (\w\w)(\/jefferson_amarals\/)(\S{1,})$ || PagePath =~ (\w\w)(\/jefferson_amarals\/)(\S{1,})$'; 
		$profile = "jefferson_amarals";
		break	;
		
	default:
		// Default
		$filter = 'fullReferrer =~ (tumblr.com(\/(r(\D){1,})))$ || fullReferrer == pinterest.com/pin/240661173810527605/'; 
		$profile = "Not set. Considering everybody.";
		break;
}

$ReturnedCount = 0;
$ga = new gapi($gaUsername, $gaPassword);
do {
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, $startOffset , $maxResults);
	$j=0;
	foreach($ga->getResults() as $result){
		if ($result->getPageviews() == 1) {
			$j++;
		} else {
			$j = $j + $result->getPageviews();
		}
	}
	if ($j >= $maxResults) {
		$startOffset = $startOffset + $maxResults;
	}
	$ReturnedCount = $ReturnedCount + $j;
} while ($j == $maxResults);


echo "# On date, " 	. $day . " | Total Number is, " . $ReturnedCount . " | From Profile, ". $profile . "." ;


?>

