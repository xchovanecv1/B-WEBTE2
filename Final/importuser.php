<?php
require_once 'Page.php';

if(!check_access_role(ROLE_ADMIN,true))
{
	return redirect_to_path(PATH_LOGIN,"Pre prístup k tejto stránke je potrebné byť prihlásený ako Administrátor", "error");
}

$uploaddir = './imports/';
$uploadfile = $uploaddir . hash("sha256",basename($_FILES['userfile']['name']).time()).".csv";

$file_good = 0;
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    add_message("","Nahrávanie súboru bolo úspešné!");
    $file_good = 1;
}

if($file_good == 1)
{
  $inpt_data = file_get_contents($uploadfile);
  if(!empty($_POST['encode']))
  {
    switch ($_POST['encode']) {
      case 'cp1250':
        $inpt_data = iconv("CP1250","UTF-8",$inpt_data);
      break;
    }
  }

$parsed_lines = preg_split ('/$\R?^/m', $inpt_data);

function parse_address($addr)
{
  $ret = array();
    $add_dt = explode(",", $addr);
    $st_dt = explode(" ",$add_dt[1]);
 
    $strt = str_replace(" ".end($st_dt), "",trim($add_dt[1]));
    $ret['Number'] = trim(end($st_dt));
    $ret['Street'] = trim($strt);
    $ret['City'] = trim($add_dt[0]);

    $ret['ZIP']  = trim(($add_dt[2]));
    if(strlen($ret['ZIP']) == 4) $ret['ZIP'] = "0".$ret['ZIP'];

  return $ret;
}

$users_to_import = array();

foreach ($parsed_lines as $key => $value) {
  if($key == 0) continue;
    $parsed_line = explode(";", ($parsed_lines[$key]));

    $db_data = array();
    $db_data['Name'] = $parsed_line[2]. " ". $parsed_line[1];
    $db_data['Mail'] = $parsed_line[3];

    $db_data['School']['Name'] = $parsed_line[4];

    $db_data['School']['Address'] = parse_address($parsed_line[5]);

    $addr = $parsed_line[8].", ".$parsed_line[6].", ".$parsed_line[7];

    $db_data['Address'] = parse_address($addr);

    $users_to_import[] = $db_data;
}

foreach ($users_to_import as $key => $value) {
  //Address creation
  if (!filter_var($value['Mail'], FILTER_VALIDATE_EMAIL)) {
    add_message("error","Prerušené spracovanie riadka ".($key+2)."! Zle zadaný mail!");
    continue;
  }

  if(is_array($value['Address']))
  {
    $users_to_import[$key]['Address'] = fetch_address($mysqli,$value['Address']);

  }

  if(is_array($value['School']['Address']))
  {
    $addr = fetch_address($mysqli,$value['School']['Address']);

    if($sel = $mysqli->prepare("SELECT ID FROM `schools` WHERE `Address` = ?")) {

    $sel->bind_param("d", $addr);
    if($sel->execute())
    {

        $result = $sel->get_result();
        $data = $result->fetch_assoc();

        $sel->close();
        if($result->num_rows > 0)
        {
          $users_to_import[$key]['School'] = $data["ID"];
        } else {
          if($pass_gen = $mysqli->prepare("INSERT INTO `schools` (`ID`, `Type`, `Name`, `Address`) VALUES (NULL, NULL, ?, ?);"))
          {

              $pass_gen->bind_param("sd", $value['School']['Name'], $addr);
              if($pass_gen->execute())
              {
                $pass_gen_id = $pass_gen->insert_id;
                $pass_gen->close();

                $users_to_import[$key]['School'] = $pass_gen_id;
              }
          }
        
        }
      }
    }
  }
}


foreach ($users_to_import as $key => $value) {
  if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `Mail` LIKE ?")) {

    $sel->bind_param("s", $value['Mail']);
    if($sel->execute())
    {

        $result = $sel->get_result();
        $data = $result->fetch_assoc();

        $sel->close();
        if($result->num_rows ==  0)
        {
          if($pass_gen = $mysqli->prepare("INSERT INTO `pass_gen` (`ID`, `Done`, `URL`, `Date`) VALUES (NULL, 0, ?, CURRENT_TIMESTAMP)"))
          {

              $pass_gen->bind_param("s",name_url_hash($value['Mail']));
              if($pass_gen->execute())
              {
                $pass_gen_id = $pass_gen->insert_id;
                //
                if($user_add = $mysqli->prepare("INSERT INTO `users` (`ID`, `Name`, `Mail`, `Password`, `PassGen`, `School`, `Address`, `Role`) VALUES (NULL, ?, ?, NULL, ?, ?, ?, '100')"))
                  {
                      $user_add->bind_param("ssddd",($value['Name']),($value['Mail']),$pass_gen_id,($value['School']),($value['Address']));
                      if($user_add->execute())
                      {

                        $user_id = $user_add->insert_id;
                        if($mail_add = $mysqli->prepare("INSERT INTO `mailer` (`ID`, `type`, `user`, `mail`, `sent`) VALUES (NULL, '1', ?, ?, '0');"))
                          {
                              $mail_add->bind_param("ds",($user_id),($value['Mail']));
                              if($mail_add->execute())
                              {
                                add_message("","Používateľ z riadka ".($key+2)." úspešne pridaný!");
                              }
                            }
                      }
                  }
                
              }
          }
        } else {
          add_message("error","Email z riadka ".($key+2)." je už zaregistrovaný!");
        }
      }
    }
}

} 

$page->setHeader("Importovať používateľov");

$page->renderHeader();
display_errors();

?>
<div class="col-md-12">
    <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pridanie trasy</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                    <i class="btn btn-block btn-warning"><b>GEO Kódovanie adries prebieha časovaným skriptom z dôvodu obmedzení API Google Máp. Z toho dôvodu môže trvať dlšie, kým sa zobrazia uživateľia na úvodnej mape.</b></i>
                </div>
                <div class="form-group">
                    <label >Zoznam používateľov</label>
                    <input class="form-control" type="file" name="userfile" placeholder="*.csv">
                </div>
                <div class="form-group">
                    <label>Kódovanie súboru</label>
                    <select class="form-control" name="encode">
                        <option value="cp1250">CP1250 (Windows)</option>
                        <option value="utf8">UTF-8</option>
                    </select>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" name="addroute" value="addroute" class="btn btn-primary">Importovať používateľov</button>
              </div>
            </form>
          </div>
</div>

<?php 
 $page->renderFooter();

?>