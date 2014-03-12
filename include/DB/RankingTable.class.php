<?php
require_once( INCLUDE_DIR . "DB/BaseTable.class.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class RankingTable extends BaseTable
{
	const RANKING_TABLE = "ranking";

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

	public function insert( PackageInfo $i )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `package` = :package AND `os` = :os AND `date` = :date";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=>$i->package, ':os'=>$i->os, ':date'=>$i->date->format("Y-m-d") );
		$state->execute( $array );
		$row = $state->fetch();
		if( $row ) return;

		try
		{
			$sql = "INSERT INTO " . self::RANKING_TABLE . " VALUES ( :package, :os, :date, :rank, :rating, :rating_count, :worst, :best, :title, :publisher, :image, :detail );";
			$state = $this->pdo->prepare( $sql );
			$array = array( ':package'=>$i->package, ':os'=>$i->os, ':date'=>$i->date->format("Y-m-d"),
					':rank'=>$i->rank, ':rating'=>(float)$i->rating, ':rating_count'=>$i->rating_count, ':worst'=>$i->rating_worst_count, ':best'=>$i->rating_best_count,
					':title'=>$i->title, ':publisher'=>$i->publisher, ':image'=>$i->image_url, ':detail'=>$i->detail_url );
			$state->execute( $array );
		}
		catch(Exception $ex)
		{
			var_dump($ex);
			var_dump($i);
		}
	}

	public function select( PackageInfo $i )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `package` = :package AND `os` = :os AND `date` = :date";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=>$i->package, ':os'=>$i->os, ':date'=>$i->date->format("Y-m-d") );
		$state->execute( $array );

		if( $row = $state->fetch() )
		{
			$i->retrieve($row);
			return $i;
		}
		return null;
	}

	public function selectByDate( DateTime $date, $os )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `os` = :os AND `date` = :date";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':os'=>$os, ':date'=>$date->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() ) $rows[] = PackageInfo::parse($row);
		return $rows;
	}

	public function selectInTerm( PackageInfo $i, DateTime $from, DateTime $to )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `package` = :package AND `date` >= :from AND `date` <= :to ";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=>$i->package, ':from'=>$from->format("Y-m-d"), ':to'=>$to->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() ) $rows[] = PackageInfo::parse($row);
		return $rows;
	}

	public function selectByPublisher( PackageInfo $i, DateTime $date )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `publisher` = :publisher AND `os` = :os AND `date` = :date ORDER BY `date` ASC";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':publisher'=>$i->publisher, ':os'=>$i->os, ':date'=>$date->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() )
		{
			$rows[] = PackageInfo::parse($row);
		}
		return $rows;
	}

	public function sortByPublisherCount( $date, $os )
	{
$sql = <<< HEAR_PUB
	SELECT publisher, COUNT(*)
	FROM `ranking`
	WHERE date = :date AND os = :os
	GROUP BY publisher
	ORDER BY COUNT(*) DESC
HEAR_PUB;

		$state = $this->pdo->prepare( $sql );
		$array = array(':os'=>$os, ':date'=>$date->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() )
		{
			$rows[] = $row;
		}
		return $rows;
	}
}