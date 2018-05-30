<?php 

session_start();

if(empty($_SESSION['data'])) $_SESSION['data'] = array();

if(!empty($_POST['add']) && !empty($_POST['text']))
{
	$_SESSION['data'][] = $_POST['text'];
}

if(empty($_GET['id'])) $_GET['id'] = 0;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Test 2</title>
	<style type="text/css">
		.links {
			    width: 150px;
			    height: 200px;
    			display: inline-block;
    			border-style: solid;
    			border-width: 2px;
    			overflow: scroll;
		}
		.datas {
			    width: 300px;
			    height: 200px;
    			display: inline-block;
    			border-style: solid;
    			border-width: 2px;
    			overflow: scroll;
		}
		.controls {
			width: 500px;
		}
		.controls input[type=text]{
			width: 80%;
		}
		.active {
			font-weight: bold;
		}
	</style>
	<script type="text/javascript">
		
		function killSession() {
		    location = 'index.php?destroySession=true';
		}
	</script>
</head>
<body>
	<div>
		<div class="links">
			<?php 
			if(!empty($_SESSION['data']))
			{
				foreach ($_SESSION['data'] as $key => $value) {
					echo "<p id='key".$key."' class='".($_GET['id'] == $key ? "active" : "")."'><a href='./?id=$key#key".$key."'>Záznam ".($key+1)."</a></p>";
				}
			}
			?>
		</div>
		<div class="datas">
			<?php 
			if(!empty($_SESSION['data']) && !empty($_SESSION['data'][$_GET['id']]))
			{
				echo "<p>".$_SESSION['data'][$_GET['id']]."</p>";
			}
			?>
		</div>
	</div>
	<div class="controls">
		<div>
			<form method="POST">	
				<input type="text" name="text" placeholder="Text novej poznamky">
				<input type="submit" name="add" value="Pridať">
			</form>
		</div>
	</div>
</body>
</html>