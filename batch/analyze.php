<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . 'analyze/Publisher.class.php' );
require_once( INCLUDE_DIR . 'analyze/BestWorst.class.php' );
require_once( INCLUDE_DIR . 'Util.class.php' );

$Today = new DateTime();
if(true)
{
	$instance = new AnalyzePublisher();
	$instance->run( $Today, OS_ANDROID );
	$instance->run( $Today, OS_IOS );
	unset($instance);
}

if(false)
{
	$instance = new AnalyzeBestWorst();
	$instance->run( Util::getAndroidLastRankingPath() );
	unset($instance);
}
?>
