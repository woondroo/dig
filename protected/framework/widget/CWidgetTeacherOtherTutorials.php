<?php
/**
 * 对应老师的其他教程
 *
 * @author wengebin
 * @date 2013-10-15
 */
class CWidgetTeacherOtherTutorials extends CWidget
{
	/**
	 * 教师ID
	 *
	 * @var array
	 */
	public $_intTeid = 0;

	/**
	 * 排除的教程
	 *
	 * @var array
	 */
	public $_aryExcude = array();

	/**
	 * 其他教程集合
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
		// 老师对应的其他教程
		$aryData = TutorialsModel::model()->getOtherTutorials( $this->_aryExcude , $this->_intTeid );

		if ( empty( $aryData ) )
			$aryData = array();

		$this->_aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'teacherothertutorials' , $aryData );
	}
}
