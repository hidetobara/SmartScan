<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );


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
		$this->saveToJson();
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

	private function saveToJson()
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

	public function loadFromJson( DateTime $date, $os )
	{
		$dateStr = $date->format("Ymd");
		$paths = glob( DATA_DIR . "publisher/" . $dateStr . "*.{$os}.csv" );
		if( count($paths) == 0 ) return null;

		$result = array();
		$this->path = $paths[0];
		$file = fopen( $this->path, "r" );
		while( $line = fgets($file) )
		{
			$item = json_decode($line, true);
			$result[] = $item;
		}
		fclose( $file );
		return $result;
	}
}
?>