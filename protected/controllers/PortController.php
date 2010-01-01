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
	public function actionGeneratekey()
	{
		$os = DIRECTORY_SEPARATOR=='\\' ? "windows" : "linux";
		$mac_addr = new CMac( $os );
		$ip_addr = new CIp( $os );

		$file = fopen( WEB_ROOT.'/js/showport.js' , 'w' );
		fwrite($file, 'add_machine(\''.md5($mac_addr->mac_addr).'\',\''.$ip_addr->ip_addr.'\');');
		fclose($file);

		echo '200';
		exit();
	}

//end class
}
