<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . 'analyze/Publisher.class.php' );
require_once( INCLUDE_DIR . 'analyze/Review.class.php' );
require_once( INCLUDE_DIR . 'Util.class.php' );

$Today = new DateTime();
if(false)
{
	$instance = new AnalyzePublisher();
	$instance->run( $Today, OS_ANDROID );
	$instance->run( $Today, OS_IOS );
	unset($instance);
}

if(true)
{
	$instance = new AnalyzeReview();
	$instance->run( $Today, OS_ANDROID );
	unset($instance);
}
?>
