<?php
/**
 * 教师相关业务
 *
 * @author wengebin
 * @date 2013-11-09
 */
class TeacherModel extends CModel
{
	/**
	 * 初始化
	 *
	 */
	public function init()
	{
		parent::init();
		$this->setDb( CDbConnection::getWebDbConnection() );
	}
	
	/**
	 * 返回惟一实例
	 *
	 * @return TeacherModel
	 */
	public static function model()
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 表名
	 *
	 * @return TeacherModel
	 */
	public function tableName()
	{
		return 'qx_teacher';
	}

	/**
	 * 主键
	 *
	 * @return TeacherModel
	 */
	public function primaryKey()
	{
		return 'te_id';
	}

	/**
	 * 获得最大老师ID
	 */
	public function getMaxTeid()
	{
		$strSql = "SELECT MAX({$this->primaryKey()}) as maxid FROM {$this->tableName()}";
		$resultData = $this->findBySql( $strSql );
		return $resultData['maxid'];
	}

//end class
}
