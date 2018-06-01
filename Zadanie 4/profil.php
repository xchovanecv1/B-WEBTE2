<?php
require_once 'Page.php';
require_once 'oauth.php';

if(empty($_SESSION['logged'])) die("neni prihlaseny, spravit error");

if(!empty($_POST['addreguser']) && !empty($_POST['login']) && !empty($_POST['pass']))
{
	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Login` = ?")) {

		$sel->bind_param("s", $_POST['login']);
		if($sel->execute())
		{

		   	$result = $sel->get_result();
		    $data = $result->fetch_assoc();
		    echo "test";
		    if($result->num_rows == 0)
		    {
		    	if($inststm = $mysqli->prepare("UPDATE `users` SET `Login` = ?, `Password` = ? WHERE `id` = ?;"))
		        {
		            $inststm->bind_param("ssi",$_POST['login'],hash('sha256', $_POST['pass']),$_SESSION['id']);

		            if($inststm->execute())
		            {
		            	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Login` = ? AND `users`.`Password` = ?")) {

		            		$sel->bind_param("ss", $_POST['login'],hash('sha256', $_POST['pass']));
		            		if($sel->execute())
							{

							   	$result = $sel->get_result();
							    $data = $result->fetch_assoc();

							    if($result->num_rows == 1)
							    {
		        					add_message("","Regulárny účet bol úspešne pridaný.");

							    	$_SESSION['logged'] = 1;
						    		foreach ($data as $key => $value) {
						    			$_SESSION[$key] = $value;
						    		}
							    }
		            		}
		            	}
		            }
		         }

		    } else {
		        add_message("error","Regulárny účet s týmto prihlasovacím menom už existuje.");
		    }
		}
	}
}

if(!empty($_POST['changeacc']) && !empty($_POST['chpass']))
{
	
	if($inststm = $mysqli->prepare("UPDATE `users` SET `Password` = ? WHERE `users`.`id` = ?;"))
	{
		$inststm->bind_param("si",hash('sha256', $_POST['chpass']),$_SESSION['id']);

		if($inststm->execute())
		{
			$_SESSION['Password'] = hash('sha256', $_POST['chpass']);
		    add_message("","Prihlasovacie heslo bolo úspešne zmenené.");
		}
	}
}

if(!empty($_POST['changepersonal']) && !empty($_POST['name']) && !empty($_POST['surname']))
{
	
	if($inststm = $mysqli->prepare("UPDATE `users` SET `Name` = ?, `Surname` = ? WHERE `users`.`id` = ?;"))
	{
		$inststm->bind_param("ssi",$_POST['name'],$_POST['surname'],$_SESSION['id']);

		if($inststm->execute())
		{
			$_SESSION['Name'] = $_POST['name'];
			$_SESSION['Surname'] = $_POST['surname'];
		    add_message("","Osobné údaje boli úspešne zmenené.");
		}
	}
}


if(!empty($_POST['addaislogin']) && !empty($_POST['isid']) && !empty($_POST['ispass']))
{

	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`LDAPLogin` = ?")) 
	{

		$sel->bind_param("s", $_POST['isid']);
		if($sel->execute())
		{

			$result = $sel->get_result();
			$data = $result->fetch_assoc();

			if($result->num_rows == 0)
			{
				$ldapuid = $_POST['isid'];
				$ldappass = $_POST['ispass'];

				$dn  = 'ou=People, DC=stuba, DC=sk';
				$ldaprdn  = "uid=$ldapuid, $dn";     

				$ldapconn = ldap_connect(LDAP_SERVER);


				$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
				/*ldap_unbind($ldapconn);*/
				$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
				

			    // verify binding
			    if ($ldapbind) {
					if($inststm = $mysqli->prepare("UPDATE `users` SET `LDAPLogin` = ? WHERE `users`.`id` = ?;"))
				    {
				        $inststm->bind_param("si",strtolower($_POST['isid']), $_SESSION['id']);

				        if($inststm->execute())
				        {
				            $_SESSION['LDAPLogin'] = strtolower($_POST['isid']);
		    				add_message("","Prihlasovanie pomocou AIS ID bolo pridané.");
				        }
				    }			//

			    } else {
		    		add_message("error","Nesprávne prihlasovacie údaje do AIS.");
			    }
			} else {
		    	add_message("error","Tento AIS účet je priradený k inému regulárnemu účtu.");
			}
		}
	}
}

