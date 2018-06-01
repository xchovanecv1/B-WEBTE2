<?php
require_once 'Page.php';

if(!check_access_role(ROLE_ADMIN,true))
{
    return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

$group_data = array();
$show_detail = 1;
if(!empty($_GET['id']))
{
    if ($sel = $mysqli->prepare("SELECT * FROM `groups` WHERE `groups`.`id` = ?")) {

    $sel->bind_param("d", $_GET['id']);
            if ($sel->execute()) {

                $result = $sel->get_result();
                $data = $result->fetch_assoc();
                if ($result->num_rows == 1) {
                    $group_data = $data;
                } else {
                     add_message("error","Skupina so zadaným ID neexistuje!");
                     $show_detail = 0;
                }
            } else {
                $show_detail = 0;
            }
    } else {
                $show_detail = 0;
            }
} else {
    add_message("error","Nezadali ste ID skupiny!");
    $show_detail = 0;
}

if($show_detail)
{
    if(isset($_GET['did']))
    {
        if ($sel = $mysqli->prepare("DELETE FROM `group_users` WHERE `group_users`.`groupid` = ? AND `group_users`.`id` = ?")) {

        $sel->bind_param("dd", $_GET['id'], $_GET['did']);
                if ($sel->execute()) {
                    if ($sel->affected_rows > 0) {
                        add_message("","Uživateľ bol úspešne zmazaný!");
                    }
                }
        }
    }

    if(!empty($_POST['addusr']) && !empty($_POST['user']))
    {
        if($sels = $mysqli->prepare("SELECT * FROM `group_users` WHERE `group_users`.`groupid` = ?;")) {

            $sels->bind_param("d",$group_data['id']);
            if($sels->execute())
            {
                $result = $sels->get_result();
                if($result->num_rows < MAX_USERS_PER_GROUP)
                {

                    if($sels = $mysqli->prepare("INSERT INTO `group_users` (`id`, `groupid`, `userid`) VALUES (NULL, ?, ?)")) {

                        $sels->bind_param("dd",$group_data['id'],$_POST['user']);
                        if($sels->execute())
                        {
                            add_message("","Uživateľ bol úspešne pridaný!");
                        }
                    }
                } else {
                    add_message("error","Dosiahli ste maximálny počet (".MAX_USERS_PER_GROUP.") pre jednu skupinu!");
                }
            }
        }
    }
}

$users_data = array();
$exclude = "(0)";
if($show_detail == 1)
{
    if ($sel = $mysqli->prepare("SELECT gu.id as 'guid', u.Name as 'name', u.ID as 'userid' FROM `group_users` gu LEFT JOIN `users` u ON gu.userid = u.ID WHERE gu.`groupid` = ?")) {

    $sel->bind_param("d", $_GET['id']);
            if ($sel->execute()) {
                
                $result = $sel->get_result();
                
                if ($result->num_rows > 0) {
                    
                    while($data = $result->fetch_assoc())
                    {
                        $users_data[$data['guid']] = $data;
                    }
                }
            } else {
                $show_detail = 0;
            }
    } else {
                $show_detail = 0;
    }

    if ($sel = $mysqli->prepare("SELECT gu.userid FROM `group_users` gu LEFT JOIN groups g ON gu.groupid = g.id WHERE g.route = ?")) {
        $sel->bind_param("d", $group_data['route']);
            if ($sel->execute()) {
                
                $result = $sel->get_result();
                
                if ($result->num_rows > 0) {
                    $exclude = "(";
                    while($data = $result->fetch_assoc())
                    {
                        $exclude .= $data['userid'].", ";
                    }
                    $exclude = rtrim($exclude,", ");
                    $exclude .= ")";
                }
            }
    }
}

$select_users = array();
if($show_detail == 1)
{
    if ($result = $mysqli->query("SELECT u.id as 'id', u.Name as 'name', a.City as 'city' FROM `users` u LEFT JOIN `address` a on u.Address = a.ID WHERE NOT u.ID IN ".$exclude.";")) {

    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
      $select_users[$row['id']] = $row['name']." (".$row['city'].")";
    }
    /* free result set */
    $result->close();
    }

}

$page->setHeader("Detail skupiny");

$page->renderHeader();
display_errors();

?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detail skupiny</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post">
                <div class="box-body">
                    <div class="form-group">
                        <a href="<?php echo BASE_URL.PATH_ROUTE_DETAIL;?>?id=<?php echo $group_data['route']?>" class="btn btn-success">Späť na trasu</a>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Uživatelia</label>
                        <select id="rtpye" class="form-control" name="user" placeholder="Vyberte si">
                            <?php 
                            foreach ($select_users as $key => $value) {
                                echo '<option value="'.$key.'">'.$value.'</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="form-group">
                        <button type="submit" name="addusr" value="addusr" class="btn btn-primary">Pridať uživateľa</button>
                    </div>
                    <?php 
                    if(count($users_data))
                    {
                        echo '
                    <div class="form-group">
                        <label class="control-label">Uživateľia priradeny k skupine</label>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Meno</th>
                                    <th>Akcia</th>
                                </tr>
                            </thead>
                            <tbody>';
                               foreach ($users_data as $key => $value) {
                                   echo '
                                <tr>
                                    <td>'.$key.'</td>
                                    <td>'.$value['name'].'</td>
                                    <td><a href="'.BASE_URL.PATH_GROUP_DETAIL.'?id='.$group_data['id'].'&did='.$key.'">Zmazať</a></td>
                                </tr>
                                   ';
                               }
                        echo '
                            </tbody>
                        </table>
                    </div>
                        ';
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
<?php
$page->renderFooter();

?>
