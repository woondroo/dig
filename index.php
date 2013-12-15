<?php
//error_reporting( E_ALL );
error_reporting(E_ALL ^ E_NOTICE);
//check config.php
if( !file_exists( dirname(__FILE__).'/protected/config/define.php' ) )
{
	exit( 'confine/define.php is not existes.' );
}
require_once( dirname(__FILE__).'/protected/config/define.php' );
// change the following paths if necessary
$nbt=dirname(__FILE__).'/protected/framework/Nbt.php';
$config=dirname(__FILE__).'/protected/config/main.php';
defined('NBT_DEBUG') or define('NBT_DEBUG',true);
//defined('TEST_MODE') or define('TEST_MODE',true);
require_once($nbt);
Nbt::createConsoleApplication($config)->run();
