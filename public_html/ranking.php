<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "android/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "ios/RankingCrawl.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class RankingWeb extends BaseWeb
{
	private $os;
	private $date;
	private $packager;

	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'ranking.tpl';
	}

	function initialize()
	{
		$this->os = strtolower( $_REQUEST['os'] );
		$this->date = new DateTime( $_REQUEST['date'] );

		if( !$this->os ) $this->os = OS_ANDROID;
	}

	function handle()
	{
		if( $this->os == OS_ANDROID ) $crawl = new AndroidRankingCrawl();
		if( $this->os == OS_IOS ) $crawl = new IosRankingCrawl();

		if( $this->os )
		{
			$this->packager = new PackageManager();
			$this->packager->load( new DateTime(), $this->os );
		}

		if( $crawl != null )
		{
			$crawl->load( $this->date );
			foreach( $crawl->items as $item )
			{
				$this->packager->get( $item );
			}
			$this->assign( "packages", $crawl->items );
		}

		$this->assign( "date_str", $this->date->format("Y-m-d") );
	}
}
$web = new RankingWeb();
$web->run();
?>