<?php
require_once( INCLUDE_DIR . "DB/BaseTable.class.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class DescriptionKeyTable extends BaseTable
{
	const DESCRIPTION_TABLE = "description_key";

	private static $Instance = null;

	public static function Factory()
	{
		if (self::$Instance == null) {
			$instance = new self();
			$instance->pdo = self::makePdo();
			self::$Instance = $instance;
		}
		return self::$Instance;
	}

	public function insert( DateTime $date, $os, $key, $count )
	{
		$sql = "INSERT INTO " . self::DESCRIPTION_TABLE . " VALUES ( :date, :os, :key, :count )";
		$sql .= " ON DUPLICATE KEY UPDATE `count`=:count";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':date'=>$date->format("Y-m-d"), ':os'=>$os, ':key'=>$key, ':count'=>$count );
		$state->execute( $array );

	}

	public function selectByKey( $key, $os, DateTime $from, DateTime $to )
	{
		$sql = "SELECT * FROM " . self::DESCRIPTION_TABLE . " WHERE `key`=:key AND `os`=:os AND `date`>=:from AND `date`<:to";
		$state = $this->pdo->prepare( $sql );
		$array = array( 'key'=>$key, ':os'=>$os, ':from'=>$from->format("Y-m-d"), ':to'=>$to->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() )
		{
			$rows [] = $row;
		}
		return $rows;
	}

	public function selectByDate( DateTime $date )
	{
		$sql = "SELECT * FROM " . self::DESCRIPTION_TABLE . " WHERE `date`=:date";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':date'=>$date->format("Y-m-d") );
		$state->execute( $array );

		$rows = array();
		while( $row = $state->fetch() )
		{
			$rows [] = $row;
		}
		return $rows;
	}
}