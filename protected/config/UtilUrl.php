<?php
class UtilUrl
{
	/**
	 * 配置需要进行地址重写的路油
	 *
	 * @param string $_route 路游
	 * @return string
	 */
	public static function configRouteReWrite( $_route = null )
	{
		$aryConfigRouteReWrite = array(
										'test/index',
										'test/list',
									);
		return in_array( $_route , $aryConfigRouteReWrite ) ? true : false;
	}
	
	/**
	 * 创建静态url地址
	 *
	 * @param string $_route
	 * @param array $_aryParams
	 * @return string,创建失败，则返回空''
	 */
	public static function createStaticUrl( $_route = null , $_aryParams = array() )
	{
		if( self::configRouteReWrite( $_route ) === false )
			return '';
		$_route = split('/',$_route);
	
		if( count($_route) !== 2 )
			return '';
		$funcName = "url".ucfirst(strtolower($_route[0])).ucfirst(strtolower($_route[1]));
		return self::$funcName( $_aryParams );
	}
	
	/**
	 * 创建资讯首页地址
	 * 
	 * @param array $_aryParams
	 * @return string
	 */
	public static function urlTestIndex( $_aryParams = array() )
	{
		return '/html/test.html';
	}
	
	/**
	 * 创建资讯列表页
	 * 
	 * @param array $_aryParams
	 * @return string
	 */
	public static function urlTestList( $_aryParams = array() )
	{
		return "/html/test/test-{$_aryParams['page']}.html";
	}
	
//end class
}