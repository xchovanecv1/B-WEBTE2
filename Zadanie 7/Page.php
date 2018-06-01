<?php
//http://tablesorter.com

require_once 'Page.class.php';
require_once 'config.php';

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

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
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


$page = new Page("Zadanie 7");
$page->addMeta('charset="utf-8"');
$page->addLink('css','style/style.css');
$page->addScript('link','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js');

$IP = get_client_ip();
if($IP != "UNKNOWN")
{
  if(empty($_SESSION['user_ip']) || $_SESSION['user_ip'] != $IP)
  {
    $_SESSION['new_ip'] = true;
    $_SESSION['user_ip'] = $IP;

    $json = file_get_contents("http://api.ipstack.com/".$IP."?access_key=".ipstack_key);
    $data = json_decode($json);

    $_SESSION['ip_data'] = $data;
    //var_dump($data);
  }
}
if(!empty($_SESSION['new_ip']) && $_SESSION['new_ip'] == true)
{
  //api.openweathermap.org/data/2.5/weather?q={city name}
  $weather_json = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=".$_SESSION['ip_data']->{"latitude"}."&lon=".$_SESSION['ip_data']->{"longitude"}."&APPID=".openweather_key);
  $weather_data = json_decode($weather_json);

  $_SESSION['weather_data'] = $weather_data;

    $time_json = file_get_contents("https://api.worldweatheronline.com/premium/v1/tz.ashx?format=json&q=".$_SESSION['user_ip']."&key=".worldweather_key);
  $time_data = json_decode($time_json);

  $_SESSION['time_data'] = $time_data->{"data"}->{"time_zone"}[0];

  $_SESSION['new_ip'] = false;
} else {
    /*echo "<pre>";

  var_dump($_SESSION['weather_data']);

  echo "</pre>";*/
}
  //var_dump();

if ($result = $mysqli->query("SELECT id,(NOW()-time) as 'diff' FROM `activity` WHERE ip = '".$_SESSION['user_ip']."' ORDER BY id DESC LIMIT 1")) {

    $data = mysqli_fetch_array($result);
    if(empty($data['diff']) || ($data['diff'] >= 86400))
    {

        $time=strtotime($_SESSION['time_data']->{"localtime"});
        $hr = intval(date("H",$time));

        $zone = 4;
        //6:00-14:00, 14:00-20:00, 20:00-24:00, 24:00-6:00
        if($hr >= 6 && $hr < 14)
        {
          $zone = 1;
        } else if($hr >= 14 && $hr < 20)
        {
          $zone = 2;
        } else if($hr >= 20 && $hr < 24)
        {
          $zone = 3;
        } 

       $mysqli->query("INSERT INTO `activity` (`ID`, `ip`, `time`, `timezone`, `country`, `city`, `flag`, `url`) VALUES (NULL, '".$_SESSION['user_ip']."', CURRENT_TIMESTAMP, '".$zone."', '".$_SESSION['ip_data']->{"country_name"}."', '".$_SESSION['ip_data']->{"city"}."', '".$_SESSION['ip_data']->{"location"}->{"country_flag"}."', '".$_SERVER[REQUEST_URI]."')");
    }

    $result->close();
}

?>