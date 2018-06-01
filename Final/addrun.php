<?php
require_once 'Page.php';

if(!check_access_role(ROLE_USER,true))
{
	return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

$page->setHeader("Pridať beh");


$active_routes = array();
$group_active = NULL;
$pref = 0;
if ($result = $mysqli->query("SELECT * FROM `route` WHERE NOT `route`.`type` = 1 AND `route`.`active`= 1 ORDER BY `route`.`date` DESC LIMIT 1;")) {
    $data = $result->fetch_assoc();

    $tp = "(Verejný mód)";
    if($data['type'] == 3) $tp = "(Štafetový mód)";

    if($data['type'] == 2)
    {
        $active_routes[$data['id']] = $data['name']." ".$tp;
        $pref = $data['id'];
    } else if($data['type'] == 3)
    {
        if ($res = $mysqli->query("SELECT g.id  FROM `groups` g LEFT JOIN `group_users` gu ON g.id = gu.groupid WHERE g.`route` = ".$data['id']." AND gu.`userid` = ".$_SESSION['ID'])) {
            if($res->num_rows > 0)
            {
                $datas = $res->fetch_assoc();
                $group_active = $datas['id'];

                $active_routes[$data['id']] = $data['name']." ".$tp;
                $pref = $data['id'];
            }
        }
    }
    $result->close();
}

if ($results = $mysqli->query("SELECT * FROM `route` WHERE `route`.`type` = 1 AND `route`.`active`= 1 AND `route`.`creator` = ".$_SESSION['ID']." ORDER BY `route`.`date` DESC LIMIT 1;")) {
    $data = $results->fetch_assoc();
    if($results->num_rows > 0)
    {
        $active_routes[$data['id']] = $data['name']." (Privátny mód)";
    }
    $results->close();
}


// define variables and set to empty values
$runlen = $runtype = $rundate = $strtime = $endtime = $gsplastart = $gsplostart = $gsplaend = $gsploend = $rate = $note ="";
$runlen_err = $runtype_err = $rundate_err = $strtime_err = $endtime_err = $gsplastart_err = $gsplostart_err = $gsplaend_err = $gsploend_err = $rate_err = $note_err ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty($_POST['runroute']))
    {
        $runtype_err = "Nevybrali ste trasu"; 
    } else {
        $runtype = test_input($_POST['runroute']);
    }

    if (empty($_POST["runlen"])) {
        $runlen_err = "Zadajte počet km";

    }
    else if(!is_numeric($_POST["runlen"])){
        $runlen_err = "Je potrebné zadať číslo";
    }
    else {
        $runlen = test_input($_POST["runlen"]);
    }

    if (!empty($_POST["rundate"])) {
        if (isValidDateTime($_POST["rundate"]) == false){
            $rundate_err = "Zadajte správny formát dátumu";
        }
        $rundate = test_input($_POST["rundate"]);
    }
     else {
         $rundate = null;
     }

    if (!empty($_POST["strtime"])){
        if (ValidTime($_POST["strtime"]) == false) {
        $strtime_err = "Zadajte správny čas začiatku tréningu";
    }
        $strtime = test_input($_POST["strtime"]);
    }
    else{
        $strtime = null;
    }
    if (!empty($_POST["endtime"])){
        if (VAlidTime($_POST["endtime"]) == false) {
        $endtime_err = "Zadajte správny čas konca tréningu";
    }
        $endtime = test_input($_POST["endtime"]);
    }
    else{
        $endtime = null;
    }

    if (!empty($_POST["gsplastart"])) {
        if (isValidLatitude($_POST["gsplastart"]) == false) {
            $gsplastart_err = "Zle zadaná zemepisná šírka";
        } else {
            $gsplastart = test_input($_POST["gsplastart"]);
        }
    }else {
            $gsplastart = null;
        }

    if (!empty($_POST["gsplostart"])) {
        if (isValidLongitude($_POST["gsplostart"]) == false) {
            $gsplostart_err = "Zle zadaná zemepisná dĺžka";
        } else {
            $gsplostart = test_input($_POST["gsplostart"]);
        }
    }
    else{
        $gsplostart = null;
    }

    if (!empty($_POST["gsplaend"])) {
        if (isValidLatitude($_POST["gsplaend"]) == false) {
            $gsplaend_err = "Zle zadaná zemepisná šírka";
        } else {
            $gsplaend = test_input($_POST["gsplaend"]);
        }
    }
    else{
        $gsplaend = null;
    }
    if (!empty($_POST["gsploend"])) {
        if (isValidLongitude($_POST["gsploend"]) == false) {
            $gsploend_err = "Zle zadaná zemepisná dĺžka";
        } else {
            $gsploend = test_input($_POST["gsploend"]);
        }
    }
    else{
        $gsploend = null;
    }

       $rate = test_input($_POST["rate"]);

    if (empty($_POST["note"])) {
        $note = null;
    } else {
        $note = test_input($_POST["note"]);
    }

    if(empty($runlen_err) && empty($strtime_err) && empty($endtime_err) && empty($gsplastart_err) && empty($gsplostart_err) && empty($gsplaend_err) && empty($gsploend_err) && empty($runlen_err) && empty($runtype_err)) {

// Prepare an insert statement
        //$sql = "INSERT INTO run (User,Groupid,Date,Start,End,StLat,StLon,EnLat,EnLon,Length,Rate,Note,Route) VALUES (?,NULL,?,?,?,?,?,?,?,?,?,?,?)";
        $sql = "INSERT INTO `run` (`ID`, `User`, `Groupid`, `Date`, `Start`, `End`, `StLat`, `StLon`, `EnLat`, `EnLon`, `Length`, `Rate`, `Note`, `Route`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ddsssssssddsd",$_SESSION['ID'],$group_active,$rundate, $strtime, $endtime, $gsplastart, $gsplostart, $gsplaend, $gsploend, $runlen, $rate, $note, $runtype);

            if ($stmt->execute()) {
                add_message("","Beh bol úspešne pridaný!");
            } else {
                add_message("error","Počas pridávania behu nastal problém!");
                //printf("Error: %s.\n", $stmt->error);
            }
        }
