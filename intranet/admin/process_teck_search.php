<?php
//require "../metrics/conn.php";
//include "../includes/header.php";
 
if (isset($_POST['teck_id']) and (is_numeric($_POST['teck_id']))){

	//echo "Test<BR>";
	$URL_redirect = "../metrics/show_teck_id_content.php?teckid=" . $_POST['teck_id'];
	//echo $URL_redirect;

	header("Location: $URL_redirect");

}
//include "../includes/footer.php";
?>
