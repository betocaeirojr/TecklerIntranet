<?php
	include "../includes/header.php";
	require "conn.php";
	
	//echo $_GET['userid'];
	$user_id = $_GET['userid'];
?>
<?php


$sql_get_user_info = "select USER_ID as UserID, LOGIN as UserLogin, USER_NAME as UserName, EMAIL as UserEmail, ACTIVE as UserStatus, USER_CREATION_DATE as UserCreationDate ". 
		"from USER where USER_ID=$user_id"; 

$sql_get_user_profile_info = "select p.PROFILE_ID as ProfileID, p.PROFILE_CREATION_DATE as ProfileCreationDate, p.SIGNATURE as ProfileSignature, p.IS_RESTRICTED as ProfileRestricted " .  
		"from PROFILE p, USER_PROFILE up where (p.PROFILE_ID = up.PROFILE_ID) and (up.USER_ID = $user_id )";

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
                <h1>Relatórios - Seguindo e Seguidores</h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
             
             
             
             
             
             
             <h3>Informações do Usuário</h3>
             <!--Bordered Table-->
              <div class="grid grid_table">
              
              <div class="grid-content overflow">
               <table class="center_content full_width"> 
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>User Login</th>
                    <th>User Name</th>
                    <th>E-mail</th>
                    <th>Data de Criação</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
					$result = mysql_query($sql_get_user_info, $conn);
					if (mysql_num_rows($result) == 0) 
						{
							echo " <br>\n";
							echo " Opps.. Somethint went wrong. Contact you administrator! \n";
						} else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
							$user_id_list[] = (int)$row['UserID'];
											if ($row['UserStatus'] == 'a') {
												$TextStatus = "Ativo";
												 
											} elseif ($row['UserStatus']=='i'){
												$TextStatus = "Inativo";
												
											} elseif ($row['UserStatus']=='f'){
												$TextStatus = "Fraude";
												} 	
													
								echo "<TR>\n";
								echo "<TD> &nbsp" . $row['UserID'] . "</TD>\n"; 
								echo "<TD> &nbsp" . $row['UserLogin']. "</TD>\n";
								echo "<TD> &nbsp" . $row['UserName']. "</TD>\n";
								echo "<TD> &nbsp" . $row['UserEmail']. "</TD>\n";
								echo "<TD> &nbsp" . $row['UserCreationDate']. "</TD>\n";
								echo "<TD>\n";
								echo "<span class='s_green " . $row['UserStatus'] . "'>\n";
								echo "" . $TextStatus . "\n";
								echo "</span>\n";
								echo "</TD>\n";
								echo "</TR>\n";
							}
						}
				  ?>
                </tbody>
              </table>
              
              <div class="clearfix"></div>
              </div>
              </div>
              <!--Bordered Table END-->
                 <!--
                 <dl class="legenda">
                 	<dt>Legenda: </dt>
                    <dd><span class="s_green a">a</span> Ativo</dd>
                    <dd><span class="s_green f">f</span> Fraude</dd>
                    <dd><span class="s_green i">i</span> Inativo</dd>
                  </dl>
                  -->
                  
                  
                  
                  
                  
             
             
             
             
             <h3>Informações dos Perfis do Usuário</h3>
             <!--Bordered Table-->
              <div class="grid grid_table">
              <div class="grid-content overflow">
               <table class="center_content full_width"> 
                <thead>
                  <tr>
                    <th>ID do Perfil</th>
                    <th>Nome do Perfil</th>
                    <th>Data de Criação</th>
                    <th>Restrito?</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  	// ------------------------------------------------------
					// User Profile Information
					// ------------------------------------------------------
					$result = mysql_query($sql_get_user_profile_info, $conn);
					if (mysql_num_rows($result) == 0) 
							{
									echo " <br>\n";
									echo " Opps.. Somethint went wrong. Contact you administrator! \n";
							} else 
							{
									while ($row = mysql_fetch_array($result)) 
									{
								$profile_id_list[] = (int)$row['ProfileID'];
											if ($row['ProfileRestricted'] == 0) {
												$ProfStatus = "Não Restrito";
												 
											} elseif ($row['ProfileRestricted']==1){
												$ProfStatus = "Restrito";
												} 
												
											echo "<TR>\n";
											echo "<TD> &nbsp" . $row['ProfileID'] . "</TD>\n"; 
											echo "<TD> &nbsp" . $row['ProfileSignature']. "</TD>\n";
											echo "<TD> &nbsp" . $row['ProfileCreationDate']. "</TD>\n";
											echo "<TD>\n";
											echo "<span class='s_green " . $ProfStatus . "'>\n";
											echo "" . $ProfStatus . "\n";
											echo "</span>\n";
											echo "</TD>\n";
											echo "</TR>\n";
									}
							}
				  ?>
                </tbody>
              </table>
              
              <div class="clearfix"></div>
              </div>
              </div>
              <!--Bordered Table END-->
             <?php
