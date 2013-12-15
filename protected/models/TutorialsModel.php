<?php
/**
 * 教程相关业务
 *
 * @author wengebin
 * @date 2013-11-11 
 */
class TutorialsModel extends CModel
{
	/**
	 * 初始化
	 */
	public function init()
	{
		parent::init();
		$this->setDb( CDbConnection::getWebDbConnection() );
	}
	
	/**
	 * 返回惟一实例
	 *
	 * @return TutorialsModel
	 */
	public static function model()
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 表名
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'qx_tutorials';
	}
	
	/**
	 * 主键
	 *
	 * @return unknown
	 */
	public function primaryKey()
	{
		return 'tu_id';
	}

	/**
	 * 获得最大教程ID
	 */
	public function getMaxTuid()
	{
		$strSql = "SELECT MAX({$this->primaryKey()}) as maxid FROM {$this->tableName()}";
		$resultData = $this->findBySql( $strSql );
		return $resultData['maxid'];
	}
	
//end class
}
