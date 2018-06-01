<?php
require_once 'Page.php';

/*
unset($_SESSION['logged']);
unset($_SESSION['token']);
unset($_SESSION['userData']);
*/

foreach ($_SESSION as $key => $value) {
	unset($_SESSION[$key]);
}
//Reset OAuth access token

//Destroy entire session
session_destroy();

add_message("","Boli ste úspešne odhlásený");
header("Location: index.php");

?>