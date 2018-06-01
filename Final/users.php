<?php
require_once 'Page.php';

if(!check_access_role(ROLE_ADMIN,true))
{
    return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

$page->setHeader("Zoznam užívateľov");

$page->renderHeader();
display_errors();
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
<?php
        $sort = $_REQUEST['sortByVal3'];

        $userID = $_REQUEST['targetUserID'];

        if(empty($sort)){
            $sort = "ID";
        } else {
            $sort = $mysqli->real_escape_string($sort);
        }


        $order = "ASC";
        $invord = "desc";        


        if(!empty($order))
        {
            if($_GET['ord'] == 'desc')
            {
                $order = "DESC";
                $invord = "asc";
            }
        }

        print "<h2>Prehľad všetkých užívateľov</h2>";
        print "<a href=\"".PATH_USERS."\">Zrušiť výber</a>";
        print "<table class=\"table table-striped\">";
        print "<tr>";
        print "<th><a href=\"".PATH_USERS."?sortByVal3=ID&ord=".$invord."&targetUserID=" . $userID . "\">ID</th>";
        print "<th><a href=\"".PATH_USERS."?sortByVal3=Name&ord=".$invord."&targetUserID=" . $userID . "\">Meno a Priezvisko</th>";
        print "<th>Akcia</th>";
        print "</tr>";

        $query = "SELECT ID, Name FROM users ORDER BY $sort ".$order;

        foreach ( $mysqli->query("$query") as $row ) {
            print   "<tr>";
            print   "<td>" . $row['ID'] . "</td>" . 
                    "<td>" . $row['Name'] . "</td>".
                    "<td><a href=\"".PATH_ROUTES."?targetUserID=" . $row['ID'] . "\">Prehľad trás</a> | <a href=\"".PATH_RUNS."?targetUserID=" . $row['ID'] . "\">Prehľad behov</a></td>";
            print   "</tr>";
        }

        print "</table>";
    ?>

 </div>
        </div>
    </div>

<?php
$page->renderFooter();

?>
