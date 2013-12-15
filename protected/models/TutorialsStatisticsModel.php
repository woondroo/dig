<?php
/**
 * 教程统计
 * 
 * @author wengebin
 * @date 2013-11-11
 */
class TutorialsStatisticsModel extends CModel
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
	 * @return TeacherStatisticsModel
	 */
	public static function model()
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 返回主键
	 *
	 * @return int
	 */
	public function primaryKey()
	{
		return 'ts_tuid';
	}
	
	/**
	 * 表名
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'qx_tutorials_statistics';
	}
	
	/**
	 * 存储缓存数据
	 *
	 * @return array
	 */
	public function storeCache()
	{
		// 初始化缓存
		$cacheModel = TutorialsStatisticsCacheModel::model();

		// 统计数据
		$result = array(
				'saveSuccess'=>0,
				'saveFail'=>0,
				'deleteSuccess'=>0,
				'deleteFail'=>0
			);

		// 获得教程最大ID，开始循环判断缓存
		$maxId = TutorialsModel::model()->getMaxTuid();
		$aryTuids = array();
		for ( $i = 1; $i <= $maxId; $i++ )
		{
			// 判断是否存在对应缓存
			if ( $cacheModel->statisticsIsExists( $i ) == false )
				continue;

			$aryTuids[] = $i;

			// 每100条统计数据更新一次
			if ( count( $aryTuids ) >= 100 || $i == $maxId )
			{
				// 批量获得统计数据
				$mergeCache = $cacheModel->getCache( $aryTuids );

				// 存储缓存数据
				$storeResult = $this->storeCacheData( $mergeCache );
				// 删除缓存数据
				$deleteResult = $cacheModel->deleteStatistics( $aryTuids );

				$result['saveSuccess'] += $storeResult['success'];
				$result['saveFail'] += $storeResult['fail'];
				$result['deleteSuccess'] += $deleteResult['success'];
				$result['deleteFail'] += $deleteResult['fail'];

				// 清空
				$aryTuids = array();
			}
		}

		return $result;
	}

	/**
	 * 保存数据到MYSQL
	 *
	 * @param array $_aryData 教程统计数据
	 * @return array
	 */
	public function storeCacheData( $_aryData = array() )
	{
		$aryResult = array( 'success'=>0 , 'fail'=>0 );

		if ( empty( $_aryData ) )
			return $aryResult;

		$insertData = array();
		foreach ( $_aryData as $teid=>$data )
		{
			$data = json_decode( $data , true );
			$tmpStr = "(".implode( "," , $data ).")";
			$insertData[] = $tmpStr;
		}

		$strSql = "INSERT INTO {$this->tableName()} VALUES ".implode( "," , $insertData )." ON DUPLICATE KEY UPDATE ts_num_view=VALUES(ts_num_view),ts_num_buy=VALUES(ts_num_buy),ts_num_comment=VALUES(ts_num_comment),ts_num_favorites=VALUES(ts_num_favorites)";
		$storeResult = $this->executeSql( $strSql );

		if ( $storeResult )
			$aryResult['success'] += count( $_aryData );
		else 
			$aryResult['fail'] += count( $_aryData );

		return $aryResult;
	}
	
//end class
}
