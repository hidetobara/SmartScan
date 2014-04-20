<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . 'analyze/Publisher.class.php' );
require_once( INCLUDE_DIR . 'analyze/Review.class.php' );
require_once( INCLUDE_DIR . 'analyze/RankingShift.class.php' );
require_once( INCLUDE_DIR . 'analyze/CountKeyword.class.php' );
require_once( INCLUDE_DIR . 'Util.class.php' );

$Today = new DateTime();
if(true)
{
	$instance = new CountKeyword();
	$instance->run($Today);
	unset($instance);
}
exit;

if(true)
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

if(true)
{
	$instance = new RankingShift();
	$instance->run( $Today );
	unset($instance);
}
?>
