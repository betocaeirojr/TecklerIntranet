<?php
	include "../includes/header.php";
	require "conn.php";
?>
   
   
<?php
$sql_num_total_users 		= "select count(USER_ID) as NumTotalUsers from USER";
$sql_num_total_profiles 	= "select count(PROFILE_ID) as NumTotalProfiles from PROFILE;";
$sql_num_total_tecks 		= "select count(POST_ID) as NumTotalTecks from POST";
$sql_num_total_shares 		= "select sum(FACEBOOK+GOOGLE_PLUS+TWITTER+LINKEDIN) as NumTotalShares from POST_SHARE";
$sql_num_total_pageviews 	= "select sum(PAGE_VIEWS) as NumTotalPageviews from POST";



$sql_num_users_month = "select count(USER_ID) as NumUsers, USER_CREATION_DATE as CreationMonth from USER group by month(USER_CREATION_DATE) order by USER_CREATION_DATE DESC limit 24"; 

$sql_num_users_day = "select count(USER_ID) as NumUsers, USER_CREATION_DATE as CreationDay from USER group by date(USER_CREATION_DATE) order by USER_CREATION_DATE ASC limit 90";

$sql_num_users_lang = "select count(VALUE) as NumUsers, VALUE as Language from USER_CONFIGURATION where CODE='LANG' group by VALUE" ;

$sql_num_profiles_month = "select count(PROFILE_ID) as NumProfiles, PROFILE_CREATION_DATE as ProfileCreationDate from PROFILE group by month(PROFILE_CREATION_DATE) order by PROFILE_CREATION_DATE DESC";

$sql_num_profiles_day = "select count(PROFILE_ID) as NumProfiles, PROFILE_CREATION_DATE as ProfileCreationDate from PROFILE group by date(PROFILE_CREATION_DATE) order by PROFILE_CREATION_DATE ASC";

$sql_avg_profiles_user = "select p.NumProfiles/u.NumUsers as AvgProfilesPerUser from (select count(inp.PROFILE_ID) as NumProfiles from PROFILE inp) p, (select count(inu.USER_ID) as NumUsers from USER inu) u";

$sql_num_tecks_month = "select count(POST_ID) as NumTecks, CREATION_DATE as CreationDate from POST group by month(CREATION_DATE) order by CREATION_DATE DESC limit 24"; 


$sql_num_tecks_day = "select count(POST_ID) as NumTecks, CREATION_DATE as CreationDate from POST group by date(CREATION_DATE) order by CREATION_DATE ASC limit 90";

$sql_num_tecks_lang = "select count(POST_ID) as NumTecks, LANGUAGE_CODE as Language from POST group by LANGUAGE_CODE order by LANGUAGE_CODE ASC" ;

$sql_num_tecks_type = "select count(POST_ID) as NumTecks, TYPE as TeckType from POST group by TYPE order by count(POST_ID) DESC";

$sql_avg_tecks_user = "select sum(c.NumPost)/count(c.User) as AvgTecksPerUser from (select count(p.POST_ID) as NumPost, p.USER_ID as User from POST p group by USER_ID) c"; 

$sql_num_tecks_user = "select count(p.POST_ID) as NumTecks, p.USER_ID as UserID from POST p group by USER_ID order by count(p.POST_ID) DESC limit 100";                  
                        
                        
                    
// Starting processing
// Total Users
$result = mysql_query($sql_num_total_users, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_users = $row['NumTotalUsers'];
		}
	}

// Total Profiles
$result = mysql_query($sql_num_total_profiles, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_profiles = $row['NumTotalProfiles'];
		}
	}


// Total Tecks
$result = mysql_query($sql_num_total_tecks, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_tecks = $row['NumTotalTecks'];
		}
	}

// Total Shares
$result = mysql_query($sql_num_total_shares, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_shares = $row['NumTotalShares'];
		}
	}

// Total PageViews
$result = mysql_query($sql_num_total_pageviews, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$res_num_total_pageviews = $row['NumTotalPageviews'];
		}
	}



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
                <h1>Relatórios</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             
             <div class="row-fluid">
             <!--visitor statistics-->
              <div class="grid span12">
              
              <div class="grid-title">
               <div class="pull-left">
                  <div class="icon-title"><i class="icon-file"></i></div>
                  <span>Métricas Consolidadas</span> 
                  <div class="clearfix"></div>
               </div>
               <div class="pull-right"> 
               	  <div class="icon-title"><a href="#"><i class="icon-refresh"></i></a></div>
                  <div class="icon-title"><a href="#"><i class="icon-cog"></i></a></div>
               </div>
              <div class="clearfix"></div>   
              </div>
            
              <div class="grid-content overflow">
                 <ul class="list-visitor">
	                            <li><span class="linecustom">
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
													while ($row = mysql_fetch_assoc($result)) 
													{
														echo "" . $row['NumUsers']. ", \n";
											
													}
												}
											?>
                                
                                	</span> 
	                                Número de Usuários Registrados: 
	                                <span class="number"><?php echo $res_num_total_users;?></span>
	                            </li>
	                            <li><span class="linecustom">
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
																	echo "" . $row['NumProfiles']. ", \n";
											
															}
													}
										  ?>
                                	</span> 
	                                Número de Perfis Criados: 
	                                <span class="number"><?php echo $res_num_total_profiles;?></span>
	                            </li>
	                            <li><span class="linecustom">
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
														echo "" . $row['NumTecks']. ", \n";
											
													}
												}
										?>
                                	</span> 
	                                Número de Tecks Publicados:
	                                <span class="number"><?php echo $res_num_total_tecks;?></span>
	                            </li>
	                            <li><span class="linecustom"></span> 
	                                Número de Shares: <span class="number"><?php echo $res_num_total_shares;?></span>
	                            </li>
	                            <li><span class="linecustom"></span> 
	                                Número de Page Views nos Tecks: 
	                                <span class="number"><?php echo $res_num_total_pageviews;?></span>
	                            </li>
	               </ul>
              </div>
              
              </div>
              <!--visitor statistics END-->
              
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

