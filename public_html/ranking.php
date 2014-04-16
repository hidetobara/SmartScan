<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "web/Pager.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );


class RankingWeb extends BaseWeb
{
	private $os;
	private $date;
	private $page;

	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'ranking.tpl';
	}

	function initialize()
	{
		$this->os = strtolower( $_REQUEST['os'] );
		$this->date = new DateTime( $_REQUEST['date'] ? $_REQUEST['date'] : "-1 hour" );
		$this->page = (int)$_REQUEST['page'];

		if( !$this->os ) $this->os = OS_ANDROID;
		if( !$this->page ) $this->page = 1;
	}

	function handle()
	{
		$ranking = RankingTable::Factory();
		$rows = $ranking->selectByDate( $this->date, $this->os );
		$pager = new Pager( $rows, 50, $this->page );

		$this->assign( "pager", $pager );
		$this->assign( "page", $this->page );

		$this->assign( "date", $this->date->format("Y-m-d") );
		$this->assign( "os", $this->os );
	}
}
$web = new RankingWeb();
$web->run();
?>