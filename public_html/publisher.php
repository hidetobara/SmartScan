<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "web/Pager.class.php" );
require_once( INCLUDE_DIR . "analyze/Publisher.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class PublisherWeb extends BaseWeb
{
	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'publisher.tpl';
	}

	function handle()
	{
		$this->handleByOs( OS_ANDROID );
		$this->handleByOs( OS_IOS );
	}

	private function handleByOs( $os )
	{
		$today = new DateTime();
		$packager = new PackageManager();
		$packager->load( $today, $os );

		$analyze = new AnalyzePublisher();
		$items = $analyze->pullup( $today, $os );
		if( !$items ) return;

		$pager = new Pager( $items, 30 );
		$out = array();
		foreach( $pager->currentItems as $item )
		{
			$pubisher = $item['publisher'];
			$packages = array();
			foreach( $item['packages'] as $p ){
				$i = PackageInfo::parse( array('package'=>$p) );
				$packager->get($i);
				$packages[] = $i;
			}
			$out[] = array('publisher'=>mb_substr($pubisher, 0, 16) , 'packages'=>$packages, 'count'=>count($packages));
		}

		$this->assign( "{$os}_publishers", $out );	//var_dump($out);
	}
}
$web = new PublisherWeb();
$web->run();
?>