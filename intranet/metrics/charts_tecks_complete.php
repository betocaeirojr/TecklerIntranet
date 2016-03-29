<?php
	include "../includes/header.php";
	require "conn.php";

  date_default_timezone_set('America/Sao_Paulo');

?>
<?php

$sql_num_tecks_month = "select count(POST_ID) as NumTecks, CREATION_DATE as CreationDate from POST group by month(CREATION_DATE) order by CREATION_DATE DESC limit 24"; 

$sql_num_tecks_day = "select count(POST_ID) as NumTecks, CREATION_DATE as CreationDate from POST group by date(CREATION_DATE) order by CREATION_DATE DESC limit 90";

$sql_num_tecks_lang = "select count(POST_ID) as NumTecks, LANGUAGE_CODE as Language from POST group by LANGUAGE_CODE order by LANGUAGE_CODE ASC" ;

$sql_num_tecks_type = "select count(POST_ID) as NumTecks, TYPE as TeckType from POST group by TYPE order by count(POST_ID) DESC";

$sql_avg_tecks_user = "select a.Tecks/b.User as AvgTecksPerUser from (select count(POST_ID) as Tecks from POST) a, (select count(USER_ID) as User from USER) b;"; 

$sql_num_tecks_user = "select count(p.POST_ID) as NumTecks, p.USER_ID as UserID from POST p group by USER_ID order by count(p.POST_ID) DESC limit 100";

$sql_top_tags_tecks = "select count(tp.POST_ID) as NumTecks, tp.TAG_ID as TagID, t.TAG as TagName 
			from TAG_POST tp, TAG t where t.tag_id = tp.tag_id group by tp.TAG_ID order by count(tp.POST_ID) DESC limit 100";

$sql_num_tecks_lang_type = "select count(POST_ID) as NumTecks, LANGUAGE_CODE, TYPE as Language from POST group by LANGUAGE_CODE, TYPE order by LANGUAGE_CODE ASC, TYPE ASC" ;

$sql_total_num_tecks = "select count(POST_ID) as NumTotalTecks from POST";

