<?php
require_once 'Page.php';
$podmienky_err = $name_err = $login_err = "";
if (!empty($_POST['reguser'])) {

    if (empty($_POST['name'])) {
        $name_err = "Je potrebné zadať celé meno!";
    }
    if (empty($_POST['login'])) {
        $login_err = "Je potrebné zadať emailovú adresu!";
    }

    if (!empty($_POST['reguser']) && !empty($_POST['login']) && !empty($_POST['name'])) {

        if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
            add_message("error", "Email nebol zadaný v správnom formáte!");
        }

        if ($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Mail` = ?")) {

            $sel->bind_param("s", $_POST['login']);
            if ($sel->execute()) {

                $result = $sel->get_result();
                $data = $result->fetch_assoc();

                if ($result->num_rows == 0) {
                    //array(3) { ["name"]=> string(4) "Test" ["login"]=> string(11) "asd@asd.asd" ["reguser"]=> string(3) "reg" }


                    if ($pass_gen = $mysqli->prepare("INSERT INTO `pass_gen` (`ID`, `Done`, `URL`, `Date`) VALUES (NULL, 0, ?, CURRENT_TIMESTAMP)")) {

                        $pass_gen->bind_param("s", name_url_hash($_POST['login']));
                        if ($pass_gen->execute()) {
                            $pass_gen_id = $pass_gen->insert_id;
                            //
                            if ($user_add = $mysqli->prepare("INSERT INTO `users` (`ID`, `Name`, `Mail`, `Password`, `PassGen`, `School`, `Address`, `Role`) VALUES (NULL, ?, ?, NULL, ?, NULL, NULL, '100')")) {
                                $user_add->bind_param("ssi", ($_POST['name']), ($_POST['login']), $pass_gen_id);
                                if ($user_add->execute()) {
                                  if ($mailer_add = $mysqli->prepare("INSERT INTO `mailer` (`ID`, `type`, `user`, `mail`, `sent`) VALUES (NULL, '1', ?, ?, '0')")) {
                                      $mailer_add->bind_param("is", $pass_gen_id, $_POST['login']);
                                      if ($mailer_add->execute()) {
                                        add_message("", "Registrácia prebehla úspešne, skontrolujte si váš e-mail pre dalšie inštrukcie!");
                                      }
                                    }
                                }
                            }

                        }
                    }
                } else {
                    add_message("error", "Zadaný účet už existuje.");
                }
            }
        }
    }
}

$page->setHeader("Registrácia používateľského účtu");

//$page->renderHeader();

display_errors("clean");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Registration Page</title>
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

  <div class="register-box-body">
    <p class="login-box-msg">Registrácia nového účtu</p>

    <form action="./register.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="name" placeholder="Celé meno">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
          <span class="error"><?php echo $name_err;?></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="login" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          <span class="error"><?php echo $login_err;?></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="reguser" value="reg">Registrovať</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <a href="./login.php" class="text-center">Už mám účet</a>
  </div>
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
