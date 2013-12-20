<?php
/**
 * 最近收藏的学员
 *
 * @author wengebin
 * @date 2013-10-15
 */
class CWidgetTeacherCollected extends CWidget
{
	/**
	 * 教师ID
	 *
	 * @var array
	 */
	public $_intTeid = 0;

	/**
	 * 学员数据集
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
		// 获得左侧推荐老师
		$aryData = TeacherModel::model()->getCollectedUser( $this->_intTeid );

		if ( empty( $aryData ) )
			$aryData = array();

		$this->_aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'collectedteacher' , $aryData );
	}
}
