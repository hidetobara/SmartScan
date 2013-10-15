<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . 'analyze/Publisher.class.php' );
require_once( INCLUDE_DIR . 'analyze/BestWorst.class.php' );


$instance = new AnalyzePublisher();
$instance->run();
unset($instance);

$instance = new AnalyzeBestWorst();
$instance->run();
unset($instance);

?>
