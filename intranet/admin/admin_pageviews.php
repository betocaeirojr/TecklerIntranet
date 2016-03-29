
<?php
	include "../includes/header.php";
	require_once "../metrics/conn.php";


// Checking whether the form has been posted or not
if (isset($_POST['queryby'])) {
  $prQueryBy = $_POST['queryby'];
  switch ($prQueryBy) {
    case 'teckid':
      $prTeckId = $_POST['teck_id'];
      $sql = 
              "select 
                sum(VIEWS) as ViewsOfTheDay, 
                date(DAY) as Day 
              from 
                DAILY_VIEWS 
              where 
                POST_ID in (" . $prTeckId  . ") and 
                date(DAY) > date('0000-00-00') 
              group by 
                date(DAY) 
              order by 
                date(DAY)";
      $pageviews_per_day = "";
      break;
    
    case 'profileid':
      $prProfileId = $_POST['profile_id'];
      $sql = 
              "select 
                sum(VIEWS) as ViewsOfTheDay, 
                date(DAY) as Day 
              from 
                DAILY_VIEWS 
              where 
                POST_ID in (select post_id from POST where profile_id=" . $prProfileId .  ") and 
                date(DAY) > date('0000-00-00') 
              group by 
                date(DAY) 
              order by 
                date(DAY)";
      $pageviews_per_day = "";
      break;

    default:
      $sql = "select 1 as Day, 2 as ViewsOfTheDay";
      break;
  }

  // Run the Query
  $result = mysql_query($sql, $conn); 
  while ($row = mysql_fetch_assoc($result)) {
    $pageviews_per_day[] = array(
                                  "Date"      => $row['Day'],
                                  "Pageviews" => $row['ViewsOfTheDay']
                                );
  }
}

?>

    




    <div id="wrap">
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "../includes/main_menu.php"; ?>
          <?php include "../includes/submenu_admin.php"; ?>
          <div class="clearfix"></div>
        </div>
        <!--SIDEBAR END-->
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
          <div class="block">
   		  <div class="clearfix"></div>
             <!--page title-->
             <div class="pagetitle">
                <h1>Pageview Reporting and Querying System</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             <div class="row-fluid">
                <!--Unordered Lists Start-->
                <div class="grid span6">
                  <div class="grid-title">
                      <div class="pull-left">
                        <div class="icon-title"><i class="icon-align-justify"></i></div>
                        <span>Pageviews Per Teck Per Day</span> 
                        <div class="clearfix"></div>
                      </div>
                    <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                            <FORM method="POST" action="admin_pageviews.php">
                            <TABLE border=1>
                                <TR>
                                    <TD>Teck ID: <input type="text" name="teck_id"></TD>
                                </TR>
                                <TR>
                                    <TD>
                                      <input type="submit" name="action" value="cancel"> 
                                      <input type="hidden" name="queryby" value="teckid">
                                      <input type="submit" name="action" value="submit">
                                    </TD>
                                </TR>
                            </TABLE>
                            </FORM> 
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Unordered Lists END-->
                
                
                <!--Ordered Lists Start-->
                <div class="grid span6">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Pageviews per Profile per Day</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                            <FORM method="POST" action="admin_pageviews.php">
                            <TABLE border=1>
                                <TR>
                                    <TD>Profile ID: <input type="text" name="profile_id"></TD>
                                </TR>
                                <TR>
                                    <TD>
                                      <input type="submit" name="action" value="cancel"> 
                                      <input type="hidden" name="queryby" value="profileid">
                                      <input type="submit" name="action" value="submit"></TD>
                                </TR>
                            </TABLE>
                            </FORM>
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Ordered Lists END-->
                <p><br> &nbsp;</p>
                <hr>

                <p> Check your results below:</p>

                <div class="grid grid_table">
                <div class="grid-content">
                  <table class="table table-striped table_centered" id="resultTable" summary="Code page support in different versions of MS Windows." rules="groups" frame="hsides" >
                  <thead>
                     <tr>
                      <?php
                        if (isset($_POST['queryby'])){
                          echo "Showing Results for ";
                          if ($prQueryBy == 'teckid') {
                            echo "<B>Teck ID</B>: " . $_POST['teck_id'];
                          } else {
                            echo "<B>Profile ID</B>: " . $_POST['profile_id'];
                          }

                          $keys = array_keys($pageviews_per_day[0]);
                          foreach ($keys as $key => $value) {
                            echo "<th align=center>";
                            echo $value;
                            echo "</th>";
                          }
                          reset($pageviews_per_day);
                        } else {
                          echo "No query has been made yet! <BR>";
                        }
                      ?>
                      </tr> 
                  </thead>
                  <tbody>
                    <?php 
                      if (isset($_POST['queryby'])) {
                        foreach ($pageviews_per_day as $ekey => $evalue) {
                          echo "<tr>";    
                          foreach ($evalue as $ikey => $ivalue) {
                            echo "<TD align=center>";
                            echo $ivalue; 
                            echo "</TD>";

                          }
                          echo "</tr>";
                        }
                      }
                    ?>
                  </tbody>
                </table>                  
                <div class="clearfix"></div>
            </div>
               
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

