<link rel="stylesheet" type="text/css" href="style.css">
<?php

$cnt = file_get_contents("out.html");
/*
$str = preg_replace('#(<head>).*?(</head>)#', '$1', $cnt );

//$value=preg_match_all('/<div class=\"mainpage">(.*?)<\/div>/s',$cnt,$estimates);
//var_dump();

file_put_contents("tst.html",$str);
*/
$doc = new DOMDocument();
@$doc->loadHTML($cnt);

$xpath = new DOMXpath($doc);

// example 1: for everything with an id
//$elements = $xpath->query("//*[@id]");

// example 2: for node data in a selected id
//$elements = $xpath->query("/html/body/div[@id='yourTagIdHere']");

// example 3: same as above with wildcard
$r = preg_match_all('#<table>(.*?)</table>#i',$cnt,$table);

echo "<div class='istab'>";

echo $table[0][0];

echo "</div>";
$elements = $xpath->query("//td[contains(@class, 'rozvrh-pred')]");

if (!is_null($elements)) {
  foreach ($elements as $element) {
  	$da = $element->getElementsByTagName("a");
  	if(!empty($da[1]->nodeValue) && !empty($da[2]->nodeValue))
  	{
   		var_dump($da[1]->nodeValue);
   		var_dump($da[2]->nodeValue);
  	}

  }
}
?>