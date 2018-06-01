<?php
require_once 'Page.php';
require_once 'oauth.php';

if(!empty($_POST['normlogin']) && !empty($_POST['login']) && !empty($_POST['pass']))
{
	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Login` = ? AND `users`.`Password` = ?")) {

		$sel->bind_param("ss", $_POST['login'],hash('sha256', $_POST['pass']));
		if($sel->execute())
		{

		   	$result = $sel->get_result();
		    $data = $result->fetch_assoc();

		    if($result->num_rows == 1)
		    {
		    	if($inststm = $mysqli->prepare("INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `time`) VALUES (NULL, ?, 'Registration', NOW());"))
		        {
		            $inststm->bind_param("i", $data['id']);

		            $inststm->execute();

		    		echo "login";
		            $_SESSION['logged'] = 1;
		    		foreach ($data as $key => $value) {
		    			$_SESSION[$key] = $value;
		    		}
		        }

		    	
		    } else {

		    	echo "zle meno alebo heslo";
		    }
		}
	}
}

if(empty($_SESSION['logged']))
	$page->setHeader("Prihlásenie používateľa do systému");
else 
	$page->setHeader("Minulé prihlásenia");

$page->renderHeader();

if(empty($_SESSION['logged']))
{

    echo '
    	<div class="tab">
		  <button class="tablinks" id="defTab" onclick="openTab(event, \'RegLogin\')">Regulárne prihlásenie</button>
		  <button class="tablinks" onclick="openTab(event, \'RegUser\')">Registrácia</button>
		  <button class="tablinks" onclick="openTab(event, \'LDAPLogin\')">AIS Stuba.sk</button>
		  <button class="tablinks" onclick="openTab(event, \'GoogleLogin\')">Google Login</button>

		</div>
		';

		display_errors();


		echo'
    	<div id="RegLogin" class="tabcontent">

	        <form method="post" action="login.php" class="form-style text-center">
	          <fieldset>
	            <legend>Prihlásenie pomocou registrovaného účtu</legend>
	              <div class="row">

	              '.create_field("login","Login","","text",true,"n").'
	              '.create_field("pass","Heslo","","password",true,"n").'
	              </div>
	          </fieldset>

	          <input type="submit" name="normlogin" value="Prihlásiť">
	          
	        </form>
	    </div>
	    ';

	    echo '
	    <div id="RegUser" class="tabcontent">

	        <form method="post" action="register.php" class="form-style text-center">
	          <fieldset>
	            <legend>Registrácia do systému</legend>
	              <div class="row">

	              '.create_field("login","Login","","text",true,"r").'
	              '.create_field("pass","Heslo","","password",true,"r").'
	              </div>
	              <div class="row">

	              '.create_field("name","Meno","","text",true,"r").'
	              '.create_field("surname","Priezvisko","","text",true,"r").'
	              </div>
	          </fieldset>

	          <input type="submit" name="reguser" value="Registrovať">

	          ';

	          echo'

	        </form>
	    </div>
	          ';

 echo '
     	<div id="LDAPLogin" class="tabcontent">

        <form method="post" action="ldap.php" class="form-style text-center">
          <fieldset>
            <legend>Prihlásenie pomocou AIS STU</legend>
              <div class="row">

              '.create_field("isid","AIS Meno","","text",true,"l").'
              '.create_field("ispass","Heslo","","password",true,"l").'
              </div>
          </fieldset>

          <input type="submit" name="aislogin" value="Prihlásiť">

          ';

          echo'

        </form>		</div>

          ';

$authUrl = $client->createAuthUrl();
 echo '
     	<div id="GoogleLogin" class="tabcontent">

        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Prihlásenie Google účtu</legend>
              <div class="row">
              <a href="'.$authUrl.'"><img src="./style/google.png" alt="Login with Google"></a>
              </div>
          </fieldset>

          ';

          echo'

        </form>		</div>

          ';


} else {

	display_errors();


	echo '
	<table id="logins" class="tablesorter"> 

	';
	if($sel = $mysqli->prepare("SELECT COUNT(*) as 'pocet', login_type as 'type'  FROM `login_history` GROUP BY `login_history`.`login_type`"))
	{
		if($sel->execute())
		{

		   	$result = $sel->get_result();
		    $header = array();
		    $tdata = array();
		    $idx = 0;


		    if($result->num_rows > 0)
		    {
		    	while($row = $result->fetch_array()) {
		    		$header[] = $row["type"];
		    		$tdata[] = $row["pocet"];
			    }
				echo '
			<thead> 
			<tr >
				<th colspan="'.$result->num_rows.'">Celkový počet prihlásení do aplikácie, rozdelených podľa typu</th>
			</tr>
			<tr> 
				';
			    foreach ($header as $key => $value) {
			    	echo '<th>'.$value.'</th> ';
			    }
			    echo '
			 </tr> 
			</thead> ';

				echo '
			<tbody> 
			<tr> 
				';
			    foreach ($tdata as $key => $value) {
			    	echo '<td>'.$value.'</td> ';
			    }
			    echo '			</tr> 
			</tbody> ';
		    }
		}
	}
	echo '
			</table>
			<br><br>
	';


	echo '
	<table id="myTable" class="tablesorter"> 
			<thead> 
			<tr >
				<th colspan="2">Zoznam prihlásení do Vásho účtu</th>
			</tr>
			<tr> 
          		<th>Spôsob prihlásenia</th>  
          		<th>Čas prihlásenia</th> 
			</tr> 
			</thead> 
			<tbody> 
	';
	if($sel = $mysqli->prepare("SELECT * FROM `login_history` WHERE `login_history`.`user_id` = ? ORDER BY `login_history`.`id` DESC"))
	{

		$sel->bind_param("i", $_SESSION['id']);
		if($sel->execute())
		{

		   	$result = $sel->get_result();


		    if($result->num_rows > 0)
		    {
		    	while($row = $result->fetch_array()) {
			        echo "
			        	<tr> 
						    <td>".$row["login_type"]."</td>
						    <td>".$row["time"]."</td>
						</tr> 
			        ";
			    }
		    }
		}
	}
	echo '

			</tbody> 
			</table> 
	';

}

 $page->renderFooter();

?>