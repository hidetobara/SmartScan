<?php
require_once( INCLUDE_DIR . "DB/BaseTable.class.php" );
require_once( INCLUDE_DIR . "CommonInfo.class.php" );


class DescriptionTable extends BaseTable
{
	const DESCRIPTION_TABLE = "description";

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

	public function insert(PackageInfo $i)
	{
		$sql = "INSERT INTO " . self::DESCRIPTION_TABLE . " VALUES ( :package, :os, :text, :updated )";
		$sql .= " ON DUPLICATE KEY UPDATE `text`=:text, `updated`=:updated";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':package'=> $i->package, ':os'=>$i->os, ':text'=>$i->description, ':updated'=>$i->date->format("Y-m-d") );
		$state->execute( $array );
	}

	public function selectCount( DateTime $date, $os, $keyword )
	{
		$sql = "SELECT COUNT(*) FROM " . self::DESCRIPTION_TABLE . " WHERE `os`=:os AND `updated`=:updated AND `text` LIKE :keyword";
		$state = $this->pdo->prepare( $sql );
		$array = array( ':os'=>$os, ':keyword'=>"%{$keyword}%", ':updated'=>$date->format("Y-m-d") );
		$state->execute( $array );
		$row = $state->fetch();
		return $row[0];
	}
}