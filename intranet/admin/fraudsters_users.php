<?php
	include "../includes/header.php";
	require "../metrics/conn.php";

?>
<?php

date_default_timezone_set("America/Sao_Paulo");

$sql_fraudster_listing = 
        "select 
          u.USER_ID as UserID, 
          u.DEFAULT_PROFILE_ID as DefaultProfileID, 
          u.USER_NAME as UserName,
          u.LOGIN as UserLogin, 
          u.EMAIL as UserEmail, 
          date(u.USER_CREATION_DATE) as UserCreationDate,
          uc.VALUE as LangCode 
        from 
          USER u, 
          USER_CONFIGURATION uc  
        where 
          u.USER_ID = uc.USER_ID and 
          u.IS_TRICKSTER = TRUE and 
          uc.CODE='lang' 
        order by 
          u.USER_NAME ASC";

$sql_fraudester_profiles_listing = 
        "select 
           u.USER_ID as UserID, 
           u.USER_NAME as UserName, 
           p.PROFILE_ID as ProfileID, 
           p.SIGNATURE as ProfileSignature,
           date(p.PROFILE_CREATION_DATE) as ProfileCreationDate,
           p.IS_RESTRICTED as IsProfileRestricted 
        from 
          PROFILE p, 
          USER_PROFILE up, 
          USER u 
        where 
          u.USER_ID = up.USER_ID and 
          p.PROFILE_ID = up.PROFILE_ID and 
          u.IS_TRICKSTER = TRUE";

$sql_fraudster_tecks_listing = 
        "select 
          p.SIGNATURE as ProfileSignature,
          p.PROFILE_ID as ProfileID, 
          count(pt.POST_ID) as NumTecks, 
          sum(pt.PAGE_VIEWS) as SumPageviews, 
          u.USER_NAME as UserName,
          u.USER_ID as UserID 
        from 
          POST pt, USER u, PROFILE p, USER_PROFILE up 
        where 
          p.PROFILE_ID = pt.PROFILE_ID and 
          u.IS_TRICKSTER = TRUE and 
          up.PROFILE_ID = p.PROFILE_ID and 
          u.USER_ID = up.USER_ID 
        group by p.PROFILE_ID order by p.SIGNATURE ASC";


$sql_top_100_user_avg_pageviews_per_teck = 
  "select 
    sum(p.PAGE_VIEWS) as SumPageviews, 
    count(p.POST_ID) as NumTecks,
    sum(p.PAGE_VIEWS) / count(p.POST_ID) as AvgPageviewsPerTeck,
    p.PROFILE_ID as ProfileID, 
    pr.SIGNATURE as ProfileSignature,
    p.USER_ID as UserID, 
    u.USER_NAME as UserName 
  from 
    POST p,
    PROFILE pr,   
    USER u, 
    USER_PROFILE up 
  where 
    p.PROFILE_ID = pr.PROFILE_ID and 
    u.USER_ID = up.USER_ID and 
    p.PROFILE_ID = up.PROFILE_ID and  
    p.USER_ID not in (select a.USER_ID from USER a where a.IS_TRICKSTER = TRUE) 
  group by 
    p.PROFILE_ID 
    order by 3 DESC limit 1000";


$sql_investigate_by_shares = "";

$sql_investigate_by_favourites = "";

$sql_investigate_by_followers = "";



