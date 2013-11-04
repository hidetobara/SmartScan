<?php
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class Util
{
	static function getAndroidLastRankingPath()
	{
		$paths = glob( DATA_DIR . "ranking/*.android.json" );
		return $paths[ count($paths)-1 ];
	}

	static function getIosLastRankingPath()
	{
		$paths = glob( DATA_DIR . "ranking/*.ios.json" );
		return $paths[ count($paths)-1 ];
	}

	/*
	 * 本番と開発でバージョンが違うため
	 */
	static function jsonEncode( $object )
	{
		if( ENV_TYPE == "RELEASE" ){
			return json_encode( $object );
		}else{
			return json_encode( $object, JSON_UNESCAPED_UNICODE );
		}
	}
	static function jsonDecode( $string )
	{
		return json_decode( $string, true );
	}
}

?>