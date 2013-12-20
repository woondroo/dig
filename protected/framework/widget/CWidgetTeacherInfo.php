<?php
/**
 * 教师详细信息
 *
 * @author wengebin
 * @date 2013-10-15
 */
class CWidgetTeacherInfo extends CWidget
{
	/**
	 * 教师ID
	 *
	 * @var int
	 */
	public $_intTeid = 0;

	/**
	 * 教师详细信息
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
		// 获得老师详细资料
		if ( !empty( $this->_intTeid ) )
			$aryData = TeacherModel::model()->getTeacherInfo( $this->_intTeid );

		if ( empty( $aryData ) )
			$aryData = array();

		$this->_aryData = $aryData;
		
		// 渲染视图
		$aryData = array();
		$this->render( 'teacherinfo' , $aryData );
	}
}
