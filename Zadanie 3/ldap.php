<?php
require_once 'Page.php';


if(!empty($_POST['aislogin']) && !empty($_POST['isid']) && !empty($_POST['ispass']) && empty($_SESSION['logged']))
{

	$ldapuid = $_POST['isid'];
	$ldappass = $_POST['ispass'];

	$dn  = 'ou=People, DC=stuba, DC=sk';
	$ldaprdn  = "uid=$ldapuid, $dn";     

	$ldapconn = ldap_connect(LDAP_SERVER);


	$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	/*ldap_unbind($ldapconn);*/
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

	    	if($result->num_rows == 1)
	    	{
	    		$_SESSION['logged'] = 1;
	    		$userid = $data['id'];

	    		foreach ($data as $key => $value) {
	    			$_SESSION[$key] = $value;
	    		}
		        
		        add_message("","Prihlásenie pomocou AIS konta prebehlo úspešne.");

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

		                 	$userid = $mysqli->insert_id;

		                 	add_message("","Registrácia pomocou AIS konta prebehla úspešne, boli ste prihlásený.");
	                    }
	                }
					//
				}
	    	}
	    	if($inststm = $mysqli->prepare("INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `time`) VALUES (NULL, ?, 'LDAP', NOW());"))
	        {
	            $inststm->bind_param("i", $userid);

	            $inststm->execute();

	           // echo $userid;
	           // die();
		    		
    			header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
	        }
	    	//
	      //var_dump("ROW deleted");
	    }
	  }





    } else {
    	add_message("error","Prihlasovacie ID alebo heslo nie sú správne");
    	header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
    }

}

$page->setHeader("Prihlásenie používateľa do systému");

$page->renderHeader();

    echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Prihlásenie pomocou AIS STU</legend>
              <div class="row">

              '.create_field("isid","AIS Meno","").'
              '.create_field("ispass","Heslo","","password").'
              </div>
          </fieldset>

          <input type="submit" name="aislogin" value="Prihlásiť">

          ';

          echo'

        </form>
          ';


 $page->renderFooter();


?>