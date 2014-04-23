<?php
require_once( INCLUDE_DIR . "DB/BaseTable.class.php" );

class RankingShiftTable extends BaseTable
{
	const REVIEW_TABLE = "ranking_shift";

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

	public function insert( PackageInfo $i, $shift )
	{
		$table = self::REVIEW_TABLE;
		$sql = "INSERT INTO $table VALUES ( :date, :os, :package, :shift )";
		$sql .= " ON DUPLICATE KEY UPDATE `shift`=:shift";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':date'=>$i->date->format('Y-m-d'), ':os'=>$i->os, ':package'=>$i->package, ':shift'=>$shift );
		$state->execute( $array );
	}

	public function selectByDate( DateTime $date, $os )
	{
		$table = self::REVIEW_TABLE;
		$sql = "SELECT * FROM $table WHERE `date` = :date AND `os` = :os ORDER BY `shift` ASC";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':date'=>$date->format('Y-m-d'), ':os'=>$os );
		$state->execute( $array );

		$list = array();
		while( $row = $state->fetch() )
		{
			$i = PackageInfo::parse($row);
			$i->os = $os;
			$i->date = clone($date);
			$i->point = $row['shift'];	// 差分を入れる

			$list[] = $i;
		}
		return $list;
	}
}
