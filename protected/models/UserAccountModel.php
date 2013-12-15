<?php
/**
 * 用户帐户日志业务逻辑
 *
 * @author wengebin
 * @date 2013-11-07
 */
class UserAccountModel extends CModel 
{
	/**数据库名*/
	private $_dbName = "user_account";
	/**表名*/
	private $_tableName = "user_account";
	
	/**
	 * 初始化
	 *
	 */
	public function init()
	{
		parent::init();
		$this->setDb( CDbConnection::getAccountDbConnection() );
	}
	
	/**
	 * 重载并初始化
	 *
	 * @return UserAccountModel
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
	 * 主键
	 *
	 * @return unknown
	 */
	public function primaryKey()
	{
		return 'ua_id';
	}
	
	/**
	 * 获得最大用户ID
	 */
	public function getMaxUid()
	{
		$strSql = "SELECT MAX({$this->primaryKey()}) as maxid FROM {$this->tableName()}";
		$resultData = $this->findBySql( $strSql );
		return $resultData['maxid'];
	}
//end class
}
