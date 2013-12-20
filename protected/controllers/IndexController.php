<?php
/**
 * Blank Controller
 * 
 * @author wengebin
 * @date 2013-12-15
 */
class IndexController extends BaseController
{
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
			$redis = new CRedis();

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

		$redis = new CRedis();
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
		$redis->saveData();

		$this->actionRestartProgram();

		echo '200';exit;
	}

	/**
	 * restart program
	 */
	public function actionRestart()
	{
		ini_set('max_execution_time', '0');

		$redis = new CRedis();
		$btcVal = $redis->readByKey( 'btc.setting' );
		$ltcVal = $redis->readByKey( 'ltc.setting' );
		$aryBTCData = empty( $btcVal ) ? array() : json_decode( $btcVal , true );
		$aryLTCData = empty( $ltcVal ) ? array() : json_decode( $ltcVal , true );

		$startTarget = isset( $_GET['tar'] ) ? htmlspecialchars( $_GET['tar'] ) : '';
		if ( empty( $startTarget ) )
		{
			echo '500';exit;
		}

		if ( !empty( $aryBTCData ) ) $commandBTC = "sudo nohup /usr/soft/cgminer-3.1.1-GC3355-SV-master/cgminer-3.1.1/cgminer -o {$aryBTCData['ad']} -u {$aryBTCData['ac']} -p {$aryBTCData['pw']} -S /dev/ttyUSB0 &";

		if ( !empty( $aryLTCData ) ) $commandLTC = "sudo nohup /usr/soft/cpuminer-master/minerd --freq=".($aryLTCData['su'] == 0 ? '600' : '800')." --gc3355=/dev/ttyUSB1 --url={$aryLTCData['ad']} --userpass={$aryLTCData['ac']}:{$aryLTCData['pw']} -2 &";
		
		if ( $startTarget == 'btc' )
			exec( $commandBTC );
		else if ( $startTarget == 'ltc' )
			exec( $commandLTC );
/*
		$btcShellFile = SHELL_DIR."btc.h";
		$saveResult = CUtilConsole::genShShellFile( $btcShellFile , $commandBTC );

		CUtilConsole::execShShellFile( $btcShellFile );
		echo 'end!';
*/
	}

	/**
	 * shutdown program
	 */
	public function actionShutdown()
	{
		exec( 'sudo ps x|grep miner' , $output );

		$pids = array();
		foreach ( $output as $r )
		{
			preg_match( '/\s*(\d+)\s*.*/' , $r , $match );
			if ( !empty( $match[1] ) ) $pids[] = $match[1];
		}

		exec( 'sudo kill -9 '.implode( ' ' , $pids ) );
		echo '200';exit;
	}

	/**
	 * check state
	 */
	public function actionCheck()
	{
		exec( 'sudo ps x|grep miner' , $output );

		$alived = array(
				'ltc'=>array(),
				'btc'=>array()
			);
		foreach ( $output as $r )
		{
			preg_match( '/.*-u\s(.+)\s-p.*/' , $r , $match_user_btc );
			preg_match( '/.*--userpass=(.*)\:.*/' , $r , $match_user_ltc );

			preg_match( '/.*(cgminer).*/' , $r , $match_btc );
			preg_match( '/.*(minerd).*/' , $r , $match_ltc );

			preg_match( '/.*-o\s(.+)\s-u.*/' , $r , $match_url_btc );
			preg_match( '/.*--url=(.*)\s--userpass.*/' , $r , $match_url_ltc );

			if ( !empty( $match_btc[1] ) 
					&& ( !is_array( $alived['btc'][$match_url_btc[1]] ) || !in_array( $match_user_btc[1] , $alived['btc'][$match_url_btc[1]] ) ) )
				$alived['btc'][$match_url_btc[1]][] = $match_user_btc[1];

			if ( !empty( $match_ltc[1] ) 
					&& ( !is_array( $alived['ltc'][$match_url_ltc[1]] ) || !in_array( $match_user_ltc[1] , $alived['ltc'][$match_url_ltc[1]] ) ) )
				$alived['ltc'][$match_url_btc[1]][] = $match_user_ltc[1];
		}

		echo json_encode( $alived );exit;
	}

//end class	
}
