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
if($sel = $mysqli->prepare("SELECT `city` as cnt, COUNT(*) as 'pocet' FROM `activity` AS ac WHERE `country` = ? GROUP BY cnt")) {

    $sel->bind_param("s", $_GET['c']);
    if($sel->execute())
    {

        $result = $sel->get_result();

echo "
  <table style='width: 100%;' class='tablesorter'>
    <tr>
      <th>Krajina</th>
      <th>Počet</th>
    </tr>
  ";

        while(($data = $result->fetch_assoc()))
        {
          echo "
              <tr style='color: black;'>
                <td>".(!empty($data['cnt']) ? $data['cnt'] : "nelokalizované mestá a vidiek")."</a></td>
                <td>".$data['pocet']."</td>
              </tr>
              ";

        }

  $result->close();

  echo "
  </table>
  ";
    }
}

 /* echo "
  <table style='width: 100%;'>
    <tr>
      <th>Krajina</th>
      <th>Počet</th>
      <th>Vlajka</th>
    </tr>
  ";*/

  /*  echo "
    <tr style='color: black;'>
      <td><a href='./forth.php?c=".$data['cnt']."'>".$data['cnt']."</a></td>
      <td>".$data['pocet']."</td>
      <td><img src='".$data['fg']."' width='50' alt='flag'></td>
    </tr>
    ";*/

/*
  echo "
  </table>
  ";*/

?>

<?php 
$page->renderFooter();
?>