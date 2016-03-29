<?php
  include "../includes/header.php";
	include "../includes/java_scripts.php"; 
  require "conn.php";
  date_default_timezone_set('America/Sao_Paulo');
  

  $sql_num_users_month = 
    "select count(USER_ID) as NumUsers, USER_CREATION_DATE as CreationMonth 
    from USER group by month(USER_CREATION_DATE) order by USER_CREATION_DATE DESC limit 24"; 

  $sql_num_users_day = 
    "select count(USER_ID) as NumUsers, USER_CREATION_DATE as CreationDay 
    from USER group by date(USER_CREATION_DATE) order by USER_CREATION_DATE DESC limit 90";

  $sql_num_users_lang = 
    "select count(VALUE) as NumUsers, VALUE as Language 
    from USER_CONFIGURATION where CODE='LANG' group by VALUE" ;

  $sql_num_profiles_month = 
    "select count(PROFILE_ID) as NumProfiles, PROFILE_CREATION_DATE as ProfileCreationDate 
    from PROFILE group by month(PROFILE_CREATION_DATE) order by PROFILE_CREATION_DATE DESC";

  $sql_num_profiles_day = 
    "select count(PROFILE_ID) as NumProfiles, PROFILE_CREATION_DATE as ProfileCreationDate 
    from PROFILE group by date(PROFILE_CREATION_DATE) order by PROFILE_CREATION_DATE DESC";

  $sql_avg_profiles_user = 
    "select p.NumProfiles/u.NumUsers as AvgProfilesPerUser from (select count(inp.PROFILE_ID) as NumProfiles 
      from PROFILE inp) p, (select count(inu.USER_ID) as NumUsers from USER inu) u";

  $sql_num_total_users = 
    "select count(USER_ID) as NumTotalUsers 
    from USER";

  $sql_num_total_profiles = 
    "select count(PROFILE_ID) as NumTotalProfiles 
    from PROFILE";

  $sql_num_active_profiles = 
    "select count(distinct PROFILE_ID) as NumActiveProfiles 
    from POST where date(CREATION_DATE) > CURDATE() - INTERVAL 90 DAY";  

  //$sql_num_profiles = "";



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
                <h1>Relatórios - Usuários e Perfis</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             <div class="row clearfix">
            <!--Informatin BOX 3-->
              <div class="span3 information-box-3 box_user">
                    <div class="item">
                      <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt="">
                        <div class="box-figures">Total Number of Users</div>
                        <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Total Numbers of Users
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_num_total_users, $conn);
                                  if (mysql_num_rows($result) == 0)
                                  {
                                                  echo " <br>\n";
                                                  echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                  } else
                                  {
                                      while ($row = mysql_fetch_assoc($result))
                                      {
                                                      echo "" . number_format($row['NumTotalUsers'], 0, ".",","). "";
                                                      $NumTotalUsers = $row['NumTotalUsers'];
                                      }
                                  }
                          ?>

                        </div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->
  
<!--Informatin BOX 3-->
              <div class="span3 information-box-3 box_user">
                    <div class="item">
                      <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt="">
                        <div class="box-figures">Total Number of Profiles</div>
                        <div class="box-title">
                                <?php
                                                                // ------------------------------------------------------
                                                                // Total Numbers of Profiles
                                                                // ------------------------------------------------------
                                                                $result = mysql_query($sql_num_total_profiles, $conn);
                                                                if (mysql_num_rows($result) == 0)
                                                                                {
                                                                                                echo " <br>\n";
                                                                                                echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                                                                } else
                                                                                {
                                                                                                while ($row = mysql_fetch_assoc($result))
                                                                                                {
                                                                                                                echo "" . number_format($row['NumTotalProfiles'], 0, ".", ","). "";
														$NumTotalProfiles = $row['NumTotalProfiles'];
                                                                                                }
                                                                                }
                                                        ?>

                        </div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->

          
