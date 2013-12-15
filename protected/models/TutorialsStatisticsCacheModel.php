<?php
/**
 * 统计数据缓存
 *
 * @author wengebin
 * @date 2013-11-11
 */
class TutorialsStatisticsCacheModel extends CModel 
{
	/**
	 * 缓存操作对象
	 * 
	 * @var CRedisHash
	 */
	private $_cache = null;
	
	/**
	 * 缓存的命名规则
	 * 
	 * @var string
	 */
	private $_cacheName = 'tutorials_statistics';

	/**
	 * 初始化
	 */
	public function init()
	{
		parent::init();

		// 初始化 Redis 类
		$this->_cache = new CRedisHash();
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
	 * @return CRedisHash
	 */
	public function getCacheConnection()
	{
		// 如果缓存对象为空
		if ( empty( $this->_cache ) )
			$this->_cache = new CRedisHash();

		return $this->_cache;
	}

	/**
	 * 获得记录缓存的名称
	 *
	 * @return string
	 */
	public function getCacheName()
	{
		return $this->_cacheName;
	}

	/**
	 * 设置记录缓存的名称
	 *
	 * @param string $_strCacheName 缓存名
	 * @return void
	 */
	public function setCacheName( $_strCacheName = 'tutorials_statistics' )
	{
		$this->_cacheName = $_strCacheName;
	}

	/**
	 * 获得教程的缓存记录数据
	 *
	 * @params array $_aryTuids 老师ID集合，也可是单一ID
	 * @return array
	 */
	public function getCache( $_aryTuids = array() )
	{
		// 获得缓存数据
		if ( count( $_aryTuids ) > 1 )
			$aryData = $this->getCacheConnection()->getMap( $this->getCacheName() , $_aryTuids );		
		else
		{
			reset( $_aryTuids );
			$intTuid = current( $_aryTuids );
			$aryData = $this->getCacheConnection()->get( $this->getCacheName() , $intTuid );
			$aryData = array( $intTuid=>$aryData );
		}

		return $aryData;
	}

	/**
	 * 判断hash field是否存在
	 *
	 * @params int $_intTuid 教程ID
	 * @return bool
	 */
	public function statisticsIsExists( $_intTuid = 0 )
	{
		return $this->getCacheConnection()->fieldExists( $this->getCacheName() , $_intTuid );
	}

	/**
	 * 删除hash field缓存
	 *
	 * @params int $_intTuids 老师ID集
	 * @return array
	 */
	public function deleteStatistics( $_intTuids = array() )
	{
		$aryResult = array( 'success'=>0 , 'fail'=>0 );

		if ( empty( $_intTuids ) )
			return $aryResult;
		
		// 删除所有指定的field
		foreach ( $_intTuids as $teid )
		{
			$delResult = $this->getCacheConnection()->remove( $this->getCacheName() , $teid );
			if ( $delResult > 0 )
				$aryResult['success'] += 1;
			else
				$aryResult['fail'] += 1;
		}
		

		return $aryResult;
	}

//end class
}
