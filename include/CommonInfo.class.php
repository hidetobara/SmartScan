<?php

class PackageInfo
{
	public $os;
	public $date;
	public $package;

	public $rank;
	public $rating;
	public $rating_count;
	public $rating_worst_count;
	public $rating_best_count;
	public $title;
	public $publisher;

	public $image_url;
	public $detail_url;

	public $point;

	public static function parse( $a )
	{
		if( !is_array($a) ) return null;

		$i = new PackageInfo();
		$i->retrieve( $a );
		return $i;
	}

	public function retrieve( $a )
	{
		if( !is_array($a) ) return;

		$this->os = $a['os'];
		$this->date = new DateTime( $a['date'] );
		$this->package = $a['package'];

		$this->rank = $a['rank'];
		$this->rating = $a['rating'];
		$this->rating_count = $a['rating_count'];
		$this->rating_worst_count = $a['rating_worst_count'];
		$this->rating_best_count = $a['rating_best_count'];
		$this->title = $a['title'];
		$this->publisher = $a['publisher'];

		$this->image_url = $a['image_url'];
		$this->detail_url = $a['detail_url'];
		if( $this->os == OS_ANDROID ) $this->detail_url = "https://play.google.com/store/apps/details?id=" . $this->package;

		$this->point = $a['point'];
	}

	public function copy( $p )
	{
		if( $p == null ) return;

		if( $p->os ) $this->os = $p->os;
		if( $p->date ) $this->date = $p->date;
		if( $p->package ) $this->package = $p->package;

		if( $p->rank ) $this->rank = $p->rank;
		if( $p->rating ) $this->rating = $p->rating;
		if( $p->rating_count ) $this->rating_count = $p->rating_count;
		if( $p->rating_worst_count ) $this->rating_worst_count = $p->rating_worst_count;
		if( $p->rating_best_count ) $this->rating_best_count = $p->rating_best_count;
		if( $p->title ) $this->title = $p->title;
		if( $p->publisher ) $this->publisher = $p->publisher;

		if( $p->image_url ) $this->image_url = $p->image_url;
		if( $p->detail_url ) $this->detail_url = $p->detail_url;

		if( $p->point ) $this->point = $p->point;
	}

	public function isAndroid(){ return $this->os == OS_ANDROID; }
	public function isIos(){ return $this->os == OS_IOS; }
}

abstract class BaseRankingCrawl
{
	abstract function run();
	abstract function save();

	public function load( $date )
	{
		$path = $this->getPath( $date );
		$content = file_get_contents( $path );
		$items = Util::jsonDecode( $content );

		$this->items = array();
		foreach( $items as $item )
		{
			$this->items[] = PackageInfo::parse( $item );
		}
		return $this->items;
	}
}

?>