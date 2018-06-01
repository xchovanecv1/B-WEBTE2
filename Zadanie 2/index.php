<?php
//http://tablesorter.com

require_once 'Page.php';

if(!empty($_GET['did']))
{
  if($delst = $mysqli->prepare("DELETE FROM `umiestnenia` WHERE `umiestnenia`.`id` = ?")) {

    $delst->bind_param("i", $_GET['did']);
    if($delst->execute())
    {
      var_dump("ROW deleted");
    }
  }
}


$sort_sql_padd = "";

if(!empty($_GET['sort']))
{

  $order = "ASC";
  if(!empty($_GET['ord']) && $_GET['ord'] == 'desc')
  {
    $order = "DESC";
  }
  $sort_table_class['class'] = $order_class;

  $sort_sql_padd .= "ORDER BY ";

  switch ($_GET['sort']) {
    case 'sname':
      $sort_sql_padd .= "Priezvisko ".$order;
    break;
    case 'year':
      $sort_sql_padd .= "Rok ".$order;
    break;
    case 'type':
      $sort_sql_padd .= "Druh ".$order.', Rok '.$order;
    break;
    
    default:
      $sort_sql_padd = "";
    break;
  }
}

$flip_order = (empty($_GET['ord']) ? "&ord=desc" : "");

$page->setHeader("Zoznam Slovenských olympískych víťazov");

$page->renderHeader();
?>
      <h2>Všetci olympísky vítazy Slovenskej Republiky</h2>
      <a href="./edit.php" class="button">Pridať športovca</a>
      <table id="myTable" class="tablesorter"> 
			<thead> 
			<tr> 
          <th>Meno Športovca</th> 
          <th class=""><a href="./?sort=sname<?php echo $flip_order; ?>">Priezvisko Športovca</a></th> 
			    <th><a href="./?sort=year<?php echo $flip_order; ?>">Rok konania</a></th> 
			    <th>Miesto konania</th> 
			    <th><a href="./?sort=type<?php echo $flip_order; ?>">Druh olympiády</a></th>
          <th>Disciplína</th> 
          <th>Medaila</th> 
			    <th colspan="2">Úpravy</th> 
			</tr> 
			</thead> 
			<tbody> 

        <?php 
			$sql = "SELECT o.name as 'Meno', o.surname as 'Priezvisko', oh.year as 'Rok', CONCAT(oh.city,\" (\",oh.country,\")\") as 'Miesto konania', oh.type as 'Druh', u.discipline as 'Disciplina', o.id_person, u.place, u.id FROM `umiestnenia` u LEFT JOIN osoby o ON u.id_person = o.id_person LEFT JOIN `oh` oh ON u.ID_OH = oh.id_OH WHERE u.place < 4 ".$sort_sql_padd.";";

			$result = $mysqli->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_array(MYSQLI_NUM)) {
			        echo "
			        	<tr> 
						    <td colspan='2'><a href='./edit.php?id=".$row[6]."'>".$row[0]." ".$row[1]."</a></td>
						    <td>".$row[2]."</td>
						    <td>".$row[3]."</td>
						    <td>".$row[4]."</td>
                <td>".$row[5]."</td> 
                <td class='place-".$row[7]."'> </td> 
						    <td class='edit'><a href='./edit.php?id=".$row[6]."&uid=".$row[8]."#u".$row[8]."'>Upraviť</a> </td> 
                <td class='edit'><a href='./index.php?did=".$row[8]."'>Zmazať</a></td> 
						</tr> 
			        ";
			    }
			}
			        ?>

			</tbody> 
			</table> 
  

<?php 

$page->renderFooter();

mysqli_close($mysqli);
?>