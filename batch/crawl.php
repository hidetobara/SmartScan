<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . "android/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "android/Detail.class.php" );
require_once( INCLUDE_DIR . "ios/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "ios/Detail.class.php" );


if(true)
{
	$ranking = new AndroidRankingCrawl();
	$infos = $ranking->run( 0, 200 );

	$package = new AndroidDetailCrawl();
	foreach( $infos as $info )
	{
		$package->run( $info );
		$info->save();
		sleep(1);
	}
}

if(false)
{
	$crawl = new IosRankingCrawl();
	$infos = $crawl->run();

	$package = new IosDetailCrawl();
	foreach( $infos as $info )
	{
		$package->run( $info );
		$info->save();
		sleep(1);
	}
}

?>