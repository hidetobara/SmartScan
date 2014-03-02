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

	private function save2Json()
	{
		$result = "";
		foreach( $this->result as $publisher => $list )
		{
			$box = array('publisher'=>$publisher, 'packages'=>$list);
			$result .= Util::jsonEncode($box) . "\n";
		}

		$path = DATA_DIR . "publisher/" . $this->date->format("Ymd") . "." . $this->os . ".csv";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		file_put_contents( $path, $result );
	}
}
?>