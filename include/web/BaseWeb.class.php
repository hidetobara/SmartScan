<?php
require_once( SMARTY_DIR . 'Smarty.class.php' );
require_once( INCLUDE_DIR . 'Log.class.php' );


class BaseWeb
{
	protected $assigned;
	protected $templateGroup;
	protected $template;
	protected $redirectUrl;

	function __construct( $opt=null )
	{
		$this->assigned = array();

		$this->assignHash( array(
			'ENV_TYPE' => ENV_TYPE,
			'HOME_URL' => HOME_URL,
			'API_URL' => API_URL,
			'ROOT_DIR' => ROOT_DIR,
			'DATA_DIR' => DATA_DIR ) );

		$this->templateGroup = 'web';
		$this->template = 'error.tpl';
	}

	function setGroup( $group ){		$this->templateGroup = $group;		}
	function setTemplate( $tpl ){		$this->template = $tpl;		}

	function assign( $key, $item ){		$this->assigned[ $key ] = $item;		}
	function assignHash( $hash )
	{
		if( !is_array($hash) || count($hash)==0 ) return;
		foreach( $hash as $key => $item ) $this->assigned[ $key ] = $item;
	}

	function setRedirect( $url ){		$this->redirectUrl = $url;		}

	function run()
	{
		try
		{
			$this->initialize();
			$this->handle();
		}
		catch(Exception $ex)
		{
			$path = LOG_DIR . 'web/error_' . date('Ymd') . '.log';
			Log::factory( $path )
				->info( $ex->getMessage() . " @" . $ex->location . " : DUMP=" . json_encode($ex->array) );
			$this->assign( 'error', $ex->getMessage() );
		}
		$this->display();
		$this->finalize();
	}

	protected function getRequest()
	{
		return $this->safeStrip( $_REQUEST );
	}

	private function safeStrip( $array )
	{
		$out = array();
		foreach( $array as $key => $value )
		{
			if( is_string($value) ) $out[ $key ] = strip_tags( $value );
			else if( is_array($value) ) $out[ $key ] = $this->safeStrip( $value );
		}
		return $out;
	}

	protected function initialize()
	{
		//ページの出力は基本禁止、COOKIE は可
	}

	protected function handle()
	{
		//変数をセットする
	}

	protected function display()
	{
		if( $this->redirectUrl )
		{
			header( sprintf("Location: %s", $this->redirectUrl) );
			return;
		}

		$smarty = new Smarty();
		$smarty->template_dir = SMARTY_TEMPLATE_DIR;
		$smarty->compile_dir  = SMARTY_WORK_DIR . 'templates_c/';
		$smarty->cache_dir    = SMARTY_WORK_DIR . 'cache/';
		$smarty->plugins_dir = array( 'plugins/', SMARTY_TEMPLATE_DIR . 'plugins/' );
		$smarty->assign( $this->assigned );

		$path = SMARTY_TEMPLATE_DIR . $this->templateGroup . '/' . $this->template;
		if( file_exists($path) ) $smarty->display( $path );
	}

	protected function finalize()
	{
		//ログの書き出しなど
	}
}
?>