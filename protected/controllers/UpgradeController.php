<?php
/**
 * Upgrade Controller
 * 
 * @author wengebin
 * @date 2013-12-29
 */
class UpgradeController extends BaseController
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
		$this->replaceSeoTitle( 'BTC & LTC 升级中心' );

		$aryData = array();
		$this->render( 'index' , $aryData );
	}

	/**
	 * Check version method
	 */
/*
	public function actionCheckversion()
	{
		// get client version
		$strVersion = isset( $_REQUEST['version'] ) ? htmlspecialchars( trim( $_REQUEST['version'] ) ) : '';

		// get timestamp
		$timestamp = isset( $_REQUEST['time'] ) ? intval( trim( $_REQUEST['time'] ) ) : 0;

		// get sign
		$sign = isset( $_REQUEST['sign'] ) ? htmlspecialchars( trim( $_REQUEST['sign'] ) ) : '';

		$isok = 0;
		$data = array();
		$msg = "";
		try
		{
			$aryData = array();
			$aryData['version'] = $strVersion;
			$aryData['time'] = $timestamp;
			// check sign
			if( !CApi::verifySign( $aryData , $sign , MAIN_DOMAIN_KEY ) )
				throw new CModelException( "签名认证失败" );

			if ( $strVersion < MAIN_DOMAIN )
			{
				$isok = 1;
				$msg = "有新版本可更新！";
			}
			else
			{
				$isok = 2;
				$msg = "暂无新版本！";
			}
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
*/

	/**
	 * Check is has new version method
	 */
	public function actionHasnew( $_boolIsExit = false )
	{
		// check version
		$aryVersionData = UtilApi::callCheckNewVersion( CUR_VERSION );
		
		if ( $_boolIsExit === false )
		{
			header('Content-Type: text/html; charset=utf-8');
			echo json_encode( $aryVersionData );
			exit();
		}
		else
		{
			return $aryVersionData;
		}
	}

	/**
	 * Upgrade version method
	 */
	public function actionUpgradeversion()
	{
		// check is newest
		$aryVersionData = $this->actionHasnew( true );

		$isok = 0;
		$data = array();
		$msg = "";

		try
		{
			if ( $aryVersionData['ISOK'] !== 1 || empty( $aryVersionData['DATA']['v'] ) )
				throw new CModelException( '当前版本无需升级！' );

			// get up to version
			$strVersion = $aryVersionData['DATA']['v'];

			if ( empty( $strVersion ) )
				throw new CModelException( '升级失败，参数不正确！' );

			if ( $strVersion <= CUR_VERSION )
				throw new CModelException( '当前版本无需升级！' );

			// execute upgrade
			$command = "sudo cd ".WEB_ROOT.";sudo wget ".MAIN_DOMAIN."/down/v{$strVersion}.zip;sudo unzip -o v{$strVersion}.zip;sudo rm -rf v{$strVersion}.zip;";
			exec( $command );
			
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
