<?php
require_once 'Page.php';
if(!empty($_POST['action']))
{
    $data = array('action' => $_POST['action']);
    switch ($_POST['action']) {
        case 'getRunningData':
            if(!empty($_POST['routeID']))
            {

                if($stmt = $mysqli->prepare("SELECT route.definition as \"def\" FROM route where route.id = ?")){
                    $stmt->bind_param("i",$route);
                    $route = $_POST['routeID'];
                    $stmt->execute();
                    $stmt->bind_result( $def);
                    $stmt->fetch();
                    $data['description'] = $def;

                    $stmt->close();
                }

                //UC: konkretny uzivatel si prezera, kolko presiel na konkretnej trase
                if(!empty($_POST['userID']))
                {
                    //if($_POST['userID'] > 0 && $_POST['routeID'] > 0){
                    if($stmt = $mysqli->prepare("SELECT c.definition AS def, (SELECT SUM(b.Length) AS \"Length\" FROM users a JOIN run b ON a.ID = b.User WHERE b.Route = ? AND a.ID = ? GROUP BY a.ID) AS \"Length\" FROM users a JOIN run b ON a.ID = b.User JOIN route as c on b.Route = c.id WHERE b.Route = ? and a.ID = ? GROUP  BY def")){
                        $stmt->bind_param("iiii",$route,$ID, $route2, $ID2 );
                        $route = $_POST['routeID'];
                        $ID = $_POST['userID'];
                        $route2 = $_POST['routeID'];
                        $ID2 = $_POST['userID'];
                        /* $route = 2;
                         $ID = 2;
                         $route2 = 2;
                         $ID2 = 2;*/
                        $stmt->execute();
                        $stmt->bind_result( $def, $Length);
                        $stmt->fetch();

                        $data['vzdialenost'] = $Length;
                    }
                } else {
                    $data['users'] = array();
                    if ($stmt = $mysqli->prepare("Select b.definition as \"def\", b.type as \"type\" FROM run as a join route as b on a.Route = b.id WHERE a.Route = ? GROUP BY  a.Route")) {
                        $stmt->bind_param("i", $route);
                        $route = $_POST['routeID'];
                        $stmt->execute();
                        //var_dump($stmt);
                        $stmt->bind_result($def, $type);
                        $stmt->fetch();
                        $typeRunners = $type;

                        $stmt->close();
                    }
                    //echo $typeRunners."<br>";
                    if ($typeRunners == 2) {
                        if ($stmt2 = $mysqli->prepare("SELECT a.ID as \"id\", a.Name AS \"name\", SUM(b.Length) AS \"length\" FROM users as a join run as b on a.ID = b.User JOIN route as c on b.Route = c.id WHERE b.Route = ? GROUP BY a.ID ORDER BY length DESC")) {
                            $stmt2->bind_param("i", $route);
                            //$route = $_POST['routeID'];
                            $route = $_POST['routeID'];
                            $stmt2->execute();
                            //var_dump($stmt2);
                            $stmt2->bind_result($id, $name, $length);
                            while ($stmt2->fetch()) {
                                //var_dump($stmt->fetch());
                                $data['users'][] = array("name" => $name, "length" => $length, "type" => "user");
                            }
                            $stmt2->close();
                        }
                    } elseif($typeRunners == 3) {
                        $crews = array();
                        if ($members = $mysqli->prepare("SELECT crew.id as \"groupID\",  member.Name as \"memberName\", member.ID as \"idecko\"  FROM groups as crew JOIN route as rout on crew.route = rout.id JOIN group_users as grou on crew.id = grou.groupid join users as member on grou.userid= member.ID WHERE rout.id = ? AND rout.type = 3")) {
                            $mysqli->set_charset("utf8");
                            $members->bind_param("i", $route);
                            //$route = $_POST['routeID'];
                            $route = $_POST['routeID'];
                            $members->execute();
                            //var_dump($stmt2);
                            $members->bind_result($groupID, $memberName, $idecko);
                            while ($members->fetch()) {
                                                                //var_dump($stmt->fetch());
                                if(!empty($crews[$groupID]))
                                {
                                    $crews[$groupID][] = $memberName;
                                } else {
                                    $crews[$groupID] = array();
                                    $crews[$groupID][] = $memberName;
                                }
                            }
                            $members->close();
                        }

                        $dataJASON[][] = array();
                        $groupRunLanght = 0;
                        $clenovia[] = array();
                        $teamCompare = "";
                        $j =0;
                        $k = 0;

                        foreach ($crews as $key => $value) {
                            if($groups = $mysqli->prepare("SELECT sum(run.Length) as \"runLenght\" FROM run WHERE run.Route = ? and run.Groupid = ?")){
                                $groups->bind_param("ii",$_POST['routeID'], $key);
                                $groups->execute();
                                $res = $groups->get_result();
                                while($row = $res->fetch_assoc())
                                {
                                    if(!empty($row['runLenght']))
                                    {
                                        $data['users'][] = array("length" => $row['runLenght'], "type" => "group", "team" => "ID č. ".$key, "members" => $value);
                                    }
                                }
                            }
                        }

                    }
                }
            }
            break;
        case 'schoolMap':
            $data['schools'] = array();
            $count = 0;
            if($user = $mysqli->query("SELECT c.ID as 'addID',a.Name as \"nameUser\", b.Name as \"nameSchool\", c.Geo as \"geo\" FROM users as a JOIN schools as b on a.School = b.ID JOIN address as c on b.Address=c.ID ORDER BY c.City, b.Name")){

                $shols = array();

                while($row = $user->fetch_assoc())
                {
                    if(!empty($row['geo']))
                    {
                        if(!empty($shols[$row['addID']]))
                        {
                            array_push($shols[$row['addID']]["users"],$row["nameUser"]);
                        } else {
                            $shols[$row['addID']] = array("name" => $row['nameSchool'], "geo" => $row['geo'], "users"=> array($row["nameUser"]));
                        }
                    }
                }
                foreach ($shols as $key => $value) {
                    $data['schools'][$count] = $value;
                    $count++;
                }
            }
            break;
        case 'usersMap':
            $data['users'] = array();
            //$sql = "SELECT b.City as \"city\", a.Name AS \"users\" FROM users as a JOIN address b ON a.Address = b.ID";
            if($user = $mysqli->query("SELECT b.ID as 'addID', b.City as \"city\", a.Name AS \"users\",  b.Geo as \"geo\" FROM users as a JOIN address b ON a.Address = b.ID ORDER BY b.City")){

                $usrs = array();
                while($row = $user->fetch_assoc())
                {
                    if(!empty($row['geo']))
                    {
                        $usrs[] = array("city" => $row['city'], "geo" => $row['geo'], "users"=> array($row["users"]));
                    }
                }

                $data['users'] = $usrs;
            }
            break;
    }
    echo json_encode($data);
}
?>