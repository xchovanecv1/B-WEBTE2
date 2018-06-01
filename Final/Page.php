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

function load_user_data($mysqli,$user_id)
{
    if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `ID` = ?")) {

    $sel->bind_param("d", $user_id);
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
        }
        $sel->close();
      }
    }
}


function fetch_address($mysqli,$address)
{
  if($sel = $mysqli->prepare("SELECT ID FROM `address` WHERE `Street` LIKE ? AND `Number` LIKE ? AND `ZIP` LIKE ? AND `City` LIKE ?")) {

    $sel->bind_param("ssss", $address['Street'], $address['Number'], $address['ZIP'], $address['City']);
    if($sel->execute())
    {

        $result = $sel->get_result();
        $data = $result->fetch_assoc();

        $sel->close();
        if($result->num_rows > 0)
        {
          return $data["ID"];
        } else {
          if($pass_gen = $mysqli->prepare("INSERT INTO `address` (`ID`, `Street`, `Number`, `ZIP`, `City`, `Country`, `Geo`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL);"))
          {

              $pass_gen->bind_param("ssss", $address['Street'], $address['Number'], $address['ZIP'], $address['City']);
              if($pass_gen->execute())
              {
                $pass_gen_id = $pass_gen->insert_id;
                $pass_gen->close();

                return $pass_gen_id;
              }
          }
        }
      }
    }
  return 0;
}

function fetch_address_data($mysqli,$addressid)
{
  if($sel = $mysqli->prepare("SELECT * FROM `address` WHERE `ID` = ?")) {

    $sel->bind_param("i", $addressid);
    if($sel->execute())
    {

        $result = $sel->get_result();
        $data = $result->fetch_assoc();

        $sel->close();
        if($result->num_rows > 0)
        {
          return $data;
        }
      }
    }
  return NULL;
}


function redirect_to_path($path,$msg="",$err_code="")
{
  if(!empty($msg))
    add_message($err_code,$msg);
  header('Location: ' . filter_var($path, FILTER_SANITIZE_URL));
  return;
}

function name_url_hash($name)
{
  return sha1($name).md5(time());
}

function check_access_role($role = ROLE_NONE)
{
  // je vobec prihlaseny ?
  
  if(empty($_SESSION['logged']))
  {
    if($role == ROLE_NONE) return true;
  } else {
    if($_SESSION['Role'] >= $role)
    {
      return true;
    }
  }

  return false;
  
  //if($role == ROLE_ADMIN) return false;
  //return true;
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

function display_errors($type = "")
{
  if(empty($_SESSION['errors']) || !is_array($_SESSION['errors'])) $_SESSION['errors'] = array();


if(empty($type))
    {
  if(count($_SESSION['errors'])) echo '<div class="col-md-12"><div class="box box-primary">';
}
  foreach ($_SESSION['errors'] as $key => $value) {
    $type = 'success';
    switch ($value[0]) {
      case 'error':
        $type = 'danger';
        break;
      case 'warning':
        $type = 'warning';
        break;

      default:
        $type = 'success';
        break;
    }

    echo '
    <div class="btn btn-block btn-'.$type.'">
        <i><b>'.$value[1].'</b></i>
    </div>
    ';
    unset($_SESSION['errors'][$key]);
  }
if(empty($type))
    {
  if(count($_SESSION['errors']))  echo '</div></div>';
}
}


$page = new Page("Zadanie 3");
$page->addMeta('charset="utf-8"');
$page->addLink('css','style/style.css');
$page->addScript('link','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js');
$page->setAdmin(check_access_role(ROLE_ADMIN,false));
$page->setUser(check_access_role(ROLE_USER,false));
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