<?php
require_once 'Page.php';
require_once 'oauth.php';

if(!empty($_POST['normlogin']) && !empty($_POST['login']) && !empty($_POST['pass']))
{
	if($sel = $mysqli->prepare("SELECT * FROM `users` WHERE `users`.`Login` = ? AND `users`.`Password` = ?")) {

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

		    		echo "login";
		            $_SESSION['logged'] = 1;
		    		foreach ($data as $key => $value) {
		    			$_SESSION[$key] = $value;
		    		}
		        }

		    	
		    } else {

		    	echo "zle meno alebo heslo";
		    }
		}
	}
}

if(empty($_SESSION['logged']))
	$page->setHeader("Prihlásenie používateľa do systému");
else 
	$page->setHeader("Zdieľaná plocha nápadov");

$page->renderHeader();

if(empty($_SESSION['logged']))
{

    echo '
    	<div class="tab">
		  <button class="tablinks" id="defTab" onclick="openTab(event, \'RegLogin\')">Regulárne prihlásenie</button>
		  <button class="tablinks" onclick="openTab(event, \'RegUser\')">Registrácia</button>
		  <button class="tablinks" onclick="openTab(event, \'LDAPLogin\')">AIS Stuba.sk</button>
		  <button class="tablinks" onclick="openTab(event, \'GoogleLogin\')">Google Login</button>

		</div>
		';

		display_errors();


		echo'
    	<div id="RegLogin" class="tabcontent">

	        <form method="post" action="login.php" class="form-style text-center">
	          <fieldset>
	            <legend>Prihlásenie pomocou registrovaného účtu</legend>
	              <div class="row">

	              '.create_field("login","Login","","text",true,"n").'
	              '.create_field("pass","Heslo","","password",true,"n").'
	              </div>
	          </fieldset>

	          <input type="submit" name="normlogin" value="Prihlásiť">
	          
	        </form>
	    </div>
	    ';

	    echo '
	    <div id="RegUser" class="tabcontent">

	        <form method="post" action="register.php" class="form-style text-center">
	          <fieldset>
	            <legend>Registrácia do systému</legend>
	              <div class="row">

	              '.create_field("login","Login","","text",true,"r").'
	              '.create_field("pass","Heslo","","password",true,"r").'
	              </div>
	              <div class="row">

	              '.create_field("name","Meno","","text",true,"r").'
	              '.create_field("surname","Priezvisko","","text",true,"r").'
	              </div>
	          </fieldset>

	          <input type="submit" name="reguser" value="Registrovať">

	          ';

	          echo'

	        </form>
	    </div>
	          ';

 echo '
     	<div id="LDAPLogin" class="tabcontent">

        <form method="post" action="ldap.php" class="form-style text-center">
          <fieldset>
            <legend>Prihlásenie pomocou AIS STU</legend>
              <div class="row">

              '.create_field("isid","AIS Meno","","text",true,"l").'
              '.create_field("ispass","Heslo","","password",true,"l").'
              </div>
          </fieldset>

          <input type="submit" name="aislogin" value="Prihlásiť">

          ';

          echo'

        </form>		</div>

          ';

$authUrl = $client->createAuthUrl();
 echo '
     	<div id="GoogleLogin" class="tabcontent">

        <form method="post" class="form-style text-center">
          <fieldset>
            <legend>Prihlásenie Google účtu</legend>
              <div class="row">
              <a href="'.$authUrl.'"><img src="./style/google.png" alt="Login with Google"></a>
              </div>
          </fieldset>

          ';

          echo'

        </form>		</div>

          ';


} else {

	display_errors();

?>
<script type="text/javascript">
	//https://stackoverflow.com/questions/2368784/draw-on-html5-canvas-using-a-mouse
	var today = new Date();

	var socket = new WebSocket('ws://webte.fej.cloud:5500');
	var usrnm = "<?php echo $_SESSION['Name'] . " ".$_SESSION['Surname']; ?>";

    var canvas, ctx, flag = false,
        fromX = 0,
        toX = 0,
        fromY = 0,
        toY = 0,
        dot_flag = false,
        cnvCol = "black",
        y = 2;


	function formatDate(date) {
	  var hours = date.getHours();
	  var minutes = date.getMinutes();
	  hours = hours % 24;
	  minutes = minutes < 10 ? '0'+minutes : minutes;
	  var strTime = hours + ':' + minutes;
	  return date.getDate()+1 + "." + date.getMonth() + ". " + date.getFullYear() + "  " + strTime;
	}
    
    function init() {
        canvas = document.getElementById('can');
        ctx = canvas.getContext("2d");
        w = canvas.width;
        h = canvas.height;
    
        canvas.addEventListener("mousemove", function (e) {
            findxy('move', e)
        }, false);
        canvas.addEventListener("mousedown", function (e) {
            findxy('down', e)
        }, false);
        canvas.addEventListener("mouseup", function (e) {
            findxy('up', e)
        }, false);
        canvas.addEventListener("mouseout", function (e) {
            findxy('out', e)
        }, false);
    }
    
    function color(clr) {
    	cnvCol = clr;
        if (clr == "white") y = 14;
        else y = 4;
    
    }
    
    function draw() {
        ctx.beginPath();
        ctx.moveTo(fromX, fromY);
        ctx.lineTo(toX, toY);
        ctx.strokeStyle = cnvCol;
        ctx.lineWidth = y;
        ctx.stroke();
        ctx.closePath();

    }
    
    function erase(force) {
    	var m = true;
        if(!force)
    	{
    		m = confirm("Prajete si zmazať pláno ? Táto zmena sa prejaví aj u ostatných používateľov!");
    	}
        if (m) {
            ctx.clearRect(0, 0, w, h);
            if(!force)
    		{
	            var data =  {"type": "erase"};
			    console.log(data);
			    socket.send(JSON.stringify(data));
			}
	     }
    }
    
    function save() {
        var dataURL = canvas.toDataURL();
        console.log(dataURL);

        $('#downurl').val(dataURL);
        $('#dwnform').submit();
    }
    
    function findxy(res, e) {
        if (res == 'down') {
            fromX = toX;
            fromY = toY;
            toX = e.clientX - canvas.offsetLeft;
            toY = e.clientY - canvas.offsetTop;
    
            flag = true;
            dot_flag = true;
            if (dot_flag) {
                ctx.beginPath();
                ctx.fillStyle = cnvCol;
                ctx.fillRect(toX, toY, 2, 2);
                ctx.closePath();
                dot_flag = false;
            }
        }
        if (res == 'up' || res == "out") {
            flag = false;
        }
        if (res == 'move') {
            if (flag) {
                fromX = toX;
                fromY = toY;
                toX = e.clientX - canvas.offsetLeft;
                toY = e.clientY - canvas.offsetTop;

                var data =  {"type": "data", "fromX": fromX, "fromY": fromY, "toX":toX, "toY":toY,"cnvCol":cnvCol,"y":y};
		        console.log(data);
		        socket.send(JSON.stringify(data));

                draw();
            }
        }
    }
    function sendMsg() {
    	var ip = $("#sndmsg");
    	
    	socket.send(JSON.stringify({"type": "msg","msg": usrnm+" píše: "+ip.val()}));
    	

    	$( ".msgBox" ).prepend( '<div class="msg">['+formatDate(new Date())+'] '+(usrnm+" píše: "+ip.val())+'</div>' );
    	ip.val("");
    }
    </script>
    <div class="colorPicker">
    	<div class="green" onclick="color('green')"></div>
        <div class="blue" onclick="color('blue')"></div>
        <div class="red" onclick="color('red')"></div>
        <div  class="yellow" onclick="color('yellow')"></div>
        <div class="orange" onclick="color('orange')"></div>
        <div  class="black" onclick="color('black')"></div>
        <div class="white" onclick="color('white')"></div>
        <button  class="button" onclick="save()">Uložiť</button>
        <button  class="button" onclick="erase(false)">Zmazať</button>
    </div>
    <canvas id="can" width="1160" height="500" ></canvas>
    <input type="text" name='msg' id="sndmsg" style="width: 85%;"><button class="button" onclick="sendMsg()" style="margin-top: 0px;">Odoslať</button>
    <div class="msgBox">

    </div>
   <div style="display: none;">
   	<form method="post" action="down.php" id="dwnform" target="_blank"><input type="hidden" name="data" id="downurl" value=""></form>
   </div>    



<script type="application/javascript">


    var content = document.getElementById('content');

    socket.onopen = function (event) {
	  socket.send(JSON.stringify({"type": "msg","msg": "<?php echo $_SESSION['Name'] . " ".$_SESSION['Surname']; ?> sa pripojil"}));
	};

    socket.onmessage = function (message) {
        var data = JSON.parse(JSON.parse(message.data).utf8Data);
        //console.log(data);
        if(data.type == "data")
        {

	        ctx.beginPath();
	        ctx.moveTo(data.fromX, data.fromY);
	        ctx.lineTo(data.toX, data.toY);
	        ctx.strokeStyle = data.cnvCol;
	        ctx.lineWidth = data.y;
	        ctx.stroke();
	        ctx.closePath();

        }
        if(data.type == "erase")
        {
        	erase(true);
        }
        if(data.type == "msg")
        {

        	$( ".msgBox" ).prepend( '<div class="msg">['+formatDate(new Date())+'] '+data.msg+'</div>' );
        	//$(".msgBox")
        }

    //var 

        //$( "table tr:nth-child("+(data.row+1)+") td:nth-child("+(data.col+1)+")" ).css("background-color", "blue");
    };

    socket.onerror = function (error) {
        console.log('WebSocket error: ' + error);
    };
   
</script>
<?php

}

 $page->renderFooter();

?>