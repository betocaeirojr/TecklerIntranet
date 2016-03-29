<?php
	include "../includes/header.php";
  date_default_timezone_set('America/Sao_Paulo');

  // Including Class Files
  require_once "Connection.php";
  require_once "Tecks.php";
  require_once "Users.php";
  require_once "Audience.php";






  $Connection     = new Connection();
  $TecksInfo      = new Tecks($Connection);
  $UsersInfo      = new Users($Connection);
  $PageviewsInfo  = new Audience($Connection);

  $yesterday               = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
  $ReferenceDate          = date('Y-m-d',$yesterday);
  $NewTecksDay            = $TecksInfo->getDashboardMetric_NewTecksDay(); 
  $NewTecksMonth          = $TecksInfo->getDashboardMetric_NewTecksMonth(); 

  $NewUsersDay                = $UsersInfo->getDashboardMetric_NewUsersDay();
  $NewProfilesDay             = $UsersInfo->getDashboardMetric_NewProfilesDay();
  $NewProfilesMonth           = $UsersInfo->getDashboardMetric_NewProfilesMonth() ;
  $ActiveProfiles             = $UsersInfo->getDashboardMetric_ActiveProfiles();
  $TotalVisitorsDay           = $UsersInfo->getDashboardMetric_TotalVisitorsDay();
  $TotalVisitorsNewUsersDay   = $UsersInfo->getDashboardMetric_TotalVisitorsDay();
  $NewUsersConversionRate     = round($NewUsersDay / $TotalVisitorsNewUsersDay,4)*100; 
  $LoggedUsersDay             = $UsersInfo->getDashboardMetric_LoggedUsersDay();
  //$PercLoggedPerTotalVisitors = round($LoggedUsersDay / $TotalVisitorsDay,4) * 100;
  $PercLoggedPerTotalVisitors = $UsersInfo->getDashboardMetric_PercLoggedPerTotalVisitorsDay();
  $BounceRateNewUser      = $UsersInfo->getDashboardMetric_NewUserBounceRateDay();

  $AveragePageviewsInTecks_Day    = $PageviewsInfo->getDashboardMetric_AvgPageviewsTecks_Day();
  $AveragePageviewsInTecks_Month  = $PageviewsInfo->getDashboardMetric_AvgPageviewsTecks_Month();
  //$AveragePageviewsInTecks_Accum  = $PageviewsInfo->getDashboardMetric_AvgPageviewsTecks_Accumulated();
  $AveragePageviewsInTecks_Accum  = 0;
  $AgingPageviews                 = $PageviewsInfo->getDashboardMetric_AgingPageviews();

  $AveragePageviewsInTecks_CG_Day   = $PageviewsInfo->getDashboardMetric_AvgPageviewsTecks_CG_Day(); 
  //$AveragePageviewsInTecks_CG_Week  = $PageviewsInfo->getDashboardMetric_AvgPageviewsTecks_CG_Week();
  $AveragePageviewsInTecks_CG_Month = $PageviewsInfo->getDashboardMetric_AvgPageviewsTecks_CG_Month();


  $NewVsRet_Bounce_PagesPerVisit_FromGA_Day   = $PageviewsInfo->getDashboardMetric_NewVsRet_PPVisit_Bounce_Day();
  $TotalVisitors_At_Day             = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['TotalVisitorsAtDay'];
  $New_Visitors_Perc_At_Day         = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['PercentNewVisitorsAtDay'];
  $Returning_Visitors_Perc_At_Day   = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['PercentRetVisitorsAtDay'];
  $PercOverallBounceRate_At_Day     = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['PercentBounceAtDay'];
  $NumPagesPerVisit_At_Day          = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['NumPagesPerVisitAtDay'];
  $TotalPagesVisited_At_Day         = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['TotalPagesVisitsAtDay'];
  $lastDayAvailableFromGAIs         = $NewVsRet_Bounce_PagesPerVisit_FromGA_Day['ReferenceDate'];

  $DaysSinceLastVisit_FromGA_Day              = $PageviewsInfo->getDashboardMetric_Recency();
  $VisitsCount_FromGA_Day                     = $PageviewsInfo->getDashboardMetric_Frequency_Last31Days(); 
  $VisitorsPerTrafficSource_FromGA_Day        = $PageviewsInfo->getDashboardMetric_VisitorsPerTrafficSource();
  $PageviewsPerTrafficSource_FromGA_Day       = $PageviewsInfo->getDashboardMetric_PageviewsPerTrafficSource();

  $ContactsImportedPerDay = $UsersInfo->getDashboardMetric_ContactsImportedDay();
  $InvitationsInfoPerDay  = $UsersInfo->getDashboardMetric_InvitationsSentDay();
  $InvitationsInfo_Sent_Per_Day     = $InvitationsInfoPerDay['InvitationsSentDay'];
  $InvitationsInfo_Read_Per_Day     = $InvitationsInfoPerDay['InvitationsReadDay'];
  $InvitationsInfo_Clicked_Per_Day  = $InvitationsInfoPerDay['InvitationsClickedDay'];
  



