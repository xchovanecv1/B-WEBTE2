<?php

class Page { 
    
    private $title = "";
    private $header;
    private $scripts = array();
    private $metas = array();
    private $links = array();
    private $is_admin = 0;
    private $is_user = 0;
    

   	function __construct($title)
   	{
   		$this->title = $title;
   	}

   	function setHeader($header)
   	{
   		$this->header = $header;
   	}

    function setAdmin($isadmin)
    {
      $this->is_admin = $isadmin;
    }

    function setUser($isuser)
    {
      $this->is_user = $isuser;
    }


  	function addScript($type,$data)
  	{
  		switch ($type) {
  			case 'link':
  				
  				$this->scripts[] = '<script src="'.$data.'"></script>';
  			break;
  			
  			case 'script':
  				$this->scripts[] = '<script type="text/javascript">'.$data.'</script>';
  			break;
  		}
  	}

  	function addMeta($data)
  	{
  		$this->metas[] = "<meta ".$data." />";
  	}

  	function addLink($type,$data)
  	{
  		switch($type)
  		{
  			case "css":
	  			$this->links[] = '<link rel="stylesheet" type="text/css" href="'.$data.'" />';
  			break;
  		}
  	}

    function addMenuLink($route,$name)
    {
      $check = str_replace("./", "", $route);
      $contains = strpos($_SERVER[REQUEST_URI],$check);
      $active = ($contains !== false ? "active" : "");
      return '<li class="'.$active.'"><a href="./'.$route.'"><span>'.$name.'</span></a></li>';
    }

    function renderHeader()
    {
      echo '
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AlphaBRAVO | daco</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="./index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>a</b>B</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Alpha</b>BRAVO</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      ';
      if($this->is_user){
      echo '
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="dist/img/avatar.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">'.$_SESSION['Name'].' '.$_SESSION['Surname'].'</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="dist/img/avatar.png" class="img-circle" alt="User Image">

                <p>
                  '.$_SESSION['Name'].' '.$_SESSION['Surname'].'
                  <small>Registrovaný od '.$_SESSION['regdate'].'</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="'.filter_var(PATH_PROFILE, FILTER_SANITIZE_URL).'" class="btn btn-default btn-flat">Profil</a>
                </div>
                <div class="pull-right">
                  <a href="'.filter_var(PATH_LOGOUT, FILTER_SANITIZE_URL).'" class="btn btn-default btn-flat">Odlhásenie</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      ';
    } else {
      echo '
      <div class="navbar-custom-menu">
        <a href="'.PATH_LOGIN.'" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Prihásenie</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Prihásenie</span>
        </a>
        <a href="'.PATH_REGISTER.'" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Registrácia</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Registrácia</span>
        </a>
      </div>
      ';
    }
    echo '
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    ';

    if($this->is_user){
      echo '
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/avatar.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>'.$_SESSION['Name'].' '.$_SESSION['Surname'].'</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      ';
    }
      echo '
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Používateľské menu</li>
        <!-- Optionally, you can add icons to the links -->
        '.$this->addMenuLink(PATH_INDEX,"Domovská stránka").'
        <li class="treeview menu-open">
          <a href="#"><i class="fa fa-link"></i> <span>Práca s trasami</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu" style="display:block;">
            '.$this->addMenuLink(PATH_ROUTE_ADD,"Pridať trasu").'
            '.$this->addMenuLink(PATH_ROUTES,"Zoznam trás").'
            '.$this->addMenuLink(PATH_RUN_ADD,"Pridať beh").'
            '.$this->addMenuLink(PATH_RUNS,"Zoznam behov").'
          </ul>
        </li>
        ';

        if($this->is_admin)
        {
          echo '
        <li class="treeview menu-open">
          <a href="#"><i class="fa fa-link"></i> <span>Administrátorské úkony</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu" style="display:block;">
            '.$this->addMenuLink(PATH_ADMIN_IMPORTUSER,"Importovať uživateľov").'
            '.$this->addMenuLink(PATH_ADMIN_USERLIST,"Zoznam uživateľov").'
          </ul>
        </li>
          ';
        }

        echo '
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        '.$this->header.'
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      ';
    }


    function renderFooter()
    {
      echo '
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Už včera bolo neskoro, no tak predsa do toho!
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2018 <a href="fej.cloud">Devátá rota</a>.</strong> Všetky práva vyhradené.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>
      ';
    }
/*
  	function renderHeader()
  	{
  		echo '
		<!DOCTYPE HTML>
		<html lang="sk">

		<head>
		  <title>'.$this->title.'</title>
  		';

  		//METAS
  		foreach ($this->metas as $value) {
  			echo $value;
  		}

  		foreach ($this->links as $value) {
  			echo $value;
  		}

  		foreach ($this->scripts as $value) {
  			echo $value;
  		}

  		echo '
  		</head>
		<body>
		  <div id="main">

		    <div id="obsah_outer">
                    ';
    if(!empty($_SESSION['logged']))
        {
          echo '
          <div id="logged">
            <a href="index.php">Minulé prihlásenia</a>
            <a href="profil.php">Profil</a>
            <div class="logout">
              <p>Prihlásený: '.$_SESSION['Name'].' '.$_SESSION['Surname'].'</p>
              <a href="./logout.php">Odhlásiť sa</a>
            </div>
          </div>
          ';
          }
          echo ';

		      <div id="header">
		        <h1>'.(!empty($this->header) ? $this->header : "Nezdany header!").'</h1>
		      </div>

		      <section id="obsah">';
  	}

  	function renderFooter()
  	{
  		echo '
		     </section>
		    </div>
		    <footer>
		      <span>&copy; 2018</span>
		    </footer>
		  </div>

		</body>
		</html>
  		';
  	}*/

}


?>