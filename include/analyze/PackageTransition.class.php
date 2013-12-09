<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "PackageManager.class.php" );


class PackageTransition
{
	function pickup( PackageInfo $info, DateTime $from, DateTime $to )
	{
		$packager = new PackageManager();
		$iphones = array();
		$androids = array();
		$categories = array();

		for( $date = clone($from); $date <= $to; $date->modify("+1 day")  )
		{
			$categories[] = $date->format("Y-m-d");

			$info->rank = null;
			$info = $packager->load( $date, OS_ANDROID )->get( $info );
			$androids[] = $info->rank;

			$info->rank = null;
			$info = $packager->load( $date, OS_IOS )->get( $info );
			$iphones[] = $info->rank;
		}
		return array( 'categories'=>$categories,
				'series'=>array( array('name'=>'Android','data'=>$androids),array('name'=>'iPhone','data'=>$iphones)) );
	}
}
