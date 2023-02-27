<?php
// The file
$filename = $_GET["url"];
$percent = 0.5; // percentage of resize
$resRatio = 380/260;



// Get new dimensions
list($width, $height) = getimagesize($filename);
//$new_width = $width * $percent;
//$new_height = $height * $percent;
$new_width = $width;
$new_height = $width/$resRatio;

// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);

$type = exif_imagetype($filename);
if($type == 2) {
	// Content type
	header('Content-type: image/jpeg');
	$image = imagecreatefromjpeg($filename);
} else if($type == 3) {
	// Content type
	header('Content-type: image/png');
	$image = imagecreatefrompng($filename);
} else {
	$image = imagecreatetruecolor(150, 30);
	$bgc = imagecolorallocate($image, 255, 255, 255);
	$tc = imagecolorallocate($image, 0, 0, 0);

	imagefilledrectangle($image, 0, 0, 150, 30, $bgc);

	/* Output an error message */
	imagestring($image, 1, 5, 5, 'Error loading ' . $filename, $tc);
	
	
	imagejpeg($img);
	imagedestroy($image);
	die();
}


imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Output
imagejpeg($image_p, null, 100);
imagedestroy($image_p);
?>
