<?php
/**
 * 教程购买记录
 *
 * @author wengebin
 * @date 2013-10-17
 */
class CWidgetTutorialsBuyHistory extends CWidget
{
	/**
	 * 教程ID
	 *
	 * @var int
	 */
	public $_intTuid = 0;

	/**
	 * 购买用户数据集
	 *
	 * @var array
	 */
	public $_aryData = array();

	/**
	 * 初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 * 运行
	 */
	public function run()
	{
		// 获得最近历史购买用户
		$aryData = OrderBuyTutorialsHistoryModel::model()->getBuyedUser( $this->_intTuid );

		if ( empty( $aryData ) )
			$aryData = array();

		$this->_aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'buyedtutorial' , $aryData );
	}
}
