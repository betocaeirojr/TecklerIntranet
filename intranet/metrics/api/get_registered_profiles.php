<?php
include "../conn.php";

$sql_num_total_profiles = "select count(PROFILE_ID) as NumTotalProfiles from PROFILE";

$result = mysql_query($sql_num_total_profiles, $conn);
if (mysql_num_rows($result) == 0)
{
     	$return_code = json_encode("");
} else {
      	while($row = mysql_fetch_assoc($result)){
      		$numProfiles = (int)$row['NumTotalProfiles'];
    	}
	$return_code = array(
			"item" => array(
					"text" => "Number of Profiles",
					"value" => $numProfiles
					)
			);

        $return_json = json_encode($return_code);
}
echo "<PRE>";
print_r($return_code);
echo "</PRE>";

echo $return_json;



?>
