
<?php
	include "../includes/header.php";
	require "conn_upd.php";
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
                <h1>Busca por informações relacionadas a Perfil e Usuário</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             <div class="row-fluid">
                <!--Unordered Lists Start-->
                <div class="grid span3">
                  <div class="grid-title">
                      <div class="pull-left">
                        <div class="icon-title"><i class="icon-align-justify"></i></div>
                        <span>Busca por PROFILE NAME</span>
                        <div class="clearfix"></div>
                      </div>
                      <div class="clearfix"></div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="grid-content">
                      <FORM method="POST" action="process_profile_search.php">
                          <TABLE border=1>
                              <TR>
                                  <TD>Profile Name: <input class="input_search" type="text" name="profile_name"></TD>
                                </TR>
                              <TR>
                                  <TD><input type="submit" name="submit_pn" value="cancel"> <input type="submit" name="submit_pn" value="submit"></TD>
                              </TR>
                          </TABLE>
                      </FORM>
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Unordered Lists END-->
                
                
                <!--Unordered Lists Start-->
                <div class="grid span3">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Busca por PROFILE ID</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>              
                  <div class="grid-content">
                            <FORM method="POST" action="process_profile_search.php">
                            <TABLE border=1>
                                <TR>
                                    <TD>Profile ID: <input class="input_search" type="text" name="profile_id"></TD>
                                </TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                </TR>
                            </TABLE>
                            </FORM>
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Unordered Lists END-->
                
                
                <!--Ordered Lists Start-->
                <div class="grid span3">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Busca por USER ID</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>        
                  <div class="grid-content">
                       <FORM method="POST" action="process_user_search.php">
                            <TABLE border=1>
                                    <TR>
                                            <TD>User ID: <input class="input_search" type="text" name="user_id"></TD>
                                    </TR>
                                    <TR>
                                            <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                    </TR>
                            </TABLE>
                      	</FORM>

                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Ordered Lists END-->     
                
                <!--Ordered Lists Start-->
                <div class="grid span3">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Busca por TECK ID</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                       <FORM method="POST" action="process_teck_search.php">
                            <TABLE border=1>
                                    <TR>
                                            <TD>Teck ID: <input class="input_search" type="text" name="teck_id"></TD>
                                    </TR>
                                    <TR>
                                            <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                    </TR>
                            </TABLE>
                		</FORM>

                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Ordered Lists END-->
                  
                <div class="clearfix"></div>    
               
               <?php include "../includes/footer.php"; ?>
              
              <div class="clearfix"></div> 
            </div>
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->


    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>

