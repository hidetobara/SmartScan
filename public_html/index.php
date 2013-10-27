<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
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
		$today = new DateTime("2013/10/27");
		$today = new DateTime();

		$packager = new PackageManager();
		$packager->load( $today, OS_ANDROID );

		$analyze = new AnalyzeBestWorst();
		$items = $analyze->pullup( $today, OS_ANDROID );
		foreach( $items as $item )
		{
			$packager->get( $item );
		}
		$this->assign( "best_packages", $items );
	}
}
$web = new IndexWeb();
$web->run();
?>