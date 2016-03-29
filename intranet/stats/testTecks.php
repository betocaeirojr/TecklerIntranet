<?php

require_once 'Tecks.php';

$teck = new Tecks();

//$rawInfo = $user->getUsersRawData($conn, 'DESC', 'array');
//echo $user->NumLinhas;

// Raw Data
echo "<h1>[INFO] -- TECKS RAW INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Tecks Raw Information....</h3> <BR>\n";
$TecksRawInfo = $teck->getTecksRawData('array', 'cons' , '2013-07-01' , 'desc');
echo "<sub>" ; print_r($TecksRawInfo); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Getting Tecks Raw Information....</h3> <BR>\n";
$TecksRawInfo = $teck->getTecksRawData('json', 'delta' , '2013-07-01' ,'asc');
echo "<sub>" ; print_r($TecksRawInfo); echo "</sub>";
echo "<BR>";

echo "<HR>\n";


// Tecks Count
echo "<h1>[INFO] -- TECKS COUNT INFORMATION </h1> \n";
echo "<h3>[INFO] -- Getting Tecks Count </h3><BR>\n";
echo $TecksNumCons = $teck->getTecksCount('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Tecks Count </h3><BR>\n";
echo $TecksNumDelta = $teck->getTecksCount('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";



// Profiles Count
echo "<h1>[INFO] -- PUBLISHED TECKS COUNT INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Published Tecks Count </h3><BR>\n";
echo $PublishedTecksNumCons = $teck->getPublishedTecksCount('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Published Tecks Count </h3><BR>\n";
echo $PublishedTecksNumDelta = $teck->getPublishedTecksCount('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";



// Published Tecks / Tecks Ratio
echo "<h1>[INFO] -- RATIO OF TECKS BY PUBLISHED TECKS INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Ratio Tecks/Published Tecks Count </h3><BR>\n";
echo $RtTeckPubTecksCons = $teck->getRatioTecksByPublishedTecks('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Ratio Tecks/Published Tecks Count </h3><BR>\n";
echo $RtTeckPubTecksCons = $teck->getRatioTecksByPublishedTecks('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";


// Average Tecks Per Profile
echo "<h1>[INFO] -- AVERAGE TECKS PER PROFILE COUNT INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Average Tecks per Profile Count</h3> <BR>\n";
echo $AvgTecksPerProfileCons = $teck->getAverageTecksPerProfile('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Tecks per Profile Count</h3> <BR>\n";
echo $AvgTecksPerProfileDelta = $teck->getAverageTecksPerProfile('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Average Tecks per Active Profiles
echo "<h1>[INFO] -- AVERAGE TECKS PER ACTIVE PROFILES INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Average Tecks per Active Profiles Count </h3><BR>\n";
echo $AvgTecksPerActiveProfileCons = $teck->getAverageTecksPerActiveProfile('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Tecks per Active Profiles Count </h3><BR>\n";
echo $AvgTecksPerActiveProfileDelta = $teck->getAverageTecksPerActiveProfile('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";


// Profiles without Tecks
echo "<h1>[INFO] -- PROFILE WITHOUT TECKS INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Total Number of Profile without Tecks </h3><BR>\n";
echo $NumProfilesWOTecksCons = $teck->getProfilesWithoutTeck('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Total Number of Profile without Tecks </h3><BR>\n";
echo $NumProfilesWOTecksDelta = $teck->getProfilesWithoutTeck('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Tecks By Lang
echo "<h1>[INFO] -- TECKS PER LANG INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Tecks PER LANG Information....</h3> <BR>\n";
$TecksPerLanguageCons = $teck->getTecksPerLanguage('array', 'cons' , '2013-07-01' , 'desc');
echo "<sub>" ; print_r($TecksPerLanguageCons); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Getting Tecks Raw Information....</h3> <BR>\n";
$TecksPerLanguageDelta = $teck->getTecksPerLanguage('json', 'delta' , '2013-07-01' ,'asc');
echo "<sub>" ; print_r($TecksPerLanguageDelta); echo "</sub>";
echo "<BR>";

echo "<HR>\n";


// Tecks By Type
echo "<h1>[INFO] -- TECKS PER TYPE INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Tecks Per Type Information....</h3> <BR>\n";
$TecksPerTypeCons = $teck->getTecksPerType('array', 'cons' , '2013-07-01' , 'desc');
echo "<sub>" ; print_r($TecksPerTypeCons); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Getting Tecks Per Type Information....</h3> <BR>\n";
$TecksPerTypeDelta = $teck->getTecksPerType('json', 'delta' , '2013-07-01' ,'asc');
echo "<sub>" ; print_r($TecksPerTypeDelta); echo "</sub>";
echo "<BR>";

echo "<HR>\n";


?>