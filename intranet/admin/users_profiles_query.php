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

            <h3> Busca por Profile </h3>
            <div class="row-fluid">
            	<!-- Search By Profile Signature/Name -->
            	<div class="grid span6">
                	<div class="grid-title">
                		<div class="pull-left">
                        	<div class="icon-title"><i class="icon-align-justify"></i></div>
                        	<span>Busca por PROFILE NAME</span>
                      	</div>
                  	</div>
                  	<div class="grid-content">
                      	<FORM method="POST" action="process_profile_search.php">
                          	<TABLE border=1>
                              	<TR> <TD>Profile Name: <input class="input_search" type="text" name="profile_name"></TD></TR>
                              	<TR>
                                  <TD><input type="submit" name="submit_pn" value="cancel"> <input type="submit" name="submit_pn" value="submit"></TD>
                              	</TR>
                          	</TABLE>
                      	</FORM>
                  	</div>
                </div>

                <!-- Search By Profile ID -->
                <div class="grid span6">
                  	<div class="grid-title">
                      	<div class="pull-left">
                      		<div class="icon-title"><i class="icon-align-justify"></i></div>
                      		<span>Busca por PROFILE ID</span>
                   		</div>
                  	</div>              
                  	<div class="grid-content">
                        <FORM method="POST" action="process_profile_search.php">
                            <TABLE border=1>
                                <TR> <TD>Profile ID: <input class="input_search" type="text" name="profile_id"></TD>
                                </TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                </TR>
                            </TABLE>
                        </FORM>
                  </div>
                </div>
                <!--Unordered Lists END-->

            	<div class="clearfix"></div> 
            </div>

            <h3> Busca por Usuario </h3>
            <div class="row-fluid">
            	<!-- User ID-->
            	<div class="grid span6">
                  	<div class="grid-title">
                      	<div class="pull-left">
                      		<div class="icon-title"><i class="icon-align-justify"></i></div>
                      		<span>Busca por USER ID</span> 
                   		</div>
                  	</div>        
                  	<div class="grid-content">
                       	<FORM method="POST" action="process_user_search.php">
                            <TABLE border=1>
                                <TR> <TD>User ID: <input class="input_search" type="text" name="user_id"></TD></TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                </TR>
                            </TABLE>
                      	</FORM>
                  </div>
                </div>

                <!-- User Email -->
            	<div class="grid span6">
                  	<div class="grid-title">
                      	<div class="pull-left">
                      		<div class="icon-title"><i class="icon-align-justify"></i></div>
                      		<span>Busca por USER Email</span> 
                   		</div>
                  	</div>        
                  	<div class="grid-content">
                       	<FORM method="POST" action="process_user_search.php">
                            <TABLE border=1>
                                <TR> <TD>User Email: <input class="input_search" type="text" name="user_email"></TD></TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                </TR>
                            </TABLE>
                      	</FORM>
                  </div>
                </div>            	
            	
            	<div class="clearfix"></div> 
            </div>

			<h3> Busca por Teck </h3>
            <div class="row-fluid">
            	<div class="grid span6">
                  	<div class="grid-title">
                      	<div class="pull-left">
                      		<div class="icon-title"><i class="icon-align-justify"></i></div>
                      		<span>Busca por TECK ID</span> 
                   		</div>
                  		<div class="clearfix"></div>   
                  	</div>
                  	<div class="grid-content">
                       	<FORM method="POST" action="process_teck_search.php">
                            <TABLE border=1>
                                <TR><TD>Teck ID: <input class="input_search" type="text" name="teck_id"></TD></TR>
                                <TR>
                                    <TD><input type="submit" name="action" value="cancel"> <input type="submit" name="action" value="submit"></TD>
                                </TR>
                            </TABLE>
                		</FORM>
                  </div>
                </div>
             	<div class="clearfix"></div> 
            </div>

            <div class="clearfix"></div> 
    	</div> <!-- end block-->
	</div> <!-- end main-->

<?php include "../includes/java_scripts.php"; ?>
<?php include "../includes/footer.php";?>
