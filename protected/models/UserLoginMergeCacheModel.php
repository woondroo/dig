<?php
/**
 * 勤学度存储
 * 
 * @author wengebin
 * @date 2013-11-06
 */
class UserLoginMergeCacheModel extends CModel
{
	/**
	 * 缓存操作对象
	 * 
	 * @var CRedisList
	 */
	private $_cache = null;
	
	/**
	 * 缓存的命名规则
	 * 
	 * @var string
	 */
	private $_cacheName = 'user_login_log_';

	/**
	 * 初始化
	 */
	public function init()
	{
		parent::init();

		// 初始化 Redis 类
		$this->_cache = new CRedisList();
	}
	
	/**
	 * 返回惟一实例
	 *
	 * @return NewsModel
	 */
	public static function model()
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 获得缓存对象
	 *
	 * @return CRedisList
	 */
	public function getCacheConnection()
	{
		// 如果缓存对象为空
		if ( empty( $this->_cache ) )
			$this->_cache = new CRedisList();

		return $this->_cache;
	}

	/**
	 * 获得记录缓存的名称
	 *
	 * @params int $_intUid 用户ID
	 * @return string
	 */
	public function getCacheName( $_intUid = 0 )
	{
		return $this->_cacheName.$_intUid;
	}

	/**
	 * 计算用户的缓存数据
	 *
	 * @params int $_intUid 用户ID
	 * @return array
	 */
	public function calculateAll( $_intUid = 0 )
	{
		if ( empty( $_intUid ) )
			return false;

		$aryData = array();
		$intIndex = 0;
		$cacheData = $this->getOldestLogCache( $_intUid , $intIndex );
		while( !empty( $cacheData ) )
		{
			$cacheDay = strtotime( date( 'Y-m-d' , $cacheData['create']) );

			// 初始状态
			$cacheLog = array(
					'ulm_uid'=>$_intUid,
					'ulm_login_count'=>0,
					'ulm_visit_count'=>0,
					'ulm_online_time'=>0,
					'ulm_merge_date'=>$cacheDay
				);

			// 如果数组中已存在统计日期相关数据，则获得继续叠加
			if ( array_key_exists( $cacheDay , $aryData ) )
				$cacheLog = $aryData[$cacheDay];

			$cacheLog['ulm_login_count'] += 1;
			$cacheLog['ulm_visit_count'] += $cacheData['count'];
			$cacheLog['ulm_online_time'] += $cacheData['counttime'];

			$aryData[$cacheDay] = $cacheLog;

			$intIndex ++;
			$cacheData = $this->getOldestLogCache( $_intUid , $intIndex );
		}

		return $aryData;
	}

	/**
	 * 获得最老记录
	 *
	 * @param int $_intUid 用户ID
	 * @param int $_intIndex 获取对应位置的数据
	 * @return array
	 */
	public function getOldestLogCache( $_intUid = 0 , $_intIndex = 0 )
	{
		// 获得旧的缓存数据
		$data = $this->getCacheConnection()->get( $this->getCacheName( $_intUid ) , CRedisList::REDIS_LIST_FRONT , $_intIndex );
		$aryData = json_decode( $data , true );

		// 判断缓存数据是否需要被统计，如果时间超过今天0点，则不纳入统计，会在明天统计
		$today = strtotime( date('Y-m-d') );
		if ( $aryData['create'] >= $today )
		{
			$aryData = array();
		}
		
		return $aryData;
	}

	/**
	 * 清除老记录
	 *
	 * @params int $_intUid 用户ID
	 * @params int $_intTimestamp 时间戳
	 * @return void
	 */
	public function clearOldestLogCache( $_intUid = 0 , $_intTimestamp = 0 )
	{
		// 获得旧的缓存数据
		$data = $this->getCacheConnection()->get( $this->getCacheName( $_intUid ) , CRedisList::REDIS_LIST_FRONT );
		$aryData = json_decode( $data , true );

		// 判断缓存数据是否需要被清除，如果记录时间在清除范围内，则删除数据
		if ( $aryData['create'] <= $_intTimestamp )
			$returnData = $this->getCacheConnection()->pop( $this->getCacheName( $_intUid ) );
		
		if ( !empty( $returnData ) )
			$this->clearOldestLogCache( $_intUid , $_intTimestamp );
	}

	/**
	 * 判断key是否存在
	 *
	 * @params int $_intUid 用户ID
	 * @return bool
	 */
	public function logIsExists( $_intUid = 0 )
	{
		return $this->getCacheConnection()->keyExists( $this->getCacheName( $_intUid ) );
	}

//end class
}