/*
$sql_category_pageviews = 
       "select 
            count(a.CategoryName) as NumOccurencies, a.CategoryName as CategoryName 
        from 
          (select 
              p.POST_ID as PostID, p.TITLE as PostTitle, p.TYPE as PostType, 
              p.PAGE_VIEWS as NumPageviews, c.CATEGORY as CategoryName
          from 
            POST p, POST_CATEGORY pc, CATEGORY c 
          where pc.CATEGORY_ID = c.CATEGORY_ID and p.POST_ID = pc.POST_ID 
          order by PAGE_VIEWS DESC) a 
        group by a.CategoryName 
        order by COUNT(a.CategoryName) DESC";

$sql_category_top100_pageviews = 
       "select 
            count(a.CategoryName) as NumOccurencies, a.CategoryName as CategoryName 
        from 
          (select 
              p.POST_ID as PostID, p.TITLE as PostTitle, p.TYPE as PostType, 
              p.PAGE_VIEWS as NumPageviews, c.CATEGORY as CategoryName
          from 
            POST p, POST_CATEGORY pc, CATEGORY c 
          where pc.CATEGORY_ID = c.CATEGORY_ID and p.POST_ID = pc.POST_ID 
          order by PAGE_VIEWS DESC limit 200) a 
        group by a.CategoryName 
        order by COUNT(a.CategoryName) DESC";

$sql_category_top1000_pageviews = 
       "select 
            count(a.CategoryName) as NumOccurencies, a.CategoryName as CategoryName 
        from 
          (select 
              p.POST_ID as PostID, p.TITLE as PostTitle, p.TYPE as PostType, 
              p.PAGE_VIEWS as NumPageviews, c.CATEGORY as CategoryName
          from 
            POST p, POST_CATEGORY pc, CATEGORY c 
          where pc.CATEGORY_ID = c.CATEGORY_ID and p.POST_ID = pc.POST_ID 
          order by PAGE_VIEWS DESC limit 2000) a 
        group by a.CategoryName 
        order by COUNT(a.CategoryName) DESC";

$sql_category_top10000_pageviews = 
       "select 
            count(a.CategoryName) as NumOccurencies, a.CategoryName as CategoryName 
        from 
          (select 
              p.POST_ID as PostID, p.TITLE as PostTitle, p.TYPE as PostType, 
              p.PAGE_VIEWS as NumPageviews, c.CATEGORY as CategoryName
          from 
            POST p, POST_CATEGORY pc, CATEGORY c 
          where pc.CATEGORY_ID = c.CATEGORY_ID and p.POST_ID = pc.POST_ID 
          order by PAGE_VIEWS DESC limit 20000) a 
        group by a.CategoryName 
        order by COUNT(a.CategoryName) DESC";   

*/        
?>  

    <div id="wrap">
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
          <?php include "../includes/main_menu.php"; ?>
          <?php include "../includes/submenu_charts.php"; ?>
          <div class="clearfix"></div>
        </div>
        <!--SIDEBAR END-->
    
    	
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
           <div class="block">
   		     <div class="clearfix"></div>
            <!--page title-->
            <div class="pagetitle">
                <h1>Reports - Fraudster Information</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->  

             <!-- START OF CONTENT BLOCK -->
              <h3>Basic information on Fraudster Users</h3>

              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>User Login/Account</th>
                        <th>User Creation Date</th>
                        <th>User Default Language Code</th>
                        <th>User Default Profile ID</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_fraudster_listing, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['UserID']        . "</TD>\n";
                      echo "<TD>" . $row['UserName']      . "</TD>\n";
                      echo "<TD>" . $row['UserEmail']        . "</TD>\n";
                      echo "<TD>" . $row['UserLogin']      . "</TD>\n";
                      echo "<TD>" . $row['UserCreationDate']        . "</TD>\n";
                      echo "<TD>" . $row['LangCode']      . "</TD>\n";
                      echo "<TD>" . $row['DefaultProfileID']      . "</TD>\n";
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->

            <!-- START OF CONTENT BLOCK -->
              <h3>Fraudster Profiles</h3>
              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Profile ID</th>
                        <th>Profile Signature</th>
                        <th>Profile Creation Date</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_fraudester_profiles_listing, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['UserID']        . "</TD>\n";
                      echo "<TD>" . $row['UserName']      . "</TD>\n";
                      echo "<TD>" . $row['ProfileID']        . "</TD>\n";
                      echo "<TD>" . $row['ProfileSignature']      . "</TD>\n";
                      echo "<TD>" . $row['ProfileCreationDate']        . "</TD>\n";
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->

            <!-- START OF CONTENT BLOCK -->
              <h3>Tecks Information for Fraudster Users</h3>
              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Profile ID</th>
                        <th>Profile Name</th>
                        <th>Number of Tecks</th>
                        <th>Total Pageviews</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_fraudster_tecks_listing, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['UserID']        . "</TD>\n";
                      echo "<TD>" . $row['UserName']      . "</TD>\n";
                      echo "<TD>" . $row['ProfileID']      . "</TD>\n";
                      echo "<TD>" . $row['ProfileSignature']      . "</TD>\n";
                      echo "<TD>" . $row['NumTecks']      . "</TD>\n";
                      echo "<TD>" . $row['SumPageviews']      . "</TD>\n";
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->


            <!-- START OF CONTENT BLOCK -->
              <h3>Suspicious Users</h3>
              <h4>For Further Investigation</h4>

              <div class="grid grid_table">
                <div class="grid-content overflow" style="width:100%; overflow-x:scroll;">
                  <table class="table table-striped table-centered">
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Profile ID</th>
                        <th>Profile Signature</th>
                        <th>Number of Tecks</th>
                        <th>Total Number of Pageviews</th>
                        <th>Average Pageview Per Teck</th>
                        <th>Facebook Account</th>
                        <th>Number of Friends in FB</th>
                        <th>Twitter Account</th>
                        <th>Number of Followers in Twitter</th>
                    </tr>

                    <?php
                    $result = mysql_query($sql_top_100_user_avg_pageviews_per_teck, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['UserID']                  . "</TD>\n";
                      echo "<TD>" . $row['UserName']                . "</TD>\n";
                      echo "<TD>" . $row['ProfileID']               . "</TD>\n";
                      echo "<TD>" . $row['ProfileSignature']        . "</TD>\n";
                      echo "<TD>" . $row['NumTecks']                . "</TD>\n";
                      echo "<TD>" . $row['SumPageviews']            . "</TD>\n";
                      echo "<TD>" . round($row['AvgPageviewsPerTeck'],2)     . "</TD>\n";

                      // Finding the Number of friends in Facebook
                      $sql_info_facebook = 
                        "select NAME as FacebookName, FRIENDS_AMOUNT as NumberOfFriends 
                        from SOCIAL_NETWORK 
                        where 
                          TYPE='facebook' and USER_ID = " . $row['UserID'] . " limit 1" ;
                      $result_fb = mysql_query($sql_info_facebook, $conn);
                      $row_fb = mysql_fetch_assoc($result_fb);
                      echo "<TD>" . (!empty($row_fb['FacebookName']) ? $row_fb['FacebookName'] : "" )     . "</TD>\n";
                      echo "<TD>" . (!empty($row_fb['NumberOfFriends']) ? $row_fb['NumberOfFriends'] : "" ) . "</TD>\n";
                
                      // Finding the Number of Followers in Twitter
                      $sql_info_twitter = 
                        "select NAME as TwitterAccount, FRIENDS_AMOUNT as NumberOfFollowers  
                        from SOCIAL_NETWORK 
                        where 
                          TYPE='twitter' and USER_ID = " . $row['UserID'] . " limit 1" ;
                      $result_tw = mysql_query($sql_info_twitter, $conn);
                      $row_ftw = mysql_fetch_assoc($result_tw);
                      echo "<TD>" . (!empty($row_tw['TwitterAccount'])  ? : "")   . "</TD>\n";
                      echo "<TD>" . (!empty($row_tw['NumberOfFollowers']) ? : "" ) . "</TD>\n";
                       
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->            




          <?php include "../includes/footer.php"; ?>
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->


    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>

