<?php


define("MYSQL_SERVER", "localhost");
define("MYSQL_USER", "user");
define("MYSQL_PASS", "");
define("MYSQL_DB", "zad2");

// Fields allowed to be edited through from

$updatables = array();

$updatables['osoby'] = array('name','surname','birthDay','birthPlace','birthCountry','deathDay','deathPlace','deathCountry');
$updatables['umiestnenia'] = array('ID_OH','place','discipline');

?>