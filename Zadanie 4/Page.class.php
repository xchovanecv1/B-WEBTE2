<?php

class Page { 
    
    private $title = "";
    private $header;
    private $scripts = array();
    private $metas = array();
    private $links = array();
    

   	function __construct($title)
   	{
   		$this->title = $title;
   	}

   	function setHeader($header)
   	{
   		$this->header = $header;
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
            <a href="index.php">Plátno</a>
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
  	}

}


?>