?>
  
    <div id="wrap">
    
    	 <!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
            <?php include "../includes/main_menu.php"; ?>
            <?php include "../includes/submenu_stats.php"; ?>
            <div class="clearfix"></div>
        </div>
        <!--SIDEBAR END-->

        <!-- ########################################## -->

        <!-- BEGIN MAIN CONTENT -->
        <div id="main" role="main">
          <div class="block">
       		  <div class="clearfix"></div>
            
            <h1> Metrics Dashboard</h1>
            <p> Base Date for Metrics refers to: <BR>
              <li> Teckler Core Metrics (Tecks, Users, etc) - <?php echo $ReferenceDate;?></li> 
              <li> Google Analytics Metrics (Pageviews, Visitors, Frequncy, Recency, etc) - <?php echo $lastDayAvailableFromGAIs;?> </li>
              <li> Combined Metrics are calculed using the oldest data, in this case <?php echo ( $lastDayAvailableFromGAIs < $ReferenceDate ? $lastDayAvailableFromGAIs : $ReferenceDate) ;?> <li>
            </p>
            <hr>

            <!--page title-->
            <div class="pagetitle">
                <h2>Tecks</h2> 
                <div class="clearfix"></div>
            </div>
            <!--page title end-->
             
            <div class="row-fluid clearfix">

                  <!--Informatin BOX 3-->
                    <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                            <div class="box-info">
                              <img src="../images/icon/stats_1.png" alt=""> 
                              <div class="box-figures">New Tecks Per Day </div>
                              <div class="box-title"> <?php echo $NewTecksDay . "<BR>(as of " . $ReferenceDate .  ")" ;?></div>
                            </div>
                        </div>
                    </div>
                  <!--Informatin BOX 3 END-->
    
                  <!--Informatin BOX 4-->
                    <div class="information-box-3 span6 box_tecks">
                          <div class="item">
                            <div class="box-info">
                              <img src="../images/icon/stats_1.png" alt="">
                              <div class="box-figures">New Tecks Per Month</div>
                              <div class="box-title"><?php echo $NewTecksMonth . "<BR>(as of " . date("Y-M", $yesterday) .  ")";?>
                              </div>
                            </div>
                          </div>
                    </div>
                  <!--Informatin BOX 4 END-->
            </div>
          
            <!-- ------------------------------------------------------------------------------------------------ -->

            <hr>
              <div class="pagetitle">
                <h2>Profiles/Users</h2> 
                <div class="clearfix"></div>
             </div>      
              
            <div class="row-fluid clearfix">

               <!--Informatin BOX 1 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">New Profiles per Day</div>
                            <div class="box-title"><?php echo $NewProfilesDay ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 1 END-->

               <!--Informatin BOX 2 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">New Profiles Per Month</div>
                            <div class="box-title"><?php echo $NewProfilesMonth ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 2 END-->

               <!--Informatin BOX 3 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Active Profiles</div>
                            <div class="box-title"><?php echo $ActiveProfiles ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 3 END-->
            </div>
              
            <div class="row-fluid clearfix">
               <!--Informatin BOX 4 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Bounce Rate - New User</div>
                            <div class="box-title"><?php echo $BounceRateNewUser . " %  " ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 4 END-->

               <!--Informatin BOX 5 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Users Conversion Rate</div>
                            <div class="box-title"><?php echo $NewUsersConversionRate . " %";?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 5 END-->

               <!--Informatin BOX 6 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> % Logged Users vs Total Visitors</div>
                            <div class="box-title"><?php echo $PercLoggedPerTotalVisitors['PercLoggedTotalVisitors'] . " %" ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 6 END-->
				    </div>

            <div class="row-fluid clearfix">
               <!--Informatin BOX 7 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Imported Contacts</div>
                            <div class="box-title"><?php echo $ContactsImportedPerDay  ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 7 END-->

               <!--Informatin BOX 8 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Invitations Sent Per Email</div>
                            <div class="box-title"><?php echo $InvitationsInfo_Sent_Per_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 8 END-->

               <!--Informatin BOX 9 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Invitations Read</div>
                            <div class="box-title"><?php echo $InvitationsInfo_Read_Per_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 9 END-->
               <!--Informatin BOX 10 -->
                  <div class="information-box-3 span3 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Invitations Actually Clicked</div>
                            <div class="box-title"><?php echo $InvitationsInfo_Clicked_Per_Day  ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 10 END-->
            </div>            


            <hr>
            <div class="pagetitle">
                <h2>Pageviews</h2> 
                <div class="clearfix"></div>
            </div>      
              
            <div class="row-fluid clearfix">

               <!--Informatin BOX 1 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Average Pageviews Per Teck - Day</div>
                            <div class="box-title"><?php echo $AveragePageviewsInTecks_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 1 END-->

               <!--Informatin BOX 2 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Average Pageviews Per Teck - Month <br></div>
                            <div class="box-title"><?php echo $AveragePageviewsInTecks_Month ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 2 END-->

               <!--Informatin BOX 3 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Average Pageviews Per Teck - Accumulated</div>
                            <div class="box-title"><?php echo $AveragePageviewsInTecks_Accum ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 3 END-->
            </div>

            <div class="row-fluid clearfix">
               <!--Informatin BOX 4 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Number of Pageviews in the Same Day</div>
                            <div class="box-title"><?php echo $AgingPageviews['P1'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 4 END-->

               <!--Informatin BOX 5 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Decai Rate - D1 vs D2-6</div>
                            <div class="box-title"><?php echo $AgingPageviews['Decai_P2_P1'];?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 5 END-->

              <!--Informatin BOX 6 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Decai Rate - D2-6 vs D7-14</div>
                            <div class="box-title"><?php echo $AgingPageviews['Decai_P3_P2'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 6 END-->
            </div>
              
            <div class="row-fluid clearfix">
              <!--Informatin BOX 7 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Decai Rate - D7-14 vs D15-30</div>
                            <div class="box-title"><?php echo $AgingPageviews['Decai_P4_P3'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 7 END-->
              <!--Informatin BOX 8 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Decai Rate - D15-30 vs D31-60</div>
                            <div class="box-title"><?php echo $AgingPageviews['Decai_P5_P4'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 8 END-->
              <!--Informatin BOX 9 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Decai Rate - D31-60 vs D61-90</div>
                            <div class="box-title"><?php echo $AgingPageviews['Decai_P6_P5'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 8 END-->
            </div>

            <div class="row-fluid clearfix">
              <!--Informatin BOX 7 -->
                  <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Average Views Per Teck - Day<br>(Total Views / Total Tecks)</div>
                            <div class="box-title"><?php echo $AveragePageviewsInTecks_CG_Day[0]['AverageViewsPerTeck'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 7 END-->
              <!--Informatin BOX 8 -->
                  <div class="information-box-3 span6 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Average Views Per Teck - Month <BR>(Total Views Month) / Total Tecks</div>
                            <div class="box-title"><?php echo $AveragePageviewsInTecks_CG_Month[0]['AverageViewsPerTeck'] ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 8 END-->

            </div>




<!-- ------------------------------------------------------------------------------------------------ -->
          

            <hr>
            <div class="pagetitle">
                <h2>Audience</h2> 
                <div class="clearfix"></div>
            </div>      
              
            <div class="row-fluid clearfix">

               <!--Informatin BOX 1 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Total Visitors at Day</div>
                            <div class="box-title"><?php echo $TotalVisitors_At_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 1 END-->

               <!--Informatin BOX 2 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Percent New Visitors <br></div>
                            <div class="box-title"><?php echo $New_Visitors_Perc_At_Day  ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 2 END-->

               <!--Informatin BOX 3 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Percent Returning Visitors</div>
                            <div class="box-title"><?php echo $Returning_Visitors_Perc_At_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 3 END-->
            </div>

            <div class="row-fluid clearfix">
               <!--Informatin BOX 4 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Number of Pages Visited At Day</div>
                            <div class="box-title"><?php echo $TotalPagesVisited_At_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 4 END-->

               <!--Informatin BOX 5 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures">Average Pages Visited per Visit</div>
                            <div class="box-title"><?php echo $NumPagesPerVisit_At_Day;?>
                            </div>
                          </div>
                        </div>
                  </div>
               <!--Informatin BOX 5 END-->

              <!--Informatin BOX 6 -->
                  <div class="information-box-3 span4 box_tecks">
                        <div class="item">
                          <div class="box-info">
                            <img src="../images/icon/stats_1.png" alt="">
                            <div class="box-figures"> Overall Bounce Rate Percentage</div>
                            <div class="box-title"><?php echo $PercOverallBounceRate_At_Day ;?>
                            </div>
                          </div>
                        </div>
                  </div>
              <!--Informatin BOX 6 END-->
            </div>
              
            <div class="row-fluid clearfix">
            

              <div class="row-fluid">
                
                <!--Striped table 1 -->
                  <div class="grid span6 grid_table">
                        <div class="grid-title">
                           <div class="pull-left">
                              <div class="icon-title"><i class="icon-eye-open"></i></div>
                              <span><?php echo "Recency" ;?></span> 
                              <div class="clearfix"></div>
                           </div>
                          
                          <div class="clearfix"></div>   
                        </div>
                        <div class="grid-content tabela_scroll">
                            <table class="table table-striped" id="exemplo">
                              <TR> 
                                <th align=center> Days Since Last Visit</th>
                                <th align=center> Visitors </th>
                                <th align=center> Pageviews </th>
                              </TR>
                              <?php 
                              asort($DaysSinceLastVisit_FromGA_Day);
                              //echo "<TR>";
                              //echo "<PRE>";
                              //print_r($VisitsCount_FromGA_Day);
                              //echo "</PRE>";
                              foreach ($DaysSinceLastVisit_FromGA_Day as $key => $value) {
                                  echo "<tr>";
                                  echo "<td>" . $value['DaysSinceLastVisit'] . "</td>";
                                  echo "<td>" . $value['Visitors'] . "</td>";
                                  echo "<td>" . $value['Pageviews'] . "</td>";
                                  echo "</tr>";
                              }
                              ?>
                          </table>                          
                          <div class="clearfix"></div>
                        </div>
                  </div>
                <!--Striped table 1 END-->

                <!--Striped table 2 -->
                  <div class="grid span6 grid_table">
                    <div class="grid-title">
                           <div class="pull-left">
                              <div class="icon-title"><i class="icon-eye-open"></i></div>
                              <span><?php echo "Frequency" ;?></span> 
                              <div class="clearfix"></div>
                           </div>
                      <div class="clearfix"></div>   
                    </div>
                    <div class="grid-content tabela_scroll">
                        <table class="table table-striped" id="exemplo">
                              <TR> 
                                <th align=center> Number of Visits</th>
                                <th align=center> Visitors </th>
                                <th align=center> Pageviews </th>
                              </TR>
                              <?php 
                              asort($VisitsCount_FromGA_Day);
                              //echo "<TR>";
                              //echo "<PRE>";
                              //print_r($VisitsCount_FromGA_Day);
                              //echo "</PRE>";
                              foreach ($VisitsCount_FromGA_Day as $key => $value) {
                                  echo "<tr>";
                                  echo "<td>" . $value['VisitsCount'] . "</td>";
                                  echo "<td>" . $value['Visitors'] . "</td>";
                                  echo "<td>" . $value['Pageviews'] . "</td>";
                                  echo "</tr>";
                              }
                              ?>
                        </table>                          
                        <div class="clearfix"></div>
                    </div>
                  </div>
                <!--Striped table 2 END-->                
              </div>
            </div>

            <div class="row-fluid clearfix">
            

              <div class="row-fluid">
                
                <!--Striped table 1 -->
                  <div class="grid span6 grid_table">
                        <div class="grid-title">
                           <div class="pull-left">
                              <div class="icon-title"><i class="icon-eye-open"></i></div>
                              <span><?php echo "Traffic Origin Segmentation - Visitor" ;?></span> 
                              <div class="clearfix"></div>
                           </div>
                          
                          <div class="clearfix"></div>   
                        </div>
                        <div class="grid-content tabela_scroll">
                            <table class="table table-striped" id="exemplo">
                              <TR> 
                                <th align=center> Date </th>
                                <th align=center> Direct (%)</th>
                                <th align=center> Organic (%)</th>
                                <th align=center> Referral (%)</th>
                                <th align=center> Not Set (%)</th>
                              </TR>
                              <?php 
                               arsort($VisitorsPerTrafficSource_FromGA_Day);
                              //echo "<TR>";
                              //echo "<PRE>";
                              //print_r($VisitsCount_FromGA_Day);
                              //echo "</PRE>";
                              foreach ($VisitorsPerTrafficSource_FromGA_Day as $key => $value) {
                                  echo "<tr>";
                                  $totalVisitors =  $value['VisitorsDirect'] + $value['VisitorsOrganic'] +
                                                    $value['VisitorsReferral'] + $value['VisitorsNotSet'];

                                  echo "<td>" . $value['RefDate'] .  "</td>" ;
                                  echo "<td>" . round($value['VisitorsDirect'] / $totalVisitors , 4) * 100   . " </td>";
                                  echo "<td>" . round($value['VisitorsOrganic']  / $totalVisitors,4) * 100   . " </td>";
                                  echo "<td>" . round($value['VisitorsReferral'] / $totalVisitors,4) * 100   . " </td>";
                                  echo "<td>" . round($value['VisitorsNotSet'] / $totalVisitors,4) * 100     . " </td>";
                                  echo "</tr>";
                              }
                              ?>
                          </table>                          
                          <div class="clearfix"></div>
                        </div>
                  </div>
                <!--Striped table 1 END-->               
                <!--Striped table 2 -->
                  <div class="grid span6 grid_table">
                        <div class="grid-title">
                           <div class="pull-left">
                              <div class="icon-title"><i class="icon-eye-open"></i></div>
                              <span><?php echo "Traffic Origin Segmentation - Pageviews" ;?></span> 
                              <div class="clearfix"></div>
                           </div>
                          
                          <div class="clearfix"></div>   
                        </div>
                        <div class="grid-content tabela_scroll">
                            <table class="table table-striped" id="exemplo">
                              <TR> 
                                <th align=center> Date </th>
                                <th align=center> Direct (%)</th>
                                <th align=center> Organic (%)</th>
                                <th align=center> Referral (%)</th>
                                <th align=center> Not Set (%)</th>
                              </TR>
                              <?php 
                               arsort($PageviewsPerTrafficSource_FromGA_Day);
                              //echo "<TR>";
                              //echo "<PRE>";
                              //print_r($PageviewsPerTrafficSource_FromGA_Day);
                              //echo "</PRE>";
                              foreach ($PageviewsPerTrafficSource_FromGA_Day as $key => $value) {
                                  echo "<tr>";
                                  $totalPageviews =  $value['PageviewsDirect'] + $value['PageviewsOrganic'] +
                                                    $value['PageviewsReferral'] + $value['PageviewsNotSet'];
                                  echo "<td>" . $value['RefDate'] .  "</td>" ;
                                  echo "<td>" . round($value['PageviewsDirect']   / $totalPageviews, 4) * 100   . " </td>";
                                  echo "<td>" . round($value['PageviewsOrganic']  / $totalPageviews, 4) * 100   . " </td>";
                                  echo "<td>" . round($value['PageviewsReferral'] / $totalPageviews, 4) * 100   . " </td>";
                                  echo "<td>" . round($value['PageviewsNotSet']   / $totalPageviews, 4) * 100     . " </td>";
                                  echo "</tr>";
                              }                              
                              ?>
                          </table>                          
                          <div class="clearfix"></div>
                        </div>
                  </div>
                <!--Striped table 1 END-->   
              </div>
            </div>


<!-- -------------------------------------------------------------------------------------------------- -->










              
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

