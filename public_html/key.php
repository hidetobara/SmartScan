<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );


class KeyWeb extends BaseWeb
{
	private $os;
	private $date;

	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'key.tpl';
	}

	function initialize()
	{
		$r = $this->getRequest();
		$this->os = strtolower( $r['os'] );
		$this->date = new DateTime( $r['date'] );

		if( !$this->os ) $this->os = OS_ANDROID;

		$this->assign( "date", $this->date->format("Y-m-d") );
		$this->assign( "os", $this->os );
	}

	function handle()
	{
	}
}
$web = new KeyWeb();
$web->run();
?>