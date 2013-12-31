<?php
/**
 * Port Controller
 * 
 * @author wengebin
 * @date 2013-12-31
 */
class PortController extends BaseController
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
		exit();
	}

	/**
	 * Check method
	 */
	public function actionCheck()
	{
		// get target check address
		$strTarAdd = isset( $_REQUEST['tar'] ) ? htmlspecialchars( $_REQUEST['tar'] ) : '';

		// get local address
		$strLocalAdd = $_SERVER['SERVER_ADDR'];

		$isok = 0;
		$data = array();
		$msg = "";

		try
		{
			if ( empty( $strTarAdd ) || $strTarAdd !== $strLocalAdd )
			{
				$data = '500';
				throw new CModelException( '我不是你要找的矿机！' );
			}
			else
			{
				$data = '200';
				$msg = '我就是矿机！';
			}
			$isok = 1;
		}
		catch ( CModelException $e )
		{
			$msg = $e->getMessage();
		}
		catch ( CException $e )
		{
			$msg = NBT_DEBUG ? $e->getMessage() : '系统错误';
		}

		header('Content-Type: text/html; charset=utf-8');
		echo $this->encodeAjaxData( $isok , $data , $msg );
		exit();
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
