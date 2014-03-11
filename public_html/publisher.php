<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "web/Pager.class.php" );
require_once( INCLUDE_DIR . "analyze/Publisher.class.php" );


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

		if( $this->os )
		{
			$this->handleByOs( $this->date, $this->os );
		}

		$this->assign( "os", $this->os );
	}

	private function handleByOs( $date, $os )
	{
		$publisher = new AnalyzePublisher();
		$list = $publisher->loadFromDb( $date, $os );

		$out = array();
		foreach( $list as $holder )
		{
			if( $holder instanceof PublisherTableHolder )
			{
				$packages = array();
				foreach( $holder->packages as $p ) $packages[] = $p;
				$out[] = array('publisher'=>mb_substr($holder->publisher, 0, 16) , 'packages'=>$packages, 'count'=>$holder->rating);
			}
		}

		$this->assign( "publishers", $out );
	}
}
$web = new PublisherWeb();
$web->run();
?>