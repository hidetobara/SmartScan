<?php
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class Util
{
	static function getAndroidLastRankingPath()
	{
		$paths = glob( DATA_DIR . "android_rank/*.json" );
		return $paths[ count($paths)-1 ];
	}

	static function getIosLastRankingPath()
	{
		$paths = glob( DATA_DIR . "ios_rank/*.json" );
		return $paths[ count($paths)-1 ];
	}

}

?>