if(!empty($_POST['aisdelete']))
{
	if($inststm = $mysqli->prepare("UPDATE `users` SET `LDAPLogin` = NULL WHERE `users`.`id` = ?;"))
	{
		$inststm->bind_param("i",$_SESSION['id']);

		if($inststm->execute())
		{
			$_SESSION['LDAPLogin'] = NULL;
		    add_message("","Spojenie s AIS účtom bolo odstránené.");
		}
	}
}

if(!empty($_POST['delgoogle']))
{
	if($inststm = $mysqli->prepare("UPDATE `users` SET `Google` = NULL WHERE `users`.`id` = ?;"))
	{
		$inststm->bind_param("i",$_SESSION['id']);

		if($inststm->execute())
		{
			$_SESSION['Google'] = NULL;
		    add_message("","Spojenie s Google účtom bolo odstránené.");
		}
	}
}

$page->setHeader("Profil používateĽa");

$page->renderHeader();
display_errors();
if(!empty($_SESSION['logged']))
{

	if(empty($_SESSION['Login']))
	{
		echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Registrácia do systému</legend>
              <div class="row">

              '.create_field("login","Login","").'
              '.create_field("pass","Heslo","","password").'
              </div>
          <input type="submit" name="addreguser" value="Pridať">
          </fieldset>


          ';

          echo'

        </form>
          ';


	} else {

		    echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Zmena údajov</legend>
              <div class="row">

              '.create_field("login","Login",$_SESSION['Login'],"text",false,"",true).'
              '.create_field("chpass","Heslo","","password").'
              </div>
          <input type="submit" name="changeacc" value="Zmena údajov">
          </fieldset>


          ';

          echo'

        </form>
          ';


	}

	echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Osobné údaje</legend>
      
              <div class="row">

              '.create_field("name","Meno",$_SESSION['Name']).'
              '.create_field("surname","Priezvisko",$_SESSION['Surname']).'
              </div>
          <input type="submit" name="changepersonal" value="Upraviť">
          </fieldset>


          ';

          echo'

        </form>
          ';


	if(empty($_SESSION['LDAPLogin']))
	{


	 	echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Pridanie prihlásenia pomocou AIS STU</legend>
              <div class="row">

              '.create_field("isid","AIS Meno","").'
              '.create_field("ispass","Heslo","","password").'
              </div>
          </fieldset>

          <input type="submit" name="addaislogin" value="Pridať">

          ';

          echo'

        </form>
          ';
    } else {
    	echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Odstránenie sprárovaného účtu AIS STU</legend>
              <div class="row">

              '.create_field("isid","AIS Meno",$_SESSION['LDAPLogin'],"text",false,"",true).'
              </div>
          <input type="submit" name="aisdelete" value="Odstrániť">
          </fieldset>


          ';

          echo'

        </form>
          ';
    }

    if(empty($_SESSION['Google']))
	{


	 	echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Pridanie prihlásenia Google účtu</legend>
              <div class="row">
              ';

		$authUrl = $client->createAuthUrl();
	    echo "<a href='".$authUrl."'><img src='./style/google.png' alt='Login with Google'></a>";
              echo '
              </div>
          </fieldset>

          ';

          echo'

        </form>
          ';
    } else {
    	echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Odstránenie sprárovaného Google účtu</legend>
              <div class="row">

              '.create_field("googleacc","Google účet",$_SESSION['Google'],"text",false,"",true).'
              </div>
          <input type="submit" name="delgoogle" value="Odstrániť">
          </fieldset>


          ';

          echo'

        </form>
          ';
    }
}

 $page->renderFooter();

?>