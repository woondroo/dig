<?php
/**
 * 勤学合并统计
 *
 * @author wengebin
 * @date 2013-11-06
 */
class UserLoginMergeModel extends CModel 
{
	/**数据库名*/
	private $_dbName = "user_login_merge_";
	/**表名*/
	private $_tableName = "user_login_merge_";
	
	/**
	 * 初始化
	 *
	 */
	public function init()
	{
		parent::init();
	}
	/**
	 * 主键
	 *
	 * @return unknown
	 */
	public function primaryKey()
	{
		return 'ulm_uid';
	}
	
	/**
	 * 重载并初始化
	 *
	 * @return UserLoginMergeModel
	 */
	public static function model( $_intUid = null )
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 返回表名
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "`{$this->_dbName}`.`{$this->_tableName}`";
	}
	
	/**
	 * 设置表名
	 *
	 * @return string | this
	 */
	public function setTableNameByUid( $_intUid = null , $_isReturnTableName = false )
	{
		$tmpUid = UtilWww::checkUid($_intUid);
		if(!$tmpUid)
			throw new CModelException('参数错误');

		//设置库名
		$strDbName = "user_login_merge_".$_intUid%10;
		if( $strDbName != $this->_dbName )
		{
			$this->_dbName = $strDbName;
			$this->setDb( CDbConnection::getAccountDbConnection() );
			$this->useDb( $strDbName );
		}

		//设置表名
		$strMd5Uid = UtilWww::md5FindNum($_intUid);
		$intNewUid = $strMd5Uid;
		$strTableName = "user_login_merge_".$intNewUid%100;
		$this->_tableName = $strTableName;

		return $_isReturnTableName ? $this->tableName() : $this;
	}

	/**
	 * 存储缓存数据
	 *
	 * @param int $_intUid 用户ID
	 * @param array $_aryData 缓存数据
	 * @return bool
	 */
	public function storeCacheData( $_intUid = 0 , $_aryData = array() )
	{
		$storeResult = false;
		$maxStamp = 0;

		if ( empty( $_aryData ) )
			return $storeResult;

		try
		{
			$this->setTableNameByUid( $_intUid );

			// 开启事务
			$transDb = array( 'account' );
			CDbConnection::startTransaction( $transDb );

			// 获得存储日期的最大时间戳
			foreach ( $_aryData as $data )
			{
				if ( $data['ulm_merge_date'] > $maxStamp )
					$maxStamp = $data['ulm_merge_date'];
			}

			// 存储获得的数据
			$storeResult = $this->storeLogForUser( $_aryData );
		} catch ( CModelException $e ) {
			$storeResult = false;
			CUtilConsole::outputStr( 'CModelException:'.$e->getMessage() );
		} catch ( Exception $e ) {
			$storeResult = false;
			CUtilConsole::outputStr( NBT_DEBUG ? $e->getMessage() : '系统错误' );
		}

		if ( $storeResult == false )
		{
			// 回滚事务
			CDbConnection::rollBackTransaction( $transDb );
		}
		else
		{
			// 提交事务
			CDbConnection::commitTransaction( $transDb );

			// 计算时间，因为合并时间都为前天凌晨时间，因此必须增加24小时
			$maxStamp += ( 24 * 3600 - 1 );
			UserLoginMergeCacheModel::model()->clearOldestLogCache( $_intUid , $maxStamp );
		}

		return $storeResult;
	}

	/**
	 * 存储勤学度数据
	 *
	 * @param array $_aryData 用户数据
	 * @return bool
	 */
	public function storeLogForUser( $_aryData )
	{
		$insertData = array();
		foreach ( $_aryData as $data )
		{
			$tmpStr = "(".implode( "," , $data ).")";
			$insertData[] = $tmpStr;
		}

		$strSql = "INSERT INTO {$this->tableName()} VALUES ".implode( "," , $insertData );
		return $this->executeSql( $strSql );
	}
	
//end class	
}
