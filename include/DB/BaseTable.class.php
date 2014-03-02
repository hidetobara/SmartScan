<?php

class BaseTable
{
    protected static function makePdo()
    {
		$commands = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
		$pdo = new PDO( sprintf("mysql:dbname=%s;host=%s", DB_NAME, DB_HOST), DB_USERNAME, DB_PASSWORD, $commands );
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $pdo;
    }

    protected $pdo;
}