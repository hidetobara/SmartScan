<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . "android/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "android/Package.class.php" );
require_once( INCLUDE_DIR . "ios/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "ios/Package.class.php" );


if(true)
{
	$crawl = new AndroidRankingCrawl();
	$crawl->run( 0, 200 );
	$package = new AndroidPackage();
	foreach( $crawl->items as $item )
	{
		$package->run( $item );
		sleep(1);
	}
	$crawl->save();
}

if(true)
{
	$crawl = new IosRankingCrawl();
	$crawl->run();
	$package = new IosPackage();
	foreach( $crawl->items as $item )
	{
		$package->run( $item );
		sleep(1);
	}
	$crawl->save();
}

?>