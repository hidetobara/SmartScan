<?php
require_once( "HTTP/Request2.php" );
require_once( INCLUDE_DIR . "Util.class.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class IosRankingCrawl extends BaseRankingCrawl
{
	const URL_RANKING = "https://itunes.apple.com/jp/rss/topgrossingapplications/limit=200/xml";

	public $items = array();
	private $date;

	public function __construct()
	{
		$this->date = new DateTime();
	}

	public function run()
	{
		$html = $this->download();
		$this->retrieve( $html );
		return $this->items;
	}

	private function download()
	{
		$request = new HTTP_Request2(self::URL_RANKING, HTTP_Request2::METHOD_GET);
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
		@$doc->loadHTML( $html );

		$xpath = new DOMXpath( $doc );
		$xpath->registerNamespace("im", "http://itunes.apple.com/rss");

		$nodes = $xpath->query( "//entry" );
		foreach( $nodes as $node )
		{
			$i = new PackageInfo();
			$i->os = OS_IOS;
			$i->date = clone($this->date);
			$i->rank = count($this->items) + 1;

			$packageNode = $xpath->query("id", $node);
			$i->package = $packageNode->item(0)->getAttribute("im:bundleid");
			$i->detail_url = $packageNode->item(0)->nodeValue;

			$titleNode = $xpath->query("name", $node);
			$i->title = $titleNode->item(0)->nodeValue;

			$publisherNode = $xpath->query("artist", $node);
			$i->publisher = $publisherNode->item(0)->nodeValue;
			$i->publisher = mb_convert_kana($i->publisher, "s");

			$imageNode = $xpath->query("image", $node);
			$i->image_url = $imageNode->item($imageNode->length-1)->nodeValue;

			$this->items[] = $i;
			//var_dump($i);
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
		return DATA_DIR . "ranking/" . $date->format("Ymd") . ".ios.json";
	}
}