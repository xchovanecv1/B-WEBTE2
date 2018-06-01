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

		            $_SESSION['logged'] = 1;
		    		foreach ($data as $key => $value) {
		    			$_SESSION[$key] = $value;
		    		}
		    		/*echo "asd "+$data['id'];
		    		die("asd "+$data['id']);*/

		    		add_message("","Boli ste úspešne prihlásený");
    				header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
		        }

		    	
		    } else {
		    	add_message("error","Nesprávne prihlasovacie meno alebo heslo");
				header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
		    }
		}
	}
}

$page->setHeader("Prihlásenie používateľa do systému");

$page->renderHeader();

    echo '

	        <form method="post" class="form-style text-center">
	          <fieldset>
	            <legend>Prihlásenie pomocou registrovaného účtu</legend>
	              <div class="row">

	              '.create_field("login","Login","").'
	              '.create_field("pass","Heslo","","password").'
	              </div>
	          </fieldset>

	          <input type="submit" name="normlogin" value="Prihlásiť">
	          
	        </form>
	    ';

 $page->renderFooter();

?>