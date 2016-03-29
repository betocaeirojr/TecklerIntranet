<?php
session_start();
//print_r($_SESSION);

$user = $_SESSION['UserName'];
//echo $user;

if (empty($_SESSION['UserName']) or 
  empty($_SESSION['UserRole']) or 
  empty($_SESSION['UserLastLogin'])){
 header('location: ../index.php');
}

//print_r($_SESSION);
$url = $_SERVER['PHP_SELF'];
//echo $url;

$url_parts = explode('/',$url);
//print_r($url_parts);

$system = $url_parts[1];
$subsystem = $url_parts[2];
$page = end($url_parts);

echo $system . ":" .$subsystem . ":" . $page;


?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Extranet Teckler</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="">
    <meta name="author" content="">


    <!-- Le styles -->
    <link href="http://localhost/teckler/intranet/css/style.css" rel="stylesheet">
    <link href="http://localhost/teckler/intranet/css/bootstrap.css" rel="stylesheet">
	  <link href="http://localhost/teckler/intranet/css/style_teckler.css" rel="stylesheet">
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/glisse942e.css?1.css">
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/jquery.jgrowl.css">
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/demo_table.css" >
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/jquery.fancybox63b9.css?v=2.1.4" media="screen" />
    
	<link rel="stylesheet" href="http://localhost/teckler/intranet/css/icon/font-awesome.css">    
    <link rel="stylesheet" href="http://localhost/teckler/intranet/css/bootstrap-responsive.css">
    
    <link rel="alternate stylesheet" type="text/css" media="screen" title="green-theme" href="http://localhost/teckler/intranet/css/color/green.css" />
	<link rel="alternate stylesheet" type="text/css" media="screen" title="red-theme" href="http://localhost/teckler/intranet/css/color/red.css" />
	<link rel="alternate stylesheet" type="text/css" media="screen" title="blue-theme" href="http://localhost/teckler/intranet/css/color/blue.css" />
    
    <link rel="alternate stylesheet" type="text/css" media="screen" title="purple-theme" href="http://localhost/teckler/intranet/css/color/purple.css" />
	<link rel="alternate stylesheet" type="text/css" media="screen" title="orange-theme" href="http://localhost/teckler/intranet/css/color/orange.css" />
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script language="JavaScript">
	Firefox = navigator.userAgent.indexOf("Firefox") >= 0;
	if(Firefox) document.write("<link rel='stylesheet' href='http://localhost/teckler/intranet/css/moz.css' type='text/css'>"); 
	</script>
    
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="">
  </head>

  <body>

    <script type="text/javascript">
    var tableToExcel = (function() {
      var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
      return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        window.location.href = uri + base64(format(template, ctx))
      }
    })()
    </script>


    <!--BEGIN HEADER-->
    <div id="header" role="banner">
       <a id="menu-link" class="head-button-link menu-hide" href="#menu"><span>Menu</span></a>
       <!--Logo--><a href="dashboard.php" class="logo"><h1>Extranet Teckler</h1></a><!--Logo END-->
       
       <!--Search-->
       <!--
       <form class="search" action="#">
         <input type="text" name="q" placeholder="Search...">
       </form>
       -->
       <!--Search END-->
       
       
       <div class="right">
       
       
       
       <!--config box-->
         <div class="dropdown left">
          <a class="dropdown-toggle head-button-link config" data-toggle="dropdown" href="#"></a>
          <div class="dropdown-menu pull-right settings-box">
          <div class="triangle-2"></div>
          
            <a href="javascript:chooseStyle('none', 30)" class="settings-link"></a>
            <a href="javascript:chooseStyle('blue-theme', 30)" class="settings-link blue"></a>
            <a href="javascript:chooseStyle('green-theme', 30)" class="settings-link green"></a>
            <a href="javascript:chooseStyle('purple-theme', 30)" class="settings-link purple"></a>
            <a href="javascript:chooseStyle('orange-theme', 30)" class="settings-link yellow"></a>
            <a href="javascript:chooseStyle('red-theme', 30)" class="settings-link red"></a>
            <div class="clearfix"></div>
          </div>
        </div>
       <!--config box end-->
       
       <!--profile box-->
         <div class="dropdown left profile">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="double-spacer"></span>
            <div class="profile-avatar"><img src="../images/avatar.png" alt=""></div>
            <div class="profile-username"><span>Bem-vindo,</span> <?php echo $user ; ?></div>
            <div class="profile-caret"> <span class="caret"></span></div>
            <span class="double-spacer"></span>
          </a>
          <div class="dropdown-menu pull-right profile-box">
          <div class="triangle-3"></div>
          
            <ul class="profile-navigation">
              <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
              <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="#"><i class="icon-info-sign"></i> Help</a></li>
              <li><a href="index.html"><i class="icon-off"></i> Logout</a></li>
            </ul>
          </div>
        </div>
        <div class="clearfix"></div>
       <!--profile box end-->
       
       </div>
       
      
    </div>
    <!--END HEADER-->
