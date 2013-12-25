<?php
require_once( INCLUDE_DIR . "Util.class.php" );


class AnalyzeBestWorst
{
	const ALL_KEY = "rating_count";
	const BEST_KEY = "rating_best_count";
	const WORST_KEY = "rating_worst_count";

	public $items;
	private $path;

	public function run( $path )
	{
		$this->path = $path;

		$this->load( $path );
		$this->analyze();
		$this->save();
	}

	private function load( $path )
	{
		$json = file_get_contents( $path );
		$this->items = json_decode( $json, true );
	}

	private function analyze()
	{
		usort(
				$this->items,
				function($a, $b)
				{
					if( !$a[AnalyzeBestWorst::WORST_KEY] ) $a[AnalyzeBestWorst::WORST_KEY] = 1;
					if( !$b[AnalyzeBestWorst::WORST_KEY] ) $b[AnalyzeBestWorst::WORST_KEY] = 1;
					$av = sqrt($a[AnalyzeBestWorst::ALL_KEY]) * $a[AnalyzeBestWorst::BEST_KEY] / $a[AnalyzeBestWorst::WORST_KEY];
					$bv = sqrt($b[AnalyzeBestWorst::ALL_KEY]) * $b[AnalyzeBestWorst::BEST_KEY] / $b[AnalyzeBestWorst::WORST_KEY];
					if( $av < $bv ) return 1; else return -1;
				}
				);
	}

	private function save()
	{
		$result = "";
		foreach( $this->items as $item )
		{
			$box = array('title'=>$item['title'], 'package'=>$item['package'], 'best'=>$item[self::BEST_KEY], 'worst'=>$item[self::WORST_KEY]);
			if( !$item[self::WORST_KEY] ) $item[self::WORST_KEY] = 1;
			$box['point'] = sqrt($item[self::ALL_KEY]) * $item[self::BEST_KEY] / $item[self::WORST_KEY];
			$result .= Util::jsonEncode($box) . "\n";
		}

		$info = pathinfo( $this->path );
		$path = DATA_DIR . "bestworst/" . $info['filename'] . ".csv";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		file_put_contents( $path, $result );
	}

	public function pickup( DateTime $date, $os )
	{
		$dateStr = $date->format("Ymd");
		$paths = glob( DATA_DIR . "bestworst/" . $dateStr . "*.{$os}.csv" );
		if( count($paths) == 0 ) return null;

		$this->items = array();
		$this->path = $paths[0];
		$file = fopen( $this->path, "r" );
		while( $line = fgets($file) )
		{
			$item = json_decode($line, true);
			$item['os'] = $os;
			$this->items[] = PackageInfo::parse( $item );
		}
		fclose( $file );
		return $this->items;
	}

}
