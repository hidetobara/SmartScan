<?php
require_once( "HTTP/Request2.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class AndroidDetailCrawl extends BasePackageCrawl
{
	const URL_PACKAGE = "https://play.google.com/store/apps/details?id=%s&hl=ja";

	public function run( PackageInfo $info )
	{
		$html = $this->download( $info );
		$this->retrieve( $info, $html );
		$this->downloadImage( $info );
	}

	private function download( PackageInfo $info )
	{
		$url = sprintf( self::URL_PACKAGE, $info->package );
		$request = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
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

	private function retrieve( PackageInfo $info, $html )
	{
		$doc = new DOMDocument();
		@$doc->loadHTML( '<?xml encoding="UTF-8">' . $html );

		$xpath = new DOMXpath( $doc );

		$titleNode = $xpath->query( "//div[@class='document-title']/div" );
		$info->title = $titleNode->item(0)->nodeValue;

		$publishNode = $xpath->query( "//a[@class='document-subtitle primary']/span" );
		$info->publisher = $publishNode->item(0)->nodeValue;
		$info->publisher = mb_convert_kana($info->publisher, "s");

		$imageNode = $xpath->query( "//div[@class='cover-container']/img[@class='cover-image']" );
		$info->image_url = $imageNode->item(0)->getAttribute( "src" );

		$valueNode = $xpath->query("//meta[@itemprop='ratingValue']");
		$info->rating = $valueNode->item(0)->getAttribute("content");

		$countNode = $xpath->query("//meta[@itemprop='ratingCount']");
		$info->rating_count = $countNode->item(0)->getAttribute("content");

		$nodes = $xpath->query("//span[@class='bar-number']");
		foreach( $nodes as $node )
		{
			$attr = $node->parentNode->getAttributeNode("class");
			if( strpos($attr->value, "one") !== false ) $info->rating_worst_count = $this->convert2number( $node->nodeValue );
			if( strpos($attr->value, "five") !== false ) $info->rating_best_count = $this->convert2number( $node->nodeValue );
		}
	}

	private function convert2number( $string )
	{
		$string = str_replace( ",", "", $string );
		return (int)$string;
	}
}

