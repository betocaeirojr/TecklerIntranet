<?php
	include "../includes/header.php";
	require "conn.php";

?>
<?php


date_default_timezone_set("America/Sao_Paulo");


$sql_top_100_user_pageviews = 
        "select 
          USER_ID as UserID, 
          sum(PAGE_VIEWS) as NumPageviews  
        from 
          POST 
        group by USER_ID 
        order by sum(PAGE_VIEWS) DESC 
        limit 100 ";

$sql_top_100_profile_pageviews = 
        "select 
          PROFILE_ID as ProfileID, 
          sum(PAGE_VIEWS) as NumPageviews 
        from 
          POST 
        group by PROFILE_ID 
        order by sum(PAGE_VIEWS) DESC 
        limit 100 " ;

$sql_top_100_teck_pageviews = 
        "select 
          p.POST_ID as PostID, 
          p.TITLE as PostTitle, 
          p.TYPE as PostType, 
          p.PAGE_VIEWS as NumPageviews, 
          c.CATEGORY as CategoryName
        from 
          POST p,
          POST_CATEGORY pc,
          CATEGORY c
        where 
          pc.CATEGORY_ID = c.CATEGORY_ID and 
          p.POST_ID = pc.POST_ID
        order by PAGE_VIEWS DESC 
        limit 100";

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
                <h1>Reports - Categories</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->  

             <!-- START OF CONTENT BLOCK -->
              <h3>Categories Most Used</h3>
              <h4>Considering All Tecks and theirs Respective Pageviews</h4>

              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>Category Name</th>
                        <th>Number of Occorrencies</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_category_pageviews, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['CategoryName']        . "</TD>\n";
                      echo "<TD>" . number_format($row['NumOccurencies'],0,".", ",")      . "</TD>\n";
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->

            <!-- START OF CONTENT BLOCK -->
              <h3>Categories Most Used</h3>
              <h4>Considering only the TOP 10.000 Tecks with more Pageviews</h4>

              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>Category Name</th>
                        <th>Number of Occorrencies</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_category_top10000_pageviews, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['CategoryName']        . "</TD>\n";
                      echo "<TD>" . number_format($row['NumOccurencies'],0,".", ",")      . "</TD>\n";
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->

            <!-- START OF CONTENT BLOCK -->
              <h3>Categories Most Used</h3>
              <h4>Considering only the TOP 1000 Tecks with more Pageviews</h4>

              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>Category Name</th>
                        <th>Number of Occorrencies</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_category_top1000_pageviews, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['CategoryName']        . "</TD>\n";
                      echo "<TD>" . number_format($row['NumOccurencies'],0,".", ",")      . "</TD>\n";
                      echo "</TR>\n";
                    }

                    ?>
                </table>
              </div>
            </div>
            <!-- END OF CONTENT BLOCK -->



            <!-- START OF CONTENT BLOCK -->
              <h3>Categories Most Used</h3>
              <h4>Considering only the TOP 100 Tecks with more Pageviews</h4>

              <div class="grid grid_table">
                <div class="grid-content overflow">
                  <table class="table table-striped">
                    <tr>
                        <th>Category Name</th>
                        <th>Number of Occorrencies</th>
                    </tr>
                    <?php
                    $result = mysql_query($sql_category_top100_pageviews, $conn);
                    while ($row = mysql_fetch_assoc($result)) 
                    {
                      echo "<TR>\n";
                      echo "<TD>" . $row['CategoryName']        . "</TD>\n";
                      echo "<TD>" . number_format($row['NumOccurencies'],0,".", ",")      . "</TD>\n";
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

