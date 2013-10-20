<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . 'analyze/Publisher.class.php' );
require_once( INCLUDE_DIR . 'analyze/BestWorst.class.php' );
require_once( INCLUDE_DIR . 'Util.class.php' );


$instance = new AnalyzePublisher();
$instance->run( Util::getAndroidLastRankingPath() );
$instance->run( Util::getIosLastRankingPath() );
unset($instance);

$instance = new AnalyzeBestWorst();
$instance->run( Util::getAndroidLastRankingPath() );
unset($instance);
?>
