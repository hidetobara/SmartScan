<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class PackageTransition
{
	function pickup( PackageInfo $info, DateTime $from, DateTime $to )
	{
		$packager = new PackageManager();
		$ranks = array();

		for( $date = clone($from); $date <= $to; $date->modify("+1 day")  )
		{
			$info->rank = null;
			$info = $packager->load( $date, $info->os )->get( $info );
			if( !$info->rank ) continue;

			$ranks[ $date->format("Y-m-d") ] = $info->rank;
		}
		return $ranks;
	}
}
