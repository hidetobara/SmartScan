<?php
require_once( INCLUDE_DIR . "Util.class.php" );


class AnalyzeBestWorst
{
	const ALL_KEY = "ratingCount";
	const BEST_KEY = "ratingBestCount";
	const WORST_KEY = "ratingWorstCount";

	private $items;
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
					$av = sqrt($a[AnalyzeBestWorst::ALL_KEY]) * $a[AnalyzeBestWorst::BEST_KEY] / $a[AnalyzeBestWorst::WORST_KEY];
					$bv = sqrt($a[AnalyzeBestWorst::ALL_KEY]) * $b[AnalyzeBestWorst::BEST_KEY] / $b[AnalyzeBestWorst::WORST_KEY];
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
			$result .= Util::jsonEncode($box) . "\n";
		}

		$os = "unknown";
		if( strpos($this->path, "android") ) $os = "android";
		if( strpos($this->path, "ios") ) $os = "ios";

		$info = pathinfo( $this->path );
		$path = DATA_DIR . "bestworst/" . $info['filename'] . ".{$os}.csv";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		file_put_contents( $path, $result );
	}
}
