<?php
@session_start();
// ----------------------------------------- 
//  The Web Help .com
// ----------------------------------------- 
header('Content-type: image/jpeg');
$width = (isset($_GET['w']))?$_GET['w']:48;
$height = (isset($_GET['h']))?$_GET['h']:22;
$text = (isset($_GET['t']))?$_GET['t']:5;

$my_image = imagecreatetruecolor($width, $height);
imagefill($my_image, 0, 0, 0xFFFFFF);

$white = imagecolorallocate($my_image, 255, 255, 255); 
$red = imagecolorallocatealpha($my_image, 255, 0, 0, 90); 
$green = imagecolorallocatealpha($my_image, 0, 255, 0, 90); 
$blue = imagecolorallocatealpha($my_image, 0, 0, 255, 90); 
$colora = array($white,$red,$green,$blue);

//Noise
for ($c = 0; $c < 50; $c++){
	$x = rand(0,$width-1);
	$y = rand(0,$height-1);
	$ci = rand(0,(count($colora)-1));
	imagesetpixel($my_image, $x, $y, $colora[$ci]/*0x000000*/);
}
//Lines Noise
for ($c = 0; $c < 25; $c++){
	$ci = rand(0,(count($colora)-1));
	$x = rand(0,$width);
	$y = rand(0,$height);
	$x2 = rand(0,$width);
	$y2 = rand(0,$height);
	imageline($my_image, $x, $y, $x2, $y2, $colora[$ci]);
}
$rand_string = rand(1000,9999);
$x = rand(1,($width-(strlen($rand_string)*$text))/2);
$y = rand(1,round(($height-$text)/2));


imagestring($my_image, $text, $x, $y,$rand_string, 0x000000);
setcookie('maxilinecookie',(md5($rand_string).'a4xn'));

imagejpeg($my_image);
imagedestroy($my_image);
?>