<?php
/**
 * 教程收藏记录
 *
 * @author wengebin
 * @date 2013-10-17
 */
class CWidgetTutorialsCollectHistory extends CWidget
{
	/**
	 * 教程ID
	 *
	 * @var int
	 */
	public $_intTuid = 0;

	/**
	 * 收藏用户数据集
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
		// 获得最近历史数藏用户
		$aryData = TutorialsModel::model()->getCollectedUser( $this->_intTuid );

		if ( empty( $aryData ) )
			$aryData = array();

		$this->_aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'collectedtutorial' , $aryData );
	}
}
