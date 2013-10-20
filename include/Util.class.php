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

	/*
	 * 本番と開発でバージョンが違うため
	 */
	static function jsonEncode( $object )
	{
		if( ENV_TYPE == "RELASE" ){
			return json_encode( $object );
		}else{
			return json_encode( $object, JSON_UNESCAPED_UNICODE );
		}
	}
}

?>