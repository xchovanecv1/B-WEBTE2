<?php
require_once 'Page.php';

if(!check_access_role(ROLE_USER,true))
{
	return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné sa prihlásiť", "error");
}

function load_school_list($mysqli,&$list)
{
	if ($result = $mysqli->query("SELECT s.ID, s.Name, a.City FROM `schools` s LEFT JOIN `address` a ON s.Address = a.ID WHERE 1;")) {

    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
      $list[$row['ID']] = $row['Name']." (".$row['City'].")";
    }
    /* free result set */
    $result->close();
	}
}

$school_list = array();

load_school_list($mysqli,$school_list);

$skname_err = $street_err = $number_err = $zip_err = $city_err ="";

// POST -> zmena skoly zo selektu
if(!empty($_POST['chngsk']))
{
  //vybral skolu zo selektu
  if(!empty($_POST['school']))
  {
  	//SELECT * FROM `schools` WHERE `ID` = 1
  	if(!empty($school_list[$_POST['school']]))
  	{
  		if($addrchng = $mysqli->prepare("UPDATE `users` SET `School` = ? WHERE `users`.`ID` = ?;")) {

	          $addrchng->bind_param("si",$_POST['school'], $_SESSION['ID']);
	          if($addrchng->execute())
	          {

                load_user_data($mysqli,$_SESSION['ID']);
	          	add_message("","Zmena školy prebehla úspešne");
	          } else {
	          	add_message("error","Počas zmeny školy nastal problém!");
	          }
	          $addrchng->close();
	     }
  	}
  }
} else if(!empty($_POST['addsk']))  // Pridanie novej skoly
{
  $err = 0;

  if(empty($_POST['skname'])) // nazov skoly
  {
      $skname_err = "Zadajte názov školy!";
    $err = 1;
  }
  $addr = array();

  if(empty($_POST['street'])) // nazov skoly
  {
      $street_err = "Zadajte názov ulice!";
    $err = 1;
  } else $addr['Street'] = $_POST['street'];
  if(empty($_POST['city'])) // ulica
  {
  	$city_err = "Zadajte názov mesta!";
    $err = 1;
  } else $addr['City'] = $_POST['city'];
  if(empty($_POST['zip'])) // cislo domu
  {
  	$zip_err = "Zadajte PSČ!";
    $err = 1;
  } else $addr['ZIP'] = $_POST['zip'];
  if(empty($_POST['number']))  
  {
  	$number_err = "Zadajte označovacie číslo!";
    $err = 1;
  } else $addr['Number'] = $_POST['number'];

  if($err == 0) // secko ok mozme poslat ziadost
  {
  	$temp_addr = fetch_address($mysqli,$addr);
  	if($sel = $mysqli->prepare("SELECT *  FROM `schools` WHERE `Address` = ? OR `Name` = ?")) {

    $sel->bind_param("ds", $temp_addr,$_POST['skname']);
    if($sel->execute())
    {

        $result = $sel->get_result();
        if($result->num_rows == 0)
        {
        	if($sels = $mysqli->prepare("INSERT INTO `schools` (`ID`, `Type`, `Name`, `Address`) VALUES (NULL, NULL, ?, ?)")) {

		    $sels->bind_param("sd",$_POST['skname'], $temp_addr);
		    if($sels->execute())
		    {
		    	$school_id = $sels->insert_id;
		    	add_message("","Škola bola úspešne pridaná!");   

		    	load_school_list($mysqli,$school_list);

		    	if($addrchng = $mysqli->prepare("UPDATE `users` SET `School` = ? WHERE `users`.`ID` = ?;")) {

			          $addrchng->bind_param("si",$school_id, $_SESSION['ID']);
			          if($addrchng->execute())
			          {

		                load_user_data($mysqli,$_SESSION['ID']);
			          	add_message("","Zmena školy prebehla úspešne");
			          } else {
			          	add_message("error","Počas zmeny školy nastal problém!");
			          }
			          $addrchng->close();
			     }
		    } else {
		    	add_message("error","Počas pridávania školy nastal problém!");
		    }
			}
        } else {
	         add_message("error","Zadaná škola už existuje!");
        }
    }
	}
  }
} else if($_POST['chngpss']) // zmena hesla
{
    if(!empty($_POST['pass2'])){
  //array(3) { ["pass"]=> string(0) "" ["pass2"]=> string(0) "" ["chngpss"]=> string(7) "chngpss" }
  if(!empty($_POST['pass']) && !empty($_POST['pass2']))
  {
    if($_POST['pass'] == $_POST['pass2'])
    {
      // menime heslo
      // hash hesla pre db hash('sha256', $_POST['pass']);
    	if($addrchng = $mysqli->prepare("UPDATE `users` SET `Password` = ? WHERE `users`.`ID` = ?;")) {

	          $addrchng->bind_param("si",hash('sha256', $_POST['pass']), $_SESSION['ID']);
	          if($addrchng->execute())
	          {

                load_user_data($mysqli,$_SESSION['ID']);
	          	add_message("","Zmena hesla prebehla úspešne");
	          } else {
	          	add_message("error","Počas zmeny hesla nastal problém!");
	          }
	          $addrchng->close();
	     }
    } else {
    	add_message("error","Zadané heslá nie sú totožné!");
    }
  }
}
else{
       $pass2_err = "Je potrebné zopakovať heslo!";
}}

$user_address = NULL;
if(!empty($_SESSION['Address'])){
	$user_address = fetch_address_data($mysqli,$_SESSION['Address']);
}

