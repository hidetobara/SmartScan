<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . "android/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "android/Package.class.php" );


$crawl = new AndroidRankingCrawl();
$crawl->run( 0, 200 );
$package = new AndroidPackage();
foreach( $crawl->items as $item )
{
	$package->run( $item );
	sleep(1);
}
$crawl->save();
?>