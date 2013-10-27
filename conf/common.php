<?php
if( ENV_TYPE == 'RELEASE' ){
	define( 'ROOT_DIR', '/home/baraoto/SmartScan/' );
	define( 'SMARTY_DIR', '/home/baraoto/Smarty-3.1.12/libs/' );

}else if( ENV_TYPE == 'DEVELOP' ){
	define( 'HOME_URL', 'http://127.0.0.1/SmartScan/public_html/' );
	define( 'API_URL', 'http://127.0.0.1/SmartScan/public_api/' );
	define( 'ROOT_DIR', 'C:/develop/xampp/htdocs/SmartScan/' );
	define( 'SMARTY_DIR', 'C:/develop/Smarty-3.1.12/libs/' );


}else{
	print( "Check ENV_TYPE !" );
	exit;
}

define( 'OS_ANDROID', 'android' );
define( 'OS_IOS', 'ios' );

define( 'CONF_DIR', ROOT_DIR . 'conf/' );
define( 'INCLUDE_DIR', ROOT_DIR . 'include/' );
define( 'LOG_DIR', ROOT_DIR . 'log/' );
define( 'DATA_DIR', ROOT_DIR . 'data/' );
define( 'TMP_DIR', ROOT_DIR . 'tmp/' );
define( 'SMARTY_WORK_DIR', ROOT_DIR . 'smarty_work/' );
define( 'SMARTY_TEMPLATE_DIR', ROOT_DIR . 'smarty/' );

mb_regex_encoding( 'UTF-8' );
ini_set( 'memory_limit', '32M' );
?>