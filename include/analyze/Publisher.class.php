<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );
require_once( INCLUDE_DIR . "DB/PublisherTable.class.php" );


class AnalyzePublisher
{
	private $date;
	private $os;
	private $result;

	public function run( DateTime $date, $os )
	{
		$this->date = $date;
		$this->os = $os;

		$this->calculate();
		$this->save2db();
	}

	private function calculate()
	{
		$this->result = array();

		$table = RankingTable::Factory();
		$items = $table->sortByPublisherCount( $this->date, $this->os );

		foreach( $items as $item )
		{
			if( (int)$item[1] <= 1 ) continue;

			$base = new PackageInfo();
			$base->publisher = $item[0];
			$base->os = $this->os;
			$infos = $table->selectByPublisher( $base, $this->date );

			foreach( $infos as $info ) $this->result[ $base->publisher ][] = $info->package;
		}
	}

	private function save2db()
	{
		$list = array();
		foreach( $this->result as $publisher => $packages )
		{
			$box = array('packages'=>$packages);
			$list[ $publisher ] = $box;
		}

		PublisherTable::Factory()->insert( $this->date, $this->os, $list );
	}

	public function loadFromDb( DateTime $date, $os )
	{
		$this->date = $date;
		$this->os = $os;

		$list = PublisherTable::Factory()->selectByDate( $this->date, $this->os );
		$ranking = RankingTable::Factory();
		foreach( $list as $holder )
		{
			foreach( $holder->packages as $i ) $ranking->select( $i );
		}
		return $list;
	}
}
?>