<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "DB/RankingTable.class.php" );
require_once( INCLUDE_DIR . "analyze/Review.class.php" );


class IndexWeb extends BaseWeb
{
	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'index.tpl';
	}

	function handle()
	{
		$today = new DateTime("-1 hour");

		$review = ReviewTable::Factory();
		$items = $review->selectByDate( $today, OS_ANDROID );

		$ranking = RankingTable::Factory();
		if( $items )
		{
			foreach( $items as $item )
			{
				$ranking->retrieve( $item );
			}
			$this->assign( "best_packages", $items );
		}
	}
}
$web = new IndexWeb();
$web->run();
?>