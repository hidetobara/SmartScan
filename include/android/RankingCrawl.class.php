<?php
require_once( "HTTP/Request2.php" );
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class AndroidRankingCrawl
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

	public function run( $start, $num )
	{
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
		$nodes = $xpath->query( "//div[@data-docid]" );

		foreach( $nodes as $node )
		{
			$item = new PackageInfo();
			$item->os = "android";
			$item->date = $this->date->format("Y-m-d H:i:s");
			$item->rank = count($this->items) + 1;

			$item->package = $node->getAttribute( "data-docid" );

			$titleNode = $xpath->query( "div/div/a[@class='title']", $node );
			$item->title = trim( $titleNode->item(0)->nodeValue );
			$publisherNode = $xpath->query( "div/div/div/a[@class='subtitle']", $node );
			$item->publisher = trim( $publisherNode->item(0)->nodeValue );

			$this->items[] = $item;
			//var_dump($item);
		}
	}

	public function save()
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
		return DATA_DIR . "android_rank/" . $date->format("Ymd") . ".json";
	}
}
?>