<?php
class CDbConnection
{
	private static $_dbConnectionFTP = null;
	private static $_dbConnectionWeb = null;
	private static $_dbConnectionOrder = null;
	private static $_dbConnectionAccount = null;
	private static $_dbTransaction = array(
				'ftp'=>'getFTPDbConnection',
				'web'=>'getWebDbConnection',
				'order'=>'getOrderDbConnection',
				'account'=>'getAccountDbConnection',
				'comment'=>'getCommentDbConnection'
			);

	/**
	 * 获得FTP管理DB connection()
	 * @return CPdo 
	 */
	public static function getFTPDbConnection()
	{
		if( self::$_dbConnectionFTP === null )
		{
			$db = new CPdo(); 
			$db->setDsn( DB_FTP_DSN );
			$db->setUserName( DB_FTP_USERNAME );
			$db->setPassword( DB_FTP_PASSWORD );
			$db->setChargset( DB_FTP_CHARGSET );
			$db->connect();
			
			self::$_dbConnectionFTP = $db;
		}
		return self::$_dbConnectionFTP;
	}

	/**
	 * 获取Web Db connection
	 * @return CPdo
	 */
	public static function getWebDbConnection()
	{
		if( self::$_dbConnectionWeb === null )
		{
			$db = new CPdo(); 
			$db->setDsn( DB_WEB_DSN );
			$db->setUserName( DB_WEB_USERNAME );
			$db->setPassword( DB_WEB_PASSWORD );
			$db->setChargset( DB_WEB_CHARGSET );
			$db->connect();
			
			self::$_dbConnectionWeb = $db;
		}
		return self::$_dbConnectionWeb;
	}
	
	/**
	 * 获取订单DB connection()
	 * @return CPdo 
	 */
	public static function getOrderDbConnection()
	{
		if( self::$_dbConnectionOrder === null )
		{
			$db = new CPdo(); 
			$db->setDsn( DB_ORDER_DSN );
			$db->setUserName( DB_ORDER_USERNAME );
			$db->setPassword( DB_ORDER_PASSWORD );
			$db->setChargset( DB_ORDER_CHARGSET );
			$db->connect();
			
			self::$_dbConnectionOrder = $db;
		}
		return self::$_dbConnectionOrder;
	}
	
	/**
	 * 获得用户帐号信息数据库DB connection()
	 * @return CPdo 
	 */
	public static function getAccountDbConnection()
	{
		if( self::$_dbConnectionAccount === null )
		{
			$db = new CPdo(); 
			$db->setDsn( DB_ACCOUNT_DSN );
			$db->setUserName( DB_ACCOUNT_USERNAME );
			$db->setPassword( DB_ACCOUNT_PASSWORD );
			$db->setChargset( DB_ACCOUNT_CHARGSET );
			$db->connect();
			
			self::$_dbConnectionAccount = $db;
		}
		return self::$_dbConnectionAccount;
	}

	/**
	 * 获得评论数据库DB connection()
	 * @return CPdo
	 */
	public static function getCommentDbConnection()
	{
		if( self::$_dbConnectionAccount === null )
		{
			$db = new CPdo(); 
			$db->setDsn( DB_COMMENT_DSN );
			$db->setUserName( DB_COMMENT_USERNAME );
			$db->setPassword( DB_COMMENT_PASSWORD );
			$db->setChargset( DB_COMMENT_CHARGSET );
			$db->connect();
			
			self::$_dbConnectionAccount = $db;
		}
		return self::$_dbConnectionAccount;
	}

	/**
	 * 根据要求开启事务
	 *
	 * @params array $_aryTransDb 需要开启的事务集
	 * @return void
	 */
	public static function startTransaction( $_aryTransDb = array() )
	{
		foreach ( $_aryTransDb as $dbName )
		{
			if ( array_key_exists( $dbName , self::$_dbTransaction ) )
			{
				$method = self::$_dbTransaction[$dbName];
				self::$method()->beginTransaction();
			}
		}
	}

	/**
	 * 根据要求提交事务
	 *
	 * @params array $_aryTransDb 需要提交的事务集
	 * @return void
	 */
	public static function commitTransaction( $_aryTransDb = array() )
	{
		foreach ( $_aryTransDb as $dbName )
		{
			if ( array_key_exists( $dbName , self::$_dbTransaction ) )
			{
				$method = self::$_dbTransaction[$dbName];
				self::$method()->commit();
			}
		}
	}

	/**
	 * 根据要求回滚事务
	 *
	 * @params array $_aryTransDb 需要回滚的事务集
	 * @return void
	 */
	public static function rollBackTransaction( $_aryTransDb = array() )
	{
		foreach ( $_aryTransDb as $dbName )
		{
			if ( array_key_exists( $dbName , self::$_dbTransaction ) )
			{
				$method = self::$_dbTransaction[$dbName];
				self::$method()->rollBack();
			}
		}
	}
	
//end class
}
