<?php
require_once 'Page.php';

if(!empty($_POST['normlogin']) && !empty($_POST['login']) && !empty($_POST['pass']))
{

	if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
		add_message("error","Email nebol zadaný v správnom formáte!");
		header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
		return;
	}

	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Mail` = ? AND `users`.`Password` = ?")) {

		$sel->bind_param("ss", $_POST['login'],hash('sha256', $_POST['pass']));
		if($sel->execute())
		{
		   	$result = $sel->get_result();
		    $data = $result->fetch_assoc();

		    if($result->num_rows == 1)
		    {
		    	if($inststm = $mysqli->prepare("INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `time`) VALUES (NULL, ?, 'Registration', NOW());"))
		        {
		            $inststm->bind_param("i", $data['id']);

		            $inststm->execute();
  
                load_user_data($mysqli,$data['ID']);

		    		add_message("","Boli ste úspešne prihlásený");
					header('Location: ' . filter_var(PATH_INDEX, FILTER_SANITIZE_URL));
					return;
		        }

		    	
		    } else {

		    	add_message("error","Zadaný účet neexistuje alebo ste zadali nesprávne heslo!");
				header('Location: ' . filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL));
				return;
		    }
		}
	}
}
/*
$page->setHeader("Prihlásenie používateľa do systému");

$page->renderHeader();
*/

// $page->renderFooter();
/*
display_errors();

    echo '

	        <form method="post" class="form-style text-center">
	          <fieldset>
	            <legend>Prihlásenie pomocou registrovaného účtu</legend>
	              <div class="row">

	              '.create_field("login","Email","").'
	              '.create_field("pass","Heslo","","password").'
	              </div>
	          </fieldset>

	          <input type="submit" name="normlogin" value="Prihlásiť">
	          
	        </form>
	    ';

*/

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
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
<body class="hold-transition login-page">
  <?php display_errors("clean"); ?>
<div class="login-box">
  <div class="login-logo">
    <a href="./login.php"><b>Alpha</b>BRAVO</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Pre pokračovanie do systému je potrebné prihlásenie</p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="login">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Heslo" name="pass">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Zapamätať prihlásenie
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="normlogin" value="asd">Prihlásiť sa</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!-- /.social-auth-links -->

    <a href="#">Zabudol som heslo</a><br>
    <a href="register.php" class="text-center">Registrácia</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

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
