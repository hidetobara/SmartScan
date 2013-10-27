<?php

class Log
{
	private $path;

	static function factory( $path )
	{
		$dir = dirname($path);
		if( !is_dir($dir) ) mkdir( $dir, 0777, true );

		$instance = new self();
		$instance->path = $path;
		return $instance;
	}

	public function info( $message )
	{
		$dateStr = date("Y-m-d H:i:s") . "\t";
		file_put_contents( $this->path, $dateStr . $message, FILE_APPEND );
	}

	public function json( array $array )
	{
		$merged = array_merge( array('DATE'=>date("Y-m-d H:i:s")) , $array );
		file_put_contents( $this->path, json_encode($merged), FILE_APPEND );
	}
}

?>