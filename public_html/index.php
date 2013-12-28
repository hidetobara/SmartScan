<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "web/Pager.class.php" );
require_once( INCLUDE_DIR . "analyze/BestWorst.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class IndexWeb extends BaseWeb
{
	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'index.tpl';
	}

	function handle()
	{
		$today = new DateTime("-1 hour");

		$packager = new PackageManager();
		$packager->load( $today, OS_ANDROID );

		$analyze = new AnalyzeBestWorst();
		$items = $analyze->pickup( $today, OS_ANDROID );
		if( $items )
		{
			$pager = new Pager( $items, 5 );
			$packager->arrayGet( $pager->currentItems );
			$this->assign( "best_packages", $pager->currentItems );
		}
	}
}
$web = new IndexWeb();
$web->run();
?>