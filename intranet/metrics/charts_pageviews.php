<?php
	include "../includes/header.php";
	require "conn.php";

?>
<?php

$sql_avg_pageviews_per_teck = "select sum(PAGE_VIEWS)/COUNT(POST_ID) as AvgPageviewsPerTeck from POST";
$sql_avg_pageviews_per_teck_type = "select round(sum(PAGE_VIEWS)/COUNT(POST_ID),2) as AvgPageviewsPerTeckType, sum(PAGE_VIEWS) as SumPageviewsPerTeckType, TYPE as TeckType from POST group by TYPE order by TYPE DESC;";

$sql_num_pageviews_total = "select sum(PAGE_VIEWS) as SumPageviews from POST"; 
$sql_top_100_user_pageviews = "select USER_ID as UserID, sum(PAGE_VIEWS) as NumPageviews  from POST group by USER_ID order by sum(PAGE_VIEWS) DESC limit 100 ";
$sql_top_100_profile_pageviews = "select PROFILE_ID as ProfileID, sum(PAGE_VIEWS) as NumPageviews from POST group by PROFILE_ID order by sum(PAGE_VIEWS) DESC limit 100 " ;
$sql_top_100_teck_pageviews = "select POST_ID as PostID, TITLE as PostTitle, TYPE as PostType, PAGE_VIEWS as NumPageviews from POST order by PAGE_VIEWS DESC limit 100";

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
                <h1>Relatórios - PageViews</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             
             
             <!--Informatin BOX 3-->
              <div class="information-box-3 box2_share">
                    <div class="item">
                      <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">
                        	<?php
								// ------------------------------------------------------
								// Total Numbers of Pageviews
								// ------------------------------------------------------
								$result = mysql_query($sql_num_pageviews_total, $conn);
								if (mysql_num_rows($result) == 0)
										{
												echo " <br>\n";
												echo " Opps.. Somethint went wrong. Contact you administrator! \n";
										} else
										{
												while ($row = mysql_fetch_array($result))
												{
														echo "" . number_format($row['SumPageviews'],0, ".", ",") . "\n";
												}
										}
							?>
                        </div>
                        <div class="box-title">Total de PageViews</div>
                      </div>
                    </div>
                    <div class="item">
                        <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">
                        	<?php
								// ------------------------------------------------------
								// Average number of Pageviews per Teck
								// ------------------------------------------------------
								$result = mysql_query($sql_avg_pageviews_per_teck, $conn);
								if (mysql_num_rows($result) == 0) 
									{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											echo "" . round($row['AvgPageviewsPerTeck'],2). "\n";
										}
									}
							?>
                        
                        </div>
                        <div class="box-title">Média de Pageviews por Teck</div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->
              
              <h3>PageViews por Tipo de Teck</h3>
              <!--Striped table-->
              <div class="grid grid_table">
              <div class="grid-content overflow">
                
                <table class="table table-striped table_icons">
                <thead>
                  <tr>
                    <th></th>
                    <th>Média de PageViews</th>
                    <th>Total de PageViews</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  	// ------------------------------------------------------
					// Avg and Total Pageviews per Teck Type
					// ------------------------------------------------------
					$result = mysql_query($sql_avg_pageviews_per_teck_type, $conn);
					if (mysql_num_rows($result) == 0) 
						{
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						} else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								echo "<TR>\n";
								echo "<TD>\n";
								echo "<span class='" . $row['TeckType']. "'></span>\n";
								echo "</TD>\n"; 
								echo "<TD>" . number_format($row['AvgPageviewsPerTeckType'],2, ".", ","). "</TD>\n";
								echo "<TD>" . number_format($row['SumPageviewsPerTeckType'],0, ".", ","). "</TD>\n"; 
								echo "</TR>\n";
							}
						}
				  ?>
                </tbody>
              </table>

                
              <div class="clearfix"></div>
              </div>
              
              </div>
              <!--Striped table END-->
              
              
              
              
              
              
              <h3>Top 100 PageViews por Teck</h3>
              <!--Sample Table-->
              <div class="grid grid_table">
              
              <div class="grid-content overflow">
                
                <table class="table table-bordered table-mod-2 table-pv-teck">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Título do Teck</th>
                    <th>Tipo</th>
                    <th>Pageviews</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  	// ------------------------------------------------------
					// TOP 100 Posts - Per Pageview
					// ------------------------------------------------------
					$result = mysql_query($sql_top_100_teck_pageviews, $conn);
					if (mysql_num_rows($result) == 0) 
						{
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						} else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								$TeckID = $row['PostID'];
								echo "<TR>\n";
								echo "<TD><a href=\"show_teck_id_content.php?teckid=$TeckID\"> " . $row['PostID'] . "</a></TD>\n";
								echo "<TD>" . substr($row['PostTitle'],0,50) . "</TD>\n";
								echo "<TD>\n";
								echo "<span class='" . $row['PostType']. "'></span>\n";
								echo "</TD>\n";
								echo "<TD class='action-table'>" . number_format($row['NumPageviews'],0, ".", ",") . "<span class='right_info'><a href=\"/intranet/show_teck_id_content.php?teckid=$TeckID\"><img src='../images/icon/table_view.png' alt=''></a></span>\n";
								echo "</TR>\n";
							}
						}
					
					echo "</table>\n";
					echo "<HR>";
				  ?>
                </tbody>
              </table>  

                
              <div class="clearfix"></div>
              </div>
              
              </div>
              <!--Sample Table END-->

             <h3>Top 100 PageViews - Usuários e Perfis</h3>
             <div class="row-fluid">
             	  <!--Striped table-->
                  <div class="grid span6 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Usuários</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>PageViews</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// TOP 100 Users - Per Pageview
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_user_pageviews, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
												echo "<TR>\n";
												echo "<TD>" . $row['UserID'] . "</TD>\n";
												echo "<TD><span class='s_blue'>" . number_format($row['NumPageviews'],0, ".", ",") . "</span></TD>\n";
												echo "</TR>\n";
										}
								}
					  ?>
                    </tbody>
                  </table>
    
                    
                  <div class="clearfix"></div>
                  </div>
                  
                  </div>
                  <!--Striped table END-->
                  
                  
                  
                  
                  
                  <!--Striped table-->
                  <div class="grid span6 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Perfis</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Perfil</th>
                        <th>PageViews</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// TOP 100 Profiles - Per Pageview
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_profile_pageviews, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
												echo "<TR>\n";
												echo "<TD>&nbsp " . $row['ProfileID'] . "</TD>\n";
												echo "<TD><span class='s_blue'>" . number_format($row['NumPageviews'],0, ".", ",") . "</span></TD>\n";
												echo "</TR>\n";
										}
								}
					  ?>
                    </tbody>
                  </table>
    
                    
                  <div class="clearfix"></div>
                  </div>
                  
                  </div>
                  <!--Striped table END-->
                  
                  
                  
                  
                  
                  
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

