<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "analyze/PackageTransition.class.php" );


class TransitionWeb extends BaseWeb
{
	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'transition.tpl';
	}

	function handle()
	{
		$transition = new PackageTransition();

		$p = PackageInfo::parse( array("package"=>"jp.colopl.quizwiz", "os"=>"android") );
		$ranks = $transition->pickup( $p, new DateTime("-1 month"), new DateTime() );
		var_dump( $ranks );
	}
}
$web = new TransitionWeb();
$web->run();
?>