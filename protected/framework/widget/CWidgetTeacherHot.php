<?php
/**
 * 推荐教师
 *
 * @author wengebin
 * @date 2013-10-15
 */
class CWidgetTeacherHot extends CWidget
{
	/**
	 * 教师数据集
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
		// 获得左侧推荐老师
		$aryData = TeacherModel::model()->getHotTeacher();

		if ( empty( $aryData ) )
			$aryData = array();

		$this->aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'hotteacher' , $aryData );
	}
}
