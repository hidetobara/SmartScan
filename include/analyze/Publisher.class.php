<?php


class AnalyzePublisher
{
	private $path;
	private $items;
	private $table;

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
		// 会社ごとに並べる
		$table = array();
		foreach( $this->items as $item )
		{
			$pub = $item['publisher'];
			$title = $item['title'];
			if( !is_array( $table[ $pub ] ) )
			{
				$table[ $pub ] = array($title);
			}
			else
			{
				$table[ $pub ][] = $title;
			}
		}

		// ソート
		$this->table = array();
		while( count($table) > 0 )
		{
			$best = null;
			$bestList = null;
			foreach( $table as $pub => $list )
			{
				if($best == null || count($list) > count($bestList) )
				{
					$best = $pub;
					$bestList = $list;
				}
			}
			$this->table[ $best ] = $bestList;
			unset( $table[ $best ] );
		}
	}

	private function save()
	{
		$result = "";
		foreach( $this->table as $publisher => $list )
		{
			$box = array('publisher'=>$publisher, 'titles'=>$list);
			$result .= json_encode($box, JSON_UNESCAPED_UNICODE) . "\n";
		}

		$info = pathinfo( $this->path );
		$path = DATA_DIR . "android_publisher/" . $info['filename'] . ".csv";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		file_put_contents( $path, $result );
	}
}
?>