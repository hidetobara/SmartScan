<?php
require_once( '../configure.php' );

$files = glob( HOME_DIR . "icon/*/*.png" );
foreach( $files as $file )
{
	$original = imagecreatefrompng($file);
	$x = imagesx($original);
	$y = imagesy($original);

	$resize = imagecreatetruecolor(THUMB_SIZE, THUMB_SIZE);
	imagealphablending($resize, false);  // アルファブレンディングをoffにする
	imagesavealpha($resize, true);       // 完全なアルファチャネル情報を保存するフラグをonにする
	imagecopyresampled($resize, $original, 0, 0, 0, 0, THUMB_SIZE, THUMB_SIZE, $x, $y);

	$cells = explode("/", $file);
	$cells = array_slice($cells, -2, 2);
	$path = HOME_DIR . "thumb/" . implode("/", $cells);
	print($file . " > " . $path . "\n");
	$dir = dirname($path);
	if( !is_dir($dir) ) mkdir( $dir, 0777, true );
	imagepng($resize, $path);
	imagedestroy($original);
	imagedestroy($resize);
}
print("OK thumb.php");