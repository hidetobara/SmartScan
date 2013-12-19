<?php
require_once( "HTTP/Request2.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class IosPackage extends BasePackage
{
	//	const URL_RANKING = "https://itunes.apple.com/jp/app/kuizurpg-mo-fa-shiito-hei/id621106129?mt=8&uo=2";

	public function run( PackageInfo $info )
	{
		$html = $this->download( $info );
		$this->retrieve( $info, $html );
		$this->downloadImage( $info );
	}

	private function download( PackageInfo $info )
	{
		if( !$info->detail_url ) return null;

		$request = new HTTP_Request2($info->detail_url, HTTP_Request2::METHOD_GET);
		$request->setConfig('follow_redirects', true);
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

		$nodes = $xpath->query("//div[@class='rating']");
		$node = $nodes->item( $nodes->length-1 );	// 全体の評価

		$review = $xpath->query( "div/span[@class='rating-star']", $node )->length;
		$review += ($xpath->query( "div/span[@class='rating-star half']", $node )->length) / 2.0;
		$info->rating = $review;

		$countNode = $xpath->query( "span[@class='rating-count']", $node );
		$countStr = $countNode->item(0)->nodeValue;
		if( preg_match( "/\d+/", $countStr, $matches ) )
		{
			$info->rating_count = (int)$matches[0];
		}
	}
}