?>



			
            
            
            
            
            
           
		<?php			
			
			// ------------------------------------------------------
			// User Tecks & Profile Information
			// ------------------------------------------------------
			echo "<h3>Informações dos Tecks por Perfil</h3>\n";
			
			//echo "<PRE>"; print_r($profile_id_list); echo $profile_listing; echo "</PRE>" ;
			//echo "<tr><th>Teck ID</th><th>Teck Title</th><th>Teck Creation Date</th><th>Teck Type</th><th>Teck Status</th><th>Is Teck Restricted </th><th>Teck Langugage</th><th>Teck Page Views </th></tr>\n";
			
			foreach($profile_id_list as $value){
					echo "<div class='grid grid_table'>\n";
					echo "<div class='grid-title'>\n";
					echo "<div class='pull-left'>\n";
					echo "<div class='icon-title'><i class='icon-eye-open'></i></div>\n";
					echo "<span>Exibindo todos os Tecks do perfil de ID $value!</span> \n";
					echo "<div class='clearfix'></div>\n";
					echo "</div>\n";
					echo "<div class='pull-right'> \n";
					echo "<div class='icon-title'><a href='#'><i class='icon-refresh'></i></a></div>\n";
					echo "<div class='icon-title'><a href='#'><i class='icon-cog'></i></a></div>\n";
					echo "</div>\n";
					echo "<div class='clearfix'></div>   \n";
					echo "</div>\n";
					echo "<div class='grid-content overflow'>\n";
					echo "<table class='table table-bordered table-mod-2 datatable_3 table-pv-teck' id=''> \n";
					echo "<thead> \n";
					echo "<tr> \n";
					echo "<th>ID</th> \n";
					echo "<th>Título do Teck</th> \n";
					echo "<th>Data de Criação</th> \n";
					echo "<th>Tipo</th> \n";
					echo "<th>Status</th> \n";
					echo "<th>Restrito</th> \n";
					echo "<th>Idioma</th> \n";
					echo "<th>Views</th> \n";
					echo "</tr> \n";
					echo "</thead> \n";
					echo "<tbody> \n";
				$sql_get_profile_tecks_info = "select p.POST_ID as TeckID, p.TITLE as TeckTitle, p.CREATION_DATE as TeckDate, p.TYPE as TeckType, p.STATUS_ID as TeckStatus, p.IS_RESTRICTED as IsTeckRestricted, p.LANGUAGE_CODE as TeckLang, p.PAGE_VIEWS as TeckPageviews from POST p where p.PROFILE_ID=$value order by p.CREATION_DATE DESC" ;
			
				$result = mysql_query($sql_get_profile_tecks_info, $conn);
				if (mysql_num_rows($result) == 0){
							echo " <br>\n";
							echo " Opps.. There are no TECKS under this PROFILE ID! \n";
					} else{
							while ($row = mysql_fetch_array($result)){
								$teck_id_list[] = (int)$row['TeckID'];
											if ($row['TeckStatus'] == 0) {
												$TeckpStatus = "Rascunho";
												 
											} elseif ($row['TeckStatus']==1){
												$TeckpStatus = "Publicado";
												} 
								$teck_id_list[] = (int)$row['TeckID'];
											if ($row['IsTeckRestricted'] == 0) {
												$TeckRest = "Não Restrito";
												 
											} elseif ($row['IsTeckRestricted']==1){
												$TeckRest = "Restrito";
												} 
												
								
						echo "<TR>\n";
						$teckid = $row['TeckID'];
						echo "<TD><a href='show_teck_id_content.php?teckid=$teckid'>" . $row['TeckID'] . "</a></TD>\n";
						echo "<TD>" . substr($row['TeckTitle'],0 , 50). "</TD>\n";
						echo "<TD>" . $row['TeckDate']. "&nbsp</TD>\n";
						echo "<TD class='t_center'> \n";
						echo "<span class='t_center " .$row['TeckType']. "'></span> \n";
						echo "</TD> \n";
						echo "<TD class='t_center'>" . $TeckpStatus . "</TD>\n";
						echo "<TD class='t_center'>" . $TeckRest . "</TD>\n";
						echo "<TD class='t_center'>" . $row['TeckLang']. "</TD>\n";
						echo "<TD class='t_center'>" . $row['TeckPageviews']. "</TD>\n";
						echo "</TR>\n";
							}
					}
				echo "</tbody> \n";
				echo "</table>  \n"; 
				echo "<div class='clearfix'></div> \n";
				echo "</div> \n";
				echo "</div> \n";
			}
			echo "Legend:<BR> Restricted: 0 - Not Restricted, 1 - Restricted<BR> Status: 1 - Created, 2 - Draft <BR>  \n";
			echo "<HR>";
			 ?>
             
           
              
              
              
             
             
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

