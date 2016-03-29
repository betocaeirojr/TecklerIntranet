<?php
	include "../includes/header.php";
	require "conn.php";

?>
<?php

$sql_num_total_following = "select count(i.NumFollowing) as NumTotalFollowing from (select count(F.PROFILE_ID) as NumFollowing, F.FOLLOWER_PROFILE_ID as FollowerProfileID, P.SIGNATURE as FollowerSignature from FOLLOWER F, PROFILE P where F.FOLLOWER_PROFILE_ID = P.PROFILE_ID group by F.FOLLOWER_PROFILE_ID order by count(F.PROFILE_ID) DESC) i ";
$sql_num_total_followed = "select count(i.NumFollowers) as NumTotalFollowed from (select count(F.FOLLOWER_PROFILE_ID) as NumFollowers, F.PROFILE_ID as ProfileBeingFollowedID, P.SIGNATURE SignatureBeingFollowed from FOLLOWER F, PROFILE P where F.PROFILE_ID = P.PROFILE_ID group by F.PROFILE_ID order by count(F.FOLLOWER_PROFILE_ID) DESC) i ";

$sql_num_followers_profile = "select count(F.FOLLOWER_PROFILE_ID) as NumFollowers, F.PROFILE_ID as ProfileID, P.SIGNATURE as ProfileSignature from FOLLOWER F, PROFILE P where F.PROFILE_ID = P.PROFILE_ID group by F.PROFILE_ID order by count(F.FOLLOWER_PROFILE_ID) DESC limit 100"; 
$sql_num_following_profile = "select count(F.PROFILE_ID) as NumFollowing, F.FOLLOWER_PROFILE_ID as ProfileID, P.SIGNATURE as ProfileSignature from FOLLOWER F, PROFILE P where F.FOLLOWEr_PROFILE_ID = P.PROFILE_ID group by F.FOLLOWER_PROFILE_ID order by count(F.PROFILE_ID) DESC limit 100";


