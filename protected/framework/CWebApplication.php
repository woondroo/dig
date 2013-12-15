<?php
/**
 * CWebApplication class file.
 * 
 * @author samson.zhou <samson.zhou@newbiiz.com>
 * @package framework
 * @date 2010-08-12
 */

class CWebApplication extends CApplication
{
	/**
	 * @return string the ID of the default controller. Defaults to 'index'.
	 */
	public $defaultController = 'index';
	public $defaultAction = 'index';
	private $baseUrl;
	
	/**
	 * Processes the current request.
	 * It first resolves the request into controller and action,
	 * and then creates the controller to perform the action.
	 */
	public function processRequest()
	{
		$aryParams = array();
		if( is_array( $_GET ) )
		{
			foreach( $_GET as $key=>$val )
			{
				$aryParams[$key] = $val;
			}
		}
		if( is_array( $_POST ) )
		{
			foreach( $_POST as $key=>$val )
			{
				$aryParams[$key] = $val;
			}
		}				
		$this->runController( $aryParams );
	}

	
	/**
	 * Initializes the application.
	 * This method overrides the parent implementation by preloading the 'request' component.
	 */
	public function init()
	{
		parent::init();
		$this->setBaseUrl( Nbt::app()->request->baseUrl );
	}
	
	/**
	 * run controller
	 * 
	 */
	public function runController( $aryParams )
	{
		$controllerName = '';
		$actionName = '';
		$routeName = $this->getRequest()->routeName;
		$route = isset( $aryParams[$routeName] ) ? $aryParams[$routeName] : null;		
		$aryRoute = is_null( $route ) ? array() : explode( '/' , $route );
				
		switch( count( $aryRoute ) )
		{
			case 1:
				$controllerName = $aryRoute[0];
				$actionName = $this->defaultAction;
				break;
			case 2:
				$controllerName = $aryRoute[0];
				$actionName = $aryRoute[1];				
				break;
			case 0:
				$controllerName = $this->defaultController;
				$actionName = $this->defaultAction;
				break;
			default:
				throw new CException( 'Route name error.' );
		}		
		
		$controller = ucfirst( $controllerName ).'Controller';
		$action = 'action'.ucfirst( $actionName );
		
		$c = new $controller();
		if( method_exists( $c , $action ) )
		{
			$c->setId( $controllerName );
			$c->setActionId( $actionName );
			
			$c->beforeAction();
			$c->$action();
			$c->afterAction();
		}
		else
		{
			throw new CHttpException( 404 , "{$controller} have not defined function {$action}" );
		}
	}
	
	/**
	 * create url 
	 * 
	 * 
	 * @return string
	 */
	public function createUrl( $_route = null , $_aryParams = array() , $_ampersand='&' )
	{
		$_route = ($_route === null) ? "{$this->defaultController}/{$this->defaultAction}" : $_route;
		
		$url = "";
		//是否进行地址重写，生成静态地址
		$url = REWRITE_MODE === true ? UtilUrl::createStaticUrl( $_route , $_aryParams ) : '';
		//没有静态地址，则生成动态地址
		if( empty( $url ) )
			$url = $this->createDynamicUrl( $_route , $_aryParams , $_ampersand );
		return $url;
	}
	
	/**
	 * 创建动态url地址
	 *
	 * @param string $_route		路由
	 * @param array $_aryParams		参数=>值
	 * @param string $_ampersand	url参数之间的边接符
	 * @return string
	 */
	public function createDynamicUrl( $_route = null , $_aryParams = array() , $_ampersand='&' )
	{
		$_route = ($_route === null) ? "{$this->defaultController}/{$this->defaultAction}" : $_route;
		$routeName = $this->getRequest()->routeName;
		$url = $this->getBaseUrl()."/index.php?{$routeName}={$_route}";		
		foreach( (array)$_aryParams as $k=>$v )
		{
			$url .= "{$_ampersand}{$k}={$v}";
		}
		return $url; 
	}
	
	/**
	 * create absolute url contains www
	 * 
	 * @return string
	 */
	public function createAbsoluteUrl( $_route = "" , $_aryParams = array() ,$_schema='',$_ampersand='&' )
	{
		return Nbt::app()->getRequest()->getHostInfo($_schema).$this->createUrl( $_route , $_aryParams , $_ampersand );
	}
	
	/**
	 * 设置网站相对路径
	 *
	 * @param string $_baseUrl
	 */
	public function setBaseUrl( $_baseUrl )
	{
		$this->baseUrl = $_baseUrl;
	}
	
	/**
	 * 获取网站相对路径
	 *
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}	
	
//end class
}
