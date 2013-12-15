<?php
/**
 * Base Controller
 * 
 * 
 * @author samson.zhou
 * @date 2013-08-28
 */
class WwwIndexController extends BaseController
{
	/**
	 * init
	 * 
	 */
	public function init()
	{
		parent::init();		
	}
	
	
	public function actionIndex()
	{
		//输出字符
		CUtilConsole::outputStr( '00000' );
		//输出一维数组
		$aryRow = array(
							'name'=>'zhouyang',
							'sex'=>'男',
							'age'=>29
						);
		CUtilConsole::outputRow( $aryRow );
		//输出二维数组
		$aryRowSets = array();
		$aryRowSets[0] = array( 'name'=>'zhouyang',	'sex'=>'男',	'age'=>29);
		$aryRowSets[1] = array( 'name'=>'zhouyang',	'sex'=>'女',	'age'=>29);
		$aryRowSets[2] = array( 'name'=>'zhouyang',	'sex'=>'女',	'age'=>29);
		
		CUtilConsole::outputRowsets( $aryRowSets );
		
		CUtilConsole::outputArray( $aryRowSets );
		
		CUtilConsole::vardumpVar( $aryRowSets );
		
		throw new CModelException( '404 error!' );
	}

	/**
	 * 统计勤学度
	 */
	public function actionMerge()
	{
		// 初始化缓存
		$cacheModel = UserLoginMergeCacheModel::model();

		// 总刷新用户数
		$countUser = 0;
		// 总刷新成功用户数
		$countSuccessUser = 0;

		// 获得用户最大ID，开始循环判断缓存
		$maxId = UserAccountModel::model()->getMaxUid();
		for ( $i = 1; $i <= $maxId; $i++ )
		{
			// 判断是否存在对应缓存
			if ( $cacheModel->logIsExists( $i ) == false )
				continue;

			$countUser ++;
			// 获得用户对应的合并过后的缓存数据
			$mergeCache = $cacheModel->calculateAll( $i );
			// 存储缓存数据
			$storeResult = UserLoginMergeModel::model()->storeCacheData( $i , $mergeCache );
			if ( $storeResult === true )
				$countSuccessUser ++;
		}

		CUtilConsole::outputArray( array( 'count'=>$countUser , 'success'=>$countSuccessUser ) );
	}

	/**
	 * 清理教师收藏记录，留下最新20条记录
	 */
	public function actionClearTeacherCollect()
	{
		$clearCount = UserFavoritesTeacherNewestModel::model()->clearHistory();
		CUtilConsole::outputStr( "Clear Count:{$clearCount}" );
	}

	/**
	 * 清理教程收藏记录，留下最新20条记录
	 */
	public function actionClearTutorialsCollect()
	{
		$clearCount = UserFavoritesTutorialsNewestModel::model()->clearHistory();
		CUtilConsole::outputStr( "Clear Count:{$clearCount}" );
	}

	/**
	 * 清理教程购买记录，留下最新20条记录
	 */
	public function actionClearTutorialsBuyRecord()
	{
		$clearCount = TutorialsBuyRecordModel::model()->clearHistory();
		CUtilConsole::outputStr( "Clear Count:{$clearCount}" );
	}

	/**
	 * 将老师统计数据刷入数据库
	 */
	public function actionMergeTeacherStatistics()
	{
		$aryResult = TeacherStatisticsModel::model()->storeCache();
		CUtilConsole::outputStr( "Merge Result:" );
		CUtilConsole::outputArray( $aryResult );
	}

	/**
	 * 将教程统计数据刷入数据库
	 */
	public function actionMergeTutorialsStatistics()
	{
		$aryResult = TutorialsStatisticsModel::model()->storeCache();
		CUtilConsole::outputStr( "Merge Result:" );
		CUtilConsole::outputArray( $aryResult );
	}
	
//end class	
}