<!--Informatin BOX 3-->
              <div class="span3 information-box-3 box_user">
                    <div class="item">
                      <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt="">
                        <div class="box-figures">Active Tecklers (90 Days)</div>
                        <div class="box-title">
                                <?php
                                                                // ------------------------------------------------------
                                                                // Total Active Profiles
                                                                // ------------------------------------------------------
                                                                $result = mysql_query($sql_num_active_profiles, $conn);
                                                                if (mysql_num_rows($result) == 0)
                                                                                {
                                                                                                echo " <br>\n";
                                                                                                echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                                                                } else
                                                                                {
                                                                                                while ($row = mysql_fetch_assoc($result))
                                                                                                {
                                                                                                                echo "" . number_format($row['NumActiveProfiles'],0,".", ","). "";
														echo "<br>(" . (round($row['NumActiveProfiles']/$NumTotalProfiles,4)*100) . "%)\n";
                                                                                                }
                                                                                }
                                                        ?>

                        </div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->
 
             <!--Informatin BOX 3-->
              <div class="span3 information-box-3 box_user">
                    <div class="item">
                      <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">Média de Perfis por Usuário</div>
                        <div class="box-title">
                        	<?php
								// ------------------------------------------------------
								// Avg Profiles per User
								// ------------------------------------------------------
								$result = mysql_query($sql_avg_profiles_user, $conn);
								if (mysql_num_rows($result) == 0)
										{
												echo " <br>\n";
												echo " Oipps.. Somethint went wrong. Contact you administrator! \n";
										} else 
										{
												while ($row = mysql_fetch_assoc($result))
												{
														echo "" . $row['AvgProfilesPerUser']. "";
												}
										}
							?>
                            
                        </div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->
             	
             </div>
             
             <h3>Usuários Registrados por País</h3>
             <!-- Registered Users - Breakdown : LANGUAGE  -->
              <div class="grid grid_table">
              <div class="grid-content overflow">
                
                <table class="table table-bordered table-mod-2">
                <thead>
                  <tr>
                    <th>Idioma</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                <?php
				$result = mysql_query($sql_num_users_lang, $conn);
				if (mysql_num_rows($result) == 0) 
					{
						echo " <br>\n";
						echo " Opps.. Somethint went wrong. Contact you administrator! \n";
					} else 
					{
						while ($row = mysql_fetch_array($result)) 
						{
							echo "<tr>\n"; 
							echo "<td class='t_b_blue'>" . $row['Language']. "</td>\n";
							echo "<td class='action-table'>" . number_format($row['NumUsers'], 0, ".", ",") ;
							echo " (" . round($row['NumUsers']/$NumTotalUsers , 4) * 100 . "%)\n";  
							echo "</td>\n";
							echo "</tr>\n";
						}
					}
				?>
                </tbody>
              </table>  
              <div class="clearfix"></div>
              </div>
              </div>
              <!-- Registered Users - Breakdown : LANGUAGE  -->
              </br>
              </br>
              
              
              <h3>Usuários Registrados</h3>
              <div class="row-fluid grid_table">
                    <!--Tabs Nav Left Start-->
                <div class="grid span12">
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
                            <th>Número de Usuários</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          	// ------------------------------------------------------
                            // Users per Day - Last 90 days
                            // ------------------------------------------------------
                            $result = mysql_query($sql_num_users_day, $conn);
                            if (mysql_num_rows($result) == 0) 
                                {
                                    echo " <br>\n";
                                    echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                } else 
                                {
                                    $infoUsersDay = "";
                                    $infoDate = "";
                                    while ($row = mysql_fetch_assoc($result)) 
                                    {
                                        echo "<tr>\n";
                                        echo "<td>" .date('Y-m-d',strtotime($row['CreationDay'])) ." </td>\n";
                                        echo "<td><span class='s_green'>" . number_format($row['NumUsers'], 0, ".", ","). "</span></td>\n";
                                        echo "</tr>\n";
                                        $infoUsersDay .= "," . $row['NumUsers'] ;
                                        $infoDate     .= "," . date("Y-m-d",strtotime($row['CreationDay']));
                                    }
                                    $infoUsersDay = substr($infoUsersDay, 1);
                                    $arr_infoUsersDay = explode(",", $infoUsersDay);
                                    $arr_infoUsersDay = array_reverse($arr_infoUsersDay);
                                    $infoUsersDay = implode(",", $arr_infoUsersDay);  

                                    $infoDate     = substr($infoDate, 1);
                                    $arr_infoDate = explode(",", $infoDate);
                                    $arr_infoDate = array_reverse($arr_infoDate);
                                    $infoDate = implode(",", $arr_infoDate);
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
                            <th>Número de Usuários</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	// ------------------------------------------------------
							// Users per month - Last 24 months
							// ------------------------------------------------------
							$result = mysql_query($sql_num_users_month, $conn);
							if (mysql_num_rows($result) == 0) 
								{
									echo " <br>\n";
									echo " Oipps.. Somethint went wrong. Contact you administrator! \n";
								} else 
								{
									while ($row = mysql_fetch_assoc($result)) 
									{
										echo "<tr>\n";
										echo "<TD>" .date('Y-m',strtotime($row['CreationMonth'])) ." </TD>\n";
										echo "<TD><span class='s_green'>" . number_format($row['NumUsers'], 0, ".", ","). "</span></TD>\n";
										echo "</tr>\n";
							
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
              <br>
              <!--  Users per Day Charts -->
              <div class='clearfix'>
                <input type='hidden' id="date-axis-X" value="<?php echo $infoDate; ?>">
                <input type='hidden' id="users-axis-Y" value="<?php echo $infoUsersDay; ?>">
                <div id="container_users" style="width:100%; height:300px;"></div>
                <script>
                  $(function () { 
                      // Treating Pageviews Graphic
                          var dateValues = $('#date-axis-X').val();
                          var xAxis = dateValues.split(",");
                          var usersValues = $('#users-axis-Y').val();
                          var yAxis = usersValues.split(',');
                          var merge = [];
                          for (var i=0; i < xAxis.length; i++) { 
                            var dateparts = xAxis[i].split("-");
                            var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
                            var users = parseInt(yAxis[i], 10);
                            merge.push([date, users]);
                          }
                          $('#container_users').highcharts({
                                chart: { type: 'line' },
                                title: { text: 'Users per Day' },
                                xAxis: { type : 'datetime' },
                                yAxis: { title: { text: 'Users per Day' } , 
                                          type: 'logarithmic'},
                                series: [{
                                    name: 'Users per Day',
                                    data: merge
                                }]
                            });
                          });
                </script>  
              </div >

              </br>
              </br>
              <h3>Perfis Criados</h3>
              <div class="row-fluid">
                    <!--Tabs Nav Left Start-->
                <div class="grid span12 grid_table">
                  <div class="grid-title"> 
                  </div>                    
                   <ul id="myTab" class="tabs-nav">
                      <li class="active"><a href="#diario_perfil" data-toggle="tab">Diário</a></li>
                      <li><a href="#mensal_perfil" data-toggle="tab">Mensal</a></li>
                    </ul>
                    <div class="clearfix"></div> 
                  <div class="grid-content">
                   <div id="myTabContent" class="tab-content">
                      <div class="tab-pane fade active in" id="diario_perfil">
                      		<div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12">
                      <div class="grid-content overflow">
                        
                        <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Data</th>
                            <th>Número de Perfis</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	// ------------------------------------------------------
							// Created Profiles  - Last 90 days
							// ------------------------------------------------------
							$result = mysql_query($sql_num_profiles_day, $conn);
							if (mysql_num_rows($result) == 0)
									{
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else
									{
											while ($row = mysql_fetch_assoc($result))
											{
													echo "<TR>\n";
													echo "<TD>" .date('Y-m-d',strtotime($row['ProfileCreationDate'])) ." </TD>\n";
													echo "<TD><span class='s_green'>" . number_format($row['NumProfiles'],0, ".",","). "</span></TD>\n";
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
                      <div class="tab-pane fade" id="mensal_perfil">
                     <div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12">
                      <div class="grid-content overflow">
                        
                        <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Mês</th>
                            <th>Número de Perfis</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	// ------------------------------------------------------
							// Profiles per month - Last 24 months
							// ------------------------------------------------------
							$result = mysql_query($sql_num_profiles_month, $conn);
							if (mysql_num_rows($result) == 0)
									{
											echo " <br>\n";
											echo " Oipps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
											while ($row = mysql_fetch_assoc($result))
											{
													echo "<tr>\n";
													echo "<TD>" .date('Y-m',strtotime($row['ProfileCreationDate'])) ." </TD>\n";
													echo "<TD><span class='s_green'>" . number_format($row['NumProfiles'],0,".",","). "</span></TD>\n";
													echo "</tr>\n";
							
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
              
              
              
              <?php include "../includes/footer.php"; ?>
              
              
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->
  </body>
</html>

