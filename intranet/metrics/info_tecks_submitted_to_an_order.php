<?php
  	include "../includes/header.php";
  	include "../includes/java_scripts.php";
  	require "conn.php";
  	date_default_timezone_set('America/Sao_Paulo');


	$prOrderID = $_GET['orderid'];

	$sql_order_id_info = 
		"select 
          co.CONTENT_ORDER_ID as OrderID, co.USER_ID as UserID, u.USER_NAME as UserName, co.PROFILE_ID as ProfileID, pro.SIGNATURE as ProfileName, 
          co.CATEGORY_ID as CategoryID, cat.CATEGORY as CategoryName, 
          co.CO_TITLE as OrderTitle, co.CO_DESCRIPTION as OrderDescription, co.PAYMENT_DATE as OrderPaymentDate, co.PRICE as OrderPrice, 
          co.LEAD_TIME as OrderDueDays, co.EXTENDED_COUNT as OrderExtendedCount 
      from 
          CONTENT_ORDER co left outer join CATEGORY cat on co.CATEGORY_ID = cat.CATEGORY_ID, 
          PROFILE pro, 
          USER u 
      where 
      co.PROFILE_ID = pro.PROFILE_ID and co.STATUS = 'publish' and 
      co.USER_ID = u.USER_ID and co.PAYMENT_DATE is not NULL and 
      co.CONTENT_ORDER_ID = "  . $prOrderID ; 

	$sql_tecks_submitted_to_an_order = 
		"select 
			co.CONTENT_ORDER_ID as OrderID, 
			pt.POST_ID as TeckID, 
			pt.TITLE as TeckTitle,
			length(pt.TEXT) as TeckNumCharacter,
			pt.TEXT as TeckContent,
			pt.PROFILE_ID as ProfileID, 
			pt.USER_ID as UserID, 
			pt.CREATION_DATE as TeckCreationDate,
			co.STATUS as OrderStatus,
			pt.STATUS_ID as TeckStatus
		from  
			TECK_CO tco right outer join CONTENT_ORDER co on tco.CONTENT_ORDER_ID = co.CONTENT_ORDER_ID,
			POST pt 
		where 
			co.STATUS not in ('draft', 'canceled') and 
			co.PAYMENT_DATE is not null and 
			tco.TECK_ID = pt.POST_ID and co.CONTENT_ORDER_ID = " . $prOrderID . "
		order by pt.CREATION_DATE ASC";



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
                <h1>Relatórios - Venda de Conteúdo - Propostas para um Pedido </h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
            <!--Striped table-->
            <h3>Informações sobre o Pedido</h3>
              <div class="grid grid_table_2">
              <div class="grid-content overflow">
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Profile Signature</th>
                    <th>Order Title</th>
                    <th>Order Price (US$)</th>
                    <th>Order Deadline in Days</th>
                    <th>Order Payment Date</th>
                    <th>Number of Extension Request</th>
                    <th>Order Category</th>
                  </tr>
                </thead>
                <tbody class="overflow_table">
                  <?php
                    // ------------------------------------------------------
                    // Tecks per User - Top 100
                    // ------------------------------------------------------
                    $result = mysql_query($sql_order_id_info, $conn);
                    while ($row = mysql_fetch_array($result)) 
                    {
                        echo "<TR>\n";
                        echo "<TD>" . $row['OrderID']. "</TD>\n";
                        echo "<TD>" . $row['UserName']  . "<BR>(ID: " . $row['UserID']          .  ")   </TD>\n";
                        echo "<TD>" . $row['ProfileName']  . "<BR>(ID: " . $row['ProfileID']    .  ")   </TD>\n";
                        echo "<TD>" . substr( $row['OrderTitle'], 0, 100)                    . " ... </TD>\n";
                        echo "<TD>" . number_format($row['OrderPrice'], 2, '.', ',')        . "     </TD>\n";
                        echo "<TD>" . number_format($row['OrderDueDays'], 0, '.', ',')      . "     </TD>\n";
                        echo "<TD>" . date("Y-m-d l H:i:s", strtotime($row['OrderPaymentDate'])) . "</TD>\n";
                        echo "<TD>" . number_format($row['OrderExtendedCount'] ,0, ".", ",")."      </TD>\n";
                        echo "<TD>" . $row['CategoryName'] . "<BR>(ID: " . $row['CategoryID']   .  ")   </TD>\n";
                        echo "</TR>\n";
                        echo "<TR><TH colspan=9>Order Description</TH></TR>\n";
                        echo "<TR><TD colspan=9>". htmlspecialchars_decode($row['OrderDescription']). "</TD></TR>\n";
                    }
                ?>
                </tbody>
              </table>
              <div class="clearfix"></div>
              </div>
              </div>
              <!--Striped table END-->


            <h3>Informações sobre as Propostas</h3>
              <div class="grid grid_table_2">
              <div class="grid-content overflow">
                
                  <?php
                    // ------------------------------------------------------
                    // Tecks per User - Top 100
                    // ------------------------------------------------------
                    $result = mysql_query($sql_tecks_submitted_to_an_order, $conn);
                    while ($row = mysql_fetch_array($result)) 
                    { ?>
                		
			              <table class="table table-striped">
			              	<tr>
			              		<th>Order ID</th>
			              		<th>Teck ID</th>
			              		<th>User ID</th>
			              		<th>Profile ID</th>
			              		<th>Size (# Characters)</th>
			              		<th>Creation Date</th>
			              		<th>Teck Status ID</th>
			              	</tr>
			                <tbody class="overflow_table"> 
						        <?php
			                        echo "<TR>\n";
			                        echo "<TD>" . $row['OrderID']		. "</TD>\n";
			                        echo "<TD>" . $row['TeckID']  		.  "</TD>\n";
			                        echo "<TD>" . $row['UserID']  		.  "</TD>\n";
			                        echo "<TD>" . $row['ProfileID']  	.  "</TD>\n";
			                        echo "<TD>" . number_format($row['TeckNumCharacter'], 2, '.', ',')        	. "     </TD>\n";
			                      	echo "<TD>" . date("Y-m-d l H:i:s", strtotime($row['TeckCreationDate'])) 	. "</TD>\n";
			                      	echo "<TD>" . $row['TeckStatus']  	.  "</TD>\n";
			                        echo "</TR>\n";
			                    ?>
                    	<tr><th colspan=7 align=center> Teck Content</th></tr>
                    	<tr><td colspan=7> <?php echo htmlspecialchars_decode($row['TeckContent']); ?></tr>
                    </tbody>
                </table>
                    <?php
                    echo "<hr><hr>";    
                    }
                ?>
                
              
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