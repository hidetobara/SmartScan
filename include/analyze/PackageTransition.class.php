<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );


class PackageTransition
{
	function pickup( PackageInfo $info, DateTime $from, DateTime $to )
	{
		$rows = RankingTable::Factory()->selectInTerm( $info, $from, $to );
		$ranks = array();
		$categories = array();

		foreach( $rows as $row )
		{
			$categories[] = $row->date->format("Y-m-d");
			$ranks[] = (int)$row->rank;
		}

		if( $row->os == OS_ANDROID ) $os = "Android";
		if( $row->os == OS_IOS ) $os = "iOS";

		return array( 'categories'=>$categories,
				'series'=>array( array('name'=>$os,'data'=>$ranks) ) );
	}
}
