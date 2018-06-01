<?php
require_once 'Page.php';


var_dump($_SESSION);
// && empty($_SESSION['logged'])
if(!empty($_POST['reguser']) && !empty($_POST['login']) && !empty($_POST['pass']) && !empty($_POST['name']) && !empty($_POST['surname']))
{
	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Login` = ?")) {

		$sel->bind_param("s", $_POST['login']);
		if($sel->execute())
		{

		   	$result = $sel->get_result();
		    $data = $result->fetch_assoc();

		    if($result->num_rows == 0)
		    {
		    	if($inststm = $mysqli->prepare("INSERT INTO `users` (`id`, `Name`, `Surname`, `Login`, `Password`, `LDAPLogin`) VALUES (NULL, ?, ?, ?, ?, NULL);"))
		        {
		            $inststm->bind_param("ssss", $_POST['name'], $_POST['surname'],$_POST['login'],hash('sha256', $_POST['pass']));

		            if($inststm->execute())
		            {
		            		if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Login` = ? AND `users`.`Password` = ?")) 
		            		{

								$sel->bind_param("ss", $_POST['login'],hash('sha256', $_POST['pass']));
								if($sel->execute())
								{

								   	$result = $sel->get_result();
								    $data = $result->fetch_assoc();

								    if($result->num_rows == 1)
								    {
								    	$_SESSION['logged'] = 1;
							    		foreach ($data as $key => $value) {
							    			$_SESSION[$key] = $value;
							    		}


										
							    		add_message("","Registrácia prebehla úspečne, boli ste prihlásený.");
					    				header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
								    }
								}
							}
		            }
		         }

		    } else {
		    	add_message("error","Zadaný účet už existuje.");
				header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
		    }
		}
	}
}
/*

if(!empty($_POST['aislogin']) && !empty($_POST['isid']) && !empty($_POST['ispass']) && empty($_SESSION['logged']))
{


	$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
	

	try {
    	//$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
	} catch (Exception $e) {
	    echo 'Caught exception: ',  $e->getMessage(), "\n";
	}

    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...";

	  if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`LDAPLogin` = ?")) {

	    $sel->bind_param("s", $ldapuid);
	    if($sel->execute())
	    {
	    	$result = $sel->get_result();
	    	$data = $result->fetch_assoc();
	    	$userid = 0;

	    	if(!empty($data))
	    	{
	    		var_dump($data);
	    		echo "login";
	    		$_SESSION['logged'] = 1;
	    		$userid = $data['id'];

	    		foreach ($data as $key => $value) {
	    			$_SESSION[$key] = $value;
	    		}

	    	} else {
	    		$sr = ldap_search($ldapconn, $ldaprdn, "uid=$ldapuid");
				$entry = ldap_first_entry($ldapconn, $sr);
	    		$usrName = ldap_get_values($ldapconn, $entry, "givenname")[0];
				$usrSurname = ldap_get_values($ldapconn, $entry, "sn")[0];
				if(!empty($usrName) && !empty($usrSurname))
				{
					if($inststm = $mysqli->prepare("INSERT INTO `users` (`id`, `Name`, `Surname`, `Login`, `Password`, `LDAPLogin`) VALUES (NULL, ?, ?, NULL, NULL, ?);"))
	                {
	                    $inststm->bind_param("sss", $usrName,$usrSurname,strtolower($_POST['isid']));

	                    if($inststm->execute())
	                    {
	                    	$_SESSION['logged'] = 1;
	                    	$_SESSION['Name'] = $usrName;
	                    	$_SESSION['Surname'] = $usrSurname;
	                    	$_SESSION['LDAPLogin'] = strtolower($_POST['isid']);
		                 	echo "Registered";
		                 	$userid = $mysqli->insert_id;
	                    }
	                }
					//
				}
	    	}
	    	if($inststm = $mysqli->prepare("INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `time`) VALUES (NULL, ?, 'LDAP', NOW());"))
	        {
	            $inststm->bind_param("i", $userid);

	            $inststm->execute();
	        }
	    	//
	      //var_dump("ROW deleted");
	    }
	  }





    } else {
        echo "LDAP bind failed...";
    }

}

*/

$page->setHeader("Registrácia používateľského účtu");

$page->renderHeader();


    echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Registrácia do systému</legend>
              <div class="row">

              '.create_field("login","Login","").'
              '.create_field("pass","Heslo","","password").'
              </div>
              <div class="row">

              '.create_field("name","Meno","").'
              '.create_field("surname","Priezvisko","").'
              </div>
          </fieldset>

          <input type="submit" name="reguser" value="Registrovať">

          ';

          echo'

        </form>
          ';


 $page->renderFooter();

?>