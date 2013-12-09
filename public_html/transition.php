<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );


class TransWeb extends BaseWeb
{
	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'transition.tpl';
	}

	function handle()
	{
		$this->assign('package', (string)$_REQUEST['package']);
	}
}
$web = new TransWeb();
$web->run();
?>