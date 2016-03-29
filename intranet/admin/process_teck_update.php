<?php
require "conn_upd.php";
include "../metrics/header.php";

//echo "<PRE>";
//print_r($_POST);
//echo "</PRE>"; 

$update_solr_needed = FALSE;
$post_id = $_POST['teck_id'];

if (isset($_POST['teck_id']) and (is_numeric($_POST['teck_id']))){

	switch ($_POST['change']){
	case "lang":
		$sql_update_teck_info = "update POST " .
					"set LANGUAGE_CODE='" . $_POST['teck_lang_code']. "' " .  
					"where POST_ID=". $_POST['teck_id'];
		//echo "DEBUG:::: ". $sql_update_teck_info ."<BR>" ; 
		break;

	case "status":
		$sql_update_teck_info =	"update POST " . 
					"set STATUS_ID=" .$_POST['teck_status'] . " " . 
					"where POST_ID=". $_POST['teck_id'];
		//echo "DEBUG:::: ". $sql_update_teck_info ."<BR>" ;
		
		if ($_POST['teck_status']>1){
			$update_solr_needed = TRUE;
		}

		break;

	case "editorial":
	
		$editor_id=$_POST['editor_id'];

		$sql_update_teck_info = "update POST set EDITOR_PROFILE_ID=$editor_id  " .
					"where POST_ID=$post_id"; 
		//echo "DEBUG: " . $sql_update_teck_info . "<BR>\n" ;

		break;
	
	default:
		break;
	}
}
	
			

$result = mysql_query($sql_update_teck_info, $conn); 
if ($result) {
	//echo "<br>\n";
	echo "Teck info has been updated at the Database.<BR> \n";
	if ($update_solr_needed){
		$solr_q = "curl http://10.0.0.84:8983/solr/tecks/update/?commit=true -H \"Content-Type: text/xml\" -d \"<delete><query>(POST_ID:$post_id)</query></delete>\"";
		//echo "<BR>DEBUG::: ". $solr_q . "<BR>\n";

		$result_SOLR = exec($solr_q); 
		echo "Teck info has been removed from the Search Engine.<BR>\n"; 
	}else{
		echo "Opps. Something went wrong. Contact our Dev Team for support<BR>\n";

	} 
}



include "../metrics/footer.php";
?>
