<?php

require_once 'Users.php';

$user = new Users();

//$rawInfo = $user->getUsersRawData($conn, 'DESC', 'array');
//echo $user->NumLinhas;

// Raw Data
echo "<h1>[INFO] -- USERS RAW INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Users Raw Information....</h3> <BR>\n";
$UsersRawInfo = $user->getUsersRawData('array', 'cons' , '2013-07-01' , 'desc');
echo "<sub>" ; print_r($UsersRawInfo); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Getting Users Raw Information....</h3> <BR>\n";
$UsersRawInfo = $user->getUsersRawData('json', 'delta' , '2013-07-01' ,'asc');
echo "<sub>" ; print_r($UsersRawInfo); echo "</sub>";
echo "<BR>";

echo "<HR>\n";

// Users Count
echo "<h1>[INFO] -- USERS COUNT INFORMATION </h1> \n";
echo "<h3>[INFO] -- Getting Users Count </h3><BR>\n";
echo $UsersNumCons = $user->getUsersCount('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Users Count </h3><BR>\n";
echo $UsersNumDelta = $user->getUsersCount('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Profiles Count
echo "<h1>[INFO] -- PROFILES COUNT INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Profiles Count </h3><BR>\n";
echo $ProfilesNumCon = $user->getProfilesCount('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Profiles Count </h3><BR>\n";
echo $ProfilesNumDelta = $user->getProfilesCount('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Active Profiles Count
echo "<h1>[INFO] -- ACTIVE PROFILES COUNT INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Active Profiles Count </h3><BR>\n";
echo $ActiveProfilesNumCons = $user->getActiveProfilesCount('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Active Profiles Count </h3><BR>\n";
echo $ActiveProfilesNumDelta = $user->getActiveProfilesCount('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Average Profiles per User
echo "<h1>[INFO] -- AVERAGE PROFILES PER USER COUNT INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Average Profiles per User Count</h3> <BR>\n";
echo $AvgProfilesUserCons = $user->getAverageProfilesPerUser('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Profiles per User Count </h3><BR>\n";
echo $AvgProfilesUserDelta = $user->getAverageProfilesPerUser('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Average Active Profiles per User
echo "<h1>[INFO] -- AVERAGE ACTIVE PROFILES PER USER INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Average Profiles per User Count </h3><BR>\n";
echo $AvgActiveProfilesUserCons = $user->getAverageActiveProfilesPerUser('array', 'cons', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Average Profiles per User Count </h3><BR>\n";
echo $AvgActiveProfilesUserDelta = $user->getAverageActiveProfilesPerUser('json', 'delta', '2013-07-01');
echo "<BR>";

echo "<HR>";

// Total Number of Users with Only 1 Profile
echo "<h1>[INFO] -- 1 PROFILE ONLY USERS INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting Total Number of Users with only 1 profile </h3><BR>\n";
echo $NumUsersW1Profile = $user->getUsersWithOnly1Profile('array', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting Total Number of Users with only 1 profile </h3><BR>\n";
echo $NumUsersW1Profile = $user->getUsersWithOnly1Profile('json', '2013-07-01');
echo "<BR>";

echo "<HR>";


// Weighted Average Number of Profiles per user (considering only users with more than 1 profile)
echo "<h1>[INFO] -- WEIGHTED AVERAGE OF PROFILES PER USER INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting the weighted average number of profiles per user </h3><BR>\n";
echo $NumUsersW1Profile = $user->getWeightedAverageProfilesPerUser('array', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting the weighted average number of profiles per user </h3><BR>\n";
echo $NumUsersW1Profile = $user->getWeightedAverageProfilesPerUser('json', '2013-07-01');
echo "<BR>";

echo "<HR>";


// Users Breaked Down by Reg Language
echo "<h1>[INFO] -- USER PER LANGUAGE BREAKDOWN INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting number of users per language </h3><BR>\n";
$NumUserByLangCount = $user->getUsersPerLanguage('array', 'cons', '2013-07-01');
echo "<sub>" ; print_r($NumUserByLangCount); echo "</sub>";
echo "<BR>";

echo "<h3>[INFO] -- Getting number of users per language </h3><BR>\n";
$NumUserByLangCount = $user->getUsersPerLanguage('json', 'delta', '2013-07-01');
echo "<sub>" ; print_r($NumUserByLangCount); echo "</sub>";
echo "<BR>";

echo "<HR>";


// Logged Users
echo "<h1>[INFO] -- LOGGED USER INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting number of Logged User </h3><BR>\n";
echo $NumLoggedUsers = $user->getLoggedUsers('array', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting number of Logged User </h3><BR>\n";
echo $NumLoggedUsers = $user->getLoggedUsers('json','2013-07-01');
echo "<BR>";

echo "<HR>";


// Logged Users
echo "<h1>[INFO] -- USER AUTOLOGIN INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting number of Users with Autologin On</h3><BR>\n";
echo $NumLoggedUsers = $user->getUsersWithAutoLogin('array', '2013-07-01');
echo "<BR>";

echo "<h3>[INFO] -- Getting number of Users with Autologin On </h3><BR>\n";
echo $NumLoggedUsers = $user->getUsersWithAutoLogin('json','2013-07-01');
echo "<BR>";


// Engaged Profiles
echo "<h1>[INFO] -- ENGAGED PROFILES INFORMATION</h1> \n";
echo "<h3>[INFO] -- Getting number of Engaged Profiles (Profiles with Tecks in the last 90 days): </h3><BR>\n";
echo $NumLoggedUsers = $user->getEngagedProfiles('json', '2013-07-01');
echo "<BR>";




?>