<?php
  include "../includes/header.php";
  include "../includes/java_scripts.php";
  require "conn.php";
  date_default_timezone_set('America/Sao_Paulo');

  // Revenue
    $sql_total_ammount_traded = 
      "select 
          (sum(VALUE) - sum(FEE)) as AmmountTraded         
        from 
          PAY.PAYMENT
        where DOCUMENT_TYPE = 'c_order'";
    $sql_total_ammount_traded_daily = 
      "select 
          (sum(VALUE) - sum(FEE)) as AmmountTraded,
          date(PAYMENT_DATE) as RefDate 
        from 
          PAY.PAYMENT  
        where 
          DOCUMENT_TYPE = 'c_order' and date(PAYMENT_DATE) = date(curdate())  
        group by date(PAYMENT_DATE) order by date(PAYMENT_DATE) DESC 
        limit 1";
    $sql_total_ammount_traded_month = 
      "select 
        (sum(VALUE) - sum(FEE)) as AmmountTraded,
        date(PAYMENT_DATE) as RefDate 
        from 
          PAY.PAYMENT 
        where 
          DOCUMENT_TYPE = 'c_order' and month(PAYMENT_DATE) = month(curdate())  
        group by year(PAYMENT_DATE), month(PAYMENT_DATE) 
        order by year(PAYMENT_DATE) DESC, month(PAYMENT_DATE) DESC
        limit 1";
    
    $sql_total_teckler_revenue = 
      "select 
          (sum(VALUE) - sum(FEE) - sum(USER_VALUE)) as TecklerRevenue          
        from 
          PAY.PAYMENT
        where DOCUMENT_TYPE = 'c_order' and REQUEST_STATUS<>'pending'";
    $sql_total_teckler_revenue_daily = 
      "select
          (sum(VALUE) - sum(FEE) - sum(USER_VALUE)) as TecklerRevenue,
          date(PAYMENT_DATE) as RefDate 
      from 
        PAY.PAYMENT  
      where 
        DOCUMENT_TYPE = 'c_order' and 
        date(PAYMENT_DATE) = date(curdate()) and 
        REQUEST_STATUS <> 'pending'   
      group by date(PAYMENT_DATE) 
      order by date(PAYMENT_DATE) DESC 
      limit 1";
    $sql_total_teckler_revenue_month = 
      "select
          (sum(VALUE) - sum(FEE) - sum(USER_VALUE)) as TecklerRevenue,
          date(PAYMENT_DATE) as RefDate 
      from 
        PAY.PAYMENT  
      where 
        DOCUMENT_TYPE = 'c_order' and 
        date(PAYMENT_DATE) = date(curdate()) and 
        REQUEST_STATUS <> 'pending'   
      group by year(PAYMENT_DATE), month(PAYMENT_DATE) 
      order by year(PAYMENT_DATE) DESC, month(PAYMENT_DATE) DESC 
      limit 1";

  // Consolidated - First Block
    $sql_total_number_of_orders = 
      "select count(CONTENT_ORDER_ID) as NumOrders 
      from CONTENT_ORDER 
      where STATUS not in ('draft', 'canceled' ) and PAYMENT_DATE is not null";
    $sql_avg_order_price = 
      "select avg(PRICE) as AvgPriceOrder 
      from CONTENT_ORDER 
      where STATUS <>'draft' and PAYMENT_DATE is not null";
    $sql_avg_number_of_tecks_per_order = 
      "select 
          sum(c.NumTecksRespostas) as NumTecks, 
          count(c.ContentOrder) as NumOrders, 
          (sum(c.NumTecksRespostas) / count(c.ContentOrder) ) as AvgTecksPerOrder 
      from 
        (select 
          count(a.TECK_ID) as NumTecksRespostas, 
          b.CONTENT_ORDER_ID as ContentOrder 
        from  TECK_CO a right outer join CONTENT_ORDER b on a.CONTENT_ORDER_ID = b.CONTENT_ORDER_ID 
        where 
          b.STATUS not in ('draft', 'canceled') and 
          b.PAYMENT_DATE is not null
        group by b.CONTENT_ORDER_ID) c";
    $sql_avg_duetime_in_days = 
      "select avg(LEAD_TIME) as AvgDueDays  
      from CONTENT_ORDER where STATUS <>'draft' and PAYMENT_DATE is not null";
  
  // Consolidated - Second Block
    $sql_avg_number_or_orders_per_buyer_id = 
      "select 
        avg(a.NumOrders) as AvgOrdersPerBuyer
      from
        (select 
          count(CONTENT_ORDER_ID) as NumOrders, USER_ID as UserID 
          from CONTENT_ORDER 
          where STATUS <>'draft' and PAYMENT_DATE is not null 
          group by USER_ID) a";
    $sql_total_number_of_cancelled_orders = 
      "select count(CONTENT_ORDER_ID) as NumCancelOrders 
      from CONTENT_ORDER where status = 'canceled' and PAYMENT_DATE is not NULL";
    $sql_total_number_of_orders_reschedulled = 
      "select count(CONTENT_ORDER_ID) as NumRescheduledOrders 
      from CONTENT_ORDER where EXTENDED_COUNT > 0 and status <> 'draft' and PAYMENT_DATE is not NULL";
    $sql_total_number_of_orders_wo_tecks = 
      "select count(CONTENT_ORDER_ID) as NumOrdersWOTecks
      from CONTENT_ORDER
      where CONTENT_ORDER_ID not in (select distinct CONTENT_ORDER_ID from TECK_CO) and 
      STATUS not in ('draft', 'canceled') and PAYMENT_DATE is not NULL";

  // Consolidated - 3rd Block
    $sql_num_orders_per_category = 
      "select 
        count(a.CONTENT_ORDER_ID) as NumOrders, 
        a.CATEGORY_ID as CategoryId, 
        b.CATEGORY as CategoryDescription
      from 
        CONTENT_ORDER a, 
        CATEGORY b 
      where 
        a.CATEGORY_ID = b.CATEGORY_ID and 
        a.STATUS <> 'draft' and 
        a.PAYMENT_DATE is not null  
      group by a.CATEGORY_ID
      order by count(a.CONTENT_ORDER_ID) DESC";

  // Consolidated - 4th Block 
    $sql_total_number_of_buyers = 
      "select count(distinct USER_ID) as NumBuyers 
      from CONTENT_ORDER 
      where STATUS <>'draft' and PAYMENT_DATE is not null";
    $sql_total_number_of_sellers = 
      "select 
        count(distinct c.Users) as NumSellers, 
        count(distinct c.Profiles) as DistinctPorfiles 
      from 
        (select a.USER_ID as Users, a.PROFILE_ID as Profiles, b.TECK_ID as TeckID 
          from POST a, TECK_CO b 
          where a.POST_ID = b.TECK_ID) c";

  // Transactional
    $sql_total_number_of_orders_per_day = 
      "select count(CONTENT_ORDER_ID) as NumOrders, date(PAYMENT_DATE) as RefDate 
      from CONTENT_ORDER
      where STATUS <>'draft' and PAYMENT_DATE is not null 
      group by date(PAYMENT_DATE) 
      order by date(PAYMENT_DATE) DESC 
      limit 90";
    $sql_total_number_of_orders_per_month = 
      "select count(CONTENT_ORDER_ID) as NumOrders, date(PAYMENT_DATE) as RefMonth 
      from CONTENT_ORDER 
      where STATUS <>'draft' and PAYMENT_DATE is not null 
      group by year(PAYMENT_DATE), month(PAYMENT_DATE) 
      order by year(PAYMENT_DATE) DESC, month(PAYMENT_DATE) DESC  
      limit 24";  
    
    $sql_total_number_of_cancelled_orders_per_day = 
      "select count(CONTENT_ORDER_ID) as NumOrders, date(PAYMENT_DATE) as RefDate 
      from CONTENT_ORDER
      where STATUS = 'canceled' and PAYMENT_DATE is not null 
      group by date(PAYMENT_DATE) 
      order by date(PAYMENT_DATE) DESC 
      limit 90";
    $sql_total_number_of_cancelled_orders_per_month = 
      "select count(CONTENT_ORDER_ID) as NumOrders, date(PAYMENT_DATE) as RefMonth 
      from CONTENT_ORDER 
      where STATUS = 'canceled' and PAYMENT_DATE is not null 
      group by year(PAYMENT_DATE), month(PAYMENT_DATE) 
      order by year(PAYMENT_DATE) DESC, month(PAYMENT_DATE) DESC  
      limit 24";  



  // Top Orders by Value Info
    $sql_top_1000_orders_info_per_value = 
      "select 
          co.CONTENT_ORDER_ID as OrderID, co.USER_ID as UserID, u.USER_NAME as UserName, co.PROFILE_ID as ProfileID, pro.SIGNATURE as ProfileName, 
          co.CATEGORY_ID as CategoryID, cat.CATEGORY as CategoryName,  
          co.CO_TITLE as OrderTitle, co.PAYMENT_DATE as OrderPaymentDate, co.PRICE as OrderPrice,  
          co.LEAD_TIME as OrderDueDays, co.EXTENDED_COUNT as OrderExtendedCount, 
          count(tco.TECK_ID) as NumProposalsReceived  
      from  
          CONTENT_ORDER co left outer join CATEGORY cat on co.CATEGORY_ID = cat.CATEGORY_ID left outer join TECK_CO tco on co.CONTENT_ORDER_ID = tco.CONTENT_ORDER_ID,  
          PROFILE pro,  
          USER u  
      where 
      co.PROFILE_ID = pro.PROFILE_ID and co.STATUS = 'publish' and co.USER_ID = u.USER_ID and co.PAYMENT_DATE is not NULL  
      group by co.CONTENT_ORDER_ID 
      order by co.PRICE DESC 
      limit 1000";
  
  // Most Recent Orders Info  
    $sql_most_1000_recent_orders_info = 
      "select 
          co.CONTENT_ORDER_ID as OrderID, co.USER_ID as UserID, u.USER_NAME as UserName, co.PROFILE_ID as ProfileID, pro.SIGNATURE as ProfileName, 
          co.CATEGORY_ID as CategoryID, cat.CATEGORY as CategoryName, 
          co.CO_TITLE as OrderTitle, co.PAYMENT_DATE as OrderPaymentDate, co.PRICE as OrderPrice, 
          co.LEAD_TIME as OrderDueDays, co.EXTENDED_COUNT as OrderExtendedCount,
          count(tco.TECK_ID) as NumProposalsReceived   
      from 
          CONTENT_ORDER co left outer join CATEGORY cat on co.CATEGORY_ID = cat.CATEGORY_ID left outer join TECK_CO tco on co.CONTENT_ORDER_ID = tco.CONTENT_ORDER_ID, 
          PROFILE pro, 
          USER u 
      where 
      co.PROFILE_ID = pro.PROFILE_ID and co.STATUS = 'publish' and co.USER_ID = u.USER_ID and co.PAYMENT_DATE is not NULL 
      group by co.CONTENT_ORDER_ID 
      order by co.PAYMENT_DATE DESC 
      limit 1000";


