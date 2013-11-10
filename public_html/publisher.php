<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "web/Pager.class.php" );
require_once( INCLUDE_DIR . "analyze/Publisher.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class PublisherWeb extends BaseWeb
{
	private $os;
	private $date;

	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'publisher.tpl';

	}

	function handle()
	{
		$this->date = new DateTime( $_REQUEST['date'] );
		$this->assign( "date", $this->date->format("Y-m-d") );

		$this->os = strtolower( $_REQUEST['os'] );
		if( OS_ANDROID == $this->os ) $this->handleByOs( OS_ANDROID );
		else if( OS_IOS == $this->os ) $this->handleByOs( OS_IOS );
		else $this->os = null;

		$this->assign( "os", $this->os );
	}

	private function handleByOs( $os )
	{
		$packager = new PackageManager();
		$packager->load( $this->date, $os );

		$analyze = new AnalyzePublisher();
		$items = $analyze->pullup( $this->date, $os );
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

		$this->assign( "publishers", $out );
	}
}
$web = new PublisherWeb();
$web->run();
?>