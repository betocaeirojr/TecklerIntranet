<?php $paginaLink = $_SERVER['SCRIPT_NAME'];?>
<ul class="additional-menu">
    <li <?php if($paginaLink == '/intranet/metrics/charts_user.php') {echo 'class="active"';} ?>><a href="charts_user.php"><i class="icon-signal"></i> Usuários e Perfis</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/charts_tecks.php') {echo 'class="active"';} ?>><a href="charts_tecks.php"><i class="icon-star"></i> Tecks</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/charts_shares.php') {echo 'class="active"';} ?>><a href="charts_shares.php"><i class="icon-star"></i> Shares</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/charts_following.php') {echo 'class="active"';} ?>><a href="charts_following.php"><i class="icon-star"></i> Following/Followers</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/charts_likes-dislikes.php') {echo 'class="active"';} ?>><a href="charts_likes-dislikes.php"><i class="icon-star"></i> Likes and Dislikes</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/charts_pageviews.php') {echo 'class="active"';} ?>><a href="charts_pageviews.php"><i class="icon-star"></i> PageViews</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_tecks_tecklers_viewed_metrics.php') {echo 'class="active"';} ?>><a href="detailed_tecks_tecklers_viewed_metrics.php"><i class="icon-star"></i> Top 100 PageViews</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_pageviews_google.php') {echo 'class="active"';} ?>><a href="detailed_pageviews_google.php"><i class="icon-star"></i> Pageviews from Google</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_average_pageviews.php') {echo 'class="active"';} ?>><a href="detailed_average_pageviews.php"><i class="icon-star"></i> Average of Pageviews</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/charts_revenue.php') {echo 'class="active"';} ?>><a href="charts_revenue.php"><i class="icon-star"></i> Revenue</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_online_users.php') {echo 'class="active"';} ?>><a href="detailed_online_users.php"><i class="icon-star"></i> Online Users</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_alexa_raking.php') {echo 'class="active"';} ?>><a href="detailed_alexa_ranking.php"><i class="icon-star"></i> Alexa Ranking</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_frequency-recency_metrics.php') {echo 'class="active"';} ?>><a href="detailed_frequency-recency_metrics.php"><i class="icon-star"></i> Frequency and Recency</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_categories.php') {echo 'class="active"';} ?>><a href="detailed_categories.php"><i class="icon-star"></i> Most Used Categories</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_ga_comparison_report.php') {echo 'class="active"';} ?>><a href="detailed_ga_comparison_report.php"><i class="icon-star"></i> Google Analytics Comp.</a></li>
    <li <?php if($paginaLink == '/intranet/metrics/detailed_content_ordering-selling_metrics.php') {echo 'class="active"';} ?>><a href="detailed_content_ordering-selling_metrics.php"><i class="icon-star"></i> Content Ordering</a></li>
</ul>
