<?php

require_once 'Page.php';

$person_data;
$umiestnenia_data;
$oh_List;


 function create_update_sql($mysqli,$table,$data,$where_field,$allowed_fields,&$compare_data,$update_data = false)
          {
            $updatesql = "UPDATE `".$table."` SET ";

              foreach ($data as $key => $value) {

                  if(in_array($key, $allowed_fields[$table]) && $compare_data[$key] != $value)
                  {
                    $val = $mysqli->real_escape_string($value);
                    if($update_data) $compare_data[$key] = $val;
                    $updatesql .= "`".$key."` = '".$val."',";
                  }
              }

              // Remove last comma ','
              $updatesql = substr($updatesql, 0, -1);
              $updatesql .= " WHERE `".$table."`.`".$where_field."` = ?;";
             return $updatesql;
          }



if(!empty($_POST['addperson']))
{
  var_dump($_POST);
  //array(9) { ["name"]=> string(3) "asd" ["surname"]=> string(3) "asd" ["birthDay"]=> string(10) "0222-02-02" ["birthPlace"]=> string(3) "asd" ["birthCountry"]=> string(3) "asd" ["deathDay"]=> string(10) "2222-02-02" ["deathPlace"]=> string(3) "asd" ["deathCountry"]=> string(3) "asd" ["addperson"]=> string(18) "Pridať športovca" }

  $sql = "INSERT INTO `osoby` (`id_person`, `name`, `surname`, `birthDay`, `birthPlace`, `birthCountry`, `deathDay`, `deathPlace`, `deathCountry`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);";
  if($stmt = $mysqli->prepare($sql)) {

      $stmt->bind_param("ssssssss",$_POST['name'],$_POST['surname'],$_POST['birthDay'],$_POST['birthPlace'],$_POST['birthCountry'],$_POST['deathDay'],$_POST['deathPlace'],$_POST['deathCountry']);

      $succ = $stmt->execute();
      if($succ)
      {
        header("Location: ./edit.php?id=".$mysqli->insert_id);
      }

  }

}
if(!empty($_GET['del']))
{
  if($stmt = $mysqli->prepare("DELETE FROM `umiestnenia` WHERE `umiestnenia`.`id_person` = ?")) {

    $stmt->bind_param("i", $_GET['del'] );
     if($stmt->execute())
     {
      if($stmt = $mysqli->prepare("DELETE FROM `osoby` WHERE `osoby`.`id_person` = ?")) {
        $stmt->bind_param("i", $_GET['del'] );
        if($stmt->execute())
        {
          header("Location: ./index.php");
        }
      }

    }
  }


}

if(!empty($_GET['id']))
{

  if($stmt = $mysqli->prepare("SELECT * FROM `osoby` o WHERE o.id_person = ?")) {

            
            $stmt->bind_param("i", $_GET['id']);


            $stmt->execute();


            $result = $stmt->get_result();

            $person_data = $result->fetch_assoc();

          if($ohstmnt = $mysqli->prepare('SELECT id_OH as \'ID\', CONCAT(oh.city," ",oh.country," ",oh.year," (",oh.type,")") as \'Name\' FROM `oh` oh WHERE 1;')) {

            $ohstmnt->execute();


            $result = $ohstmnt->get_result();

            while($row = $result->fetch_assoc())
            {
              $oh_List[$row['ID']] = $row['Name'];
            }

          }

          if($umiestneniastmt = $mysqli->prepare("SELECT * FROM `umiestnenia` u WHERE u.id_person = ?")) {

            $umiestneniastmt->bind_param("i", $_GET['id']);


            $umiestneniastmt->execute();


            $result = $umiestneniastmt->get_result();

            while($row = $result->fetch_assoc())
            {
              $umiestnenia_data[$row['id']] = $row;
            }

          }
            
            // Saving person form
            if(!empty($_POST['updateperson']))
            {
              //UPDATE `osoby` SET `deathCountry` = 'as' WHERE `osoby`.`id_person` = 1;
              $updatesql = create_update_sql($mysqli,"osoby",$_POST,"id_person",$updatables,$person_data,true);              

              if($updtst = $mysqli->prepare($updatesql))
              {
                  
                  $updtst->bind_param("i", $_GET['id']);

                  var_dump($updtst->execute());
              }

            }

            if(!empty($_POST['updaterecord']))
            {
              $id = intval($_POST['id']);
              if(!empty($umiestnenia_data[$id]))
              {

                $updatesql = create_update_sql($mysqli,"umiestnenia",$_POST,"id",$updatables,$umiestnenia_data[$id],true);     

                if($updtst = $mysqli->prepare($updatesql))
                {
                    
                    $updtst->bind_param("i", $id );

                    var_dump($updtst->execute());
                }
              }
            }

            if(!empty($_POST['deleterecord']))
            {
              $id = intval($_POST['id']);
              if(!empty($umiestnenia_data[$id]))
              {
                if($dlttst = $mysqli->prepare('DELETE FROM `umiestnenia` WHERE `umiestnenia`.`id` = ?'))
                {
                    $dlttst->bind_param("i", $id );

                    if($dlttst->execute())
                    {
                      unset($umiestnenia_data[$id]);
                    }
                }
              }

              //
            }

            if(!empty($_POST['addrecord']))
            {
              //
              if(!empty($_POST['ID_OH']) && !empty($_POST['place']) && !empty($_POST['discipline']))
              {
                if($inststm = $mysqli->prepare("INSERT INTO `umiestnenia` (`id`, `id_person`, `ID_OH`, `place`, `discipline`) VALUES (NULL, ?, ?, ?, ?)"))
                {
                    $inststm->bind_param("iiis", $_GET['id'],$_POST['ID_OH'],$_POST['place'],$_POST['discipline']);

                    if($inststm->execute())
                    {
                      $id = intval($_GET['id']);
                      $idoh = intval($_POST['ID_OH']);
                      $place = intval($_POST['place']);

                      $umiestnenia_data[$mysqli->insert_id] = array("id" => $mysqli->insert_id,"id_person" => $id,"ID_OH" => $idoh, "place" => $place, "discipline" => $_POST['discipline']);
                      // pridany
                    }
                }
              }
            }

  }

}


