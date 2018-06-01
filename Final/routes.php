<?php

require_once "Page.php";


if(!check_access_role(ROLE_USER,true))
{
    return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

$page->setHeader("Zoznam trás");

$page->renderHeader();
display_errors();
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
<?php

        $sort = $_REQUEST['sortByVal1'];
        $order = "ASC";
        $invord = "desc";
        $userID = $_REQUEST['targetUserID'];
        if(!empty($userID))
            $userID = $mysqli->real_escape_string($userID);
        else 
            $userID = "";

        $where = "";
        if(!empty($userID))
                $where = "WHERE route.creator = ".($userID);

        if(!check_access_role(ROLE_ADMIN,true))
        {
            $where = "WHERE (NOT route.type = 1) OR (route.type = 1 AND route.creator = ".$_SESSION['ID'].")";    
        }
        
        if(empty($sort)){
            $sort = "route.id";
        }
        if(!empty($order))
        {
            if($_GET['ord'] == 'desc')
            {
                $order = "DESC";
                $invord = "asc";
            }
        }
        
        if($userID == "" || $userID == $_SESSION['ID']){
            print "<h2>Prehľad všetkých dostupných trás</h2>";
        }
        else{
            print "<h2>Prehľad všetkých dostupných trás užívateľa s ID č. " . $userID . "</h2>"; 
        }

        if(!empty($userID))
        {
            echo "<a class='btn btn-block btn-success' href='".PATH_ROUTES."'>Späť na všetky trasy</a>";
        }

        print "<table class=\"table table-striped\">";
        print "<tr>";
        print "<th><a href=\"".PATH_ROUTES."?sortByVal1=route.id&targetUserID=".$userID."&targetUserID=" . $userID . "&ord=".$invord."\">ID</a></th>";
        print "<th><a href=\"".PATH_ROUTES."?sortByVal1=route.name&targetUserID=".$userID."&targetUserID=" . $userID . "&ord=".$invord."\">Názov</a></th>";
        print "<th><a href=\"".PATH_ROUTES."?sortByVal1=users.Name&targetUserID=".$userID."&targetUserID=" . $userID . "&ord=".$invord."\">Autor</a></th>";
        print "<th><a href=\"".PATH_ROUTES."?sortByVal1=route.active&targetUserID=".$userID."&targetUserID=" . $userID . "&ord=".$invord."\">Aktívna/Nektívna</a></th>";
        print "<th><a href=\"".PATH_ROUTES."?sortByVal1=route.type&targetUserID=".$userID."&targetUserID=" . $userID . "&ord=".$invord."\">Mód</a></th>";
        echo "<th>Akcia</th>";
        print "</tr>";

        $query = "SELECT route.id AS 'id', route.name AS 'nameRoute', users.Name AS 'nameUser', users.ID AS 'idUser', route.active AS 'active', route.type AS 'type' FROM route INNER JOIN users ON route.creator = users.ID ".$where." ORDER BY $sort ".$order;
        
        foreach ( $mysqli->query("$query") as $row ) {

            if($row['active'] == 0){
                $activeString = "<i class='btn btn-block btn-danger'><b>Neaktívna</b></i>";
            }
            else if($row['active'] == 1){
                $activeString = "<i class='btn btn-block btn-success'><b>Aktívna</b></i>";
            }

            if($row['type'] == 1){
                $typeString = "Privátny";
            }
            else if($row['type'] == 2){
                $typeString = "Verejný";
            }
            else if($row['type'] == 3){
                $typeString = "Štafetový";
            }

            print   "<tr>";
            print   "<td>" . $row['id'] . "</td>" . 
                    "<td>" . $row['nameRoute'] . "</td>" . 
                    "<td><a href=\"./".PATH_ROUTES."?targetUserID=".$row['idUser']."\">" . $row['nameUser'] . "</a></td>" . 
                    "<td>" . $activeString . "</td>" . 
                    "<td>" . $typeString . "</td>" .
                    "<td><a href='".PATH_ROUTES_DETAIL."?id=".$row['id']."'>Prezrieť</a></td>";
            print   "</tr>";
        }

        print "</table><br>";
    ?>

 </div>
        </div>
    </div>

<?php
$page->renderFooter();

?>
