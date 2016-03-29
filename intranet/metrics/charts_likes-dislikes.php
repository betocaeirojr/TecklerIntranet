<?php
	include "../includes/header.php";
	require "conn.php";
  date_default_timezone_set('America/Sao_Paulo');

?>
<?php

$sql_num_total_votes = 
  "select 
    sum(a.NumRates) as NumTotalVotes 
  from 
    (select 
      count(RATING_ID) as NumRates , 
      RATING_CREATION_DATE as CreationDate 
    from 
      RATING 
    group by date(RATING_CREATION_DATE) 
    order by date(RATING_CREATION_DATE) DESC) a";

$sql_num_total_rated_tecks = "select count(distinct POST_ID) as NumTecksRated from RATING ";

$sql_num_total_rated_profiles = "select count(distinct PROFILE_ID) as NumProfilesRated from RATING ";

//$sql_num_total_rates_day = "select count(RATING_ID) as NumRates, RATING_CREATION_DATE as CreationDate from RATING group by date(RATING_CREATION_DATE) order by date(RATING_CREATION_DATE) DESC limit 90 "; 
$sql_num_total_rates_day = 
  "select 
    date(a.CreationDate) as ReferenceDate,
    (a.NumTecks) as NumTecksVoted,
    (b.NumRates) as NumTotalRates
  from 
    (select
      count(distinct POST_ID) as NumTecks, 
      RATING_CREATION_DATE as CreationDate
    from
      RATING 
    group by date(RATING_CREATION_DATE) 
    order by date(RATING_CREATION_DATE) DESC 
    limit 90) a, 
    (select 
      count(RATING_ID) as NumRates, 
      RATING_CREATION_DATE as CreationDate 
    from 
      RATING 
    group by date(RATING_CREATION_DATE) 
    order by date(RATING_CREATION_DATE) DESC 
    limit 90) b 
  where
    date(a.CreationDate) = date(b.CreationDate) 
  order by  date(a.CreationDate) DESC";


//$sql_num_total_rates_month = "select count(RATING_ID) as NumRates , RATING_CREATION_DATE as CreationDate from RATING group by month(RATING_CREATION_DATE) order by month(RATING_CREATION_DATE) DESC limit 24";
$sql_num_total_rates_month = 
  "select 
    date(a.CreationDate) as ReferenceDate,
    (a.NumTecks) as NumTecksVoted,
    (b.NumRates) as NumTotalRates 
  from 
    (select 
      count(distinct POST_ID) as NumTecks, 
      RATING_CREATION_DATE as CreationDate 
    from 
      RATING 
    group by year(RATING_CREATION_DATE), month(RATING_CREATION_DATE) 
    order by year(RATING_CREATION_DATE) DESC, month(RATING_CREATION_DATE) 
    limit 24) a, 
    (select 
      count(RATING_ID) as NumRates, 
      RATING_CREATION_DATE as CreationDate 
    from 
      RATING 
    group by year(RATING_CREATION_DATE), month(RATING_CREATION_DATE)  
    order by year(RATING_CREATION_DATE), month(RATING_CREATION_DATE) DESC 
    limit 24) b 
  where
  date(a.CreationDate) = date(b.CreationDate) and 
  year(a.CreationDate) <> 0000
  order by date(a.CreationDate) DESC";

$sql_top_100_rated_tecks = "select count(RATING_ID) as NumRates, POST_ID as PostID from RATING group by POST_ID order by count(RATING_ID) DESC limit 100 ";

$sql_top_100_best_rated_tecks = "select sum(RATING) as SumRates, POST_ID as PostID FROM RATING group by POST_ID order by sum(rating) DESC limit 100";

$sql_top_100_worst_rated_tecks = "select sum(RATING) as SumRates , POST_ID as PostID FROM RATING group by POST_ID order by sum(rating) ASC limit 100";

$sql_top_100_rated_profiles = "select count(RATING_ID) as NumRates, PROFILE_ID as ProfileID from RATING group by PROFILE_ID order by count(RATING_ID) DESC limit 100";

$sql_top_100_best_rated_profiles = "select sum(RATING) as SumRates , PROFILE_ID as ProfileID FROM RATING group by PROFILE_ID order by sum(rating) DESC limit 100";

