<?php
/**
 * 章节列表
 *
 * @author wengebin
 * @date 2013-10-17
 */
class CWidgetChapterList extends CWidget
{
	/**
	 * 教程ID
	 *
	 * @var int
	 */
	public $_intTuid = 0;

	/**
	 * 章节数据集
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
		// 获得章节列表
		$aryData = TutorialsChapterModel::model()->getChapters( $this->_intTuid );

		if ( empty( $aryData ) )
			$aryData = array();

		$this->_aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'chapterlist' , $aryData );
	}
}
