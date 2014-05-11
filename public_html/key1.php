<?php
require_once( "../configure.php" );

require_once( INCLUDE_DIR . "web/BaseWeb.class.php" );
require_once( INCLUDE_DIR . "DB/DescriptionKeyTable.class.php" );


class KeyWeb extends BaseWeb
{
	private $os;
	private $date;

	function __construct( $opt=null )
	{
		parent::__construct( $opt );
		$this->template = 'key1.tpl';
	}

	function initialize()
	{
		$r = $this->getRequest();
		$this->os = strtolower( $r['os'] );
		$this->date = new DateTime( $r['date'] );

		if( !$this->os ) $this->os = OS_ANDROID;

		$this->assign( "date", $this->date->format("Y-m-d") );
		$this->assign( "os", $this->os );
	}

	function handle()
	{
		$rows = DescriptionKeyTable::Factory()->selectByDate( $this->date, $this->os );
		$words = array();
		$values = array();
		foreach( $rows as $row )
		{
			$words[] = $row['key'];
			$values[] = $row['count'];
		}

		$key_words = "['" . implode( "','", $words ) . "']";
		$key_values = "[" . implode( ",", $values ) . "]";
		$this->assign( "key_words", $key_words );
		$this->assign( "key_values", $key_values );
	}
}
$web = new KeyWeb();
$web->run();
?>