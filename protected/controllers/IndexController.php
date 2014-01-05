<?php
/**
 * Index Controller
 * 
 * @author wengebin
 * @date 2013-12-15
 */
class IndexController extends BaseController
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
		try
		{
			$this->replaceSeoTitle( 'BTC & LTC 挖矿设置' );

			// open redis
			$redis = $this->getRedis();

			// Tip data
			$aryTipData = array();
			$aryBTCData = array();
			$aryLTCData = array();

			$btcVal = $redis->readByKey( 'btc.setting' );
			$ltcVal = $redis->readByKey( 'ltc.setting' );
			$aryBTCData = empty( $btcVal ) ? array() : json_decode( $btcVal , true );
			$aryLTCData = empty( $ltcVal ) ? array() : json_decode( $ltcVal , true );
			
			// if commit save
			if ( Nbt::app()->request->isPostRequest )
			{
				$strBTCAddress = isset( $_POST['address_btc'] ) ? htmlspecialchars( $_POST['address_btc'] ) : '';
				$strBTCAccount = isset( $_POST['account_btc'] ) ? htmlspecialchars( $_POST['account_btc'] ) : '';
				$strBTCPassword = isset( $_POST['password_btc'] ) ? htmlspecialchars( $_POST['password_btc'] ) : '';

				$strLTCAddress = isset( $_POST['address_ltc'] ) ? htmlspecialchars( $_POST['address_ltc'] ) : '';
				$strLTCAccount = isset( $_POST['account_ltc'] ) ? htmlspecialchars( $_POST['account_ltc'] ) : '';
				$strLTCPassword = isset( $_POST['password_ltc'] ) ? htmlspecialchars( $_POST['password_ltc'] ) : '';

				$aryBTCData['ad'] = $strBTCAddress;
				$aryBTCData['ac'] = $strBTCAccount;
				$aryBTCData['pw'] = $strBTCPassword;
				$aryBTCData['su'] = isset( $aryBTCData['su'] ) ? $aryBTCData['su'] : 0;

				$aryLTCData['ad'] = $strLTCAddress;
				$aryLTCData['ac'] = $strLTCAccount;
				$aryLTCData['pw'] = $strLTCPassword;
				$aryLTCData['su'] = isset( $aryLTCData['su'] ) ? $aryLTCData['su'] : 0;

				// store data
				$redis->writeByKey( 'btc.setting' , json_encode( $aryBTCData ) );
				$redis->writeByKey( 'ltc.setting' , json_encode( $aryLTCData ) );
				$redis->saveData();
				
				$aryTipData['status'] = 'success';
				$aryTipData['text'] = '保存成功!';
			}
		} catch ( Exception $e ) {
			$aryTipData['status'] = 'error';
			$aryTipData['text'] = '保存失败!';
		}

		$aryData = array();
		$aryData['tip'] = $aryTipData;
		$aryData['btc'] = $aryBTCData;
		$aryData['ltc'] = $aryLTCData;
		$this->render( 'index' , $aryData );
	}

	/**
	 * super mode
	 */
	public function actionMode()
	{
		// is super mode
		$intIsSuper = isset( $_GET['s'] ) ? intval( $_GET['s'] ) : 0;

		$redis = $this->getRedis();
		$btcVal = $redis->readByKey( 'btc.setting' );
		$ltcVal = $redis->readByKey( 'ltc.setting' );
		$aryBTCData = empty( $btcVal ) ? array() : json_decode( $btcVal , true );
		$aryLTCData = empty( $ltcVal ) ? array() : json_decode( $ltcVal , true );

		if ( $intIsSuper === 1 )
		{
			$aryBTCData['su'] = 1;
			$aryLTCData['su'] = 1;
		}
		else
		{
			$aryBTCData['su'] = 0;
			$aryLTCData['su'] = 0;
		}

		// store data
		$redis->writeByKey( 'btc.setting' , json_encode( $aryBTCData ) );
		$redis->writeByKey( 'ltc.setting' , json_encode( $aryLTCData ) );

		$this->actionRestart( true );
		echo '200';exit;
	}

	/**
	 * restart program
	 */
	public function actionRestart( $_boolIsNoExist = false )
	{
		$this->actionShutdown( true );

		ini_set('max_execution_time', '0');

		$redis = $this->getRedis();
		$usbVal = $redis->readByKey( 'usb.status' );
		if ( empty( $usbVal ) )
		{
			if ( $_boolIsNoExist === false )
			{
				echo '500';exit;
			}
			else return false;
		}

		$usbData = json_decode( $usbVal , true );
		if ( empty( $usbData ) )
		{
			if ( $_boolIsNoExist === false )
			{
				echo '200';exit;
			}
			else return true;
		}
		
		$aryBTCUsb = array();
		$aryLTCUsb = array();
		foreach ( $usbData as $usb=>$model )
		{
			if ( in_array( $model , array( 'btc' , 'ltc' ) ) )
			{
				if ( $model == 'btc' )
					$aryBTCUsb[$usb] = $model;
				else if ( $model == 'ltc' )
					$aryLTCUsb[$usb] = $model;
			}
		}

		foreach ( $aryBTCUsb as $usb=>$model )
		{
			$this->restartByUsb( $usb , $model );
		}

		// if btc machine has restart
		if ( count( $aryBTCUsb ) > 0 ) sleep( 3 );

		foreach ( $aryLTCUsb as $usb=>$model )
		{
			$this->restartByUsb( $usb , $model );
		}

		if ( $_boolIsNoExist === false )
		{
			echo '200';exit;
		}
		else return true;
	}

	/**
	 * restart program by usb
	 */
	public function restartByUsb( $_strUsb = '' , $_strUsbModel = '' , $_strSingleShutDown = '' )
	{
		if ( !empty( $_strSingleShutDown ) )
			$this->actionShutdown( true , $_strSingleShutDown );

		if ( empty( $_strUsb ) || empty( $_strUsbModel ) )
			return false;

		$startUsb = $_strUsb;
		$startModel = $_strUsbModel;
	
		$redis = $this->getRedis();
		$setVal = $redis->readByKey( "{$startModel}.setting" );
		$aryData = empty( $setVal ) ? array() : json_decode( $setVal , true );
		if ( empty( $aryData ) )
			return false;

		if ( $startModel == 'btc' )
			$command = "sudo nohup /home/wwwroot/dig/soft/cgminer -o {$aryData['ad']} -u {$aryData['ac']} -p {$aryData['pw']} -S {$startUsb} >/dev/null 2>&1 &";
		else if ( $startModel == 'ltc' )
			$command = "sudo nohup /home/wwwroot/dig/soft/minerd --freq=".($aryData['su'] == 0 ? '600' : '900')." --gc3355={$startUsb} --url={$aryData['ad']} --userpass={$aryData['ac']}:{$aryData['pw']} -2 >/dev/null 2>&1 &";

		exec( $command );
		return true;
	}

	/**
	 * shutdown program
	 */
	public function actionShutdown( $_boolIsNoExist = false , $_strSingleShutDown = '' )
	{
		exec( 'sudo ps x|grep miner' , $output );

		$pids = array();
		$inglePids = array();
		foreach ( $output as $r )
		{
			preg_match( '/\s*(\d+)\s*.*/' , $r , $match );
			if ( !empty( $match[1] ) ) $pids[] = $match[1];
			
			if ( !empty( $_strSingleShutDown ) )
			{
				preg_match( '/.*-S\s(.+).*/' , $r , $match_usb_btc );
				preg_match( '/.*--gc3355=(.+)\s--url=.*/' , $r , $match_usb_ltc );
				if ( in_array( $_strSingleShutDown , array( $match_usb_btc[1] , $match_usb_ltc[1] ) ) ) $inglePids[] = $match[1];
			}
		}

		if ( !empty( $_strSingleShutDown ) )
			exec( 'sudo kill -9 '.implode( ' ' , $inglePids ) );
		else if ( !empty( $pids ) )
			exec( 'sudo kill -9 '.implode( ' ' , $pids ) );
		
		if ( $_boolIsNoExist === false )
		{
			echo '200';exit;
		}
		else return true;
	}

	/**
	 * check state
	 */
	public function actionCheck( $_boolIsNoExist = false )
	{
		exec( 'sudo ps x|grep miner' , $output );

		$alived = array();
		$died = array();

		$redis = $this->getRedis();
		$usbVal = $redis->readByKey( 'usb.status' );

		if ( !empty( $usbVal ) )
			$usbData = json_decode( $usbVal , true );
		else
			$usbData = array();

		foreach ( $output as $r )
		{
			//preg_match( '/.*-u\s(.+)\s-p.*/' , $r , $match_user_btc );
			//preg_match( '/.*--userpass=(.*)\:.*/' , $r , $match_user_ltc );

			preg_match( '/.*(cgminer).*/' , $r , $match_btc );
			preg_match( '/.*(minerd).*/' , $r , $match_ltc );

			//preg_match( '/.*-o\s(.+)\s-u.*/' , $r , $match_url_btc );
			//preg_match( '/.*--url=(.*)\s--userpass.*/' , $r , $match_url_ltc );

			preg_match( '/.*-S\s(.+).*/' , $r , $match_usb_btc );
			preg_match( '/.*--gc3355=(.+)\s--url=.*/' , $r , $match_usb_ltc );

			if ( !array_key_exists( $match_usb_btc[1] , $usbData ) && !array_key_exists( $match_usb_ltc[1] , $usbData ) )
			{
				$match_usb = !empty( $match_usb_btc[1] ) ? $match_usb_btc[1] : $match_usb_ltc[1];
				$this->actionShutdown( true , $match_usb );
				continue;
			}

			if ( !empty( $match_btc[1] ) && !array_key_exists( $match_usb_btc[1] , $alived ) )
				$alived[$match_usb_btc[1]] = 'btc';

			if ( !empty( $match_ltc[1] ) && !array_key_exists( $match_usb_ltc[1] , $alived ) )
				$alived[$match_usb_ltc[1]] = 'ltc';
		}

		ksort( $alived );

		foreach ( $usbData as $usb=>$model )
		{
			if ( !array_key_exists( $usb , $alived ) )
				$died[$usb] = $model;
		}

		ksort( $died );

		$aryData = array();
		$aryData['alived'] = $alived;
		$aryData['died'] = $died;
		$aryData['super'] = $this->getSuperModelState();

		if ( $_boolIsNoExist === false )
		{
			echo json_encode( $aryData );exit;
		}
		else 
			return $aryData;
	}

	/**
	 * check run state
	 */
	public function actionCheckrun()
	{
		// check data
		$aryData = $this->actionCheck( true );
		
		if ( count( $aryData['alived'] ) === 0 && count( $aryData['died'] ) > 0 )
			echo $this->actionRestart( true ) === true ? 1 : -1;
		else
			echo 0;
		exit;
	}

	/**
	 * check usb state
	 */
	public function actionUsbstate()
	{
		$redis = $this->getRedis();
		$usbVal = $redis->readByKey( 'usb.status' );

		$usbData = array();
		if ( !empty( $usbVal ) )
			$usbData = json_decode( $usbVal , true );

		exec( 'sudo ls /dev/*USB*' , $output );

		$waitSetUsb = array();
		foreach ( $usbData as $uk=>$u )
		{
			if ( !in_array( $uk , $output ) )
				unset( $usbData[$uk] );
			else if ( $u == -1 )
				$waitSetUsb[] = $uk;
		}

		foreach ( $output as $r )
		{
			if ( !array_key_exists( $r , $usbData ) )
			{
				$usbData[$r] = -1;
				$waitSetUsb[] = $r;
			}
		}

		$redis->writeByKey( 'usb.status' , json_encode( $usbData ) );
		echo json_encode( $waitSetUsb );
	}

	/**
	 * set usb state
	 */
	public function actionUsbset()
	{
		$redis = $this->getRedis();
		$usbVal = $redis->readByKey( 'usb.status' );

		$setUsbKey = isset( $_GET['usb'] ) ? htmlspecialchars( $_GET['usb'] ) : '';
		$setUsbTo = isset( $_GET['to'] ) ? htmlspecialchars( $_GET['to'] ) : '';

		if ( empty( $setUsbKey ) || empty( $setUsbTo ) )
		{
			echo '500';exit;
		}

		if ( !empty( $usbVal ) )
			$usbData = json_decode( $usbVal , true );
		else 
		{
			echo '500';exit;
		}

		if ( array_key_exists( $setUsbKey , $usbData ) && in_array( $setUsbTo , array( 'ltc' , 'btc' , '0' ) ) )
			$usbData[ $setUsbKey ] = $setUsbTo;
		else 
		{
			echo '500';exit;
		}

		$redis->writeByKey( 'usb.status' , json_encode( $usbData ) );
		$this->restartByUsb( $setUsbKey , $setUsbTo , $setUsbKey );

		echo '200';exit;
	}

	/**
	 * restart target usb
	 */
	public function actionRestartTarget()
	{
		$setUsbKey = isset( $_GET['usb'] ) ? htmlspecialchars( $_GET['usb'] ) : '';
		if ( empty( $setUsbKey ) )
		{
			echo '500';exit;
		}

		$redis = $this->getRedis();
		$usbVal = $redis->readByKey( 'usb.status' );

		if ( !empty( $usbVal ) )
			$usbData = json_decode( $usbVal , true );
		else 
		{
			echo '500';exit;
		}

		if ( array_key_exists( $setUsbKey , $usbData ) )
			$setUsbTo = $usbData[$setUsbKey];
		else 
		{
			echo '500';exit;
		}

		$this->restartByUsb( $setUsbKey , $setUsbTo , $setUsbKey );

		echo '200';exit;
	}

	/**
	 * get super model state
	 */
	public function getSuperModelState()
	{
		$redis = $this->getRedis();
		$btcVal = $redis->readByKey( 'btc.setting' );
		$aryBTCData = empty( $btcVal ) ? array() : json_decode( $btcVal , true );

		return !empty( $aryBTCData ) && intval( $aryBTCData['su'] ) === 1 ? true : false;
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
