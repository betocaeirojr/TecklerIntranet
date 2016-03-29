<?php
session_start();

if ($_SESSION['isUserLoggedIn']==1) {
	include "includes/header.php";
?>
	<H2>Here you can track our main metrics/KPIs on demmand</H2>
		<UL>
			<LI> <a href="consol_metrics.php">Consolidated Metrics (#Tecks, #Users, #Shares, #PageViews)</a><BR><BR></LI>
			<LI> <a href="detailed_users.php">Detailed Metrics - Users & Profiles</a><BR><BR></LI>
			<LI> <a href="detailed_tecks.php">Detailed Metrics - Tecks</a><BR><BR></LI>
			<LI> <a href="detailed_shares.php">Detailed Metrics - Shares</a><BR><BR></LI>
			<LI> <a href="detailed_follow.php">Detailed Metrics - Following/Followers</a><BR><BR></LI>
			<LI> <a href="detailed_likes.php"> Detailed Metrics - Likes and Dislikes </a><BR><BR></LI>
			<!--LI> <a href="detailed_favorites.php"> Detailed Metrics - Favorites </a><BR><BR></LI-->
			<LI> <a href="detailed_pageviews.php">Detailed Metrics - PageViews</a><BR><BR></LI>
<?php
include "includes/footer.php";
} else {
	echo "You must be logged in to access this page.\n";
	echo "<a href='index.html'>Click here to Log In!</a>\n";
}
?>
