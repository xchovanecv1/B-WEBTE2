<?php
require_once 'Page.php';

$getcontent = "";

if(!empty($_POST['aislogin']) && !empty($_POST['isid']) && !empty($_POST['ispass']) && empty($_SESSION['logged']))
{

  $ldapuid = $_POST['isid'];
  $ldappass = $_POST['ispass'];

  $dn  = 'ou=People, DC=stuba, DC=sk';
  $ldaprdn  = "uid=$ldapuid, $dn";     

  $ldapconn = ldap_connect(LDAP_SERVER);


  $set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
  /*ldap_unbind($ldapconn);*/
  $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
  

  try {
      //$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
  } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
  }

    // verify binding
    if ($ldapbind) {

      $sr = ldap_search($ldapconn, $ldaprdn, "uid=".$ldapuid);
      $entry = ldap_first_entry($ldapconn, $sr);
      $usrId = ldap_get_values($ldapconn, $entry, "uisid")[0];

      add_message("","Prihlásenie pomocou AIS konta prebehlo úspešne.");
      $_SESSION['logged'] = 1;
      $_SESSION['ldap'] = $ldapuid;
      $_SESSION['ldapid'] = $usrId;

      $username = trim($ldapuid);
      $password = trim($ldappass);

      $url=""; 
      $postinfo = "credential_0=".$username."&credential_1=".$password."&credential_2=86400&login=Prihl%C3%A1si%C5%A5+sa&destination=/auth/katalog/rozvrhy_view.pl?rozvrh_student_obec=1";


      $cookie_file_path = "./cookie/".md5(date("Y-m-d H:i:s").rand ()).rand ().$ldapuid.".txt";

      $_SESSION['coookie_path'] = $cookie_file_path ;

      $crl = curl_init();
      curl_setopt($crl, CURLOPT_HEADER, false);
      curl_setopt($crl, CURLOPT_NOBODY, false);
      curl_setopt($crl, CURLOPT_URL, AIS_LOGIN);
      curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 1);
      curl_setopt($crl, CURLOPT_COOKIEJAR, $cookie_file_path);
      curl_setopt($crl, CURLOPT_COOKIEFILE, $cookie_file_path);
      curl_setopt($crl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
      curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($crl, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
      curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($crl, CURLOPT_FOLLOWLOCATION, 0);

      curl_setopt($crl, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($crl, CURLOPT_POST, 1);
      curl_setopt($crl, CURLOPT_POSTFIELDS, $postinfo);
      curl_exec($crl);

      curl_close($crl);

    } else {
      add_message("error","Prihlasovacie ID alebo heslo nie sú správne");
    }
}


if(empty($_SESSION['logged']))
	$page->setHeader("Prihlásenie používateľa do systému");
else 
	$page->setHeader("AIS Rozvrh");

$page->renderHeader();

if(empty($_SESSION['logged']))
{

    echo '
    	<div class="tab">
		  <button class="tablinks" id="defTab" onclick="openTab(event, \'LDAPLogin\')">AIS Stuba.sk</button>

		</div>
		';

		display_errors();
 echo '
     	<div id="LDAPLogin" class="tabcontent">

        <form method="post" action="index.php" class="form-style text-center">
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
		  
} else {

	display_errors();

      $postinfo = "rozvrh_student_obec=1&format=html;rozvrh_student=".$_SESSION['ldapid'];

      $crl = curl_init();
      curl_setopt($crl, CURLOPT_HEADER, false);
      curl_setopt($crl, CURLOPT_NOBODY, false);
      curl_setopt($crl, CURLOPT_URL, AIS_ROZVRH);
      curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 1);

      curl_setopt($crl, CURLOPT_COOKIEJAR, $_SESSION['coookie_path']);
      curl_setopt($crl, CURLOPT_COOKIEFILE, $_SESSION['coookie_path']);

      curl_setopt($crl, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
      curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($crl, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
      curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($crl, CURLOPT_FOLLOWLOCATION, 0);

      curl_setopt($crl, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($crl, CURLOPT_POST, 1);
      curl_setopt($crl, CURLOPT_POSTFIELDS, $postinfo);

      $getcontent = curl_exec($crl);

      curl_close($crl);

if(!empty($getcontent))
{
  $doc = new DOMDocument();
  @$doc->loadHTML($getcontent);

  $xpath = new DOMXpath($doc);

  $r = preg_match_all('#<table>(.*?)</table>#i',$getcontent,$table);

  echo "<div class='istab'>";

  echo $table[0][0];

  echo "</div>";
  $elements = $xpath->query("//td[contains(@class, 'rozvrh-pred')]");

  if (!is_null($elements)) {
    echo '
    <table id="myTable" class="tablesorter"> 
      <thead> 
      <tr>
        <th colspan="2">Zoznam predmetov a prednášajúcich</th>
      </tr>
      <tr> 
              <th>Predmet</th>  
              <th>Prednášajúci</th> 
      </tr> 
      </thead> 
      <tbody> 
  


    ';
    foreach ($elements as $element) {
      $da = $element->getElementsByTagName("a");
      if(!empty($da[1]->nodeValue) && !empty($da[2]->nodeValue))
      {
        echo "<tr><td>".$da[1]->nodeValue."</td><td>".$da[2]->nodeValue."</td></tr>";
      }

    }
    echo '      </tbody> 
      </table>';
  }
}

?>


<?php

}

 $page->renderFooter();

?>