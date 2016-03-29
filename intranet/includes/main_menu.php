<?php 
session_start();

$username = $_SESSION['UserName'];
$userrole = $_SESSION['UserRole'];
//echo $user;

if (empty($_SESSION['UserName']) or empty($_SESSION['UserRole']) or empty($_SESSION['UserLastLogin'])){
 header('location: ../index.php');
}

// Para uso do Leo
$paginaLink = $_SERVER['SCRIPT_NAME'];

// Para uso do Beto
$domainName=$_SERVER['SERVER_NAME'];
$applicationName = "teckler/intranet";
$baseURL = "http://".$domainName."/".$applicationName;
// echo $baseURL;


?>
<ul class="main-menu">
    <li <?php if($paginaLink == '/intranet/dashboard.php') {echo 'class="active"';} ?>><a href="../dashboard.php"><i class="general"></i>Geral</a></li>
    <li 
		<?php if($paginaLink == '/intranet/metrics/index.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/metrics/charts_user.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/metrics/charts_tecks.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/metrics/charts_shares.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/metrics/charts_following.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/metrics/charts_likes-dislikes.php') {echo 'class="active"';} ?>
    	<?php if($paginaLink == '/intranet/metrics/charts_pageviews.php') {echo 'class="active"';} ?>
    > <?php echo "<a href=" . $baseURL . "/metrics/index.php" . ">" ;?><i class="statistics"></i>Relatórios</a></li>
    <!-- <li><a href="page-404.html"><i class="pages"></i> Tecks</a></li> -->
<?php 
if ($userrole=='superuser' OR $userrole=='administrative' OR $userrole=='developer'){

?>   
    <li
    	<?php if($paginaLink == '/intranet/admin/index.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/admin/admin_user_profiles.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/admin/admin_tecks.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/admin/admin_users.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/paypal/html/paypal_query_transfer_interface.php') {echo 'class="active"';} ?>
    
    ><?php echo "<a href=" . $baseURL . "/admin/index.php" . ">" ;?><i class="components"></i>Admin</a></li>
<?php 
}
?>


<?php 
if ($userrole=='superuser' OR $userrole=='administrative' OR $userrole=='developer'){
?>   
    <li
    	<?php if($paginaLink == '/intranet/monitoring/index.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/monitoring/monitoring_infrasctructure.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/monitoring/monitoring_aplication_profiler.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/monitoring/monitoring_database_statistics.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/monitoring/monitoring_log_mining.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/monitoring/monitoring_caching_statistics.php') {echo 'class="active"';} ?>
    ><?php echo "<a href=" . $baseURL . "/monitoring/index.php" . ">" ;?><i class="tables"></i>Monitoramento</a></li>
<?php 
}
?>

<?php 
if ($userrole=='superuser' OR $userrole=='administrative' OR $userrole=='developer'){
?>   
    <li
        <?php if($paginaLink == '/intranet/stats/stats_ViewAudienceMetrics.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/stats/stats_ViewEmailTrackingMetrics.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/stats/stats_ViewRevenueMetrics.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/stats/stats_ViewSocialMetrics.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/stats/stats_ViewTecksMetrics.php') {echo 'class="active"';} ?>
        <?php if($paginaLink == '/intranet/stats/stats_ViewUsersMetrics.php') {echo 'class="active"';} ?>
    ><?php echo "<a href=" . $baseURL . "/stats/index.php" . ">" ;?><i class="pages"></i>Stats</a></li>
<?php 
}
?>


    <!--
    <li><a href="forms.html"><i class="forms"></i> Forms</a></li>
    <li><a href="tables.html"><i class="tables"></i> Tables</a></li>
    <li><a href="contacts.html"><i class="bonus"></i> Bonus</a></li>
    -->
</ul>
