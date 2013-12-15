<?php
/**
 * 教程购买记录
 * 
 * @author wengebin
 * @date 2013-11-08
 */
class TutorialsBuyRecordModel extends CModel
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
	 * @return TutorialsBuyRecordModel
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
		return 'qx_tutorials_buy_record';
	}
	
	/**
	 * 清除最新20条以外的历史记录
	 *
	 * @return bool
	 */
	public function clearHistory()
	{
		$strSql = "set @rn=0,@prev_tuid=0;";
		$this->executeSql( $strSql );

		$strSql = "DELETE tu.* FROM ".$this->tableName()." AS tu 
			INNER JOIN(
				SELECT T2.* FROM(
					SELECT T1.*,
					@rn:=CASE WHEN @prev_tuid=tu_tuid THEN @rn+1 ELSE 1 END AS rn,
					@prev_tuid:=tu_tuid 
					FROM 
					(
						SELECT tu_tuid,tu_uid,tu_time FROM ".$this->tableName()." ORDER BY tu_tuid ASC,tu_time DESC
					) AS T1
					) AS T2 WHERE rn > 20
			) AS tu2 ON tu.tu_tuid=tu2.tu_tuid and tu.tu_uid=tu2.tu_uid";

		$resultData = $this->executeSql( $strSql );
		return $resultData;
	}
	
//end class
}