$numTotalOrders         = "";
$amountTradedDaily      = "";
$amountTradedMonthly    = "";
$tecklerRevenueToday    = "";
$tecklerRevenueTomonth  = "";
  


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
                <h1>Relatórios - Venda de Conteúdo - Pedidos & Propostas </h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
            <h2>Métricas de Receita</h2>
            <!-- Consolidated  - Revenue Information -->
             <div class="row-fluid clearfix">
                <!--Informatin BOX 1-->
                  <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Valor Total Negociado (US $)</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Total Ammount Traded (Excluding PayPal Fee)
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_ammount_traded, $conn);
                                    while ($row = mysql_fetch_array($result)){
                                        echo number_format($row['AmmountTraded'],2, '.', ',');
                                    }
                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 1 END-->        
                <!--Informatin BOX 2-->
                  <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Receita Teckler (US $)</div>
                            <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Receita Teckler Total Acumulada
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_total_teckler_revenue, $conn);
                                  while ($row = mysql_fetch_array($result)){
                                          echo number_format($row['TecklerRevenue'], 2, ".", ",");
                                  }
                                ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 2 END-->
             </div>
             <div class="row-fluid clearfix">
                <!--Informatin BOX 1-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Valor Negociado - Dia(US $)</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Valor Negociado HOJE
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_ammount_traded_daily, $conn);
                                    while ($row = mysql_fetch_array($result)){
                                        $amountTradedDaily = $row['AmmountTraded']; 
                                        echo number_format($row['AmmountTraded'],2, '.', ',');
                                    }
                                    if (empty($amountTradedDaily)) {
                                      echo "Nothing traded <br>today\n";
                                    }
                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 1 END--> 
                <!--Informatin BOX 2-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Valor Negociado - Mês(US $)</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Valor Negociado ESSE MES
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_ammount_traded_month, $conn);
                                    while ($row = mysql_fetch_array($result)){
                                        echo number_format($row['AmmountTraded'],2, '.', ',');
                                    }
                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 2 END--> 
                <!--Informatin BOX 3-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Receita Teckler - Dia(US $)</div>
                            <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Receita Teckler HOJE
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_total_teckler_revenue_daily, $conn);
                                  while ($row = mysql_fetch_array($result)){
                                      $tecklerRevenueToday = $row['TecklerRevenue'];
                                      echo number_format($row['TecklerRevenue'], 2, ".", ",");
                                  }
                                  if (empty($tecklerRevenueToday)) {
                                    echo "No revenue <br> earned today\n";
                                  }  

                                ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 3 END-->
                <!--Informatin BOX 4-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Receita Teckler - Mês(US $)</div>
                            <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Receita Teckler ESSE MES
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_total_teckler_revenue_month, $conn);
                                  while ($row = mysql_fetch_array($result)){
                                      $tecklerRevenueTomonth = $row['TecklerRevenue'];
                                      echo number_format($row['TecklerRevenue'], 2, ".", ",");
                                  }
                                  if (empty($tecklerRevenueTomonth)) {
                                    echo "No revenue <br> earned this month\n";
                                  }
                                ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 3 END-->
             </div>

             <hr>

             <h2> Métricas de Transação </h2>
             <!-- Consolidated  - 1st Block of Information -->
             <div class="row-fluid clearfix">
                <!--Informatin BOX 1-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Total de Pedidos Ativos</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Total de Pedidos
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_number_of_orders, $conn);
                                    while ($row = mysql_fetch_array($result)) {
                                      echo number_format($row['NumOrders'], 0, ".", ",") ; 
                                      $numTotalOrders = $row['NumOrders'];
                                    }
                                    
                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 1 END-->        
                <!--Informatin BOX 2-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Preço Médio dos Pedidos (US$)</div>
                            <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Num Total Tecks
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_avg_order_price, $conn);
                                  while ($row = mysql_fetch_array($result)){
                                          echo ( !empty($row['AvgPriceOrder']) ? number_format($row['AvgPriceOrder'], 2, "." , "," ): "0.00");
                                   }
                                ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 2 END-->
                <!--Informatin BOX 3 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Média de Tecks p/ Pedidos</div>
                            <div class="box-title">
                                  <?php
                                      $result = mysql_query($sql_avg_number_of_tecks_per_order, $conn);
                                      while ($row = mysql_fetch_array($result)){
                                          //echo "Opps! Houston we've got a problem!";
                                          echo ( !empty($row['AvgTecksPerOrder']) ? number_format($row['AvgTecksPerOrder'], 2 , ".", ","): "0.00");
                                      }
                                  ?>
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 3 END-->
                <!--Informatin BOX 4 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Tempo Médio dos Pedidos (dias)</div>
                            <div class="box-title">
                                  <?php
                                      $result = mysql_query($sql_avg_duetime_in_days, $conn);
                                      while ($row = mysql_fetch_array($result)){
                                        echo ( !empty($row['AvgDueDays']) ? number_format($row['AvgDueDays'], 3, ".", ",") : "0.00");
                                      }
                                  ?>
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 4 END-->
             </div>

             <!-- Consolidated  - 2nd Block of Information -->
             <div class="row-fluid clearfix">
               <!--Informatin BOX 1-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Pedidos Cancelados</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Avg Tecks per User
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_number_of_cancelled_orders, $conn);
                                    while ($row = mysql_fetch_array($result)) {
                                        echo ( !empty($row['NumCancelOrders']) ? number_format($row['NumCancelOrders'], 0, ".", ",") : "0" );
                                    }

                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                 <!--Informatin BOX 1 END-->  
               <!--Informatin BOX 2-->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Pedidos Prorrogados</div>
                            <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Extended/Rescheduled Orders
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_total_number_of_orders_reschedulled, $conn);
                                  while ($row = mysql_fetch_array($result)){
                                          echo ( !empty($row['NumRescheduledOrders'])? number_format($row['NumRescheduledOrders'], 0, ".", ",") : "0" );
                                  }
                                ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 2 END-->
               <!--Informatin BOX 3 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Pedidos sem Propostas</div>
                            <div class="box-title">
                                  <?php
                                      $result = mysql_query($sql_total_number_of_orders_wo_tecks, $conn);
                                      while ($row = mysql_fetch_array($result))
                                      {
                                          echo ( !empty($row['NumOrdersWOTecks'])? number_format($row['NumOrdersWOTecks'],0,".", ",") : "0" );
                                      }
                                  ?>
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 3 END-->             
               <!--Informatin BOX 4 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Média de Pedidos por Demandante</div>
                            <div class="box-title">
                                  <?php
                                      $result = mysql_query($sql_avg_number_or_orders_per_buyer_id, $conn);
                                      while ($row = mysql_fetch_array($result)){
                                          echo ( !empty($row['AvgOrdersPerBuyer']) ?  number_format($row['AvgOrdersPerBuyer'],0, ".", ",") : "0.00");
                                      }
                                  ?>
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 4 END-->
              </div>

             <!-- Consolidated  - 3rd Block of Information-->
             <div class="row-fluid clearfix">
                <!--Informatin BOX 1-->
                  <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt=""> 
                            <div class="box-figures">Número Total de Compradores</div>
                            <div class="box-title">
                                <?php
                                    // ------------------------------------------------------
                                    // Avg Tecks per User
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_number_of_buyers, $conn);
                                    while ($row = mysql_fetch_array($result)){
                                      echo ( !empty($row['NumBuyers']) ? number_format($row['NumBuyers'],0, ".", ",") : "0");
                                    }
                                ?>
                                
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 1 END-->  
                <!--Informatin BOX 2-->
                  <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Número Total de Vendedores (Proponentes)</div>
                            <div class="box-title">
                                <?php
                                  // ------------------------------------------------------
                                  // Num Total Tecks
                                  // ------------------------------------------------------
                                  $result = mysql_query($sql_total_number_of_sellers, $conn);
                                  while ($row = mysql_fetch_array($result)){
                                      echo ( !empty($row['NumSellers'])? number_format($row['NumSellers'],0, ".", ",") : "0");
                                  }
                                ?>
    
                            </div>
                          </div>
                        </div>
                  </div>
                  <!--Informatin BOX 2 END-->
              </div>
            
            <hr>

              
             <h3>Número de Pedidos por Categoria</h3>
              <div class="grid grid_table">
                <div class="grid-content overflow">  
                  <table class="table table-bordered table-mod-2">
                    <thead>
                      <tr>
                        <th>Categoria</th>
                        <th>Número de Pedidos</th>
                        <th>% Pedidos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                          // ------------------------------------------------------
                          // Pedidos por Categoria
                          // ------------------------------------------------------
                          $result = mysql_query($sql_num_orders_per_category, $conn);
                          while ($row = mysql_fetch_array($result)) 
                          {
                            echo "<TR>\n"; 
                            echo "<TD class='t_b_blue'>"      . $row['CategoryDescription'] . "</TD>\n";
                            echo "<TD class='action-table'>"  . $row['NumOrders'] . "</TD>\n";
                            echo "<TD class='action-table'>"  . number_format((round($row['NumOrders'] / $numTotalOrders, 2) * 100),2,".", ",")   . "</TD>\n";
                            echo "</TR>\n";
                          }
                        ?>
                    </tbody>
                  </table>  
                  <div class="clearfix"></div>
                </div>
              </div>


              <!-- Transactional Info -->
              <!-- Registered Users - Breakdown : LANGUAGE  -->
              <h3>Pedidos por Período</h3>
              <div class="row-fluid">
                  <div class="grid titles span6 grid_table"><h4> Pedidos Totais</h4></div>
                  <div class="grid titles span6 grid_table"><h4> Pedidos Cancelados</h4></div>
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
                    <div class="grid-content">
                     <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="home">
                            <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                                <div class="grid-content overflow">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>Data</th>
                                        <th>Número de Pedidos</th>
                                    </thead>
                                    <tbody>
                                      <?php
                                          // ------------------------------------------------------
                                          // Order per Day - Last 90 days
                                          // ------------------------------------------------------
                                          $result = mysql_query($sql_total_number_of_orders_per_day, $conn);
                                          $infoOrdersDay = "";
                                          $infoDate = "";
                                          while ($row = mysql_fetch_assoc($result)) {
                                              echo "<TR>\n";
                                              echo "<TD>" .date('Y-m-d',strtotime($row['RefDate'])) ." </TD>\n";
                                              echo "<TD><span class='s_green'>" . number_format($row['NumOrders'],0,".",","). "</span></TD>\n";
                                              echo "</TR>\n";
                                            
                                              $infoOrdersDay .= "," . $row['NumOrders'] ;
                                              $infoDate     .= "," . date("Y-m-d",strtotime($row['RefDate']));
                                          }
                                          
                                          // High Charts - 
                                          if (!empty($infoOrdersDay)){
                                            $infoOrdersDay = substr($infoOrdersDay, 1);
                                            $arr_infoOrdersDay = explode(",", $infoOrdersDay);
                                            $arr_infoOrdersDay = array_reverse($arr_infoOrdersDay);
                                            $infoOrdersDay = implode(",", $arr_infoOrdersDay);  

                                            $infoDate     = substr($infoDate, 1);
                                            $arr_infoDate = explode(",", $infoDate);
                                            $arr_infoDate = array_reverse($arr_infoDate);
                                            $infoDate = implode(",", $arr_infoDate);
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
                          <div class="grid-content overflow">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Mês</th>
                                  <th>Número de Pedidos</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    // ------------------------------------------------------
                                    // Orders per month - Last 24 months
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_number_of_orders_per_month, $conn);
                                    while ($row = mysql_fetch_assoc($result)) {
                                        echo "<tr>\n";
                                        echo "<TD>" .date('Y-m',strtotime($row['RefMonth'])) ." </TD>\n";
                                        echo "<TD><span class='s_green'>" . number_format($row['NumOrders'],0,".",","). "</span></TD>\n";
                                        echo "</tr>\n";
                                
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
                  <div class="grid span6 grid_table">
                    <div class="grid-title"> 
                    </div>                    
                     <ul id="myTab" class="tabs-nav">
                        <li class="active"><a href="#home1" data-toggle="tab">Diário</a></li>
                        <li><a href="#profile1" data-toggle="tab">Mensal</a></li>
                      </ul>
                      <div class="clearfix"></div> 
                    <div class="grid-content">
                     <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="home1">
                            <div class="row-fluid">
                            <!--Striped table-->
                              <div class="grid span12 grid_table">
                                <div class="grid-content overflow">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>Data</th>
                                        <th>Número de Pedidos</th>
                                    </thead>
                                    <tbody>
                                      <?php
                                          // ------------------------------------------------------
                                          // Order per Day - Last 90 days
                                          // ------------------------------------------------------
                                          $result = mysql_query($sql_total_number_of_cancelled_orders_per_day, $conn);
                                          while ($row = mysql_fetch_assoc($result)) {
                                              echo "<TR>\n";
                                              echo "<TD>" .date('Y-m-d',strtotime($row['RefDate'])) ." </TD>\n";
                                              echo "<TD><span class='s_green'>" . number_format($row['NumOrders'],0,".",","). "</span></TD>\n";
                                              echo "</TR>\n";
                                            
                                            
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
                        <div class="tab-pane fade" id="profile1">
                       <div class="row-fluid">
                        <!--Striped table-->
                        <div class="grid span12 grid_table">
                          <div class="grid-content overflow">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Mês</th>
                                  <th>Número de Pedidos</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    // ------------------------------------------------------
                                    // Orders per month - Last 24 months
                                    // ------------------------------------------------------
                                    $result = mysql_query($sql_total_number_of_cancelled_orders_per_month, $conn);
                                    while ($row = mysql_fetch_assoc($result)) {
                                        echo "<tr>\n";
                                        echo "<TD>" .date('Y-m',strtotime($row['RefMonth'])) ." </TD>\n";
                                        echo "<TD><span class='s_green'>" . number_format($row['NumOrders'],0,".",","). "</span></TD>\n";
                                        echo "</tr>\n";
                                
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



                
              
              <!--  Users per Day Charts -->
              <br>
              <div class='clearfix'>
                <input type='hidden' id="date-axis-X" value="<?php echo $infoDate; ?>">
                <input type='hidden' id="order-axis-Y" value="<?php echo $infoOrdersDay; ?>">
                <div id="container_orders" style="width:100%; height:300px;"></div>
                <script>
                  $(function () { 
                      // Treating Pageviews Graphic
                          var dateValues = $('#date-axis-X').val();
                          var xAxis = dateValues.split(",");
                          var ordersValues = $('#order-axis-Y').val();
                          var yAxis = ordersValues.split(',');
                          var merge = [];
                          for (var i=0; i < xAxis.length; i++) { 
                            var dateparts = xAxis[i].split("-");
                            var date = Date.UTC(dateparts[0], parseInt(dateparts[1],10)-1, dateparts[2]);
                            var orders = parseInt(yAxis[i], 10);
                            merge.push([date, orders]);
                          }
                          $('#container_orders').highcharts({
                                chart: { type: 'line' },
                                title: { text: 'Orders per Day' },
                                xAxis: { type : 'datetime' },
                                yAxis: { title: { text: 'Orders per Day' } , 
                                          type: 'logarithmic'},
                                series: [{
                                    name: 'Orders per Day',
                                    data: merge
                                }]
                            });
                          });
                </script>  
              </div >
              
            <!--Striped table-->
              <h3>Pedidos Ativo/Publicados Por Usuário - Top 1000 (Por valor)</h3>
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
                    <th>Proposals Received</th>
                  </tr>
                </thead>
                <tbody class="overflow_table">
                  <?php
                    // ------------------------------------------------------
                    // Tecks per User - Top 1000
                    // ------------------------------------------------------
                    $result = mysql_query($sql_top_1000_orders_info_per_value, $conn);
                    while ($row = mysql_fetch_array($result)) 
                    {
                        echo "<TR>\n";
                        echo "<TD><a href='info_tecks_submitted_to_an_order.php?orderid=".$row['OrderID'] . "'>" . $row['OrderID']. "</TD>\n";
                        echo "<TD>" . $row['UserName']  . "<br>(ID: " . $row['UserID']          .  ")   </TD>\n";
                        echo "<TD>" . $row['ProfileName']  . "<br>(ID: " . $row['ProfileID']    .  ")   </TD>\n";
                        echo "<TD>" . substr( $row['OrderTitle'], 0, 100)                    . " ... </TD>\n";
                        echo "<TD>" . number_format($row['OrderPrice'], 2, '.', ',')        . "     </TD>\n";
                        echo "<TD>" . number_format($row['OrderDueDays'], 0, '.', ',')      . "     </TD>\n";
                        echo "<TD>" . date("Y-m-d l H:i:s", strtotime($row['OrderPaymentDate'])) . "</TD>\n";
                        echo "<TD>" . number_format($row['OrderExtendedCount'] ,0, ".", ",")."      </TD>\n";
                        echo "<TD>" . $row['CategoryName'] . "<br>(ID: " . $row['CategoryID']   .  ")   </TD>\n";
                        echo "<TD>" . $row['NumProposalsReceived'] . "</TD>\n";
                        echo "</TR>\n";
                    }
                ?>
                </tbody>
              </table>
              <div class="clearfix"></div>
              </div>
              </div>
              <!--Striped table END-->
              
            <!--Striped table-->
              <h3>Pedidos Ativos/Publicados Por Usuário - Top 1000 (Mais Recentes)</h3>
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
                    <th>Proposals Received</th>
                  </tr>
                </thead>
                <tbody class="overflow_table">
                  <?php
                    // ------------------------------------------------------
                    // Tecks per User - Top 100
                    // ------------------------------------------------------
                    $result = mysql_query($sql_most_1000_recent_orders_info, $conn);
                    while ($row = mysql_fetch_array($result)) 
                    {
                        echo "<TR>\n";
                        echo "<TD><a href='info_tecks_submitted_to_an_order.php?orderid=".$row['OrderID'] . "'>" . $row['OrderID']. "</TD>\n";
                        echo "<TD>" . $row['UserName']  . "<BR>(ID: " . $row['UserID']          .  ")   </TD>\n";
                        echo "<TD>" . $row['ProfileName']  . "<BR>(ID: " . $row['ProfileID']    .  ")   </TD>\n";
                        echo "<TD>" . substr( $row['OrderTitle'], 0, 100)                    . " ... </TD>\n";
                        echo "<TD>" . number_format($row['OrderPrice'], 2, '.', ',')        . "     </TD>\n";
                        echo "<TD>" . number_format($row['OrderDueDays'], 0, '.', ',')      . "     </TD>\n";
                        echo "<TD>" . date("Y-m-d l H:i:s", strtotime($row['OrderPaymentDate'])) . "</TD>\n";
                        echo "<TD>" . number_format($row['OrderExtendedCount'] ,0, ".", ",")."      </TD>\n";
                        echo "<TD>" . $row['CategoryName'] . "<BR>(ID: " . $row['CategoryID']   .  ")   </TD>\n";
                        echo "<TD>" . $row['NumProposalsReceived'] . "</TD>\n";
                        echo "</TR>\n";
                    }
                ?>
                </tbody>
              </table>
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


    


  </body>
</html>

