<?php
require_once 'Page.php';

 $show_form = 0;
if(!empty($_GET['x']))
{
  if($sel = $mysqli->prepare("SELECT *  FROM `pass_gen` WHERE `URL` = ? AND `Done` = 0")) {

    $sel->bind_param("s", $_GET['x']);
    if($sel->execute())
    {

        $result = $sel->get_result();
        $data = $result->fetch_assoc();

        if($result->num_rows != 0)
        {
          if($usrsel = $mysqli->prepare("SELECT * FROM `users` WHERE `PassGen` = ?")) {

          $usrsel->bind_param("i", $data["ID"]);
          if($usrsel->execute())
          {

              $usrresult = $usrsel->get_result();
              $usrdata = $usrresult->fetch_assoc();

              if($usrresult->num_rows != 0)
              {
                if(!empty($_POST['actuser']) && !empty($_POST['pass1']) && !empty($_POST['pass2']))
                {
                  if($_POST['pass2'] == $_POST['pass1'])
                  {
                    if($upd_pass_gen = $mysqli->prepare("UPDATE `pass_gen` SET `Done` = '1' WHERE `pass_gen`.`ID` = ?"))
                    {
                        $upd_pass_gen->bind_param("i",$data["ID"]);
                        if($upd_pass_gen->execute())
                        {
                           if($upd_pass_gen = $mysqli->prepare("UPDATE `users` SET `Password` = ? WHERE `users`.`PassGen` = ?"))
                          {
                              $upd_pass_gen->bind_param("si",hash('sha256', $_POST['pass1']),$data["ID"]);
                              if($upd_pass_gen->execute())
                              { 
                                  add_message("","Účet bol úspešne aktivovaný, môžete sa prihlásiť!");
                                  header('Location: ' . filter_var(PATH_LOGIN, FILTER_SANITIZE_URL));
                                  return;
                              } 
                          }
                          //
                        }
                    }
                  } else {
                    add_message("error","Zadané heslá sa nezhodujú!");
                    $show_form = 1;
                  }
                } else {
                  $show_form = 1;
                }
              }
            }
          }
        }
    }
  }
}

$page->setHeader("Potvrdenie používateľského účtu");

//$page->renderHeader();

display_errors();

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Activation Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="./bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="./plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="./index2.html"><b>Alpha</b>BRAVO</a>
  </div>
<?php if($show_form): ?>
  <div class="register-box-body">
    <p class="login-box-msg">Registrácia nového účtu</p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="pass1" placeholder="Heslo">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="pass2" placeholder="Zopakujte heslo">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="actuser" value="reg">Aktivovať</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
<?php endif; ?>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="./plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
