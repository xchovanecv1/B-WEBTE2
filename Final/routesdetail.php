<?php
require_once 'Page.php';

if(!check_access_role(ROLE_USER,true))
{
    return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

$route_data = array();
$show_detail = 1;
if(!empty($_GET['id']))
{
    if ($sel = $mysqli->prepare("SELECT * FROM `route` WHERE `route`.`id` = ?")) {

    $sel->bind_param("d", $_GET['id']);
            if ($sel->execute()) {

                $result = $sel->get_result();
                $data = $result->fetch_assoc();

                if ($result->num_rows == 1) {
                    $route_data = $data;
                } else {
                     add_message("error","Trasa so zadaným ID neexistuje!");
                     $show_detail = 0;
                }
            } else {
                $show_detail = 0;
            }
    } else {
                $show_detail = 0;
            }
} else {
    add_message("error","Nezadali ste ID trasy!");
    $show_detail = 0;
}

if($show_detail == 1)
{
    if($route_data['type'] == 1) // Private
    {
        if($route_data['creator'] != $_SESSION['ID'])
        {
            if(!check_access_role(ROLE_ADMIN,true))
            {
                add_message("error","Nemáte oprávnenia pre zobrazenie tejto trasy!");
                $show_detail = 0;
            }
        }
    }
}
//
$route_groups = array();

if($show_detail == 1)
{
    if($route_data['type'] == 3) // Štafeta
    {
        if ($result = $mysqli->query("SELECT * FROM `groups` WHERE `groups`.`route` = ".$route_data['id'].";")) {

        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $clens = "";

            if ($res = $mysqli->query("SELECT u.Name as 'Name' FROM `group_users` gu LEFT JOIN `users` u ON gu.userid = u.ID WHERE gu.`groupid` = ".$row['id'].";")) {
                while($dta = $res->fetch_array(MYSQLI_ASSOC))
                {
                    $clens .= $dta['Name'].", ";
                }
                $clens = rtrim($clens,", ");

                $res->close();
            }
            $row['clens'] = $clens;
            $route_groups[$row['id']] = $row;

        }
        /* free result set */
        $result->close();
        }
    }
}

$page->setHeader("Detail trasy");

$page->renderHeader();
display_errors();

?>


    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detail trasy</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post">
                <div class="box-body">
                    <div class="form-group">
                        <label >Názov trasy</label>
                        <input class="form-control" type="text" name="routename" value="<?php echo $route_data['name'];?>" placeholder="Názov trasy" disabled>

                    </div>
                    <div class="form-group">
                        <label class="control-label">Typ trasy</label>
                        <select id="rtpye" class="form-control" name="rtype" placeholder="Vyberte si" disabled="">
                            <option value="1" <?php echo ($route_data['type'] == 1 ? "selected" : ""); ?>>Privátny mód</option>
                            <option value="2" <?php echo ($route_data['type'] == 2 ? "selected" : ""); ?>>Verejný mód</option>
                            <option value="3" <?php echo ($route_data['type'] == 3 ? "selected" : ""); ?>>Štafetový mód</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label class="control-label">Aktívna</label>
                        <select id="active" class="form-control" name="active" placeholder="Vyberte si" disabled="">
                            <option value="0" <?php echo ($route_data['active'] == 0 ? "selected" : ""); ?>>Neaktívna</option>
                            <option value="1" <?php echo ($route_data['active'] == 1 ? "selected" : ""); ?>>Aktívna</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <a href="./<?php echo PATH_ROUTE_DETAIL.'?id='.$route_data['id'];?>" name="chngactv" value="chngactv" class="btn btn-success btn-block">Upraviť nastavenia trasy</a>
                    </div>
                    <?php 
                    if($route_data['type'] == 3)
                    {
                        echo '
                    <div class="form-group">
                        <label class="control-label">Skupiny priradené k trase</label>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Skupiny</th>
                                    <th>Členovia</th>
                                </tr>
                            </thead>
                            <tbody>';
                        if(count($route_groups))
                        {
                               foreach ($route_groups as $key => $value) {
                                   echo '
                                <tr>
                                    <td>'.$key.'</td>
                                    <td>'.$value['clens'].'</td>
                                </tr>
                                   ';
                               }
                        }
                        echo '
                            </tbody>
                        </table>
                    </div>
                        ';
                    }
                    ?>
                    <div class="form-group">
                        <label for="skool" class="control-label">Trasa</label>
                        <div id="map" style="width: 100%; height: 100%; display: block;min-height: 350px;"></div>
                        <div id="pano"></div>
                        <input type="hidden" name="mapData" value='<?php echo base64_encode(($route_data['definition'])); ?>' id="mapData">
                        <?php 
                        if($route_data['type'] == 1) // Private
                        {
                            echo '<input type="hidden" id="userID" value="'.$route_data['creator'].'">';
                            echo '<input type="hidden" id="routeID" value="'.$route_data['id'].'">';

                        } else {
                            echo '<input type="hidden" id="routeID" value="'.$route_data['id'].'">';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php 
if($show_detail)
{
if($route_data['type'] != 1) // Štafeta
{
    echo '<script type="text/javascript" src="show_runners_on_route.js"></script>';
} else {
    echo '<script type="text/javascript" src="show_runner_position_on_route.js"></script>';
}
?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer></script>

<?php
}
$page->renderFooter();

?>
