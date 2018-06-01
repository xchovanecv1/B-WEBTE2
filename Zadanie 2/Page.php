<?php
//http://tablesorter.com

require_once 'config.php';
require_once 'Page.class.php';


$mysqli= mysqli_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
// Check connection
if (mysqli_connect_errno())
  {
  die( "Failed to connect to MySQL: " . mysqli_connect_error());
  }
if (!$mysqli->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
    exit();
}




$page = new Page("Zadanie 2");
$page->addMeta('charset="utf-8"');
$page->addLink('css','style/style.css');
//$page->addScript('link','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js');
//$page->addScript('link','style/jquery.tablesorter.min.js');
//$page->addScript('script','$(document).ready(function() { $("#myTable").tablesorter({ headers: { 6: {  sorter: false } } }); } );');




?>