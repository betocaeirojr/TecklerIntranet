<?php
//session_start();
//print_r($_SESSION);

	include "includes/header.php";
	require "metrics/conn.php";


?>

    




    <div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "includes/main_menu.php"; ?>
          
          <?php include "includes/submenu_geral.php"; ?>
          
          
          <div class="clearfix"></div>
          
          
        </div>
        <!--SIDEBAR END-->
    
    	
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
          <div class="block">
   		  <div class="clearfix"></div>
            
             <!--page title-->
             <div class="pagetitle">
                <h1>Dashboard</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             <!--General Start-->
                <div class="grid">
                  <div class="grid-title">
                      <div class="pull-left">
                      <div class="icon-title"><i class="icon-align-justify"></i></div>
                      <span>Bem-vindo</span> 
                      <div class="clearfix"></div>
                   </div>
                   <div class="pull-right"> 
                      <div class="icon-title"><a href="#"><i class="icon-refresh"></i></a></div>
                      <div class="icon-title"><a href="#"><i class="icon-trash"></i></a></div>
                   </div>
                  <div class="clearfix"></div>   
                  </div>

              
                  <div class="grid-content">
                     
                   <div class="row-fluid">
                      <div class="span12">
                      <span class="font-24">Extranet administrativa Teckler</span><br><br>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.
                      <br><br>
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.</p>
                      <br>
                      </div>
                   </div>
                     
                     
                   <div class="clearfix"></div>
                  </div>
                </div>
                <!--General END-->
             
             
             
             
             
             <!--quick stats box-->
             <!--
             <div class="grid-transparent row-fluid quick-stats-box">
               <div class="span3">
             	    <span>21</span> Shares
               </div>
               <div class="span3 red">
                 	<span>11</span> Usuários
               </div>
               <div class="span3 orange">
              		<span>28</span> Perfis Criados
               </div>
               <div class="span3 green">
               		<span>51</span> Tecks Criados
               </div>  
             </div>
             <div class="clearfix"></div>
             -->
             <!--quick stats box END-->
             
             
               
               <?php include "includes/footer.php"; ?>
              
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->
    <?php include "includes/java_scripts.php"; ?>
	<?php print_r($_SESSION); ?>


  </body>
</html>

