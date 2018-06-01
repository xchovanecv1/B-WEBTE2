<?php
require_once 'Page.php';

if(!check_access_role(ROLE_USER,true))
{
    return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

$routename = $rtype = $mapData = $length = "";
$routename_err = $rtype_err = $mapData_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!empty($_POST['addroute'])) {
        $err = 0;
        if (empty($_POST['routename'])) {
            $routename_err = "Zadajte nazov trasy";
            $err = 1;
        } else {
            $routename = ($_POST["routename"]);
        }

        if(empty($_POST['rtype']))
        {
            $rtype_err = "Nevybrali ste mod trasy";
            $err = 1;
        }else {
            if($_POST['rtype'] != "1")
            {
                if(!check_access_role(ROLE_ADMIN,true))
                {
                    $rtype_err = "Zadaný režim nie je dostupný!";
                    $err = 1;
                }
            }
            $rtype = test_input($_POST["rtype"]);
        }


        if (empty($_POST['mapData']) && empty($_POST['trasaod']) && empty($_POST['trasado'])) {
            $mapData_err = "Nie je zvolená žiadna trasa, zadajte dopyt alebo trasu zvoľte na mape";
            $err = 1;
        } else {
            $mapData = test_input($_POST["mapData"]);
            $Data = json_decode($mapData,true);
            $length = $Data['length'];
        }
//        print_r($mapData);
        // ok secko zadame mozme spracovat
//        if ($err == 0) {
//            var_dump($_POST);
//        }
    }
    // print_r($mapData);
    // echo $length;
    if(empty($routename_err) && empty($rtype_err) && empty($mapData_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO `route` (`id`, `name`, `definition`, `length`, `active`, `creator`, `type`) VALUES (NULL, ?, ?, ?, 0, ?, ?)";
        if($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssddi",$routename,$mapData,$length,$_SESSION['ID'],$rtype);

            if ($stmt->execute()) {
                $id = $stmt->insert_id;
                add_message("","Trasa bola úspešne pridaná!");
                add_message("warning","Trasa je štandardne deaktivovaná, je potrebné ju aktivovať dodatočne!");

                header('Location: ' . filter_var(PATH_ROUTE_DETAIL."?id=".$id, FILTER_SANITIZE_URL));
                return;
            } else {
                add_message("error","Počas pridávania trasy nastal problém!");
            }
        }
// Close statement
        $stmt->close();
    }


// Close connection
    $mysqli->close();

}
function test_input($data) {
    $data = trim($data);
    return $data;
}

$page->setHeader("Pridať trasu");

$page->renderHeader();
display_errors();

?>

    <script type="text/javascript" src="map_create.js"></script>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pridanie trasy</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post">
                <div class="box-body">
                    <div class="form-group">
                        <label >Názov trasy</label>
                        <input class="form-control" type="text" name="routename" placeholder="Názov trasy">
                        <span class="error"><?php echo $routename_err;?></span>

                    </div>
                    <div class="form-group">
                        <label class="control-label">Typ trasy</label>
                        <select id="rtpye" class="form-control" name="rtype" placeholder="Vyberte si">
                            <option value="1">Privátny mód</option>
                            <?php 
                            if(check_access_role(ROLE_ADMIN,false))
                            { ?>
                                <option value="2">Verejný mód</option>
                                <option value="3">Štafetový mód</option>
                            <?php }?>
                        </select>
                        <span class="error"><?php echo $rtype_err;?></span>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-md-12">
                            <div class="col-sm-12 col-md-5">
                                <label class="control-label">Trasa od:</label>
                                <input type="text" class="form-control" id="address-from" class="full-width" value="" name="trasaod">
                            </div>

                            <div class="col-sm-12 col-md-5">
                                <label class="control-label">Trasa do:</label>
                                <input type="text" class="form-control" id="address-to" class="full-width" value="" name="trasado">
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <label class="control-label">Akcia:</label>
                                <button type="button" id="showWay" class="form-control btn btn-primary">Zobraziť trasu</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="skool" class="control-label">Výber trasy</label>
                        <div id="map" style="width: 100%; height: 100%; display: block;min-height: 350px;"></div>
                        <div id="pano"></div>
                        <input type="hidden" name="mapData" id="mapData">
                        <span class="error"><?php echo $mapData_err;?></span>

                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" name="addroute" value="addroute" class="btn btn-primary">Pridať trasu</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer></script>

<?php
$page->renderFooter();

?>
