<?php

require_once("config.php");

//https://stackoverflow.com/questions/4133859/round-up-to-nearest-multiple-of-five-in-php

function roundUpToAny($n,$x=5) {
    return round(($n+$x/2)/$x)*$x;
}


function create_bar($in_data){


if(!isset($in_data["a"]) || !isset($in_data["b"]) || !isset($in_data["c"]) || !isset($in_data["d"]) || !isset($in_data["e"]) || !isset($in_data["fx"]) || !isset($in_data["fn"]) ||  !isset($in_data["yr"]) ||  !isset($in_data["cnt"]))
{
    die("Nedostatok parametrov");
}

$data = [
    'A' => intval($in_data['a']),
    'B' => intval($in_data['b']),
    'C' => intval($in_data['c']),
    'D' => intval($in_data['d']),
    'E' => intval($in_data['e']),
    'FX' => intval($in_data['fx']),
    'FN' => intval($in_data['fn'])
];


$total = intval($in_data['cnt']);

$rest = $total-($data['A'] + $data['B'] + $data['C'] + $data['D'] + $data['E'] + $data['FX'] + $data['FN']);
$data['Zostatok'] = $rest;


$title = "Webové technológie 2 šk. rok ".$in_data['yr'];
$xaxisT = "Hodnotenie";
$yaxisT = "Počet študentov";


$imageWidth = 700;
$imageHeight = 400;


$gridTop = 40;
$gridLeft = 50;
$gridBottom = 330;
$gridRight = 640;
$gridHeight = $gridBottom - $gridTop;
$gridWidth = $gridRight - $gridLeft;


$lineWidth = 1;
$barWidth = 20;

$font = './OpenSans-Regular.ttf';
$fontSize = 10;

$labelMargin = 8;


$yMaxValue = roundUpToAny(max($data));

$yLabelSpan = 5;


$chart = imagecreate($imageWidth, $imageHeight);

$white = imagecolorallocate($chart, 255, 255, 255);
$labelColor = $axisColor = ImageColorAllocate($chart, 153, 153, 153); 
$gridColor = imagecolorallocate($chart, 212, 212, 212);
$barColor = ImageColorAllocate($chart, 0, 0, 255); 

imagefill($chart, 0, 0, $white);

imagesetthickness($chart, $lineWidth);


for($i = 0; $i <= $yMaxValue; $i += $yLabelSpan) {
    $y = $gridBottom - $i * $gridHeight / $yMaxValue;

    // draw the line
    imageline($chart, $gridLeft, $y, $gridRight, $y, $gridColor);

    // draw right aligned label
    $labelBox = imagettfbbox($fontSize, 0, $font, strval($i));
    $labelWidth = $labelBox[4] - $labelBox[0];

    $labelX = $gridLeft - $labelWidth - $labelMargin;
    $labelY = $y + $fontSize / 2;

    imagettftext($chart, $fontSize, 0, $labelX, $labelY, $labelColor, $font, strval($i));
}


$barSpacing = $gridWidth/count($data);
$itemX = $gridLeft+($barSpacing/2);

foreach($data as $key => $val) {
    // Draw the bar
    $x1 = $itemX-($barWidth/2);
    $y1 = $gridBottom - ($val / $yMaxValue) * $gridHeight;
    $x2 = $itemX + $barWidth / 2;

    imagefilledrectangle($chart, $x1, $y1, $x2, $gridBottom, $barColor);

    // Draw the label
    $labelBox = imagettfbbox($fontSize, 0, $font, $key);
    $labelWidth = $labelBox[4] - $labelBox[0];

    $labelX = $itemX - $labelWidth / 2;
    $labelY = $gridBottom + $labelMargin + $fontSize;

    imagettftext($chart, $fontSize, 0, $labelX, $labelY, $labelColor, $font, $key);

    $itemX += $barSpacing;
}

// Axis

imageline($chart, $gridLeft, $gridTop, $gridLeft, $gridBottom, $axisColor);
imageline($chart, $gridLeft, $gridBottom, $gridRight, $gridBottom, $axisColor);


// TITULOK
list($left,, $right) = imageftbbox(15, 0, $font, $title);
$dwidth = ($right - $left)/2;

imagettftext($chart, 15, 0, (($imageWidth/2)-$dwidth), 25, $barColor, $font, $title);

// X axis
list($left,, $right) = imageftbbox(12, 0, $font, $xaxisT);
$dwidth = ($right - $left)/2;

imagettftext($chart, 12, 0, (($imageWidth/2)-$dwidth), $imageHeight-20, $barColor, $font, $xaxisT);

// Y axis
list($left,, $right) = imageftbbox(12, 0, $font, $yaxisT);
$dwidth = ($right - $left)/2;

imagettftext($chart, 12, 90, 20, (($imageHeight/2)+$dwidth), $barColor, $font, $yaxisT);

/*
header('Content-Type: image/png');

if(!empty($_GET['thumb']) == "1")
{
    $thumb = imagescale($chart,400);
    imagepng($thumb);
    imagedestroy($thumb);
} else {
    imagepng($chart);
}

*/

return $chart;

}


header('Content-Type: image/png');


$out = imagecreatetruecolor(1400, 1200);


$white = imagecolorallocate($out, 255, 255, 255);
imagefill($out, 0, 0, $white);


$img1 = create_bar($data1213);
$img2 = create_bar($data1314);
$img3 = create_bar($data1415);
$img4 = create_bar($data1516);
$img5 = create_bar($data1617);
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
//imagedestroy($chart);