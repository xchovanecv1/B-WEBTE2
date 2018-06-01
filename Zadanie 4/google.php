<?php
require_once 'Page.php';
require_once 'oauth.php';

 ?>

<?php

$page->setHeader("Prihlásenie používateľa do systému");

$page->renderHeader();


if(isset($_GET['code'])){
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
	}

	if (isset($_SESSION['token'])) {
	    $client->setAccessToken($_SESSION['token']);
	}
	//
	if ($client->getAccessToken()) {
	    //Get user profile data from google
	    $gpUserProfile = $google_oauthV2->userinfo->get();

	    if(!empty($_SESSION['logged']) && !empty($_SESSION['id']))
	    {
	    	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Google` = ?")) 
	    	{

				$sel->bind_param("s", $gpUserProfile['email']);
				if($sel->execute())
				{

				   	$result = $sel->get_result();
				    if($result->num_rows == 0)
				    {
				    	if($sel = $mysqli->prepare("UPDATE `users` SET `users`.`Google` = ? WHERE `users`.`id` = ?")) 
					    {

						    $sel->bind_param("si", $gpUserProfile['email'],$_SESSION['id']);
						    if($sel->execute())
						    {
						    	$_SESSION['Google'] = $gpUserProfile['email'];

						    	add_message("","Váš účet bol úspešne prepojený s Google kontom.");
								header('Location: profil.php');
						    }
						}
				    }
				}
			}
	    	
	    } else {

	    	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Google` = ?")) 
		    {

			    $sel->bind_param("s", $gpUserProfile['email']);
			    if($sel->execute())
			    {
			    	$result = $sel->get_result();
			    	$data = $result->fetch_assoc();
			    	$userid = 0;

			    	if($result->num_rows == 1)
			    	{
			    		// Login
			    		if($inststm = $mysqli->prepare("INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `time`) VALUES (NULL, ?, 'Google', NOW());"))
				        {
				            $inststm->bind_param("i", $data['id']);

				            $inststm->execute();
;
				            $_SESSION['logged'] = 1;
				    		foreach ($data as $key => $value) {
				    			$_SESSION[$key] = $value;
				    		}
							
						    add_message("","Prihlásenie pomocou Google konta prebehlo úspešne.");
							header('Location: index.php');
				        }

			    	} else {
			    		if($inststm = $mysqli->prepare("INSERT INTO `users` (`id`, `Name`, `Surname`, `Google`) VALUES (NULL, ?, ?, ?);"))
		                {
		                    $inststm->bind_param("sss", $gpUserProfile['given_name'],$gpUserProfile['family_name'],$gpUserProfile['email']);

		                    if($inststm->execute())
		                    {
		                    	$_SESSION['logged'] = 1;
		                    	$_SESSION['Name'] = $gpUserProfile['given_name'];
		                    	$_SESSION['Surname'] = $gpUserProfile['family_name'];
		                    	$_SESSION['Google'] = $gpUserProfile['email'];
			                 	echo "Registered";
			                 	$_SESSION['id'] = $mysqli->insert_id;

								add_message("","Registrácia pomocou Google konta prebehlo úspešne.");
								header('Location: index.php');
		                    }
		                }
			    	}
			    }
			}
	    }
	  
	} else {
	    $authUrl = $client->createAuthUrl();
	    echo "<a class='center' href='".$authUrl."'><img class='center' src='./style/google.png' alt='Login with Google'></a>";
	}

 $page->renderFooter();

?>