$sql_total_active_tecks = "select count(POST_ID) as NumTotalActiveTecks from POST where STATUS_ID=1";

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
                <h1>Relatórios - Tecks</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             <div class="row-fluid clearfix">
                 <!--Informatin BOX 3-->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Média de Tecks por Usuário</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Avg Tecks per User
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_avg_tecks_user, $conn);
                                    if (mysql_num_rows($result) == 0) 
                                        {
                                            echo " <br>\n";
                                            echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                        } else 
                                        {
                                            while ($row = mysql_fetch_array($result)) 
                                            {
                                                echo "" . round($row['AvgTecksPerUser'],2). "\n";
                                            }
                                        }
                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 3 END-->
                  
                  
    
                <!--Informatin BOX 4-->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Numero Total de Tecks</div>
                            <div class="box-title">
                                    <?php
                                                                    // ------------------------------------------------------
                                                                    // Num Total Tecks
                                                                    // ------------------------------------------------------
                                                                    $result = mysql_query($sql_total_num_tecks, $conn);
                                                                    if (mysql_num_rows($result) == 0)
                                                                            {
                                                                                    echo " <br>\n";
                                                                                    echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                                                            } else
                                                                            {
                                                                                    while ($row = mysql_fetch_array($result))
                                                                                    {
                                                                                            echo "" . $row['NumTotalTecks']. "\n";
                                                $TotalNumTecks = $row['NumTotalTecks'];
                                                                                    }
                                                                            }
                                                            ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 4 END-->
    
               <!--Informatin BOX 5 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Numero Total de Tecks Ativos</div>
                            <div class="box-title">
                                    <?php
                                                                    // ------------------------------------------------------
                                                                    // Num Total Tecks
                                                                    // ------------------------------------------------------
                                                                    $result = mysql_query($sql_total_active_tecks, $conn);
                                                                    if (mysql_num_rows($result) == 0)
                                                                            {
                                                                                    echo " <br>\n";
                                                                                    echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                                                            } else
                                                                            {
                                                                                    while ($row = mysql_fetch_array($result))
                                                                                    {
                                                                                            echo "" . $row['NumTotalActiveTecks']. "\n";
                                                $ActiveTecks = $row['NumTotalActiveTecks'];
                                                echo "( " . round($ActiveTecks/$TotalNumTecks, 4)*100 . "% )\n";
                                                                                    }
                                                                            }
                                                            ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 4 END-->

				</div>



              
             <h3>Tecks por tipo</h3>
             <!--quick stats box-->
             <div class="grid-transparent row-fluid quick-stats-box">
                <?php
					// ------------------------------------------------------
					// Tecks per Type
					// ------------------------------------------------------
					$result = mysql_query($sql_num_tecks_type, $conn);
					if (mysql_num_rows($result) == 0) 
						{
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						} else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								echo "<div class='span3'>\n";
								echo "<span class='num_tecks'>\n";
								echo "" . $row['NumTecks']. "\n";
								echo "( " . round($row['NumTecks'] / $TotalNumTecks, 4 )*100 . "% )\n";
								echo "</span>\n";
								echo "<span class='" . $row['TeckType']. "'</span>\n";
								echo "</div>\n";
							}
						}
				?>  
             </div>
             <div class="clearfix"></div>
             <!--quick stats box END-->


			
              <!-- Registered Users - Breakdown : LANGUAGE  -->
              <h3>Tecks por Idioma</h3>
              <div class="grid grid_table">
              <!--
              <div class="grid-title">
               <div class="pull-left">
                  <div class="icon-title"><i class="icon-eye-open"></i></div>
                  <span>Tecks por Idioma</span> 
                  <div class="clearfix"></div>
               </div>
               <div class="pull-right"> 
               	  <div class="icon-title"><a href="#"><i class="icon-refresh"></i></a></div>
                  <div class="icon-title"><a href="#"><i class="icon-cog"></i></a></div>
               </div>
              <div class="clearfix"></div>   
              </div>
              -->
              <div class="grid-content overflow">
                
                <table class="table table-bordered table-mod-2">
                <thead>
                  <tr>
                    <th>Idioma</th>
                    <th>Número de Tecks</th>
                  </tr>
                </thead>
                <tbody>
                <?php
					// ------------------------------------------------------
					// Tecks per Language
					// ------------------------------------------------------
					$result = mysql_query($sql_num_tecks_lang, $conn);
					if (mysql_num_rows($result) == 0) 
						{
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						} else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								echo "<TR>\n"; 
								echo "<TD class='t_b_blue'>" . $row['Language']. "</TD>\n";
								echo "<TD class='action-table'>" . $row['NumTecks']. "</TD>\n";
								echo "</TR>\n";
							}
						}
				?>
                </tbody>
              </table>  
              <div class="clearfix"></div>
              </div>
              </div>
              <!-- Registered Users - Breakdown : LANGUAGE  -->
              
              
              
            
              
              <h3>Tecks Publicados</h3>
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
                      <div class="grid span12 grid_table">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Data</th>
                            <th>Número de Tecks</th>
                            <th>||</th>
			                      <th> Text </th>
                            <th> Image </th>
                            <th> Audio </th>
                            <th> Video </th>
                            <th>||</th>
                            <th> Ar </th>
                            <th> De </th>
                            <th> En </th>
                            <th> Es </th>                                                      
                            <th> Fr </th>
                            <th> He </th>
                            <th> Hi </th>
			                      <th> It </th>
                            <th> Jp </th>
                            <th> Ko </th>
                            <th> Pt </th>
                            <th> Ru </th>
                            <th> Zh </th>
                        </thead>
                        <tbody>
                          <?php
                          		// ------------------------------------------------------
								// Tecks per Day - Last 90 months
								// ------------------------------------------------------
								

                $result = mysql_query($sql_num_tecks_day, $conn);
								if (mysql_num_rows($result) == 0) 
									{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
										while ($row = mysql_fetch_assoc($result)) 
										{
											echo "<TR>\n";
											echo "<TD>" .date('Y-m-d',strtotime($row['CreationDate'])) ." </TD>\n";
											echo "<TD><span class='s_green'>" . $row['NumTecks']. "</span></TD>\n";
			                
                        // Get Breakdown per Teck Type
                        
                        echo "<TD>||</TD>\n"; 
                        $tecks_type_i = "0";
                        $tecks_type_t = "0";
                        $tecks_type_v = "0";
                        $tecks_type_a = "0";

                        $sql_tecks_per_type_given_day = 
                        "select count(POST_ID) as Num, TYPE as Type from POST where date(CREATION_DATE) = '" . 
                        date('Y-m-d',strtotime($row['CreationDate'])) . "' group by TYPE order by TYPE ASC"; 
                        $result_type = mysql_query($sql_tecks_per_type_given_day, $conn);
                        while ($row_type = mysql_fetch_assoc($result_type)) {
                          switch ($row_type['Type']) {
                             case 't':
                               $tecks_type_t = $row_type['Num'];
                               break;
                             case 'i':
                               $tecks_type_i = $row_type['Num'];
                               break;
                             case 'a':
                               $tecks_type_a = $row_type['Num'];
                               break;
                              case 'v':
                               $tecks_type_v = $row_type['Num'];
                               break;
                             default:
                               //$tecks_type_t = $row_type['Num'];
                               break;
                           } //End Switch 

                        } // End While

                        echo "<TD><span class='s_green'>" . $tecks_type_t . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_type_i . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_type_a . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_type_v . "</span></TD>\n";

                       echo "<TD>||</TD>\n";
                         
  		                  // Get Breakdown por Lang

                        $tecks_lang_ar = "0";
                        $tecks_lang_de = "0";
                        $tecks_lang_en = "0";
                        $tecks_lang_es = "0";
                        $tecks_lang_fr = "0";
                        $tecks_lang_he = "0";
                        $tecks_lang_hi = "0";
                        $tecks_lang_it = "0";
                        $tecks_lang_jp = "0";
                        $tecks_lang_ko = "0";
                        $tecks_lang_pt = "0";
                        $tecks_lang_ru = "0";
                        $tecks_lang_zh = "0";


                        $sql_tecks_per_lang_given_day = 
                        "select count(POST_ID) as Num, LANGUAGE_CODE as Lang from POST where date(CREATION_DATE) = '" . 
                        date('Y-m-d',strtotime($row['CreationDate'])) . "' group by LANGUAGE_CODE order by LANGUAGE_CODE ASC"; 
                        $result_lang = mysql_query($sql_tecks_per_lang_given_day, $conn);
                        while ($row_lang = mysql_fetch_assoc($result_lang)) {
                          switch ($row_lang['Lang']) {
                             case 'ar':
                               $tecks_lang_ar = $row_lang['Num'];
                               break;
                             case 'de':
                               $tecks_lang_de = $row_lang['Num'];
                               break;
                             case 'en':
                               $tecks_lang_en = $row_lang['Num'];
                               break;
                             case 'es':
                               $tecks_lang_es = $row_lang['Num'];
                               break;
                             case 'fr':
                               $tecks_lang_fr = $row_lang['Num'];
                               break;
                             case 'he':
                               $tecks_lang_he = $row_lang['Num'];
                               break;
                             case 'hi':
                               $tecks_lang_hi = $row_lang['Num'];
                               break;
                             case 'it':
                               $tecks_lang_it = $row_lang['Num'];
                               break;
                             case 'jp':
                               $tecks_lang_jp = $row_lang['Num'];
                               break;
                             case 'ko':
                               $tecks_lang_ko = $row_lang['Num'];
                               break;
                             case 'pt':
                               $tecks_lang_pt = $row_lang['Num'];
                               break;
                             case 'ru':
                               $tecks_lang_ru = $row_lang['Num'];
                               break;
                             case 'zh':
                               $tecks_lang_zh = $row_lang['Num'];
                               break;

                             default:
                               //$tecks_lang_es = $row_lang['Num'];
                               break;

                           } //End Switch 

                        } // End While                      

                        echo "<TD><span class='s_green'>" . $tecks_lang_ar . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_de . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_en . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_es . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_fr . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_he . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_hi . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_it . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_jp . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_ko . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_pt . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_ru . "</span></TD>\n";
                        echo "<TD><span class='s_green'>" . $tecks_lang_zh . "</span></TD>\n";
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
                      <div class="grid span12 grid_table">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Mês</th>
                            <th>Número de Tecks</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	// ------------------------------------------------------
							// Tecks per month - Last 24 months
							// ------------------------------------------------------
							$result = mysql_query($sql_num_tecks_month, $conn);
							if (mysql_num_rows($result) == 0) 
								{
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else 
								{
									while ($row = mysql_fetch_assoc($result)) 
									{
										echo "<tr>\n";
										echo "<TD>" .date('Y-m',strtotime($row['CreationDate'])) ." </TD>\n";
										echo "<TD><span class='s_green'>" . $row['NumTecks']. "</span></TD>\n";
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
              
              
              
              
              
              <!--Striped table-->
              <h3>Tecks Por Usuário - Top 100</h3>
              <div class="grid grid_table_2">
              <!--
              <div class="grid-title">
               <div class="pull-left">
                  <div class="icon-title"><i class="icon-eye-open"></i></div>
                  <span>Perfis Mais Seguidos - Top 100</span> 
                  <div class="clearfix"></div>
               </div>
               <div class="pull-right"> 
               	  <div class="icon-title"><a href="#"><i class="icon-refresh"></i></a></div>
                  <div class="icon-title"><a href="#"><i class="icon-cog"></i></a></div>
               </div>
              <div class="clearfix"></div>   
              </div>
            	-->
              <div class="grid-content overflow">
                
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Usuários</th>
                    <th>Tecks</th>
                  </tr>
                </thead>
                <tbody class="overflow_table">
                  <?php
				  		// ------------------------------------------------------
						// Tecks per User - Top 100
						// ------------------------------------------------------
						$result = mysql_query($sql_num_tecks_user, $conn);
						if (mysql_num_rows($result) == 0) 
							{
								echo " <br>\n";
								echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							} else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									
									$sql_username_from_tecks = "select user_name as Username from USER where user_id = " . $row['UserID'];
									$result_name =  mysql_query($sql_username_from_tecks, $conn);
									while ($row_name = mysql_fetch_array($result_name)){
									  $username = $row_name['Username'];
									};
									$userid=$row['UserID'];
									echo "<TR>\n";
									echo "<TD><A HREF=\"detailed_user_info.php?userid=$userid\"> " . $row['UserID']   . "</a> </TD>\n";
									echo "<TD> " . $username . "</TD>\n";
									echo "<TD><span class='s_green'>" . $row['NumTecks'] . "</span></TD>\n";
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
              <h3>Most Used Tags - Top 100</h3>
              <div class="grid grid_table_2">
              <!--
              <div class="grid-title">
               <div class="pull-left">
                  <div class="icon-title"><i class="icon-eye-open"></i></div>
                  <span>Most Used Tags - Top 100</span>
                  <div class="clearfix"></div>
               </div>
               <div class="pull-right">
                  <div class="icon-title"><a href="#"><i class="icon-refresh"></i></a></div>
                  <div class="icon-title"><a href="#"><i class="icon-cog"></i></a></div>
               </div>
              <div class="clearfix"></div>
              </div>
                -->
              <div class="grid-content overflow">

                <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Tag </th>
                    <th>Num of Tecks</th>
                  </tr>
                </thead>
                <tbody class="overflow_table">
<?php
                                                // ------------------------------------------------------
                                                // Most Used Tags  - Top 100
                                                // ------------------------------------------------------
                                                $result = mysql_query($sql_top_tags_tecks, $conn);
                                                if (mysql_num_rows($result) == 0)
                                                        {
                                                                echo " <br>\n";
                                                                echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                                        } else
                                                        {
                                                                while ($row = mysql_fetch_array($result))
                                                                {

                                                                        echo "<TR>\n";
                                                                        echo "<TD>" . $row['TagName']   . "</TD>\n";
                                                                        echo "<TD><span class='s_green'>" . $row['NumTecks'] . "</span></TD>\n";
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