if(!empty($_POST['chngadd']))
{
	$addr = array();
	$err = 0;

  if(empty($_POST['street'])) // nazov skoly
  {
      $street_err = "Zadajte názov ulice!";
    $err = 1;
  } else $addr['Street'] = $_POST['street'];
  if(empty($_POST['city'])) // ulica
  {
      $city_err = "Zadajte názov mesta!";
    $err = 1;
  } else $addr['City'] = $_POST['city'];
  if(empty($_POST['zip'])) // cislo domu
  {
      $zip_err = "Zadajte PSČ!";
    $err = 1;
  } else $addr['ZIP'] = $_POST['zip'];
  if(empty($_POST['number']))  
  {
      $number_err = "Zadajte označovacie číslo!";
    $err = 1;
  } else $addr['Number'] = $_POST['number'];

  if($err == 0) // secko ok mozme poslat ziadost
  {
  	$temp_addr = fetch_address($mysqli,$addr);
  	if(empty($_SESSION['Address']) || $_SESSION['Address'] != $temp_addr)
  	{
  		if(!empty($_SESSION['ID']))
  		{
  			//
  			if($addrchng = $mysqli->prepare("UPDATE `users` SET `Address` = ? WHERE `users`.`ID` = ?;")) {

	          $addrchng->bind_param("ii",$temp_addr, $_SESSION['ID']);
	          if($addrchng->execute())
	          {

                load_user_data($mysqli,$_SESSION['ID']);
                $user_address = fetch_address_data($mysqli,$temp_addr);
	          	add_message("","Zmena adresy prebehla úspešne");
	          } else {
	          	add_message("error","Počas zmeny adresy nastal problém!");
	          }
	          $addrchng->close();
	      }
  		}
  	}
  }
}


$page->setHeader("Profil používateľa");

$page->renderHeader();
display_errors();

$form_data = array();

$form_data['Name'] = "";
if(!empty($_SESSION['Name'])) $form_data['Name'] = $_SESSION['Name'];
$form_data['Email'] = "";
if(!empty($_SESSION['Mail'])) $form_data['Email'] = $_SESSION['Mail'];

?>

<div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#settings" data-toggle="tab">Osobné nastavenia</a></li>
              <li><a href="#school" data-toggle="tab">Navštevovaná škola</a></li>
            </ul>
            <div class="tab-content">
              <!-- /.tab-pane -->
              <div class="tab-pane" id="school">
                <form class="form-horizontal" method="post">
                  <div class="form-group">
                    <label for="skool" class="col-sm-2 control-label">Navštevovaná škola</label>

                    <div class="col-sm-10">
                      <select id="skool" class="form-control" name="school" placeholder="Vyberte si">
                        <?php 
                        foreach ($school_list as $key => $value) {
                          echo '<option value="'.$key.'" '.($key == $_SESSION['School'] ? "selected" : "").'>'.$value.'</option>';
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" name="chngsk" value="chngsk" class="btn btn-danger">Zmeniť školu</button>
                    </div>
                  </div>
                  <div class="box-header with-border">
                    <h3 class="box-title">Mnou navštevovaná škola nie je v zozname
                  </div>
                  <div class="form-group">
                    <label for="skname" class="col-sm-2 control-label">Názov školy</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="skname" name="skname" placeholder="Názov školy">
                        <span class="error"><?php echo $skname_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="strt" class="col-sm-2 control-label">Ulica</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="strt" name="street" placeholder="Ulica">
                        <span class="error"><?php echo $street_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="hsnm" class="col-sm-2 control-label">Označovacie číslo</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="hsnm" name="number" placeholder="Označovacie číslo">
                        <span class="error"><?php echo $number_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="psc" class="col-sm-2 control-label">PSČ</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="psc" name="zip" placeholder="PSČ">
                        <span class="error"><?php echo $zip_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="cty" class="col-sm-2 control-label">Mesto</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="cty" name="city" placeholder="Mesto">
                        <span class="error"><?php echo $city_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" name="addsk" value="addsk" class="btn btn-danger">Pridať žiadosť</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="timeline">
              </div>
              <div class="tab-pane active" id="settings">
                <form class="form-horizontal" method="post">
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" disabled value="<?php echo $form_data['Email']; ?>" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Meno používateľa</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="<?php echo $form_data['Name']; ?>" disabled id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Heslo</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="pass" id="pass">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Zopakuj heslo</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="pass2" id="pass2">
                        <span class="error"><?php echo $pass2_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" name="chngpss" value="chngpss" class="btn btn-danger">Zmeniť heslo</button>
                    </div>
                  </div>
              </form>
				<form class="form-horizontal" method="post">
                  <div class="form-group">
                    <label for="strt" class="col-sm-2 control-label">Ulica</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="strt" value="<?php echo (!empty($user_address['Street']) ? $user_address['Street'] : "" ); ?>" name="street" placeholder="Ulica">
                        <span class="error"><?php echo $street_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="hsnm" class="col-sm-2 control-label">Označovacie číslo</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="hsnm" value="<?php echo (!empty($user_address['Number']) ? $user_address['Number'] : "" ); ?>" name="number" placeholder="Označovacie číslo">
                        <span class="error"><?php echo $number_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="psc" class="col-sm-2 control-label">PSČ</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="<?php echo (!empty($user_address['ZIP']) ? $user_address['ZIP'] : "" ); ?>" id="psc" name="zip" placeholder="PSČ">
                        <span class="error"><?php echo $zip_err;?></span>

                    </div>
                  </div>
                  <div class="form-group">
                    <label for="cty" class="col-sm-2 control-label">Mesto</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="<?php echo (!empty($user_address['City']) ? $user_address['City'] : "" ); ?>" id="cty" name="city" placeholder="Mesto">
                        <span class="error"><?php echo $city_err;?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" name="chngadd" value="chngadd" class="btn btn-danger">Zmeniť adresu</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>

<?php 
 $page->renderFooter();

?>
