<?php
header('Content-Type: image/png');

$im = imagecreatetruecolor(400, 30);

$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 399, 29, $white);
if (!$_GET['text']) $text = base64_decode('WmVuVGhhbmc=');
else $text = $_GET['text'];
$font = 'fonts/' . $_GET['font'];
imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);
imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

imagepng($im);
imagedestroy($im);
?>

