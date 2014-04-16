<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "web/Pager.class.php" );
require_once( INCLUDE_DIR . "analyze/RankingShift.class.php" );


class ShiftWeb extends BaseWeb
{
	private $os;
	private $date;

	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'shift.tpl';
	}

	function initialize()
	{
		$this->os = strtolower( $_REQUEST['os'] );
		$this->date = new DateTime( $_REQUEST['date'] ? $_REQUEST['date'] : "-1 hour" );

		if( !$this->os ) $this->os = OS_ANDROID;
	}

	function handle()
	{
		$shift = new RankingShift();
		$rows = $shift->select( $this->date, $this->os );
		$pager = new Pager( $rows, 30 );

		$this->assign( "date", $this->date->format("Y-m-d") );
		$this->assign( "packages", $pager->currentItems );
	}
}
$web = new ShiftWeb();
$web->run();
