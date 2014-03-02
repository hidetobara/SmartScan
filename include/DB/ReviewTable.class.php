<?php
require_once( INCLUDE_DIR . "DB/BaseTable.class.php" );

class ReviewTable extends BaseTable
{
	const REVIEW_TABLE = "review";

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

	public function insert( DateTime $date, $os, $packages )
	{
		$table = self::REVIEW_TABLE;
		$sql = <<< HEAR_INSERT
INSERT INTO $table
VALUES ( :date, :os, :package, :point )
HEAR_INSERT;
		$state = $this->pdo->prepare( $sql );
		foreach( $packages as $p )
		{
			$array = array(':date'=>$date->format("Y-m-d"), ':os'=>$os, ':package'=>$p->package, ':point'=>$p->point );
			$state->execute( $array );
		}
	}

	public function select( DateTime $date, $os )
	{
		$table = self::REVIEW_TABLE;
		$sql = <<< HEAR_DATE
SELECT `package`,`point` FROM $table
WHERE date = :date AND os = :os
ORDER BY `point` DESC
HEAR_DATE;
		$state = $this->pdo->prepare( $sql );
		$array = array( ':date'=>$date->format('Y-m-d'), ':os'=>$os );
		$state->execute( $array );

		$list = array();
		while( $row = $state->fetch() )
		{
			$i = PackageInfo::parse($row);
			$i->os = $os;
			$i->date = clone($date);

			$list[] = $i;
		}
		return $list;
	}
}