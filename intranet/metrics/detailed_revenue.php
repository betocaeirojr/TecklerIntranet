<?php
	include "../includes/header.php";
	require "conn_rev.php";

?>
<?php

  //$conn = mysql_connect('localhost', 'root')
  //  or die('Could not connect to the database; ' . mysql_error());
  
  //mysql_select_db('PAY', $conn)
  //  or die('Could not select database; ' . mysql_error());

date_default_timezone_set("America/Sao_Paulo");

// SQL Statements
//Total Ammount Per Profile - TOP 100

$sql_total_expected_ammount_per_profile = 
                  "select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as ExpectedAmmount_USD, 
                      SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
                    from STATEMENT 
                    group by SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
                    order by sum(EXPECTED_VALUE) DESC limit 100" ; 

$sql_expected_pending_ammount_per_profile = 
                    "select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as ExpectedAmmount_USD, 
                      SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
                    from STATEMENT 
                    where STATUS='pending' 
                    group by SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
                    order by sum(EXPECTED_VALUE) DESC limit 100" ; 

$sql_ammount_tobewithdrawn_per_profile = 
                    "select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as ExpectedAmmount_USD, 
                      SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
                    from STATEMENT 
                    where STATUS='ok' 
                    group by SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
                    order by sum(EXPECTED_VALUE) DESC limit 100" ; 

$sql_ammount_withdrawn_per_profile = 
                    "select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as ExpectedAmmount_USD, 
                      SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
                    from STATEMENT 
                    where STATUS='withdrawn' 
                    group by SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
                    order by sum(EXPECTED_VALUE) DESC limit 100"  ;


// Real and Expected Revenue Per Period
// // Ever
$sql_expected_revenue_ever = 
                  "select 
                    sum(EXPECTED_VALUE)*0.000001*0.98 as TotalExpectedAmmountDue_USD 
                  from STATEMENT";

$sql_real_revenue_ever = 
                  "select 
                    sum(VALUE)*0.000001*0.98 as TotalEarnedAmmountDue_USD 
                  from STATEMENT";

