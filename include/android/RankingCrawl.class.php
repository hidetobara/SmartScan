<?php
require_once( "HTTP/Request2.php" );
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );

class AndroidRankingCrawl extends BaseRankingCrawl
{
	const URL_RANKING = "https://play.google.com/store/apps/collection/topgrossing?hl=ja";
	/*
	 start:300
	num:60
	numChildren:0
	ipf:1
	xhr:1
	token:2Uyfsv-7Y2l3x4cZWsGGprWergI:1381051909291
	hl:ja
	*/
	public $items = array();
	private $date;

	public function __construct()
	{
		$this->date = new DateTime();
	}

	public function run()
	{
		$start = 0;
		$num = 200;

		while( $num > 0 )
		{
			if( $num >= 100 )
			{
				$count = 100;
				$num -= 100;
			}
			else
			{
				$count = $num;
				$num = 0;
			}

			$html = $this->download( $start, $count );
			$this->retrieve( $html );

			$start += $count;
		}
		//$this->save();
		return $this->items;
	}

	/*
	 * 100個以上同時には取得できない
	*/
	private function download( $start, $num )
	{
		$request = new HTTP_Request2(self::URL_RANKING, HTTP_Request2::METHOD_POST);
		$request->addPostParameter("start", $start);
		$request->addPostParameter("num", $num);
		$request->addPostParameter("hl", "ja");
		$request->setConfig('ssl_verify_peer', false);
		$request->setConfig('ssl_verify_host', false);

		$response = $request->send();
		if($response->getStatus() != 200){
			var_dump($response);
			return false;
		}
		$data = $response->getBody();
		return $data;
	}

	private function retrieve( $html )
	{
		$doc = new DOMDocument();
		@$doc->loadHTML( '<?xml encoding="UTF-8">' . $html );

		$xpath = new DOMXpath( $doc );
		$nodes = $xpath->query( "//div[@data-docid and @data-server-cookie]" );

		foreach( $nodes as $node )
		{
			$item = new PackageInfo();
			$item->os = "android";
			$item->date = clone($this->date);
			$item->rank = count($this->items) + 1;

			$item->package = $node->getAttribute( "data-docid" );

			$this->items[] = $item;
			//var_dump($item);
		}
	}

	public function save()
	{
		//$this->save2json();
		$this->save2db();
	}

	public function save2db()
	{
	}

	public function save2json()
	{
		$path = $this->getPath( $this->date );
		$dir = dirname( $path );
		if( !file_exists($dir) ) mkdir( $dir, 0777, true );

		$json = Util::jsonEncode( $this->items );
		$json = str_replace("},", "},\n", $json);
		file_put_contents( $path, $json );
	}

	public function getPath( $date )
	{
		return DATA_DIR . "ranking/" . $date->format("Ymd") . ".android.json";
	}
}
?>