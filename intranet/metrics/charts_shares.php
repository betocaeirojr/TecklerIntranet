<?php
	include "../includes/header.php";
	require "conn.php";

?>
<?php

$sql_avg_shares_per_teck = "select sum(c.NumShares)/count(c.PostID) AvgSharesPerTeck from (select sum(ps.FACEBOOK+ps.GOOGLE_PLUS+ps.TWITTER+ps.LINKEDIN) as NumShares, ps.POST_ID as PostID from POST_SHARE ps group by POST_ID) c";

$sql_num_shares_total    = "select sum(FACEBOOK+GOOGLE_PLUS+TWITTER+LINKEDIN) as NumShares from POST_SHARE"; 
$sql_num_shares_fb_total = "select sum(FACEBOOK) as NumSharesFB from POST_SHARE";
$sql_num_shares_gp_total = "select sum(GOOGLE_PLUS) as NumSharesGP from POST_SHARE" ;
$sql_num_shares_tw_total = "select sum(TWITTER) as NumSharesTW from POST_SHARE";
$sql_num_shares_ld_total = "select sum(LINKEDIN) as NumSharesLD from POST_SHARE"; 

// $sql_num_tecks_user = "select count(p.POST_ID) as NumTecks, p.USER_ID as UserID from POST p group by USER_ID order by count(p.POST_ID) DESC limit 100";

