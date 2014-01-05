<?php
/**
 * Port Controller
 * 
 * @author wengebin
 * @date 2013-12-31
 */
class PortController extends BaseController
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
		exit();
	}

	/**
	 * Check method
	 */
	public function actionCheck()
	{
		$this->layout = "blank";
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
				$data['ip'] = $strLocalAdd;
				$data['key'] = KEY;
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

		$data = $this->encodeAjaxData( $isok , $data , $msg );
		$this->render( 'index' , array('data'=>$data) );
	}

	/**
	 * Generate key for machine
	 */
	public function actionGeneratekey( $_boolIsNoExist = false )
	{
		$os = DIRECTORY_SEPARATOR=='\\' ? "windows" : "linux";
		$mac_addr = new CMac( $os );
		$ip_addr = new CIp( $os );

		if ( file_exists( WEB_ROOT.'/js/RKEY.TXT' ) )
		{
			$strRKEY = file_get_contents( WEB_ROOT.'/js/RKEY.TXT' );
		}

		if ( isset( $strRKEY ) && empty( $strRKEY ) )
		{
			$this->generateRKEY();
			$strRKEY = file_get_contents( WEB_ROOT.'/js/RKEY.TXT' );
		}

		$key_file = fopen( WEB_ROOT.'/js/showport.js' , 'w' );
		fwrite($key_file, 'add_machine(\''.md5($mac_addr->mac_addr.'-'.$strRKEY).'\',\''.$ip_addr->ip_addr.'\');');
		fclose($key_file);

		if ( $_boolIsNoExist === true )
			return true;
		else
		{
			echo '200';
			exit();
		}
	}

	/**
	 * Cancel bind
	 */
	public function actionCancelbind()
	{
		$boolResult = $this->generateRKEY();
		if ( $boolResult === true )
			$boolResult = $this->actionGeneratekey( true );

		if ( $boolResult === true )
			UtilMsg::saveTipToSession( '取消绑定成功，请重新扫描绑定！' );
		else
			UtilMsg::saveErrorTipToSession( '取消绑定失败，再试试！' );

		$this->redirect( array( 'index/index' ) );
	}

	/**
	 * Generate random key
	 */
	public function generateRKEY()
	{
		srand((double)microtime()*1000000);

		$file = fopen( WEB_ROOT.'/js/RKEY.TXT' , 'w' );
		fwrite($file, rand());
		fclose($file);

		return true;
	}

//end class
}
