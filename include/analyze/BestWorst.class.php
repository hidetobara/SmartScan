<?php


class AnalyzeBestWorst
{
	const ALL_KEY = "ratingCount";
	const BEST_KEY = "ratingBestCount";
	const WORST_KEY = "ratingWorstCount";

	private $path;
	private $items;

	public function run()
	{
		$this->load();
		$this->analyze();
		$this->save();
	}

	private function load()
	{
		$paths = glob( DATA_DIR . "android_rank/*.json" );
		$this->path = $paths[ count($paths)-1 ];

		$json = file_get_contents( $this->path );
		$this->items = json_decode( $json, true );
	}

	private function analyze()
	{
		usort(
				$this->items,
				function($a, $b)
				{
					$av = sqrt($a[self::ALL_KEY]) * $a[self::BEST_KEY] / $a[self::WORST_KEY];
					$bv = sqrt($a[self::ALL_KEY]) * $b[self::BEST_KEY] / $b[self::WORST_KEY];
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
			$result .= json_encode($box, JSON_UNESCAPED_UNICODE) . "\n";
		}

		$info = pathinfo( $this->path );
		$path = DATA_DIR . "android_bestworst/" . $info['filename'] . ".csv";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		file_put_contents( $path, $result );
	}
}