$sql_rate_people_followed = "select of.NumFollowed/ou.NumUsers as PercFollowed from (select count(distinct f.profile_id) as NumFollowed from FOLLOWER f) of, (select count(distinct u.USER_ID) as NumUsers from USER u )  ou ";
$sql_rate_people_following = "select of.NumFollowers/ou.NumUsers as PercFollowing from (select count(distinct f.follower_profile_id) as NumFollowers from FOLLOWER f) of, (select count(distinct u.USER_ID) as NumUsers from USER u )  ou ";

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
                <h1>Relatórios - Seguindo e Seguidores</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             <!--quick stats box-->
             <div class="grid-transparent row-fluid quick-stats-box">
               <div class="span3">
             	    <span>
                    	<?php
							// -------------------------------------------------------------
							// Number of people following
							// -------------------------------------------------------------
							$result = mysql_query($sql_num_total_following, $conn);
							if (mysql_num_rows($result) == 0)
									{
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else
									{
											while ($row = mysql_fetch_array($result))
											{
													echo "" . number_format($row['NumTotalFollowing'],0,".","," ). "";
											}
									}
						
						?>
                    </span> Following 
               </div>
               <div class="span3 red">
                 	<span>
                    	<?php
							$result = mysql_query($sql_num_total_followed, $conn);
							if (mysql_num_rows($result) == 0)
									{
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else
									{
											while ($row = mysql_fetch_array($result))
											{
													echo "" . number_format($row['NumTotalFollowed'],0,".",",") . "";
											}
									}
						?>
                    
                    </span> Followers
               </div>
             </div>
             <div class="clearfix"></div>
             <!--quick stats box END-->


				<h3>% de Usuários Seguindo / Sendo seguido</h3>
              <!--Progress Bars Start-->
                <div class="grid grid_table">
                  <div class="grid-content">
                             <div class="formRow">
                                    <label>Seguindo - 
                                    	<?php
										// -------------------------------------------------------------
										// %% Number of people following / %% Number of People being followed
										// -------------------------------------------------------------
										$result = mysql_query($sql_rate_people_following, $conn);
										if (mysql_num_rows($result) == 0)
												{
														echo " <br>\n";
														echo " Opps.. Somethint went wrong. Contact you administrator! \n";
												} else
												{
														while ($row = mysql_fetch_array($result))
														{
																echo "" . $row['PercFollowing']*100 . "%";
														}
												}
										?>
                                    </label>
                                    <div class="formRight">
                                        <div class="progress progress-info">
                                          <div class="bar" style="width: 
                                          	<?php
										// -------------------------------------------------------------
										// %% Number of people following / %% Number of People being followed
										// -------------------------------------------------------------
										$result = mysql_query($sql_rate_people_following, $conn);
										if (mysql_num_rows($result) == 0)
												{
														echo " <br>\n";
														echo " Opps.. Somethint went wrong. Contact you administrator! \n";
												} else
												{
														while ($row = mysql_fetch_array($result))
														{
																echo "" . $row['PercFollowing']*100 . "%";
														}
												}
										?>
                                          
                                          "></div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                             </div>
                             <div class="formRow">
                                    <label>Sendo Seguidos - 
                                    	<?php
										// -------------------------------------------------------------
										// %% Number of people following / %% Number of People being followed
										// -------------------------------------------------------------
										$result = mysql_query($sql_rate_people_followed, $conn);
										if (mysql_num_rows($result) == 0)
												{
														echo " <br>\n";
														echo " Opps.. Somethint went wrong. Contact you administrator! \n";
												} else
												{
														while ($row = mysql_fetch_array($result))
														{
																echo "" . $row['PercFollowed']*100 . "%";
														}
												}
									?>
                                    
                                    
                                    :</label>
                                    <div class="formRight">
                                        <div class="progress progress-info">
                                          <div class="bar" style="width: 
                                          	<?php
										// -------------------------------------------------------------
										// %% Number of people following / %% Number of People being followed
										// -------------------------------------------------------------
										$result = mysql_query($sql_rate_people_followed, $conn);
										if (mysql_num_rows($result) == 0)
												{
														echo " <br>\n";
														echo " Opps.. Somethint went wrong. Contact you administrator! \n";
												} else
												{
														while ($row = mysql_fetch_array($result))
														{
																echo "" . $row['PercFollowed']*100 . "%";
														}
												}
									?>
                                    
                                          "></div>
                                        </div>
                                    </div>
                             </div>
                             
                    <div class="clearfix"></div>
                  </div>    
                </div>
                <!--Progress Bars END-->
              	
                
                
                
              <h3>Perfis Mais Seguidos - Top 100</h3>
              <!--Striped table-->
              <div class="grid grid_table">
              <div class="grid-content overflow">
                
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Seguidores</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  	// ------------------------------------------------------
					// Most Followed Profiles - Top 100
					// ------------------------------------------------------
					$result = mysql_query($sql_num_followers_profile, $conn);
					if (mysql_num_rows($result) == 0) 
						{
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						} else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								echo "<TR>\n";
								echo "<TD>" . $row['ProfileID'] . " </TD>\n";
								echo "<TD>" . $row['ProfileSignature'] . " </TD>\n";
								echo "<TD><span class='s_green'>" . number_format($row['NumFollowers'],0,".", "," ). "</span></TD>\n";
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
                
               
               
               
              
              
              <h3>Perfis que Mais Seguem - Top 100</h3>
                <!--Striped table-->
              <div class="grid grid_table">
              <div class="grid-content overflow">
                
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Seguindo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                  <?php
				  	// ------------------------------------------------------
					// Most Following Profiles - Top 100
					// ------------------------------------------------------
					$result = mysql_query($sql_num_following_profile, $conn);
					if (mysql_num_rows($result) == 0)
							{
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							} else
							{
									while ($row = mysql_fetch_array($result))
									{
											echo "<TR>\n";
											echo "<TD>" . $row['ProfileID'] . " </TD>\n";
											echo "<TD>" . $row['ProfileSignature'] . " </TD>\n";
											echo "<TD><span class='s_green'>" . number_format($row['NumFollowing'],0,".",",") . "</span></TD>\n";
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

