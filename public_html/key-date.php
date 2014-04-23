<?php
require_once( "../configure.php" );
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "DB/DescriptionKeyTable.class.php" );


class KeyDateApi
{
	function run()
	{
		$date = new DateTime($_REQUEST['date']);
		$key = (string)$_REQUEST['key'];
		$os = (string)$_REQUEST['os'];

		$out = array();
		$table = DescriptionKeyTable::Factory();
		$rows = $table->selectByKey( $key, $os, $date, $date );
		if(count($rows) == 0)
		{
			$out['data'] = array( array("Nothing", 1) );
		}
		else
		{
			$count = (int)$rows[0]['count'];
			$other = 200 - $count;
			$out['data'] = array(array($key, $count), array("Other", $other));
		}

		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		print Util::jsonEncode( $out );
	}
}
$api = new KeyDateApi();
$api->run();