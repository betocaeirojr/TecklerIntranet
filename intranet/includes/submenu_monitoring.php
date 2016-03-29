<?php $paginaLink = $_SERVER['SCRIPT_NAME'];?>
<ul class="additional-menu">
    <li <?php if($paginaLink == '/intranet/monitoring/monitoring_infrasctructure.php') {echo 'class="active"';} ?>><a href="monitoring_infrasctructure.php"><i class="icon-signal"></i> Infrastructure</a><a class="external_link" href="http://54.236.232.200/zabbix" target="_blank"></a></li>
    <li <?php if($paginaLink == '/intranet/monitoring/monitoring_aplication_profiler.php') {echo 'class="active"';} ?>><a href="monitoring_aplication_profiler.php"><i class="icon-signal"></i> Application Profiler</a><a class="external_link" href="http://newrelic.com/platform" target="_blank"></a></li>
    <li <?php if($paginaLink == '/intranet/monitoring/monitoring_database_statistics.php') {echo 'class="active"';} ?>><a href="monitoring_database_statistics.php"><i class="icon-signal"></i> Database Statistics</a><a class="external_link" href="http://54.236.232.200:5555" target="_blank"></a></li>
    <li <?php if($paginaLink == '/intranet/monitoring/monitoring_log_mining.php') {echo 'class="active"';} ?>><a href="monitoring_log_mining.php"><i class="icon-signal"></i> Log Mining</a><a class="external_link" href="http://54.236.232.200:8000" target="_blank"></a></li>
    <li <?php if($paginaLink == '/intranet/monitoring/monitoring_caching_statistics.php') {echo 'class="active"';} ?>><a href="monitoring_caching_statistics.php"><i class="icon-signal"></i> Caching Statistics</a><a class="external_link" href="http://54.236.232.200/phpMemcachedAdmin" target="_blank"></a></li>
    <li <?php if($paginaLink == '/intranet/monitoring/admin_user_profiles.php') {echo 'class="active"';} ?>><a class="inactive" href="#"><i class="icon-signal"></i> Search Statistics</a></li>
    <li <?php if($paginaLink == '/intranet/monitoring/admin_user_profiles.php') {echo 'class="active"';} ?>><a class="inactive" href="#"><i class="icon-signal"></i> Front-end</a></li>
</ul>
