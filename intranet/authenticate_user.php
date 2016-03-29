<?php 
session_start();

$_SESSION['UserName'] = "";
$_SESSION['UserRole'] = "";


// ///////////////////////////////////
// Authenticate User
//
function getUserAccessLevel($pUser_Email, $pUser_Passwd){

   require "const.php";

   $conn = mysql_connect(SQL_HOST_SEC, SQL_USER_SEC, SQL_PASS_SEC)
        or die("Could not connect to the database ". SQL_HOST_SEC . ": " . mysql_error());

    mysql_select_db(SQL_DB_SEC, $conn)
        or die("Could not select database " . SQL_DB_SEC . ":" . mysql_error());

    $sql_check_user_cred = "select user_name, user_role from USERS where " . 
		"user_email = '" . $pUser_Email . "' and " . 
		"user_password = password('" . $pUser_Passwd . "')"; 
    
    //echo "<P><B>DEBUG::</B> $sql_check_user_cred </P>\n";

    $result = mysql_query($sql_check_user_cred, $conn)
   	or die('Could not look up user information: ' . mysql_error());
				
    // echo "DEBUG:: $result <BR>";
    while($row = mysql_fetch_array($result)){
	    $user_info['username'] = $row['user_name'];
	    $user_info['userrole'] = $row['user_role'];
    } 
    
    /* **** DEBUG ******************
    echo "DEBUG:: row info is: <BR>\n";
    echo "<PRE>\n";
    print_r($row);
    echo "</PRE>\n";
    ***************************** */

    if ( $user_info['username']=="" or $user_info['userrole']=="" ) {
      return $user_info ="";
    } else {
        return	$user_info;
    }

//}

} // END FUNCTION

// //////// START DEBUG
/* 
echo "<pre>";
print_r($_POST);
print "</pre>";

echo "User Email: " 	. $_POST['user_email'] 	. "<BR>\n";
echo "User Password : " . $_POST['user_passwd'] . "<BR>\n";
*/
// //////// END DEBUG
 

if (isset($_POST['user_email'])) {
   if (isset($_POST['user_passwd'])){
	$user_info = getUserAccessLevel($_POST['user_email'], $_POST['user_passwd']);
        //echo "<pre>\n";
        //print_r($user_info);
        //echo "</pre>\n";

        if (!empty($user_info)){
	    $_SESSION['UserName'] = $user_info['username'];
            $_SESSION['UserRole'] = $user_info['userrole'];
            $_SESSION['UserLastLogin'] = time();

	    //echo "<PRE>";
	    //print_r($_SESSION);
	    //echo "</PRE>";
            header('location:dashboard.php');

        } else {
		header('location:index.html');
        }
    } else {
	//header('location:index.html');	
    }
} else {
   //header('location:index.html');
}


?>
