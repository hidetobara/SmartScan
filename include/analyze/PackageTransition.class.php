<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class PackageTransition
{
	function pickup( PackageInfo $info, DateTime $from, DateTime $to )
	{
		$packager = new PackageManager();
		$ranks = array();
		$categories = array();

		for( $date = clone($from); $date <= $to; $date->modify("+1 day")  )
		{
			$categories[] = $date->format("Y-m-d");

			$info->rank = null;
			$info = $packager->load( $date, $info->os )->get( $info );
			$ranks[] = $info->rank;
		}

		if( $info->os == OS_ANDROID ) $os = "Android";
		if( $info->os == OS_IOS ) $os = "iOS";

		return array( 'categories'=>$categories,
				'series'=>array( array('name'=>$os,'data'=>$ranks) ) );
	}
}
