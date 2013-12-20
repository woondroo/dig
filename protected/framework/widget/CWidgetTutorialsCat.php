<?php
/**
 * 教程分类模块
 *
 * @author wengebin
 * @date 2013-10-12
 */
class CWidgetTutorialsCat extends CWidget
{
	/**
	 * 所有教程分类
	 *
	 * @var array
	 */
	public $_aryCategories = array();

	/**
	 * 是否显示“全部”菜单项
	 *
	 * @var int
	 */
	public $_showAll = 1;

	/**
	 * 其他url参数
	 *
	 * @var array
	 */
	public $_aryParams = array();

	/**
	 * 定位到目标页面
	 *
	 * @var string
	 */
	public $_strTargetPage = 'channel/list';

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
		// 获得所有教程分类
		$aryCategories = TutorialsCatModel::model()->getCategories();

		if ( empty( $aryCategories ) )
			$aryCategories = array();

		// 增加“全部”选项
		if ( $this->_showAll === 1 )
			$aryCategories = array_merge( array( '0'=>'全部' ) , $aryCategories );

		$this->_aryCategories = $aryCategories;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'tutorialscat' , $aryData );
	}
}
