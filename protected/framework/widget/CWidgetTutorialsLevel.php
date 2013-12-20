<?php
/**
 * 教程级别模块
 *
 * @author wengebin
 * @date 2013-10-12
 */
class CWidgetTutorialsLevel extends CWidget
{
	/**
	 * 所有教程级别
	 *
	 * @var array
	 */
	public $_aryLevels = array();

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
		// 获得所有教程级别
		$aryLevels = CUtil::getTurorialsLevel(null);

		if ( empty( $aryLevels ) )
			$aryLevels = array();

		// 增加“全部”选项
		if ( $this->_showAll === 1 )
			$aryLevels = array_merge( array( '0'=>'全部' ) , $aryLevels );

		$this->_aryLevels = $aryLevels;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'tutorialslevel' , $aryData );
	}
}
