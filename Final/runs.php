<?php
require_once 'Page.php';
require_once 'tablePerformancePDF.php';

mb_internal_encoding('UTF-8');

if(!check_access_role(ROLE_USER,true))
{
    return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné byť prihlásený ako Administrátor", "error");
}

$page->setHeader("Detail behu");

        $sort = $mysqli->real_escape_string($_REQUEST['sortByVal2']);
        $userID = $mysqli->real_escape_string($_REQUEST['targetUserID']);

        if(empty($userID))
        {
            $userID = $_SESSION['ID'];
        }

        if($userID != $_SESSION['ID'])
        {
            if(!check_access_role(ROLE_ADMIN,true))
            {
                $userID = $_SESSION['ID'];
            }
        }

        if($sort == ""){
            $sort = "Date";
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
        
        $output = "";


        if($userID == "" || $userID == $_SESSION['ID']){
            $output .="<h2>Prehľad mojich výkonov</h2>";
        }
        else{

            $query = "SELECT Name FROM users WHERE ID=$userID";
            $data = $mysqli->query($query)->fetch_assoc();
            $output .="<h2>Prehľad výkonov užívateľa " . $data["Name"] . " (ID: ".$userID.")</h2>"; 
        }
        $output .= "<table class=\"table table-striped\">";
        $output .= "<tr>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=Date&ord=".$invord."&targetUserID=" . $userID . "\">Dátum</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=Start&ord=".$invord."&targetUserID=" . $userID . "\">Čas (začiatok)</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=End&ord=".$invord."&targetUserID=" . $userID . "\">Čas (koniec)</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=StLat&ord=".$invord."&targetUserID=" . $userID . "\">Z.Š. (začiatok)</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=StLon&ord=".$invord."&targetUserID=" . $userID . "\">Z.D. (začiatok)</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=EnLat&ord=".$invord."&targetUserID=" . $userID . "\">Z.Š. (koniec)</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=EnLon&ord=".$invord."&targetUserID=" . $userID . "\">Z.D. (koniec)</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=Length&ord=".$invord."&targetUserID=" . $userID . "\">Vzdialenosť</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=Rate&ord=".$invord."&targetUserID=" . $userID . "\">Hodnotenie</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=Note&ord=".$invord."&targetUserID=" . $userID . "\">Poznámka</a></th>";
        $output .= "<th><a href=\"".PATH_RUNS."?sortByVal2=Speed&targetUserID=" . $userID . "&ord=".$invord."\">Rýchlosť (km/h)</a></th>";
        $output .= "</tr>";


        $query = "SELECT Date, Start, End, StLat, StLon, EnLat, EnLon, Length, Rate, Note, (Length/(TIME_TO_SEC(TIMEDIFF(End,Start))/3600)) as 'Speed' FROM run WHERE User=$userID ORDER BY $sort $order";

        $distance_total = (float)0;
        $performance_counter = 0;

        foreach ( $mysqli->query($query) as $row ) {

            $output .=   "<tr>";
            $output .=   "<td>" . $row['Date'] . "</td>" . 
                    "<td>" . $row['Start'] . "</td>" . 
                    "<td>" . $row['End'] . "</td>" . 
                    "<td>" . $row['StLat'] . "</td>" . 
                    "<td>" . $row['StLon'] . "</td>" . 
                    "<td>" . $row['EnLat'] . "</td>" . 
                    "<td>" . $row['EnLon'] . "</td>" . 
                    "<td>" . $row['Length'] . " km</td>" . 
                    "<td>" . (!empty($RUN_RATINGS[$row['Rate']]) ? $RUN_RATINGS[$row['Rate']] : "Neznáme hodnotenie" ) . "</td>" . 
                    "<td>" . $row['Note'] . "</td>" .
                    "<td>" . round($row['Speed'],2) . "</td>";
            $output .=   "</tr>";

            $performance_counter++; 
            $distance_total += (float)$row['Length'];
        }

        if($performance_counter)
                $distance_avg = (float)$distance_total / (float)$performance_counter;
        else {
                $distance_avg = 0;
        }
        $output .= "</table><br>";

        $output .= "<i class='btn btn-block btn-success'><b>Priemerná vzdialenosť pre všetky výkony: " . round($distance_avg,2) . " km</b></i><br>";

        if(isset($_GET['way']))
        {
            if($_GET['way'] == 'pdf')
            {
                return generate_pdf($output);
            }
        }

$page->renderHeader();
display_errors();

?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">

                <?php

                echo $output;
        // Task #10
        $urlparams = end(explode("?",$_SERVER['REQUEST_URI']));

        print "<br><a class='btn btn-primary' target='_blank' href=\"".PATH_RUNS."?".$urlparams."&way=pdf\"><b>Vygeneruj PDF</b></a>";
    ?>

 </div>
        </div>
    </div>

<?php
$page->renderFooter();

?>
