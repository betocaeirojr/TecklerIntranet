<?php

require_once 'Audience.php';

$audience = new Audience();

//$rawInfo = $user->getUsersRawData($conn, 'DESC', 'array');
//echo $user->NumLinhas;


// Raw Data
echo "<h1>[INFO] -- AUDIENCE INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Pageviews Raw Information....</h3> <BR>\n";
$PageviewsRawInfo = $audience->getPageviewsRawData('array', '2013-07-25' , 'desc');
echo "<sub>" ; print_r($PageviewsRawInfo); echo "</sub>";
echo "<BR>";
echo "<h3>[INFO] -- Getting Pageviews Per Teck Type Raw Information....</h3> <BR>\n";
$PageviewsPerTeckTypeRawInfo = $audience->getPageViewsPerTeckTypeRawData('json' , '2013-07-25' ,'asc');
echo "<sub>" ; print_r($PageviewsPerTeckTypeRawInfo); echo "</sub>";
echo "<BR>";
// Alexa Count
echo "<h1>[INFO] -- ALEXA INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Alexa Count Information....</h3> <BR>\n";
$AlexaRanking = $audience->getAlexaRawData('array', '2013-07-25');
echo "<sub>" ; print_r($AlexaRanking); echo "</sub>";
echo "<BR>";

echo "<HR>\n";

// Pageviews Count
echo "<h1>[INFO] -- PAGEVIEWS INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Pageviews Count Information....</h3> <BR>\n";
echo $PageviewsCount = $audience->getPageviewsCount('array', '2013-07-25' , 'desc');
echo "<BR>";




// Average Pageview Info
echo "<h1>[INFO] -- AVERAGE PAGEVIEWS INFORMATION </h1> \n";
echo "<h2>[INFO] -- Average Pageview Per Teck and Profile<h2>\n";
echo "<h3>[INFO] -- Getting Average Pageviews per Teck Count </h3><BR>\n";
echo $AvgPVPerTeck = $audience->getAveragePageviewsPerTeckCount('array', '2013-07-25');
echo "<BR>";
echo "<h3>[INFO] -- Getting Average Pageviews per Teck Count </h3><BR>\n";
echo $AvgPVPerProfile = $audience->getAveragePageviewsPerProfileCount('json', '2013-07-25');
echo "<BR>";

// Weighted Average
echo "<h2>[INFO] -- Weighted Average Pageview Per Teck and Profile<h2>\n";
echo "<h3>[INFO] -- Getting Weighted Average Pageviews per Teck Count </h3><BR>\n";
echo $WAvgPVPerTeck = $audience->getWeightedAveragePageviewsPerTeckCount('json', '2013-07-25');
echo "<BR>";
echo "<h3>[INFO] -- Getting Weighted Average Pageviews per Teck Count </h3><BR>\n";
echo $WAvgPVPerProfile = $audience->getWeightedAveragePageviewsPerProfileCount('array', '2013-07-25');
echo "<BR>";


// Tecks Without Pageviews
echo "<h2>[INFO] -- Tecks Without Pageviews<h2>\n";
echo "<h3>[INFO] -- Getting Tecks without pageviews Count </h3><BR>\n";
echo $TecksWOPV = $audience->getTecksWithoutPageviews('json', '2013-07-25', 'abs');
echo "<BR>";
echo "<h3>[INFO] -- Getting Tecks without pageviews Percent </h3><BR>\n";
echo $PercTecksWOPV = $audience->getTecksWithoutPageviews('array', '2013-07-25', 'perc');
echo "<BR>";

// Profiles Without Pageviews
echo "<h2>[INFO] -- Tecks Without Pageviews<h2>\n";
echo "<h3>[INFO] -- Getting Tecks without pageviews Count </h3><BR>\n";
echo $TecksWOPV = $audience->getProfilesWithoutPageviews('array', '2013-07-25', 'abs');
echo "<BR>";
echo "<h3>[INFO] -- Getting Tecks without pageviews Percent </h3><BR>\n";
echo $PercTecksWOPV = $audience->getProfilesWithoutPageviews('json', '2013-07-25', 'perc');
echo "<BR>";



?>