$sql_top_100_worst_rated_profiles = "select sum(RATING) as SumRates, PROFILE_ID as ProfileID FROM RATING group by PROFILE_ID order by sum(rating) ASC limit 100 ";


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
                <h1>Relatórios - Likes e Dislikes</h1> 
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
                                // -------------------------------------------------------------
                                // Total Number of Votes
                                // -------------------------------------------------------------
                                $result = mysql_query($sql_num_total_votes, $conn);
                                if (mysql_num_rows($result) == 0)
                                    {
                                        echo " <br>\n";
                                        echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                    } else
                                    {
                                        while ($row = mysql_fetch_array($result))
                                        {
                                            echo "" . number_format($row['NumTotalVotes'],0,".","," ). "\n";
                                        }
                                    }
                              ?>
                        </div>
                        <div class="box-title">N&uacute;mero Total de Votos</div>
                      </div>
                    </div>
                    <div class="item">
                      <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">
                        	<?php
                								// -------------------------------------------------------------
                								// Number of Rated Tecks / Number of Rated Profiles
                								// -------------------------------------------------------------
                								$result = mysql_query($sql_num_total_rated_tecks, $conn);
                								if (mysql_num_rows($result) == 0)
                										{
                												echo " <br>\n";
                												echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                										} else
                										{
                												while ($row = mysql_fetch_array($result))
                												{
                														echo "" . number_format($row['NumTecksRated'], 0, ".","," ). "\n";
                												}
                										}
                							?>
                        </div>
                        <div class="box-title">Tecks Votados</div>
                      </div>
                    </div>
                    <div class="item">
                        <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">
                        	<?php
              								$result = mysql_query($sql_num_total_rated_profiles, $conn);
              								if (mysql_num_rows($result) == 0)
              										{
              												echo " <br>\n";
              												echo " Opps.. Somethint went wrong. Contact you administrator! \n";
              										} else
              										{
              												while ($row = mysql_fetch_array($result))
              												{
              														echo "" . number_format($row['NumProfilesRated'],0,".",",") . "\n";
              												}
              										}
              							?>
                        
                        </div>
                        <div class="box-title">Perfis Votados</div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->            
              
              <h3>Número de Votos (Likes e Dislikes)</h3>
              <div class="row-fluid">
                    <!--Tabs Nav Left Start-->
                <div class="grid span12 grid_table">
                  <div class="grid-title"> 
                  </div>                    
                   <ul id="myTab" class="tabs-nav">
                      <li class="active"><a href="#home" data-toggle="tab">Diário</a></li>
                      <li><a href="#profile" data-toggle="tab">Mensal</a></li>
                    </ul>
                    <div class="clearfix"></div> 
                  <div class="grid-content">
                   <div id="myTabContent" class="tab-content">
                      <div class="tab-pane fade active in" id="home">
                      		<div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Data</th>
                            <th>Número de Votos</th>
                            <th>Número de Tecks Votados</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          	// ------------------------------------------------------
							// Total Numbers of Rates - last 90 days
							// ------------------------------------------------------
							$result = mysql_query($sql_num_total_rates_day, $conn);
							if (mysql_num_rows($result) == 0) 
									{
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
											while ($row = mysql_fetch_array($result)) 
											{
													echo "<TR>\n";
													echo "<TD>" .date('Y-m-d',strtotime($row['ReferenceDate'])) ." </TD>\n";
													echo "<TD><span class='s_green'>" . number_format($row['NumTotalRates'],0,".",",") . "</span></TD>\n";
                          echo "<TD><span class='s_green'>" . number_format($row['NumTecksVoted'],0,".",",") . "</span></TD>\n";
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
                      </div>
                      <div class="tab-pane fade" id="profile">
                     <div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Mês</th>
                            <th>Número de Votos</th>
                            <th>Número de Tecks Votados</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	// ------------------------------------------------------
							// Total Numbers of Rates - last 24 months
							// ------------------------------------------------------
							$result = mysql_query($sql_num_total_rates_month, $conn);
							if (mysql_num_rows($result) == 0) 
								{
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										echo "<TR>\n";
                          echo "<TD>" .date('Y-m-d',strtotime($row['ReferenceDate'])) ." </TD>\n";
                          echo "<TD><span class='s_green'>" . number_format($row['NumTotalRates'],0,".",",") . "</span></TD>\n";
                          echo "<TD><span class='s_green'>" . number_format($row['NumTecksVoted'],0,".",",") . "</span></TD>\n";
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
                      </div>
                    </div>
                  </div>
                 </div>
                 <!--Tabs Nav Left END-->
              
              </div>
              
              
              
              
              
              
              
              
              
              
              
              
          
              
              
             
             
             
             
             
             
             
             <h3>Top 100 Votos em Tecks</h3>
             <div class="row-fluid">
             	  <!--Striped table-->
                  <div class="grid span4 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Tecks Mais Votados</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Votos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Most Rated Tecks - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_rated_tecks, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
						
												echo "<TR>\n";
												echo "<TD>" . $row['PostID']   . " </TD>\n";
												echo "<TD><span class='s_blue'>" . $row['NumRates'] . "</span></TD>\n";
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
                  <div class="grid span4 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Tecks mais BEM votados</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Votos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Best Rated Tecks - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_best_rated_tecks, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
						
												echo "<TR>\n";
												echo "<TD>" . $row['PostID']   . " </TD>\n";
												echo "<TD><span class='s_blue'>" . $row['SumRates'] . "</span></TD>\n";
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
                  <div class="grid span4 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Tecks mais MAL votados</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Votos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Worst Rated Tecks - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_worst_rated_tecks, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
						
												echo "<TR>\n";
												echo "<TD>" . $row['PostID']   . " </TD>\n";
												echo "<TD><span class='s_blue'>" . $row['SumRates'] . "</span></TD>\n";
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
              
              
              
              
              
              
              
              
              
              
              
              
              <h3>Top 100 Votos em Perfis</h3>
             <div class="row-fluid">
             	  <!--Striped table-->
                  <div class="grid span4 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Perfis Mais Votados</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Perfil</th>
                        <th>Votos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Most Rated Profiles  - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_rated_profiles, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
						
												echo "<TR>\n";
												echo "<TD>" . $row['ProfileID']   . " </TD>\n";
												echo "<TD><span class='s_blue'>" . number_format($row['NumRates'],0,".",",") . "</span></TD>\n";
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
                  <div class="grid span4 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Perfis mais BEM votados</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Perfil</th>
                        <th>Votos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Best Rated Profiles  - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_best_rated_profiles, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
						
												echo "<TR>\n";
												echo "<TD>" . $row['ProfileID']   . " </TD>\n";
												echo "<TD><span class='s_blue'>" . number_format($row['SumRates'],0,".",",") . "</span></TD>\n";
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
                  <div class="grid span4 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Perfis mais MAL votados</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Perfi</th>
                        <th>Votos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Worst Rated Profiles  - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_top_100_worst_rated_profiles, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
						
												echo "<TR>\n";
												echo "<TD>" . $row['ProfileID']   . " </TD>\n";
												echo "<TD><span class='s_blue'>" . number_format($row['SumRates'],0,".",",") . "</span></TD>\n";
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

