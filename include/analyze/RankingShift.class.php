<?php
require_once( INCLUDE_DIR . "DB/RankingShiftTable.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );


class RankingShift
{
	function run( DateTime $date )
	{
		$this->runByOs( $date, OS_ANDROID );
		$this->runByOs( $date, OS_IOS );
	}

	function runByOs( DateTime $date, $os )
	{
		$table = array();
		for( $back = 0; $back < 2; $back++ )
		{
			$day = clone($date);
			$day->modify("-$back day");
			$list = RankingTable::Factory()->selectByDate( $day, $os );

			foreach( $list as $item )
			{
				if( !$table[ $item->package ] ) $table[ $item->package ] = array();
				$table[ $item->package ][ $back ] = $item->rank;
			}
		}

		foreach( $table as $package => $ranks )
		{
			if( count($ranks) < 2 ) continue;
			$shift = $ranks[0] - $ranks[1];
			if( $shift < 0 )
			{
				$i = new PackageInfo();
				$i->package = $package;
				$i->date = $date;
				$i->os = $os;
				RankingShiftTable::Factory()->insert( $i, $shift );
			}
		}
	}

	function select( DateTime $date, $os )
	{
		$rows = RankingShiftTable::Factory()->selectByDate( $date, $os );
		foreach( $rows as $row )
		{
			RankingTable::Factory()->retrieve( $row );
		}
		return $rows;
	}
}
