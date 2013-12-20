<?php
class UtilUrl
{
	// 配置
	private static $_aryConfigRouteReWrite = array(
						''=>'index/index',
						'logout'=>'login/logout'
					);

	/**
	 * 获得配置
	 */
	public static function getConfig()
	{
		return self::$_aryConfigRouteReWrite;
	}

//end class
}
