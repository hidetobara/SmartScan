<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "analyze/PackageTransition.class.php" );


class TransitionApi
{
	function run()
	{
		$transition = new PackageTransition();

		//$_REQUEST['package'] = "com.sega.chainchronicle";
		$p = PackageInfo::parse( $_REQUEST );
		$out = $transition->pickup( $p, new DateTime("-1 month"), new DateTime() );

		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		print Util::jsonEncode( $out );
	}
}
$web = new TransitionApi();
$web->run();
?>