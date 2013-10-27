<?php
require_once( INCLUDE_DIR . "CommonInfo.class.php" );
require_once( INCLUDE_DIR . "android/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "ios/RankingCrawl.class.php" );


class PackageManager
{
	private static $Instance = null;
	public static function factory()
	{
		if( self::$Instance == null ) self::$Instance = new PackageManager();
		return self::$Instance;
	}

	private $items;

	public function load( $date, $os )
	{
		$this->items = array();	// clear

		$path = null;
		if( $os == OS_IOS )
		{
			$crawl = new IosRankingCrawl();
			$path = $crawl->getPath( $date );
		}
		if( $os == OS_ANDROID )
		{
			$crawl = new AndroidRankingCrawl();
			$path = $crawl->getPath( $date );
		}
		if( !$path ) return $this;

		$content = file_get_contents( $path );
		if( !$content ) return $this;

		$array = json_decode( $content, true );
		foreach( $array as $a )
		{
			$p = PackageInfo::parse( $a );
			if( $p == null || !$p->package ) continue;
			$this->items[ $p->package ] = $p;
		}
		return $this;
	}

	public function get( PackageInfo $p )
	{
		$p->copy( $this->items[ $p->package ] );
		return $p;
	}
}