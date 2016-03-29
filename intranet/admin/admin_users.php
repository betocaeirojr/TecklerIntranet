
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
                <h1>Atualização de Informações do Usuário</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             
             
             
             
             
             
             <div class="row-fluid">
                <!--Unordered Lists Start-->
                <div class="grid span6">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Configuração de Idioma do Usuário</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                            <FORM method="POST" action="process_user_update.php">
                            <TABLE border=1>
                                <TR>
                                    <TD>User ID: <input type="text" name="user_id"></TD>
                                </TR>
                                <TR>
                                    <TD>
                                    	User Language Code: 
                                    	<SELECT name="user_lang_code">
                                            <OPTION value="en">Select One </OPTION>
                                            <OPTION value="ar">Arabic </OPTION>		
                                            <OPTION selected value="en">English </OPTION>
                                            <OPTION value="fr">French </OPTION>
                                            <OPTION value="de">German </OPTION>
                                            <OPTION value="he">Hebrew </OPTION>
                                            <OPTION value="hi">Hindi </OPTION>
                                            <OPTION value="it">Italian </OPTION>
                                            <OPTION value="jp">Japonese </OPTION>
                                            <OPTION value="ko">Korean </OPTION>
                                            <OPTION value="zh">Mandarin </OPTION>	
                                            <OPTION value="pt">Portuguese </OPTION>
                                            <OPTION value="ru">Russian </OPTION>
                                            <OPTION value="es">Spanilh </OPTION>
                                       	</SELECT>
                                    </TD>
                                </TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="hidden" name="change" value="lang"><input type="submit" name="action_lang" value="submit"></TD>
                                </TR>
                            </TABLE>
                            </FORM>
                            <P>PS: Changing the Language Code of an User, also changes ALL Tecks for ALL Profiles of this user!<BR>
                            So, use it wisely! </P>
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Unordered Lists END-->
                
                
                <!--Ordered Lists Start-->
                <div class="grid span6">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Status do Usuário</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                            <FORM method="POST" action="process_user_update.php">
                            <TABLE border=1>
                                    <TR>
                                            <TD>User ID: <input type="text" name="user_id"></TD>
                                    </TR>
                                <TR>
                                            <TD>
                                            	User Status: 
                                            	<SELECT name="user_status">
                                                    <OPTION value="a">Active </OPTION>
                                                    <OPTION value="i">Inactive </OPTION>
                                                    <OPTION value="f">Fraud </OPTION>
                                                </SELECT>
                                            </TD>
                                    </TR>
                                    <TR>
                                            <TD><input type="submit" name="action" value="cancel"> <input type="hidden" name="change" status="status"><input type="submit" name="action" value="submit"></TD>
                                    </TR>
                            </TABLE>
                            </FORM>
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--Ordered Lists END-->
        
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

