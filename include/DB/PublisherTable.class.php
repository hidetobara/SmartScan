<?php
require_once( INCLUDE_DIR . "DB/BaseTable.class.php" );

class PublisherTableHolder
{
	public $publisher;
	public $rating;
	public $packages;
}

class PublisherTable extends BaseTable
{
    const RANKING_TABLE = "publisher";

    private static $Instance = null;
    public static function Factory()
    {
        if( self::$Instance == null )
        {
            $instance = new self();
			$instance->pdo = self::makePdo();
            self::$Instance = $instance;
        }
        return self::$Instance;
    }

	public function insert( DateTime $date, $os, $publishers )
	{
		$table = self::RANKING_TABLE;
		$sql = <<< HEAR_INSERT
INSERT INTO $table
VALUES ( :date, :os, :publisher, :rating, :data )
HEAR_INSERT;
		$state = $this->pdo->prepare( $sql );
		foreach( $publishers as $p => $data )
		{
			$rating = $data['rating'] ? $data['rating'] : count( $data['packages'] );
			$text = Util::jsonEncode( $data );
			$array = array(':date'=>$date->format("Y-m-d"), ':os'=>$os, ':publisher'=>$p, ':rating'=>$rating, ':data'=>$text);
			$state->execute( $array );
		}
	}

	public function selectByDate( DateTime $date, $os )
	{
		$table = self::RANKING_TABLE;
		$sql = <<< HEAR_DATE
SELECT `publisher`,`rating`,`data` FROM $table
WHERE date = :date AND os = :os
ORDER BY `rating` DESC
HEAR_DATE;
		$state = $this->pdo->prepare( $sql );
		$array = array( ':date'=>$date->format('Y-m-d'), ':os'=>$os );
		$state->execute( $array );

		$list = array();
		while( $row = $state->fetch() )
		{
			$holder = new PublisherTableHolder();
			$holder->publisher = $row['publisher'];
			$holder->rating = $row['rating'];
			$holder->packages = array();

			$data = Util::jsonDecode($row['data']);
			foreach( $data['packages'] as $p )
			{
				$i = PackageInfo::parse(array('package'=>$p));
				$i->os = $os;
				$i->date = clone($date);
				$holder->packages[] = $i;
			}
			$list[] = $holder;
		}
		return $list;
	}
}