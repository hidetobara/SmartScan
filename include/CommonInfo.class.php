<?php

/*
 * アプリのパッケージ情報
 */
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

	public $description;

	public $image_url;
	public $detail_url;
	public $image_cache;

	public $point;

	public static function parse( $a )
	{
		if( $a instanceof PackageInfo )
		{
			$i = new PackageInfo();
			$i->copy( $a );
			return $i;
		}
		if( is_array($a) )
		{
			$i = new PackageInfo();
			$i->retrieve( $a );
			return $i;
		}
		return null;
	}

	public function retrieve( $a )
	{
		if( !is_array($a) ) return;

		if( $a['os'] ) $this->os = strtolower( $a['os'] );
		if( $a['date'] ) $this->date = new DateTime( $a['date'] );
		if( $a['package'] ) $this->package = $a['package'];

		if( $a['rank'] ) $this->rank = $a['rank'];
		if( $a['rating'] ) $this->rating = $a['rating'];
		if( $a['rating_count'] ) $this->rating_count = $a['rating_count'];
		if( $a['rating_worst_count'] ) $this->rating_worst_count = $a['rating_worst_count'];
		if( $a['rating_best_count'] ) $this->rating_best_count = $a['rating_best_count'];
		if( $a['title'] ) $this->title = $a['title'];
		if( $a['publisher'] ) $this->publisher = $a['publisher'];

		if( $a['image_url'] ) $this->image_url = $a['image_url'];	// direct url of image

		if( $this->os == OS_ANDROID ) $this->detail_url = "https://play.google.com/store/apps/details?id=" . $this->package;
		else if( $a['detail_url'] ) $this->detail_url = $a['detail_url'];

		$this->image_cache = "thumb/" . $this->os . "/" . $this->package . ".png";	// cache of image

		if( $a['point'] ) $this->point = $a['point'];
	}

	public function copy( $p )
	{
		if( $p == null ) return;

		if( $p->os ) $this->os = $p->os;
		if( $p->date ) $this->date = clone($p->date);
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

	public function save()
	{
		$table = RankingTable::Factory();
		$table->insert($this);
	}
}

/*
 * クローラーの基本部分
 */
abstract class BaseRankingCrawl
{
	abstract function run();
	abstract function save();

	public function load( $date )
	{
		$path = $this->getPath( $date );
		$content = @file_get_contents( $path );
		$items = Util::jsonDecode( $content );
		if( !$items ) return array();

		$this->items = array();
		foreach( $items as $item )
		{
			$this->items[] = PackageInfo::parse( $item );
		}
		return $this->items;
	}
}

/*
 * パッケージャーの基本部分
 */
abstract class BasePackageCrawl
{
	public function downloadImage( PackageInfo $info )
	{
		if( !$info || !$info->image_url ) return false;

		$path = HOME_DIR . "icon/" . $info->os . "/" . $info->package . ".png";
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		$request = new HTTP_Request2( $info->image_url, HTTP_Request2::METHOD_GET);
		$request->setConfig('ssl_verify_peer', false);
		$request->setConfig('ssl_verify_host', false);

		$response = $request->send();
		if($response->getStatus() != 200){
			//var_dump($response);	// とれない時はあきらめる
			return false;
		}
		$data = $response->getBody();
		if( !$data ) return false;

		file_put_contents( $path, $data );
		return true;
	}
}
?>