<?php

require_once 'Social.php';

$socialActivity = new Social();

//$rawInfo = $user->getUsersRawData($conn, 'DESC', 'array');
//echo $user->NumLinhas;




// Social Activity
echo "<h1>[INFO] -- SOCIAL ACTIVITY COUNT INFORMATION </h1> \n";
echo "<h2>[INFO] -- Raw Information </h2>\n";
echo "<h3>[INFO] -- Following Raw Data </h3><BR>\n";
$FollowingRawData = $socialActivity->getFollowRawData('array', '2013-07-25');
echo "<sub>" ; print_r($FollowingRawData); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Votes Raw Data </h3><BR>\n";
$VotesRawData = $socialActivity->getVotesRawData('json', '2013-07-25');
echo "<sub>" ; print_r($VotesRawData); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Sharing Raw Data </h3><BR>\n";
$SharingRawData = $socialActivity->getSharesRawData('array', '2013-07-25');
echo "<sub>" ; print_r($SharingRawData); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Sharing Per Social Network Raw Data </h3><BR>\n";
$SharingPerSocialNetworkRawData = $socialActivity->getSharesPerSocialNetworkRawData('json', '2013-07-25');
echo "<sub>" ; print_r($SharingPerSocialNetworkRawData); echo "</sub>";
echo "<BR>\n";


// ****************************************************************************
// ****************************************************************************
echo "<HR>\n";
// ****************************************************************************
// ****************************************************************************


echo "<h2>[INFO] -- Total Counts</h2>\n";
echo "<h3>[INFO] -- Getting Following Count </h3><BR>\n";
echo $FollowingNumCons = $socialActivity->getFollowingCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Followed Count </h3><BR>\n";
echo $FollowedNumCons = $socialActivity->getFollowedCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Voted Tecks Count </h3><BR>\n";
echo $VotedTecksNumCons = $socialActivity->getVotedTecksCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Voted Profiles Count </h3><BR>\n";
echo $VotedProfilesNumCons = $socialActivity->getVotedProfilesCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Shares Count </h3><BR>\n";
echo $SharesNumCons = $socialActivity->getSharesCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Shares Count </h3><BR>\n";
echo $SharesNumCons = $socialActivity->getSharesPerSocialNetworkCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Tecks Without Shares Count </h3><BR>\n";
echo $TecksWOSharesCons = $socialActivity->getTecksWithoutSharesCount('array', '2013-07-25');
echo "<BR>";

// ****************************************************************************
// ****************************************************************************
echo "<HR>\n";
// ****************************************************************************
// ****************************************************************************


echo "<h2>[INFO] -- Average Counts</h2>\n";

// Profiles Count
echo "<h3>[INFO] -- Getting Average Following Count </h3><BR>\n";
echo $AverageFollowingCons = $socialActivity->getAverageFollowingCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Followed Count </h3><BR>\n";
echo $AverageFollowedCons = $socialActivity->getAverageFollowedCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Votes per Teck Count </h3><BR>\n";
echo $AverageVotesTeckCount = $socialActivity->getAverageVotesPerTeckCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Votes per Profile Count </h3><BR>\n";
echo $AverageVotesProfilesCount = $socialActivity->getAverageVotesPerProfileCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Shares per Teck Count </h3><BR>\n";
echo $AverageSharesTeckCount = $socialActivity->getAverageSharesPerTeckCount('json', '2013-07-25');
echo "<BR>";

// ****************************************************************************
// ****************************************************************************
echo "<HR>\n";
// ****************************************************************************
// ****************************************************************************


echo "<h2>[INFO] -- Weighted Average Counts</h2>\n";

// Profiles Count
echo "<h3>[INFO] -- Getting Average Following Count </h3><BR>\n";
echo $WAverageFollowingCons = $socialActivity->getWeightedAverageFollowingCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Followed Count </h3><BR>\n";
echo $WAverageFollowedCons = $socialActivity->getWeightedAverageFollowedCount('array', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Votes per Teck Count </h3><BR>\n";
echo $WAverageVotesTeckCount = $socialActivity->getWeightedAverageVotesPerTeckCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Votes per Profile Count </h3><BR>\n";
echo $WAverageVotesProfilesCount = $socialActivity->getWeightedAverageVotesPerProfileCount('json', '2013-07-25');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Shares per Teck Count </h3><BR>\n";
echo $WAverageSharesTeckCount = $socialActivity->getWeightedAverageSharesPerTeckCount('json', '2013-07-25');
echo "<BR>";

echo "<HR>";


/* 

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
*/

?>