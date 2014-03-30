<?php
require_once( '../configure.php' );
require_once( INCLUDE_DIR . 'file/PackageManager.class.php' );


$packager = PackageManager::factory();
$from = new DateTime("2013-12-01");
$to = new DateTime();
for( $date = clone($from); $date <= $to; $date->modify("+1 day") )
{
	var_dump($date);
	foreach( array(OS_IOS, OS_ANDROID) as $os )
	{
		$packager->clear();
		$packager->loadFromFile( $date, $os );
		$packager->save2db();
	}
}