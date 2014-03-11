<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );


class RankingWeb extends BaseWeb
{
	private $os;
	private $date;

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
		$ranking = RankingTable::Factory();
		$items = $ranking->selectByDate( $this->date, $this->os );
		$this->assign( "packages", $items );

		$this->assign( "date", $this->date->format("Y-m-d") );
		$this->assign( "os", $this->os );
	}
}
$web = new RankingWeb();
$web->run();
?>