$sql_num_shares_fb_teck = "select sum(FACEBOOK) as NumSharesFB, POST_ID as PostID from POST_SHARE group by POST_ID order by sum(FACEBOOK) DESC limit 100";
$sql_num_shares_gp_teck = "select sum(GOOGLE_PLUS) as NumSharesGP, POST_ID as PostID from POST_SHARE group by POST_ID order by sum(GOOGLE_PLUS) DESC limit 100";
$sql_num_shares_tw_teck = "select sum(TWITTER) as NumSharesTW, POST_ID as PostID from POST_SHARE group by POST_ID order by sum(TWITTER) DESC limit 100";
$sql_num_shares_ld_teck = "select sum(LINKEDIN) as NumSharesLD, POST_ID as PostID from POST_SHARE group by POST_ID order by sum(LINKEDIN) DESC limit 100";

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
                <h1>Relatórios - Shares</h1> 
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
								// Average number of shares per Teck - Consolidated
								// ------------------------------------------------------
								$result = mysql_query($sql_avg_shares_per_teck, $conn);
								if (mysql_num_rows($result) == 0) 
									{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											echo "" . round($row['AvgSharesPerTeck'],2). "\n";
										}
									}
							?>
                        </div>
                        <div class="box-title">Média de Shares por Teck</div>
                      </div>
                    </div>
                    <div class="item">
                        <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">
                        	<?php
								// ------------------------------------------------------
								// Total Numbers of Shares
								// ------------------------------------------------------
								$result = mysql_query($sql_num_shares_total, $conn);
								if (mysql_num_rows($result) == 0) 
									{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											echo "" . number_format($row['NumShares'],0,".", ","). "\n";
										}
									}
							?>
                        
                        </div>
                        <div class="box-title">Número Total de Shares</div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->
             
             
             
             
          
              
              
             <h3>Shares por Rede Social</h3>
             <!--quick stats box-->
             <div class="grid-transparent row-fluid quick-stats-box">
             	<div class='span3 facebook'>
					<span class="share_social">
						<?php
						// ------------------------------------------------------
						// Shares per Social Network
						// ------------------------------------------------------
						$result_fb = mysql_query($sql_num_shares_fb_total, $conn);
						if (mysql_num_rows($result_fb) == 0) 
							{
								echo " <br>\n";
								echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							} else 
							{
								while ($row = mysql_fetch_array($result_fb)) 
								{
									echo "" . number_format($row['NumSharesFB'],0, ".", ",") . "\n";
								}
							}

						?>
					</span>
					<span class="num_shares facebook">Facebook</span>
				</div>
                
                <div class='span3 google'>
					<span class="share_social">
						<?php
							$result_gp = mysql_query($sql_num_shares_gp_total, $conn);
							if (mysql_num_rows($result_gp) == 0)
									{
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else
									{
											while ($row = mysql_fetch_array($result_gp))
											{
													echo "<TD>" . number_format($row['NumSharesGP'],0, ".", ",")  . "</TD>\n";
											}
									}
	

						?>
					</span>
					<span class="num_shares google">Google+</span>
				</div>
                
                <div class='span3 twitter'>
					<span class="share_social">
						<?php
							$result_tw = mysql_query($sql_num_shares_tw_total, $conn);
							if (mysql_num_rows($result_tw) == 0)
									{
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else
									{
											while ($row = mysql_fetch_array($result_tw))
											{
													echo "<TD>" . number_format($row['NumSharesTW'],0,".", ",") . "</TD>\n";
											}
									} 

						?>
					</span>
					<span class="num_shares twitter">Twitter</span>
				</div>
                
                <div class='span3 linkedin'>
					<span class="share_social">
						<?php
						$result_ld = mysql_query($sql_num_shares_ld_total, $conn);
						if (mysql_num_rows($result_ld) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result_ld))
										{
												echo "<TD>" . number_format($row['NumSharesLD'],0,".",",") . "</TD>\n";
										}
								}
						?>
					</span>
					<span class="num_shares linkedin">Linkedin</span>
				</div>
             </div>
             <div class="clearfix"></div>
             <!--quick stats box END-->
             
             
             
             
             
             <script>
             	// Set the classes that TableTools uses to something suitable for Bootstrap
				$.extend( true, $.fn.DataTable.TableTools.classes, {
					"container": "btn-group",
					"buttons": {
						"normal": "btn",
						"disabled": "btn disabled"
					},
					"collection": {
						"container": "DTTT_dropdown dropdown-menu",
						"buttons": {
							"normal": "",
							"disabled": "disabled"
						}
					}
				} );
				
				// Have the collection use a bootstrap compatible dropdown
				$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
					"collection": {
						"container": "ul",
						"button": "li",
						"liner": "a"
					}
				} );
             </script>
             
             <h3>Top 100 Shares por Rede Social</h3>
             <div class="row-fluid">
             	  <!--Striped table-->
                  <div class="grid span3 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Facebook</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped" id="exemplo">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Shares</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// //////////////////////////////////////////////////////////////////
						// ------------------------------------------------------
						// Shares - TOP 100 Facebook
						// ------------------------------------------------------
						$result = mysql_query($sql_num_shares_fb_teck, $conn);
						if (mysql_num_rows($result) == 0) 
							{
								echo " <br>\n";
								echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							} else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									echo "<TR>\n";
									echo "<TD>" . $row['PostID'] ." </TD>\n";
									echo "<TD><span class='s_blue s_facebook'>" . number_format($row['NumSharesFB'], 0,".", ",") . "</span></TD>\n";
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
                  
                     <script>
						$(document).ready( function () {
							$('#exemplo').dataTable( {
								"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
								"oTableTools": {
									"aButtons": [
										"copy",
										"print",
										{
											"sExtends":    "collection",
											"sButtonText": 'Save <span class="caret" />',
											"aButtons":    [ "csv", "xls", "pdf" ]
										}
									]
								}
							} );
						} );

					  </script>
					  
					  
                  
                  
                  
                  
                  <!--Striped table-->
                  <div class="grid span3 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Google+</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Shares</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Shares - TOP 100 GooglePlus
						// ------------------------------------------------------
						$result = mysql_query($sql_num_shares_gp_teck, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
												echo "<TR>\n";
												echo "<TD>" . $row['PostID'] ." </TD>\n";
												echo "<TD><span class='s_blue s_google'>" . number_format($row['NumSharesGP'],0, ".", ",") . "</span></TD>\n";
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
                  <div class="grid span3 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Twitter</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Shares</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Shares - TOP 100 Twitter
						// ------------------------------------------------------
						$result = mysql_query($sql_num_shares_tw_teck, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
												echo "<TR>\n";
												echo "<TD>" . $row['PostID'] ." </TD>\n";
												echo "<TD><span class='s_blue s_twitter'>" . number_format($row['NumSharesTW'],0, ".",",") . "</span></TD>\n";
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
                  <div class="grid span3 grid_table">
                  
                  <div class="grid-title">
                   <div class="pull-left">
                      <div class="icon-title"><i class="icon-eye-open"></i></div>
                      <span>Linkedin</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                
                  <div class="grid-content overflow">
                    
                    <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID do Teck</th>
                        <th>Shares</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	// ------------------------------------------------------
						// Shares - TOP 100 LinkedIn
						// ------------------------------------------------------
						$result = mysql_query($sql_num_shares_ld_teck, $conn);
						if (mysql_num_rows($result) == 0)
								{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								} else
								{
										while ($row = mysql_fetch_array($result))
										{
												echo "<TR>\n";
												echo "<TD>" . $row['PostID'] ." </TD>\n";
												echo "<TD><span class='s_blue s_linkedin'>" . number_format($row['NumSharesLD'],0,".","," ). "</span></TD>\n";
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

