<?php

require_once("config.php");



function create_pie($in_data){



if(!isset($in_data["a"]) || !isset($in_data["b"]) || !isset($in_data["c"]) || !isset($in_data["d"]) || !isset($in_data["e"]) || !isset($in_data["fx"]) || !isset($in_data["fn"]) ||  !isset($in_data["yr"]) ||  !isset($in_data["cnt"]))
{
    die("Nedostatok parametrov");
}

$imageWidth = 700;
$imageHeight = 400;

$total = intval($in_data['cnt']);


$title = "Webové technológie 2 šk. rok ".$in_data['yr'];
$xaxisT = "Hodnotenie";
$yaxisT = "Počet študentov";

$font = './OpenSans-Regular.ttf';
$fontSize = 10;

// Create an image
$image = imagecreatetruecolor($imageWidth, $imageHeight);

// Allocate some colors
$white    = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
$gray     = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
$darkgray = imagecolorallocate($image, 0x90, 0x90, 0x90);
$navy     = imagecolorallocate($image, 0x00, 0x00, 0x80);
$darknavy = imagecolorallocate($image, 0x00, 0x00, 0x50);
$red      = imagecolorallocate($image, 0xFF, 0x00, 0x00);
$darkred  = imagecolorallocate($image, 0x90, 0x00, 0x00);
$blue  = imagecolorallocate($image, 0, 0, 0xFF);
$lblue  = imagecolorallocate($image, 0, 0, 0xFF);

	$redpen = ImageColorAllocate($image, 255, 0, 0); 
    $greenpen = ImageColorAllocate($image, 0, 153, 0); 
    $bluepen = ImageColorAllocate($image, 0, 0, 255); 
    $blackpen = ImageColorAllocate($image, 0, 0, 0); 
    $whitepen = ImageColorAllocate($image, 255, 255, 255); 
    $yellowpen = ImageColorAllocate($image, 255, 255, 0); 
    $aquapen = ImageColorAllocate($image, 0, 255, 255); 
    $fuschiapen = ImageColorAllocate($image, 255, 0, 255); 
    $greypen = ImageColorAllocate($image, 153, 153, 153); 
    $silverpen = ImageColorAllocate($image, 204, 204, 204); 
    $tealpen = ImageColorAllocate($image, 0, 153, 153); 
    $limepen = ImageColorAllocate($image, 0, 255, 0); 
    $navypen = ImageColorAllocate($image, 0, 0, 153); 
    $purplepen = ImageColorAllocate($image, 153, 0, 153); 
    $maroonpen = ImageColorAllocate($image, 153, 0, 0); 
    $olivepen = ImageColorAllocate($image, 153, 153, 0); 


$data = [
    'A' => array(intval($in_data['a']),$redpen),
    'B' => array(intval($in_data['b']),$greenpen),
    'C' => array(intval($in_data['c']),$bluepen),
    'D' => array(intval($in_data['d']),$blackpen),
    'E' => array(intval($in_data['e']),$silverpen),
    'FX' => array(intval($in_data['fx']),$greypen),
    'FN' => array(intval($in_data['fn']),$purplepen)
];


$rest = $total-($data['A'][0] + $data['B'][0] + $data['C'][0] + $data['D'][0] + $data['E'][0] + $data['FX'][0] + $data['FN'][0]);
$data['Zostatok'] = array($rest,$limepen);

$onepersent = $total/100;


imagefill($image, 0, 0, $white);

$angle = 360;

$leg_start_x = 390;
$leg_start_y = 100;
$leg_size = 10;
$leg_line_siz = 30;


imagettftext($image, 15, 0, $leg_start_x, $leg_start_y-25, $barColor, $font, "Hodnotenie");

$start = 0; 
$end = 0;
foreach ($data as $key => $value) {
	$persent = 0;
	if($value[0])
	{
	$persent = ($value[0]/$onepersent);
	$start = $end; 
	$end = $start + $persent*$angle/100;

	imagefilledarc($image, 200, 200, 300, 300,  $start,  $end, $value[1], IMG_ARC_PIE);
	}

	imagefilledrectangle($image, $leg_start_x, $leg_start_y, $leg_start_x+$leg_size, $leg_start_y+$leg_size, $value[1]);
	

	imagettftext($image, 12, 0, $leg_start_x + 20 , $leg_start_y + 13, $barColor, $font, $key. ": ".$value[0]." (".round($persent,2)."%)");

	$leg_start_y += $leg_line_siz;
	
}


// TITULOK
list($left,, $right) = imageftbbox(15, 0, $font, $title);
$dwidth = ($right - $left)/2;

imagettftext($image, 15, 0, (($imageWidth/2)-$dwidth), 25, $barColor, $font, $title);
 

return $image;

 }
 /*
//Flush the image
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
*/


header('Content-Type: image/png');


$out = imagecreatetruecolor(1400, 1200);

$white = imagecolorallocate($out, 255, 255, 255);
imagefill($out, 0, 0, $white);

$img1 = create_pie($data1213);
$img2 = create_pie($data1314);
$img3 = create_pie($data1415);
$img4 = create_pie($data1516);
$img5 = create_pie($data1617);
//imagepng($img);


imagecopyresampled($out, $img1, 0, 0, 0, 0, 700, 400, 700, 400);
imagecopyresampled($out, $img2, 700, 0, 0, 0, 700, 400, 700, 400);

imagecopyresampled($out, $img3, 0, 400, 0, 0, 700, 400, 700, 400);
imagecopyresampled($out, $img4, 700, 400, 0, 0, 700, 400, 700, 400);

imagecopyresampled($out, $img5, 400, 800, 0, 0, 700, 400, 700, 400);

if(!empty($_GET['thumb']) == "1")
{
    $thumb = imagescale($out,700);
    imagepng($thumb);
    imagedestroy($thumb);
} else {
    imagepng($out);
}

imagedestroy($out);
imagedestroy($img1);
imagedestroy($img2);
imagedestroy($img3);
imagedestroy($img4);
imagedestroy($img5);
?>