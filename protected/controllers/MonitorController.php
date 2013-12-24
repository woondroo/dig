<?php
/**
 * Monitor Controller
 * 
 * @author wengebin
 * @date 2013-12-24
 */
class MonitorController extends BaseController
{
	private $_redis;

	/**
	 * init
	 */
	public function init()
	{
		parent::init();		
	}
	
	/**
	 * Index method
	 */
	public function actionIndex()
	{
		$this->replaceSeoTitle( 'BTC & LTC 监控中心' );

		$aryData = array();
		$this->render( 'index' , $aryData );
	}

	/**
	 * get redis connection
	 */
	public function getRedis()
	{
		if ( empty( $this->_redis ) )
			$this->_redis = new CRedis();

		return $this->_redis;
	}

//end class
}
