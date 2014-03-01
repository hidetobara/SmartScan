<?php

class RankingTable
{
	const RANKING_TABLE = "ranking";

	private static $Instance = null;
	public static function Factory()
	{
		if( self::$Instance == null )
		{
			$instance = new self();
			$commands = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
			$instance->pdo = new PDO( sprintf("mysql:dbname=%s;host=%s", DB_NAME, DB_HOST), DB_USERNAME, DB_PASSWORD, $commands );
			$instance->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			self::$Instance = $instance;
		}
		return self::$Instance;
	}

	private $pdo;

	public function insert( PackageInfo $i )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `package` = :package AND `os` = :os AND `date` = :date";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=>$i->package, ':os'=>$i->os, ':date'=>$i->date->format("Y-m-d") );
		$state->execute( $array );
		$row = $state->fetch();
		if( $row ) return;

		$sql = "INSERT INTO " . self::RANKING_TABLE . " VALUES ( :package, :os, :date, :rank, :rating, :rating_count, :worst, :best, :title, :publisher, :image_url );";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=>$i->package, ':os'=>$i->os, ':date'=>$i->date->format("Y-m-d"),
				':rank'=>$i->rank, ':rating'=>(float)$i->rating, ':rating_count'=>$i->rating_count, ':worst'=>$i->rating_worst_count, ':best'=>$i->rating_best_count,
				':title'=>$i->title, ':publisher'=>$i->publisher, ':image_url'=>$i->image_url );
		$state->execute( $array );
	}

	public function selectByPackage( PackageInfo $i, $from, $to )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `package` = :package AND `os` = :os AND `date` >= :from AND `date` <= :to";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=>$i->package, ':os'=>$i->os, ':from'=>$from->format("Y-m-d"), ':to'=>$to->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() )
		{
			$row[] = PackageInfo::parse($row);
		}
		return $rows;
	}

	public function selectByPublisher( PackageInfo $i, $date )
	{
		$sql = "SELECT * FROM " . self::RANKING_TABLE . " WHERE `publisher` = :publisher AND `os` = :os AND `date` = :date";
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
		};
		return $rows;
	}
}