<?php
	require "conn_rev.php";

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
             
/* ********************************************************************
*************************************************************************
                      Aggregated Revenue 
************************************************************************
********************************************************************* */
echo "Today is: " .  date('Y-m-d');

echo "<h3> Aggregated Revenue </h3>";
echo "<table border=1>\n";
echo "<tr>
        <th>Expected Revenue (USD) <sup>1</sup> </th>
        <th>Real Revenue (USD)<sup>2</sup> </th>
      </tr>\n";              
echo "<tr>\n";
// ------------------------------------------------------
// Total Expected Revenue
// ------------------------------------------------------
$result = mysql_query($sql_expected_revenue_ever, $conn);
if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " Opps.. Somethint went wrong. Contact you administrator! \n";
	} else 
	{
		while ($row = mysql_fetch_array($result)) 
		{
			echo "<TD>" . round($row['TotalExpectedAmmountDue_USD'],2). "</TD>\n";
		}
	}        
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
      echo "<TD>" . round($row['TotalEarnedAmmountDue_USD'],2). "</TD>\n";
    }
  }

// ------------------------------------------------------
// DELTA - REAL / Expected
// ------------------------------------------------------
/*
$result = mysql_query($sql_rate_expected_real, $conn);
if (mysql_num_rows($result) == 0) 
  {
    echo " <br>\n";
    echo " Opps.. Somethint went wrong. Contact you administrator! \n";
  } else 
  {
    while ($row = mysql_fetch_array($result)) 
    {
      echo "<TD>" . (round($row['RevenueAssertivesRate'],3)*100) . "% &nbsp; </TD>\n";
    }
  }
*/
echo "</tr>\n";
echo "</table>\n";
echo "Legend: 1. Before Google Verification, 2. After Google Verification, 3. Real / Expected <br> All values in US Dollar!\n";
echo "<HR>\n";

/* ********************************************************************
*************************************************************************
                      Expected Revenue 
************************************************************************
********************************************************************* */
echo "<h3>Expected Revenue - Yet to Be Verified by Google   </h3>";
echo "<p> Breakdown By Day - Last 90 days</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Expected Revenue (USD)</th>
          <th>Expected Revenue - User Share(USD) </th>
          <th>Expected Revenue - Teckler Share(USD) </th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

echo "<p> Breakdown By Month - Last 24 Months</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Expected Revenue (USD)</th>
          <th>Expected Revenue - User Share(USD)</th>
          <th>Expected Revenue - Teckler Share(USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";
echo "<HR>";



/* ********************************************************************
*************************************************************************
                      Actual Revenue 
************************************************************************
********************************************************************* */
echo "<h3>Actual Revenue - As Verified by Google   </h3>";
echo "<p> Breakdown By Day - Last 90 days</p>\n";
echo "<B>Google Takes 30 days for verification, so we are already offsetting this timelag</B>";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Actual Revenue (USD)/th>
          <th>Actual Revenue - User Share(USD)/th>
          <th>Actual Revenue - Teckler Share(USD)/th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

echo "<p> Breakdown By Month - Last 24 Months</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Expected Revenue (USD)</th>
          <th>Expected Revenue - User Share(USD)</th>
          <th>Expected Revenue - Teckler Share(USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";
echo "<HR>";

/* ********************************************************************
*************************************************************************
Revenue - by Status (Pending / Withdrawn / To be Withdrawn) - Aggregated
************************************************************************
********************************************************************* */
echo "<h3>Aggregated Ammount Due - By Status</h3>";

echo "<table border=1>\n";
echo "<tr>
          <th>Pending (USD)</th>
          <th>To be Withdrawn (USD)</th>
          <th>Already Withdrawn (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
      echo "<TD>" . round($row['TotalAmmount'],2). "</TD>\n";
    }
  }        
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

echo "</tr>\n";
echo "</table>\n";
echo "<HR>";




/* ********************************************************************
*************************************************************************
      Actuals - Status (Pending / Withdrawn / To be Withdrawn) 
************************************************************************
********************************************************************* */
// ///////////////////////////////////////////////////
// Ready to be Withdrawn
// ////////////////////////////////////////////////////
echo "<h3>Total Ammount Ready to Be Withdrawn</h3>";

echo "<p> Breakdown By Day - Last 90 days</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Ammount (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

echo "<p> Breakdown By Month - Last 24 Months</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Ammount (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

// //////////////////////////////////////////////////
// Already  Withdrawn
// ////////////////////////////////////////////////
echo "<h3>Total Ammount Already Withdrawn</h3>";

echo "<p> Breakdown By Day - Last 90 days</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Ammount (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

echo "<p> Breakdown By Month - Last 24 Months</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Ammount (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

// //////////////////////////////////////////////////
// Pending  Withdrawn
// ////////////////////////////////////////////////
echo "<h3>Total Ammount Pending</h3>";

echo "<p> Breakdown By Day - Last 90 days</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Ammount (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

echo "<p> Breakdown By Month - Last 24 Months</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>Total Ammount (USD)</th>
        </tr>\n";              
echo "<tr>\n";
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
echo "</tr>\n";
echo "</table>\n";

echo "<HR>";

/* ********************************************************************
*************************************************************************
      DELTA - REAL / EXPECTED - PER PERIOD
************************************************************************
********************************************************************* */

echo "<h3>Assertiveness Ratio</h3>";

echo "<p> Breakdown By Day - Last 90 days</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>% Assertiveness</th>
        </tr>\n";              
echo "<tr>\n";
$result = mysql_query($sql_rate_expected_real_per_day, $conn);
if (mysql_num_rows($result) == 0) 
  {
    echo " <br>\n";
    echo " Opps.. Somethint went wrong. Contact you administrator! \n";
  } else 
  {
    while ($row = mysql_fetch_assoc($result)) 
    {
      //if ((date() - date())){
        echo "<TR>\n";
        echo "<TD>" . date('Y-m-d',strtotime($row['DueDate']))   ." </TD>\n";
        echo "<TD>" . $row['RevenueAssertivenessRatio']        . "</TD>\n";
        echo "</TR>\n";
      //}
    }
  }
echo "</tr>\n";
echo "</table>\n";

echo "<p> Breakdown By Month - Last 24 Months</p>\n";
echo "<table border=1>\n";
echo "<tr>
          <th>Date </th>
          <th>% Assertiveness</th>
        </tr>\n";              
echo "<tr>\n";
$result = mysql_query($sql_rate_expected_real_per_month, $conn);
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
      echo "<TD>" . $row['RevenueAssertivenessRatio']      . "</TD>\n";
      echo "</TR>\n";

    }
  }
echo "</tr>\n";
echo "</table>\n";

echo "<HR>";

?>

