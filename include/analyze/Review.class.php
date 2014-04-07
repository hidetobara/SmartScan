<?php
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "DB/ReviewTable.class.php" );


class AnalyzeReview
{
	private $os;
	private $date;

	public $items;

	public function run( DateTime $date, $os )
	{
		$this->date = clone($date);
		$this->os = $os;

		$this->load();
		$this->analyze();
		$this->save2db();
	}

	private function load()
	{
		$this->items = RankingTable::Factory()->selectByDate( $this->date, OS_ANDROID );
	}

	private function analyze()
	{
		usort(
				$this->items,
				function($a, $b)
				{
					if( !$a->rating_worst_count ) $a->rating_worst_count = 1;
					if( !$b->rating_worst_count ) $b->rating_worst_count = 1;
					$av = sqrt($a->rating_count) * $a->rating_best_count / $a->rating_worst_count;
					$bv = sqrt($b->rating_count) * $b->rating_best_count / $b->rating_worst_count;
					if( $av < $bv ) return 1; else return -1;
				}
				);
	}

	private function save2db()
	{
		foreach( $this->items as $item )
		{
			$item->point = sqrt($item->rating_count) * $item->rating_best_count / $item->rating_worst_count;
		}
		$this->items = array_slice( $this->items, 0, 5 );

		ReviewTable::Factory()->insert( $this->date, $this->os, $this->items );
	}
}
