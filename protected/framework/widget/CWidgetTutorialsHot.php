<?php
/**
 * 推荐教程
 *
 * @author wengebin
 * @date 2013-10-15
 */
class CWidgetTutorialsHot extends CWidget
{
	/**
	 * 教程数据集
	 *
	 * @var array
	 */
	public $aryData = array();

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
		// 获得推荐教程
		$aryData = TutorialsModel::model()->getHotTutorials();

		if ( empty( $aryData ) )
			$aryData = array();

		$this->aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'hottutorials' , $aryData );
	}
}