// // Day
// // Expected
$sql_expected_revenue_per_day = 
                  "select 
                    (sum(EXPECTED_VALUE)*0.000001*0.98) as TotalExpectedAmmountDue_USD,
                    (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as UserExpectedAmmountDue_USD,
                    (sum(EXPECTED_VALUE)*0.000001*0.98*0.3) as TecklerExpectedAmmountDue_USD, 
                    FROM_UNIXTIME(DAY_SEQ * 86400) as DueDate 
                  from STATEMENT 
                  group by FROM_UNIXTIME(DAY_SEQ * 86400) 
                  order by FROM_UNIXTIME(DAY_SEQ * 86400) DESC 
                  limit 90";

// // // Real;
$sql_real_revenue_per_day = 
                  "select 
                    sum(VALUE)*0.000001*0.98 as TotalActualAmmountDue_USD,
                    sum(VALUE)*0.000001*0.98*0.7 as UserActualAmmountDue_USD,
                    sum(VALUE)*0.000001*0.98*0.3 as TecklerActualAmmountDue_USD, 
                    FROM_UNIXTIME(DAY_SEQ * 86400) as DueDate 
                  from STATEMENT 
                  where 
                    DATE(FROM_UNIXTIME(DAY_SEQ * 86400)) < CURDATE() - INTERVAL 30 DAY 
                  group by FROM_UNIXTIME(DAY_SEQ * 86400) 
                  order by FROM_UNIXTIME(DAY_SEQ * 86400) DESC 
                  limit 90";

// // Month
// // Expected
$sql_expected_revenue_per_month = 
                  "select 
                    (sum(EXPECTED_VALUE)*0.000001*0.98) as TotalExpectedAmmountDue_USD,
                    (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as UserExpectedAmmountDue_USD,
                    (sum(EXPECTED_VALUE)*0.000001*0.98*0.3) as TecklerExpectedAmmountDue_USD, 
                    (FROM_UNIXTIME(DAY_SEQ * 86400)) as DueDate 
                  from STATEMENT 
                  group by month(FROM_UNIXTIME(DAY_SEQ * 86400)) 
                  order by month(FROM_UNIXTIME(DAY_SEQ * 86400)) DESC
                  limit 24";

// // // Real;
$sql_real_revenue_per_month = 
                  " select 
                    sum(VALUE)*0.000001*0.98 as TotalActualAmmountDue_USD,
                    sum(VALUE)*0.000001*0.98*0.7 as UserActualAmmountDue_USD,
                    sum(VALUE)*0.000001*0.98*0.3 as TecklerActualAmmountDue_USD, 
                    (FROM_UNIXTIME(DAY_SEQ * 86400)) as DueDate 
                  from STATEMENT 
                  group by month(FROM_UNIXTIME(DAY_SEQ * 86400)) 
                  order by month(FROM_UNIXTIME(DAY_SEQ * 86400)) DESC 
                  limit 24";



// Withdrawn
// Total Ammount Ever Withdrawn
$sql_total_ammount_withdrawn = 
                    "select 
                      sum(VALUE)*0.000001*0.98*0.7 as TotalAmmount
                    from STATEMENT 
                    where status='withdrawn'"; 

$sql_total_ammount_withdrawn_per_day = 
                    "select 
                      sum(VALUE)*0.000001*0.98*0.7 as TotalAmmount, 
                      FROM_UNIXTIME(DAY_SEQ * 86400) as DueDate 
                    from STATEMENT 
                    where status='withdrawn' 
                    group by FROM_UNIXTIME(DAY_SEQ * 86400) 
                    order by FROM_UNIXTIME(DAY_SEQ * 86400) DESC 
                    limit 90"; 

$sql_total_ammount_withdrawn_per_month = 
                    "select 
                      sum(VALUE)*0.000001*0.98*0.7 as TotalAmmount, 
                      (FROM_UNIXTIME(DAY_SEQ * 86400)) as DueDate 
                    from STATEMENT 
                    where status='withdrawn' 
                    group by month(FROM_UNIXTIME(DAY_SEQ * 86400)) 
                    order by month(FROM_UNIXTIME(DAY_SEQ * 86400)) DESC
                    limit 24" ; 

// Ready to be Withdrawn
// Total Ammount Ever Withdrawn
$sql_total_ammount_tobe_withdrawn =  
                    "select 
                      sum(VALUE)*0.000001*0.98*0.7 as TotalAmmount
                    from STATEMENT 
                    where status='ok'"; 

$sql_total_ammount_tobe_withdrawn_per_day = 
                    "select 
                      sum(VALUE)*0.000001*0.98*0.7 as TotalAmmount, 
                      FROM_UNIXTIME(DAY_SEQ * 86400) as DueDate 
                    from STATEMENT 
                    where status='ok' 
                    group by FROM_UNIXTIME(DAY_SEQ * 86400) 
                    order by FROM_UNIXTIME(DAY_SEQ * 86400) DESC
                    limit 90"; 

$sql_total_ammount_tobe_withdrawn_per_month = 
                    "select 
                      sum(VALUE)*0.000001*0.98*0.7 as TotalAmmount, 
                      (FROM_UNIXTIME(DAY_SEQ * 86400)) as DueDate 
                    from STATEMENT 
                    where status='ok' 
                    group by month(FROM_UNIXTIME(DAY_SEQ * 86400)) 
                    order by month(FROM_UNIXTIME(DAY_SEQ * 86400)) DESC
                    limit 24" ; 

// Still to Be Verified
$sql_total_ammount_pending =  
                    "select 
                      sum(EXPECTED_VALUE)*0.000001*0.98*0.7 as TotalAmmount
                    from STATEMENT 
                    where status='pending'"; 

$sql_total_ammount_pending_per_day = 
                    "select 
                      sum(EXPECTED_VALUE)*0.000001*0.98*0.7 as TotalAmmount, 
                      FROM_UNIXTIME(DAY_SEQ * 86400) as DueDate 
                    from STATEMENT 
                    where status='pending' 
                    group by FROM_UNIXTIME(DAY_SEQ * 86400) 
                    order by FROM_UNIXTIME(DAY_SEQ * 86400) DESC
                    limit 90";

$sql_total_ammount_pending_per_month = 
                    "select 
                      sum(EXPECTED_VALUE)*0.000001*0.98*0.7 as TotalAmmount, 
                      (FROM_UNIXTIME(DAY_SEQ * 86400)) as DueDate 
                    from STATEMENT 
                    where status='pending' 
                    group by month(FROM_UNIXTIME(DAY_SEQ * 86400)) 
                    order by month(FROM_UNIXTIME(DAY_SEQ * 86400)) DESC
                    limit 24" ; 

// DELTA (REAL / EXPECTED)
$sql_rate_expected_real = "select avg(value/expected_value) as RevenueAssertivesRate from STATEMENT
                          ";

$sql_rate_expected_real_per_day = 
                    "select 
                      avg(value/expected_value) as RevenueAssertivenessRatio, 
                      FROM_UNIXTIME(DAY_SEQ * 86400) as DueDate 
                    from STATEMENT 
                    where 
                    DATE(FROM_UNIXTIME(DAY_SEQ * 86400)) < CURDATE() - INTERVAL 30 DAY 
                    group by (FROM_UNIXTIME(DAY_SEQ * 86400)) 
                    order by (FROM_UNIXTIME(DAY_SEQ * 86400)) DESC 
                    limit 90";

$sql_rate_expected_real_per_month = 
                    "select 
                      avg(value/expected_value) as RevenueAssertivenessRatio, 
                      (FROM_UNIXTIME(DAY_SEQ * 86400)) as DueDate 
                    from STATEMENT 
                    group by month(FROM_UNIXTIME(DAY_SEQ * 86400)) 
                    order by month(FROM_UNIXTIME(DAY_SEQ * 86400)) DESC 
                    limit 24";

$sql_rate_expected_real_per_profile_top = 
                    "select 
                      avg(value/expected_value) RevenueAssertivesRate
                      SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) as Profile 
                    from STATEMENT  
                    group by SUBSTRING(AD_UNIT_NAME FROM 1 FOR (POSITION('_' IN ltrim(AD_UNIT_NAME)))-1) 
                    order by sum(EXPECTED_VALUE) DESC limit 100" ;
             
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
                <h1>Relatórios - Revenue</h1> 
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
								$result = mysql_query($sql_expected_revenue_ever, $conn);
								if (mysql_num_rows($result) == 0) 
									{
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									} else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											echo "" . round($row['TotalExpectedAmmountDue_USD'],2). "\n";
										}
									}  
							?>
                        </div>
                        <div class="box-title">Expected Revenue (USD)</div>
                      </div>
                    </div>
                    <div class="item">
                        <div class="box-info">
                        <img src="../images/icon/stats_1.png" alt=""> 
                        <div class="box-figures">
                        	<?php
								// ------------------------------------------------------
								// Total Earned Revenue
								// ------------------------------------------------------
								$result = mysql_query($sql_real_revenue_ever, $conn);
								if (mysql_num_rows($result) == 0) 
								  {
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								  } else 
								  {
									while ($row = mysql_fetch_array($result)) 
									{
									  echo "" . round($row['TotalEarnedAmmountDue_USD'],2). "\n";
									}
								  }
							?>
                        </div>
                        <div class="box-title">Real Revenue (USD)</div>
                      </div>
                    </div>
              </div>
              <!--Informatin BOX 3 END-->
              
              
              
              
              
              
              <h3>Aggregated Ammount Due - By Status</h3>
             <!--quick stats box-->
             <div class="grid-transparent row-fluid quick-stats-box grid_table">
            	  <div class="span3 bg_red">
                  	<span>
                    	<?php
					// ------------------------------------------------------
					// Total Ammount Pending
					// ------------------------------------------------------
					$result = mysql_query($sql_total_ammount_pending, $conn);
					if (mysql_num_rows($result) == 0) 
					  {
						echo " <br>\n";
						echo " Opps.. Somethint went wrong. Contact you administrator! \n";
					  } else 
					  {
						while ($row = mysql_fetch_array($result)) 
						{
						  echo "" . round($row['TotalAmmount'],2). "\n";
						}
					  }   
				?> 
                    </span>  
					<span class="short_font">Pending</span>
                  </div>
				 
                 
                 <div class="span3 bg_orange">
                  	<span>
                    	<?php
						// ------------------------------------------------------
						// Total Ammount to Ready to Be Withdrawn
						// ------------------------------------------------------
						$result = mysql_query($sql_total_ammount_tobe_withdrawn, $conn);
						if (mysql_num_rows($result) == 0) 
						  {
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						  } else 
						  {
							while ($row = mysql_fetch_array($result)) 
							{
							  echo "<TD>" . round($row['TotalAmmount'],2). "</TD>\n";
							}
						  }
				?> 
                    </span>  
					<span class="short_font">To be Withdrawn</span>
                  </div>
                  
                  
                  <div class="span3 bg_green">
                  	<span>
                    	<?php
						// ------------------------------------------------------
						// Total Ammount Already Withdrawn
						// ------------------------------------------------------
						$result = mysql_query($sql_total_ammount_withdrawn, $conn);
						if (mysql_num_rows($result) == 0) 
						  {
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						  } else 
						  {
							while ($row = mysql_fetch_array($result)) 
							{
							  echo "<TD>" . round($row['TotalAmmount'],2) . "</TD>\n";
							}
						  }

				?> 
                    </span>  
					<span class="short_font">Already Withdrawn</span>
                  </div>
             </div>
             <div class="clearfix"></div>
             <!--quick stats box END-->






             
             
          	  
                    <div class="row-fluid">
                    	<div class="grid titles span6 grid_table">
                        	<h3>Expected Revenue</h3>
                            <h4>Yet to Be Verified by Google</h4>
                        </div>
                        <div class="grid titles span6 grid_table">
                        	<h3>Actual Revenue</h3>
                            <h4>As Verified by Google</h4>
                        </div>
                    
                    </div>
                      
                      
                      <div class="row-fluid">
                            <!--Tabs Nav Left Start-->
                        <div class="grid span6 grid_table">
                          <div class="grid-title"> 
                          </div>                    
                           <ul id="myTab" class="tabs-nav">
                              <li class="active"><a href="#home" data-toggle="tab">Diário</a></li>
                              <li><a href="#profile" data-toggle="tab">Mensal</a></li>
                            </ul>
                            <div class="clearfix"></div> 
                          <div class="grid-content scroll_table">
                           <div id="myTabContent" class="tab-content">
                              <div class="tab-pane fade active in" id="home">
                                    <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                              <div class="grid-content overflow">
                                <table class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>Date</th>
                                      <th>Total Expected (USD)</th>
                                      <th>User Share (USD) </th>
                                      <th>Teckler Share (USD) </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                        $result = mysql_query($sql_expected_revenue_per_day, $conn);
                                        if (mysql_num_rows($result) == 0) 
                                          {
                                            echo " <br>\n";
                                            echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                          } else 
                                          {
                                            while ($row = mysql_fetch_assoc($result)) 
                                            {
                                              echo "<TR>\n";
                                              echo "<TD>" . date('Y-m-d',strtotime($row['DueDate']))   ." </TD>\n";
                                              echo "<TD>" . $row['TotalExpectedAmmountDue_USD']        . "</TD>\n";
                                              echo "<TD>" . $row['UserExpectedAmmountDue_USD']         . "</TD>\n";
                                              echo "<TD>" . $row['TecklerExpectedAmmountDue_USD']      . "</TD>\n";
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
                              </div>
                              <div class="tab-pane fade" id="profile">
                             <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                              <div class="grid-content scroll_table overflow">
                                <table class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>Date</th>
                                      <th>Total Expected (USD)</th>
                                      <th>User Share(USD)</th>
                                      <th>Teckler Share(USD)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $result = mysql_query($sql_expected_revenue_per_month, $conn);
                                    if (mysql_num_rows($result) == 0) 
                                      {
                                        echo " <br>\n";
                                        echo " Opps.. Somethint went wrong. Contact you administrator! \n";
                                      } else 
                                      {
                                        while ($row = mysql_fetch_assoc($result)) 
                                        {
                                          echo "<TR>\n";
                                          echo "<TD>" . date('Y-m',strtotime($row['DueDate'])) ." </TD>\n";
                                          echo "<TD>" . $row['TotalExpectedAmmountDue_USD']      . "</TD>\n";
                                          echo "<TD>" . $row['UserExpectedAmmountDue_USD']       . "</TD>\n";
                                          echo "<TD>" . $row['TecklerExpectedAmmountDue_USD']    . "</TD>\n";
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
                              </div>
                            </div>
                          </div>
                         </div>
                         <!--Tabs Nav Left END-->
                 
                 
                 
                 
                 
                 
                 
                 
              
                    <!--Tabs Nav Left Start-->
                <div class="grid span6 grid_table">
                  <div class="grid-title"> 
                  </div>                    
                   <ul id="myTab" class="tabs-nav">
                      <li class="active"><a href="#home_dia" data-toggle="tab">Diário</a></li>
                      <li><a href="#profile_dia" data-toggle="tab">Mensal</a></li>
                    </ul>
                    <div class="clearfix"></div> 
                  <div class="grid-content scroll_table">
                   <div id="myTabContent" class="tab-content">
                      <div class="tab-pane fade active in" id="home_dia">
                      		<div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12 grid_table">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                          	  <th>Date</th>
                              <th>Total Expected (USD)</th>
                              <th>User Share (USD)</th>
                              <th>Teckler Share (USD)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  		$result = mysql_query($sql_real_revenue_per_day, $conn);
								if (mysql_num_rows($result) == 0) 
								  {
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								  } else 
								  {
									while ($row = mysql_fetch_assoc($result)) 
									{
									  echo "<TR>\n";
									  echo "<TD>" . date('Y-m-d',strtotime($row['DueDate']))   ." </TD>\n";
									  echo "<TD>" . $row['TotalActualAmmountDue_USD']        . "</TD>\n";
									  echo "<TD>" . $row['UserActualAmmountDue_USD']         . "</TD>\n";
									  echo "<TD>" . $row['TecklerActualAmmountDue_USD']      . "</TD>\n";
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
                      </div>
                      <div class="tab-pane fade" id="profile_dia">
                     <div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12 grid_table">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                              <th>Date </th>
                              <th>Total Expected (USD)</th>
                              <th>User Share (USD)</th>
                              <th>Teckler Share (USD)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	$result = mysql_query($sql_real_revenue_per_month, $conn);
							if (mysql_num_rows($result) == 0) 
							  {
								echo " <br>\n";
								echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							  } else 
							  {
								while ($row = mysql_fetch_assoc($result)) 
								{
								  echo "<TR>\n";
								  echo "<TD>" . date('Y-m-d',strtotime($row['DueDate'])) ." </TD>\n";
								  echo "<TD>" . $row['TotalActualAmmountDue_USD']      . "</TD>\n";
								  echo "<TD>" . $row['UserActualAmmountDue_USD']       . "</TD>\n";
								  echo "<TD>" . $row['TecklerActualAmmountDue_USD']    . "</TD>\n";
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
                      </div>
                    </div>
                  </div>
                 </div>
                 <!--Tabs Nav Left END-->
              
                 
              </div>
              
              
             
             
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              <h3>Total Ammount </h3>
              <div class="row-fluid">
                    	<div class="grid titles span6 grid_table">
                            <h4>Ready to Be Withdrawn</h4>
                        </div>
                        <div class="grid titles span6 grid_table">
                            <h4>Already Withdrawn</h4>
                        </div>                    
                    </div>
                      <div class="row-fluid">
                            <!--Tabs Nav Left Start-->
                        <div class="grid span6 grid_table">
                          <div class="grid-title"> 
                          </div>                    
                           <ul id="myTab" class="tabs-nav">
                              <li class="active"><a href="#home_t3" data-toggle="tab">Diário</a></li>
                              <li><a href="#profile_t3" data-toggle="tab">Mensal</a></li>
                            </ul>
                            <div class="clearfix"></div> 
                          <div class="grid-content scroll_table">
                           <div id="myTabContent" class="tab-content">
                              <div class="tab-pane fade active in" id="home_t3">
                                    <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                              <div class="grid-content overflow">
                                <table class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>Date</th>
                                      <th>Total Ammount (USD)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                        $result = mysql_query($sql_total_ammount_tobe_withdrawn_per_day, $conn);
										if (mysql_num_rows($result) == 0) 
										  {
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
										  } else 
										  {
											while ($row = mysql_fetch_assoc($result)) 
											{
											  echo "<TR>\n";
											  echo "<TD>" . date('Y-m-d',strtotime($row['DueDate']))   ." </TD>\n";
											  echo "<TD>" . $row['TotalAmmount']        . "</TD>\n";
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
                              </div>
                              <div class="tab-pane fade" id="profile_t3">
                             <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                              <div class="grid-content scroll_table overflow">
                                <table class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>Date</th>
                                      <th>Total Ammount (USD)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $result = mysql_query($sql_total_ammount_tobe_withdrawn_per_month, $conn);
									if (mysql_num_rows($result) == 0) 
									  {
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									  } else 
									  {
										while ($row = mysql_fetch_assoc($result)) 
										{
										  echo "<TR>\n";
										  echo "<TD>" . date('Y-m-d',strtotime($row['DueDate'])) ." </TD>\n";
										  echo "<TD>" . $row['TotalAmmount']      . "</TD>\n";
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
                              </div>
                            </div>
                          </div>
                         </div>
                         <!--Tabs Nav Left END-->
                 
                 
                 
                 
                 
                 
                 
                 
              
                    <!--Tabs Nav Left Start-->
                <div class="grid span6 grid_table">
                  <div class="grid-title"> 
                  </div>                    
                   <ul id="myTab" class="tabs-nav">
                      <li class="active"><a href="#home_dia3" data-toggle="tab">Diário</a></li>
                      <li><a href="#profile_dia3" data-toggle="tab">Mensal</a></li>
                    </ul>
                    <div class="clearfix"></div> 
                  <div class="grid-content scroll_table">
                   <div id="myTabContent" class="tab-content">
                      <div class="tab-pane fade active in" id="home_dia3">
                      		<div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12 grid_table">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                          	  <th>Date</th>
                              <th>Total Ammount (USD)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  		$result = mysql_query($sql_total_ammount_withdrawn_per_day, $conn);
								if (mysql_num_rows($result) == 0) 
								  {
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
								  } else 
								  {
									while ($row = mysql_fetch_assoc($result)) 
									{
									  echo "<TR>\n";
									  echo "<TD>" . date('Y-m-d',strtotime($row['DueDate']))   ." </TD>\n";
									  echo "<TD>" . $row['TotalAmmount']        . "</TD>\n";
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
                      </div>
                      <div class="tab-pane fade" id="profile_dia3">
                     <div class="row-fluid">
              		<!--Striped table-->
                      <div class="grid span12 grid_table">
                      <div class="grid-content overflow">
                        <table class="table table-striped">
                        <thead>
                          <tr>
                              <th>Date </th>
                              <th>Total Ammount (USD)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
						  	$result = mysql_query($sql_total_ammount_withdrawn_per_month, $conn);
							if (mysql_num_rows($result) == 0) 
							  {
								echo " <br>\n";
								echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							  } else 
							  {
								while ($row = mysql_fetch_assoc($result)) 
								{
								  echo "<TR>\n";
								  echo "<TD>" . date('Y-m',strtotime($row['DueDate'])) ." </TD>\n";
								  echo "<TD>" . $row['TotalAmmount']      . "</TD>\n";
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
                      </div>
                    </div>
                  </div>
                 </div>
                 <!--Tabs Nav Left END-->
              
                 
              </div>
              
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             <h3>Total Ammount Pending</h3>
                      <div class="row-fluid">
                            <!--Tabs Nav Left Start-->
                        <div class="grid span12 grid_table">
                          <div class="grid-title"> 
                          </div>                    
                           <ul id="myTab" class="tabs-nav">
                              <li class="active"><a href="#home_t4" data-toggle="tab">Diário</a></li>
                              <li><a href="#profile_t4" data-toggle="tab">Mensal</a></li>
                            </ul>
                            <div class="clearfix"></div> 
                          <div class="grid-content scroll_table">
                           <div id="myTabContent" class="tab-content">
                              <div class="tab-pane fade active in" id="home_t4">
                                    <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                              <div class="grid-content overflow">
                                <table class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>Date </th>
          							  <th>Total Ammount (USD)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                        $result = mysql_query($sql_total_ammount_pending_per_day, $conn);
										if (mysql_num_rows($result) == 0) 
										  {
											echo " <br>\n";
											echo " Opps.. Somethint went wrong. Contact you administrator! \n";
										  } else 
										  {
											while ($row = mysql_fetch_assoc($result)) 
											{
											  echo "<TR>\n";
											  echo "<TD>" . date('Y-m-d',strtotime($row['DueDate']))   ." </TD>\n";
											  echo "<TD>" . $row['TotalAmmount']        . "</TD>\n";
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
                              </div>
                              <div class="tab-pane fade" id="profile_t4">
                             <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                              <div class="grid-content scroll_table overflow">
                                <table class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>Date </th>
          							  <th>Total Ammount (USD)</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $result = mysql_query($sql_total_ammount_pending_per_month, $conn);
									if (mysql_num_rows($result) == 0) 
									  {
										echo " <br>\n";
										echo " Opps.. Somethint went wrong. Contact you administrator! \n";
									  } else 
									  {
										while ($row = mysql_fetch_assoc($result)) 
										{
										  echo "<TR>\n";
										  echo "<TD>" . date('Y-m',strtotime($row['DueDate'])) ." </TD>\n";
										  echo "<TD>" . $row['TotalAmmount']      . "</TD>\n";
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
                              </div>
                            </div>
                          </div>
                         </div>
                         <!--Tabs Nav Left END-->
                 
                 
              		</div>
                      </div>
                    </div>
                  </div>
                 </div>
                 <!--Tabs Nav Left END-->
              
                 
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