//$updatables

/*
<select>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select>
*/

function create_select($name,$text,$data,$default = false,$req = true,$id = "")
{

    $out = '
                  <div class="field">
                    <label for="'.$name.$id.'" class="'.($req ? 'req' : '').'"><span>'.$text.':</span></label>
                    <div>
                    <select id="'.$name.$id.'" name="'.$name.'">

                    ';

                    foreach ($data as $key => $value) {
                      $out .= '<option value="'.$key.'" '.(($default !== false && $key == $default) ? 'selected' : '' ).'>'.$value.'</option>';
                    }

    $out .= '

                    </select>

                    </div>
                  </div>';
  return $out;
}

function create_field($name,$text,$value,$type="text",$req = true,$id = "")
{ 
  $out = '
                  <div class="field">
                    <label for="'.$name.$id.'" class="'.($req ? 'req' : '').'"><span>'.$text.':</span></label>
                    <div>
                    <input '.($req ? 'required' : '').' type="'.$type.'" id="'.$name.$id.'" name="'.$name.'" value="'.$value.'">
                    </div>
                  </div>';
  return $out;
}

$page->setHeader("Zoznam Slovenských olympískych víťazov");

$page->renderHeader();

?>

        <a href="./index.php" class="button">Prjejsť na úvodnú tabuľku</a>
        <h2>Úprava osobných údajov športovca</h2>
        
        <?php
        if(!empty($person_data))
        {
          echo '<div><a href="./edit.php?del='.($person_data['id_person']).'" class="button">Odstrániť športovca a jeho záznamy z OH</a></div>';
        
        }

        
        echo '
        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Osobné údaje</legend>
              <div class="row">

              '.create_field("name","Meno",$person_data['name']).'
              '.create_field("surname","Priezvisko",$person_data['surname']).'
              </div>
              <div class="row">
                '.create_field("birthDay","Dátum narodenia",$person_data['birthDay'],'date').'
                '.create_field("birthPlace","Miesto narodenia",$person_data['birthPlace']).'
                
              </div>
              <div class="row">
                '.create_field("birthCountry","Krajina narodenia",$person_data['birthCountry']).'
                '.create_field("deathDay","Dátum úmrtia",$person_data['deathDay'],'date',false).'
                
              </div>
              <div class="row">
                '.create_field("deathPlace","Miesto úmrtia",$person_data['deathPlace'],'text',false).'
                '.create_field("deathCountry","Krajina úmrtia",$person_data['deathCountry'],'text',false).'
                
              </div>
          </fieldset>

          <input type="submit" name="'.(empty($person_data['name']) ? "addperson" : "updateperson").'" value="'.(empty($person_data['name']) ? "Pridať športovca" : "Uložiť údaje").'">

          ';

          echo'

        </form>
          ';

        
        if(!empty($person_data))
        {
          if(!empty($umiestnenia_data) && is_array($umiestnenia_data))
          {
        echo '
        <h2>Úprava úspechov športovca</h2>
        <div class="text-center">
        <fieldset>
            <legend>Športové úspechy</legend>
        ';
        foreach ($umiestnenia_data as $key => $value) {
        
        echo '<div id="u'.$value['id'].'" class="'.((!empty($_GET['uid']) && $_GET['uid'] == $value['id']) ? "form-selected" : "").'"><form method="post" class="form-style allinrow">';
        echo create_field("discipline","Disciplína",$value['discipline'],'text',true,$value['id']);
        echo create_field("place","Umiestnenie",$value['place'],'number',true,$value['id']);
        echo create_select('ID_OH',"Miesto konania",$oh_List,$value['ID_OH'],true,$value['id']);

        echo '
        <input type="hidden" name="id" value="'.$value['id'].'">
        <input type="submit" name="updaterecord" value="Uložiť">
        <input type="submit" name="deleterecord" value="Zmazať">

        </form></div>
          ';

        }

        echo '</fieldset></div>';
      }
        echo '<h2>Pridanie úspechov športovca</h2>
        <div class="text-center">
        <fieldset>
            <legend>Nový úspech</legend>
        ';

        echo '<div><form method="post" class="form-style allinrow">';
        echo create_field("discipline","Disciplína","",'text',true);
        echo create_field("place","Umiestnenie",1,'number',true);
        echo create_select('ID_OH',"Miesto konania",$oh_List,false,true);

        echo '
        <input type="submit" name="addrecord" value="Pridať">

        </form>
        </div>
        </fieldset>
        </div>
          ';

        }
        ?>

<?php 

$page->renderFooter();

mysqli_close($mysqli);
?>