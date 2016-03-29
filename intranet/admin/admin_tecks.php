
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
                <h1>Atualização de Informações do Teck</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             
             
             
             
             
             <div class="row-fluid">
                <!--Unordered Lists Start-->
                <div class="grid span6">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Atualização de Informações de Idioma</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                            <FORM method="POST" action="process_teck_update.php">
                            <TABLE border=1>
                                <TR>
                                    <TD>Teck ID: <input type="text" name="teck_id"></TD>
                                </TR>
                                <TR>
                                    <TD>Language: 
                                    <select name="teck_lang_code">
                                        <option value="en">Select One	</option>
                                        <option value="ar">Arabic 	</option>
                                        <option value="en">English 	</option>
                                        <option value="fr">French 	</option>
                                        <option value="de">German 	</option>
                                        <option value="he">Hebrew 	</option>
                                        <option value="hi">Hindi 	</option>
                                        <option value="it">Italian 	</option>
                                        <option value="jp">Japonese	</option>
                                        <option value="ko">Korean 	</option>
                                        <option value="zh">Mandarin	</option>
                                        <option value="pt">Portuguese 	</option>
                                        <option value="ru">Russian 	</option>
                                        <option value="es">Spanish 	</option>
                                    </select>
                                    </TD>
                                </TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="hidden" name="change" value="lang"><input type="submit" name="action" value="submit"></TD>
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
                      <span>Atualização de Status do Teck</span> 
                      <div class="clearfix"></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>
                  <div class="grid-content">
                            <FORM method="POST" action="process_teck_update.php">
                            <TABLE border=1>
                                    <TR>
                                            <TD>Teck ID: <input type="text" name="teck_id"></TD>
                                    </TR>
                            
                                <TR>
                                <TD>
                                	Status: 
                                    <select name="teck_status">
                                        <option value="1">Select One </option>
                                        <option value="1">Published </option>
                                        <option value="2">Draft </option>
                                        <option value="3">Blocked </option>
                                    </select></TD>
                                </TR>        
                            
                                
                                <TR>
                                            <TD><input type="submit" name="action" value="cancel"> <input type="hidden" name="change" value="status"><input type="submit" name="action" value="submit"></TD>
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

