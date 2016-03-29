<?php $paginaLink = $_SERVER['SCRIPT_NAME'];?>
<ul class="additional-menu">
    <li <?php if($paginaLink == '/intranet/admin/users_profiles_query.php') {echo 'class="active"';} ?>><a href="users_profiles_query.php"><i class="icon-signal"></i> Usuários e Perfis</a></li>
    <li <?php if($paginaLink == '/intranet/admin/admin_tecks.php') {echo 'class="active"';} ?>><a href="admin_tecks.php"><i class="icon-star"></i> Tecks</a></li>
    <li <?php if($paginaLink == '/intranet/admin/admin_users.php') {echo 'class="active"';} ?>><a href="admin_users.php"><i class="icon-star"></i> Users</a></li>
   	<li <?php if($paginaLink == '/intranet/admin/admin_pageviews.php') {echo 'class="active"';} ?>><a href="admin_pageviews.php"><i class="icon-star"></i> Pageviews per Day</a></li>
    <li <?php if($paginaLink == '/intranet/admin/fraudsters_users.php') {echo 'class="active"';} ?>><a href="fraudsters_users.php"><i class="icon-star"></i> Fraudsters Users Info</a></li>
    <li <?php if($paginaLink == '/intranet/admin/fraud_analysis.php') {echo 'class="active"';} ?>><a href="fraud_analysis.php"><i class="icon-star"></i> Fraud Analysis</a></li>
    <li <?php if($paginaLink == '/intranet/fraud/show_discounted_fraudlent_views.php') {echo 'class="active"';} ?>><a href="../fraud/show_discounted_fraudlent_views.php"><i class="icon-star"></i> Fraudlent Pageviews</a></li>
    <li <?php if($paginaLink == '/paypal/html/paypal_query_transfer_interface.php') {echo 'class="active"';} ?>><a href="../../paypal/html/paypal_query_transfer_interface.php"><i class="icon-star"></i> User Paying Interface</a></li>
    <li <?php if($paginaLink == '/intranet/admin/list_user_info.php') {echo 'class="active"';} ?>><a href="list_user_info.php"><i class="icon-star"></i> User Listing</a></li>
</ul>