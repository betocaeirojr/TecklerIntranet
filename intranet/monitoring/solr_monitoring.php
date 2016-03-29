<?php

//$code = file_get_contents('http://10.0.0.83:8983/solr/select?q=teckler&wt=php');
$code = file_get_contents('http://10.0.0.83:8983/solr/select?q=*:*&stats=true');
eval("\$result = " . $code . ";");
print_r($result);

?>
