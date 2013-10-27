<?php
require_once( INCLUDE_DIR . "Util.class.php" );


class AnalyzePublisher
{
	private $path;
	private $source;
	private $result;

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
		$this->source = array();
		$this->result = array();
	}

	private function load( $path )
	{
		$json = file_get_contents( $path );
		$this->source = json_decode( $json, true );
	}

	private function analyze()
	{
		// 会社ごとに並べる
		$table = array();
		foreach( $this->source as $item )
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
			$this->result[ $best ] = $bestList;
			unset( $table[ $best ] );
		}
	}

	private function save()
	{
		$result = "";
		foreach( $this->result as $publisher => $list )
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

	public function pullup( DateTime $date, $os )
	{
		$dateStr = $date->format("Ymd");
		$paths = glob( DATA_DIR . "publisher/" . $dateStr . "*.{$os}.csv" );
		if( count($paths) == 0 ) return null;

		$this->result = array();
		$this->path = $paths[0];
		$file = fopen( $this->path, "r" );
		while( $line = fgets($file) )
		{
			$item = json_decode($line, true);
			$this->result[] = $item;
		}
		fclose( $file );
		return $this->result;
	}
}
?>