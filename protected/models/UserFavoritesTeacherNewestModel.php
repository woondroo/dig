<?php
/**
 * 老师最新收藏
 *
 * @author wengebin
 * @data 2013-11-07
 */
class UserFavoritesTeacherNewestModel extends CModel 
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
	 * 重载并初始化
	 *
	 * @return UserFavoritesTeacherNewestModel
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
		return "qx_teacher_favorites";
	}

	/**
	 * 清除最新20条以外的历史记录
	 *
	 * @return bool
	 */
	public function clearHistory()
	{
		$strSql = "set @rn=0,@prev_teid=0;";
		$this->executeSql( $strSql );

		$strSql = "DELETE tf.* FROM ".$this->tableName()." AS tf 
			INNER JOIN(
				SELECT T2.* FROM(
					SELECT T1.*,
					@rn:=CASE WHEN @prev_teid=tf_teid THEN @rn+1 ELSE 1 END AS rn,
					@prev_teid:=tf_teid 
					FROM 
					(
						SELECT tf_teid,tf_uid,tf_time FROM ".$this->tableName()." ORDER BY tf_teid ASC,tf_time DESC
					) AS T1
					) AS T2 WHERE rn > 20
			) AS tf2 ON tf.tf_teid=tf2.tf_teid and tf.tf_uid=tf2.tf_uid";

		$resultData = $this->executeSql( $strSql );
		return $resultData;
	}
	
//end class	
}
