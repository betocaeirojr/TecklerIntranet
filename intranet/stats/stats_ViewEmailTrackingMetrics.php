<?php
	include "../includes/header.php";

	include("MailTrackingDynamoDbStats.php");

	$EmailTrackingMetrics = new MailTrackingDynamoDbStats();
	$EMT_Details = $EmailTrackingMetrics->getEmailTrackingInfo();

  asort($EMT_Details);
	//echo "<PRE>";
	//print_r($EmailTrackingMetrics->getEmailTrackingInfo());
	//echo "</PRE>";
?>



    <div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "../includes/main_menu.php"; ?>
          
          <?php include "../includes/submenu_stats.php"; ?>
          
          
          <div class="clearfix"></div>
          
          
        </div>
        <!--SIDEBAR END-->
    
    	
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
          <div class="block">
   		  <div class="clearfix"></div>
            
             <!--page title-->
             <div class="pagetitle">
                <h1>Email Tracking Metrics</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             	
             <!--Sample Table-->
              <div class="grid">
            
              <div class="grid-content overflow">
                
                <table class="table table-bordered table-mod-2">
                <thead>
                  	<tr>
						<?php 
							$keys = array_keys($EMT_Details[0]);
							foreach ($keys as $key => $value) {
								echo "<th align=center>";
								echo $value;
								echo "</th>";
							}
							reset($EMT_Details);
						?>	
						<th> Last Update at </th>
					</tr>
                </thead>
                <tbody>
	                  <?php 
						foreach ($EMT_Details as $ekey => $evalue) {
							echo "<tr>";		
							foreach ($evalue as $ikey => $ivalue) {
								echo "<TD>";
								echo (is_numeric($ivalue) ?  number_format($ivalue,0, ".", ",") : ($ikey == 'EmailDate' ? date("Y-m-d l",strtotime($ivalue)) : $ivalue ) ); 
								echo "</TD>";
							}
							echo "<TD> " . date('Y-m-d H:i:s') . " </TD>\n";
							echo "</tr>";
						}
						?>
                </tbody>
              </table>  

                
              <div class="clearfix"></div>
              </div>
              
              </div>
              <!--Sample Table END-->
             
             
               
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


