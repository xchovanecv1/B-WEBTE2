<?php

require_once "./router.php";

define("MYSQL_SERVER", "localhost");
define("MYSQL_USER", "final");
define("MYSQL_PASS", "");
define("MYSQL_DB", "final");

define("ROLE_NONE", 0);
define("ROLE_USER", 100);
define("ROLE_ADMIN", 200);

define("SMTP_HOST",'');
define("SMTP_USER",'');
define("SMTP_PASSWORD",'');
define("SMTP_PORT",465);
define("SMTP_FROM",'');

define("SMTP_LIMIT_ONE_RUN", 5);

define("GOOGLE_MAP_API", "");
define("GOOGLE_MAP_MAX_REQUESTS", 5);

define("BASE_URL",'');

define("MAX_USERS_PER_GROUP",6);


$RUN_RATINGS = array(
	1 => "Super",
	2 => "Dobre",
	3 => "Pohoda",
	4 => "Zle",
	5 => "Neprípustné",
);

?>