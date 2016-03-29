<?php $paginaLink = $_SERVER['SCRIPT_NAME'];?>
<ul class="additional-menu">
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewAudienceMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewAudienceMetrics.php"><i class="icon-signal"></i> Audience Metrics</a></li>
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewEmailTrackingMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewEmailTrackingMetrics.php"><i class="icon-signal"></i> Email Tracking</a></li>
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewPageviewsAgingMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewPageviewsAgingMetrics.php"><i class="icon-signal"></i> PV Aging Metrics</a></li>
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewRevenueMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewRevenueMetrics.php"><i class="icon-signal"></i> Revenue Metrics</a></li>
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewSocialMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewSocialMetrics.php"><i class="icon-signal"></i> Social Metrics</a></li>
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewTecksMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewTecksMetrics.php"><i class="icon-signal"></i> Tecks Metrics</a></li>
    <li <?php if($paginaLink == '/intranet/stats/stats_ViewUsersMetrics.php') {echo 'class="active"';} ?>><a href="stats_ViewUsersMetrics.php"><i class="icon-signal"></i> Users Metrics</a></li>

</ul>