// Close statement
            $stmt->close();
    }


}

function isValidLongitude($longitude){
    if(preg_match("/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/", $longitude)) {
        return true;
    } else {
        return false;
    }
}
function isValidLatitude($latitude){
    if (preg_match("/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/", $latitude)) {
        return true;
    } else {
        return false;
    }
}

function ValidTime($time){
    if (preg_match("/^(0[1-9]|1[0-9]|2[0-3]):([0-5][1-9])$/", $time))
    {
        return true;
    } else {
        return false;
    }

}
function isValidDateTime($dateTime)
{
    if (preg_match("/^[0-9]{4}.(0[1-9]|1[0-2]).(0[1-9]|[1-2][0-9]|3[0-1])$/", $dateTime))
    {
        return true;
    } else {
        return false;
    }
}
function test_input($data) {
    $data = trim($data);
    return $data;
}

$page->renderHeader();
display_errors();
?>

<script type="text/javascript" src="get_coordinates.js"></script>

<div class="col-md-6">
  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pridanie behu</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
              <div class="box-body">
                <div class="form-group">
                    <label for="skool">Aktívna trasa</label>
                    <select class="form-control" name="runroute">
                        <?php 
                        if(count($active_routes))
                        {
                            foreach ($active_routes as $key => $value) {
                                echo '<option value="'.$key.'" '.($key == $pref ? "selected" : "").'>'.$value.'</option>';
                            }
                        }
                        ?>
                        
                    </select>
                    <span class="error"><?php echo $runtype_err;?></span>
                </div>
                <div class="form-group">
                    <label for="skool">Počet odbehnutých kilometrov</label>
                    <input class="form-control" type="text" name="runlen" placeholder="">
                    <span class="error"><?php echo $runlen_err;?></span>
                </div>
                <div class="form-group">
                    <label for="skool">Deň uskutočnenia behu</label>
                    <input class="form-control" type="date" name="rundate" placeholder="">
                    <span class="error"><?php echo $rundate_err;?></span>

                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="form-group">
                      
                        <label class="control-label">Začiatok tréningu</label>
                        <input class="form-control" type="time" name="strtime" placeholder="">
                        <span class="error"><?php echo $strtime_err;?></span>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="skname" class="control-label">Koniec tréningu</label>
                        <input type="time" class="form-control" name="endtime" placeholder="">
                        <span class="error"><?php echo $endtime_err;?></span>

                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="form-group">
                      
                        <label class="control-label">Zemepisná šírka začiatku</label>
                        <input class="form-control" type="text" name="gsplastart" placeholder="">
                        <span class="error"><?php echo $gsplastart_err;?></span>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="skname" class="control-label">Zemepisná dĺžka začiatku</label>
                        <input type="text" class="form-control" name="gsplostart" placeholder="">
                        <span class="error"><?php echo $gsplostart_err;?></span>

                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="form-group">
                      
                        <label class="control-label">Zemepisná šírka konca</label>
                        <input class="form-control" type="text" name="gsplaend" placeholder="">
                        <span class="error"><?php echo $gsplaend_err;?></span>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="skname" class="control-label">Zemepisná dĺžka konca</label>
                        <input type="text" class="form-control" name="gsploend" placeholder="">
                        <span class="error"><?php echo $gsploend_err;?></span>

                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <label for="skool" class="control-label">Hodnotenie</label>
                    <select id="skool" class="form-control" name="rate" placeholder="Vyberte si">
                        <?php 
                        global $RUN_RATINGS;

                        foreach ($RUN_RATINGS as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                        ?>
                    </select>
                  </div>
                <div class="form-group">
                  <label>Poznámka</label>
                  <textarea class="form-control" name="note" rows="3" placeholder="Poznámka k behu ..."></textarea>
                    <span class="error"><?php echo $note_err;?></span>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary" value="Submit">Submit</button>
              </div>
            </form>
          </div>

  

</div>


  <div class="col-md-6">



          <div class="box box-primary" style="min-height: 450px;height: 100%;width: 100%;">
            <div class="box-header with-border">
              <h3 class="box-title">Mapa pre výber koordinátov</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div id="map" style="width: 100%; height: 100%; display: block;min-height: 350px;"></div>
            <div id="pano"></div>
            <br>
            <div class="col-sm-12 col-md-12 review">
              <div class="form-group">
                <div class="col-sm-12 col-md-6">
                    <label>Zemepisná šírka:</label>
                    <input id="lat" type="text" readonly="readonly" class="full-width">
                </div>

                <div class="col-sm-12 col-md-6">
                    <label>Zemepisná dĺžka:</label>
                    <input id="long" type="text" readonly="readonly" class="full-width">
                </div>
              </div>
            </div>
          </div>
</div>

<script   src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer></script>
<?php 
 $page->renderFooter();

?>
