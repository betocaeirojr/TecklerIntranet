<?php

require_once 'Revenue.php';

$revenue = new Revenue();

// Raw Data
echo "<h1>[INFO] -- REVENUE RAW INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Consolidated Revenue Raw Information....</h3> <BR>\n";
$ConsRevenueRawInfo = $revenue->getRevenueRawData('array', 'cons' , '2013-07-01' , 'desc');
echo "<sub>" ; print_r($ConsRevenueRawInfo); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Getting Delta Revenue Raw Information....</h3> <BR>\n";
$DeltaRevenueRawInfo = $revenue->getRevenueRawData('json', 'delta' , '2013-07-01' ,'asc');
echo "<sub>" ; print_r($DeltaRevenueRawInfo); echo "</sub>";
echo "<BR>";

echo "<HR>\n";

echo "<h1>[INFO] - EXPECTED REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getExpectedRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getExpectedRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - ACTUAL REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getActualRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getActualRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - PENDING REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getPendingRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getPendingRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - VERIFIED REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getVerifiedRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getVerifiedRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - REQUESTED REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getRequestedRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getRequestedRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - WITHDRAWN REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getWithdrawnRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getWithdrawnRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - ERROR REVENUE </h1>\n";
echo "<h2>[INFO] - Consolidate </h2>\n";
$value = $revenue->getErrorRevenueCount('array', 'cons', '2013-07-01');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Delta of the Day</h2>\n";
$value = $revenue->getErrorRevenueCount('json', 'delta', '2013-07-01');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - AVERAGE REVENUE </h1>\n";
echo "<h2>[INFO] - Average per Profile</h2>\n";
$value = $revenue->getAverageRevenue('array', '2013-07-01', 'profile');
echo $value; 
echo "<BR>";
echo "<h2>[INFO] - Average Per Teck</h2>\n";
$value = $revenue->getAverageRevenue('array', '2013-07-01', 'teck');
echo $value; 

echo "<HR>";

echo "<h1>[INFO] - WEIGHTED AVERAGE REVENUE </h1>\n";
echo "<h2>[INFO] - Weighted Average per Profile</h2>\n";
$value = $revenue->getWeightedAverageRevenue('array', '2013-07-01', 'profile');
echo $value; 
echo "<h2>[INFO] - Weighted Average per Teck</h2>\n";
$value = $revenue->getWeightedAverageRevenue('json', '2013-07-01', 'teck');
echo $value; 

echo "<HR>";


?>