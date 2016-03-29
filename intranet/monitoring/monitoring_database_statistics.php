
<?php
	include "../includes/header.php";
?>



    <div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "../includes/main_menu.php"; ?>
          
          <?php include "../includes/submenu_monitoring.php"; ?>
          
          
          <div class="clearfix"></div>
          
          
        </div>
        <!--SIDEBAR END-->
    
    	
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
          <div class="block">
   		  <div class="clearfix"></div>
            
             <!--page title-->
             <div class="pagetitle">
                <h1>Database Statistics</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             	<!--General Start-->
                <div class="grid">
                	<iframe src="http://54.236.232.200:5555" frameborder="0" width="100%" scrolling="no" height="700px"></iframe>
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

