<?php
require_once( INCLUDE_DIR . "Util.class.php" );


class AnalyzePublisher
{
	private $path;
	private $items;
	private $table;

	public function run( $path )
	{
		$this->path = $path;

		$this->clear();
		$this->load( $path );
		$this->analyze();
		$this->save();
	}

	private function clear()
	{
		$this->table = array();
	}

	private function load( $path )
	{
		$json = file_get_contents( $path );
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
			$result .= Util::jsonEncode($box) . "\n";
		}

		$os = "unknown";
		if( strpos($this->path, "android") ) $os = "android";
		if( strpos($this->path, "ios") ) $os = "ios";

		$info = pathinfo( $this->path );
		$path = DATA_DIR . "publisher/" . $info['filename'] . ".{$os}.csv";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		file_put_contents( $path, $result );
	}
}
?>