<?php
require_once( "../configure.php" );


function smarty_function_base64image($params, $template)
{
	$url = HOME_DIR . $params['url'];
	$style = $params['style'];
	$context = @file_get_contents( $url );
	if( !$context ) return "<img error='not found' />";

	$info = pathinfo( $url );
	$ext = $info['extension'];
	if( $ext == "png" ) { }
	else if( $ext == "jpg" || $ext == "jpeg" ) { $ext = "jpeg"; }
	else return "<img error='unknown extension' />";

	return "<img style='{$style}' src='data:image/{$ext};base64," . base64_encode( $context ) . "'>";
}
?>