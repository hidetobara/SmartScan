<?php
require_once( INCLUDE_DIR . "DB/DescriptionTable.class.php" );
require_once( INCLUDE_DIR . "DB/DescriptionKeyTable.class.php" );


class CountKeyword
{
	const KEYWORDS = "RPG,ドラゴン,イケメン,恋愛";

	function run( $today )
	{
		$desc = DescriptionTable::Factory();
		$descKey = DescriptionKeyTable::Factory();
		$keywords = mb_split(",", self::KEYWORDS);

		foreach( $keywords as $keyword )
		{
			foreach (array(OS_ANDROID, OS_IOS) as $os)
			{
				$count = $desc->selectCount( $today, $os, $keyword );
				$descKey->insert( $today, $os, $keyword, $count );
			}
		}
	}
}