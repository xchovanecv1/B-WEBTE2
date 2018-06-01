<?php
//http://tablesorter.com

require_once 'Page.class.php';

session_start();
session_regenerate_id(true); 


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


$page = new Page("Zadanie 6");
$page->addMeta('charset="utf-8"');
$page->addLink('css','style/style.css');
$page->addScript('link','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js');
//$page->addScript('link','style/jquery.tablesorter.min.js');

$page->addScript('link','style/script.js');


?>