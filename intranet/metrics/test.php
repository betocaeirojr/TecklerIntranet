<?php

date_default_timezone_set('America/Sao_Paulo');
require_once "Connection.php";
require_once "Users.php";

$Connection = new Connection();
$UserMetrics = new Users($Connection);

echo "blablabla<BR>\n";
$test = $UserMetrics->getContentCreators_TotalVisitorsRatio();

echo "<PRE>";
print_r($test);
echo "</PRE>";


?>