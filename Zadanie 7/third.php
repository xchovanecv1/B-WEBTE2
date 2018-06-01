<?php 
require_once "Page.php";

$page->setHeader("Mashup");
$page->renderHeader();

/*
if(!empty($_SESSION['ip_data']))
{
  echo "<pre>";

  var_dump($_SESSION['ip_data']);

  echo "</pre>";
}
*/
if ($result = $mysqli->query("SELECT `country` as cnt, COUNT(*) as 'pocet', `flag` as fg FROM `activity` AS ac WHERE 1 GROUP BY cnt,fg")) {

  echo "
  <table style='width: 100%;' class='tablesorter'>
    <tr>
      <th>Krajina</th>
      <th>Počet</th>
      <th>Vlajka</th>
    </tr>
  ";
  while(($data = mysqli_fetch_array($result)))
  {
    echo "
    <tr style='color: black;'>
      <td><a href='./forth.php?c=".$data['cnt']."'>".$data['cnt']."</a></td>
      <td>".$data['pocet']."</td>
      <td><img src='".$data['fg']."' width='50' alt='flag'></td>
    </tr>
    ";
  }
  $result->close();

  echo "
  </table>
  ";
}

if ($result = $mysqli->query("SELECT url, COUNT(*) as 'pocet' FROM `activity` GROUP BY url ORDER BY pocet DESC")) {

  echo "
  <table style='width: 100%;' class='tablesorter'>
    <tr>
      <th>Linka</th>
      <th>Počet</th>
    </tr>
  ";
  while(($data = mysqli_fetch_array($result)))
  {
    echo "
    <tr style='color: black;'>
      <td>".$data['url']."</td>
      <td>".$data['pocet']."</td>
    </tr>
    ";
  }
  $result->close();

  echo "
  </table>
  ";
}

function zone($z)
{ 
  switch ($z) {
    case '1':
      return "6:00-14:00";
      break;
    case '2':
      return "14:00-20:00";
      break;
    case '3':
      return "20:00-24:00";
      break;
    
    default:
      return "24:00-6:00";
      break;
  }
}

if ($result = $mysqli->query("SELECT timezone, COUNT(*) as 'pocet' FROM `activity` GROUP BY timezone ORDER BY pocet DESC")) {

  echo "
  <table style='width: 100%;' class='tablesorter'>
    <tr>
      <th>Časová zóna</th>
      <th>Počet</th>
    </tr>
  ";
  while(($data = mysqli_fetch_array($result)))
  {
    echo "
    <tr style='color: black;'>
      <td>".zone($data['timezone'])."</td>
      <td>".$data['pocet']."</td>
    </tr>
    ";
  }
  $result->close();

  echo "
  </table>
  ";
}


//

$page->renderFooter();
?>