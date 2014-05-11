<?php
require_once( INCLUDE_DIR . "keyword/mecab.function.php" );
require_once( INCLUDE_DIR . "DB/DescriptionTable.class.php" );
require_once( INCLUDE_DIR . "DB/DescriptionKeyTable.class.php" );


class UsedKeyword
{
	const KEY_LIMIT = 100;

	private $date;
	private $os;
	private $keys;

	function run( DateTime $date, $os )
	{
		$this->date = $date;
		$this->os = $os;
		$this->keys = array();

		$texts = DescriptionTable::Factory()->selectByDate( $this->date, $os );
		foreach( $texts as $p => $text )
		{
			$lines = mb_split( "[。\n]+", $text );
			$title = array();
			foreach( $lines as $line )
			{
				$items = mecab($line);
				foreach( $items as $item )
				{
					if( $item['parse'] != "名詞" ) continue;
					if( !$item['word'] || $item['word'] == "*" ) continue;
					$title[ $item['word'] ]++;
				}
			}
			foreach( $title as $word => $count ) $this->keys[ $word ]++;
			//print("$p is done.\n");
		}
		arsort( $this->keys );
		$this->keys = array_slice( $this->keys, 0, self::KEY_LIMIT );
		foreach( $this->keys as $key => $count )
		{
			DescriptionKeyTable::Factory()->insert( $this->date, $this->os, $key, $count );
		}
	}
}
?>
