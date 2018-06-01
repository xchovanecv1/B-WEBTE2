<?php
//http://tablesorter.com

require_once 'config.php';
require_once 'Page.class.php';

session_start();
session_regenerate_id(true); 

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


function create_field($name,$text,$value,$type="text",$req = true,$id = "",$disabled = false)
{ 
  $out = '
                  <div class="field">
                    <label for="'.$name.$id.'" class="'.($req ? 'req' : '').'"><span>'.$text.':</span></label>
                    <div>
                    <input '.($disabled ? "disabled" : "").' '.($req ? 'required' : '').' type="'.$type.'" id="'.$name.$id.'" name="'.$name.'" value="'.$value.'">
                    </div>
                  </div>';
  return $out;
}


function add_message($type,$text)
{
  if(empty($_SESSION['errors']) || !is_array($_SESSION['errors'])) $_SESSION['errors'] = array();

  $_SESSION['errors'][] = array($type,$text);

}

function display_errors()
{
  if(empty($_SESSION['errors']) || !is_array($_SESSION['errors'])) $_SESSION['errors'] = array();

  foreach ($_SESSION['errors'] as $key => $value) {

    echo '
    <div class="main-width error-bar '.$value[0].'">
        <p>
            <span><i>!</i></span>'.$value[1].'
        </p>
    </div>
    ';
    unset($_SESSION['errors'][$key]);
  }

}


$page = new Page("Zadanie 3");
$page->addMeta('charset="utf-8"');
$page->addLink('css','style/style.css');
$page->addScript('link','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js');
//$page->addScript('link','style/jquery.tablesorter.min.js');

if(!empty($_SESSION['logged']))
{
$page->addScript('script','

$( document ).ready(function() {
  window.onscroll = function() {myFunction()};

  // Get the navbar
  var navbar = document.getElementById("logged");

  // Get the offset position of the navbar
  var sticky = navbar.offsetTop;

  // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
  function myFunction() {
    if (window.pageYOffset >= sticky) {
      navbar.classList.add("sticky")
    } else {
      navbar.classList.remove("sticky");
    }
  }
});
');
}

$page->addScript('script','
$( document ).ready(function() {
  document.getElementById("defTab").click();
});
function openTab(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

');


?>