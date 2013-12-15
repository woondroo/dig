<?php
/**
 * Upload Controller
 * 
 * @author wengebin
 * @date 2013-10-08
 */
class UploadIndexController extends BaseController
{
	/**
	 * init
	 */
	public function init()
	{
		parent::init();		
	}
	
	/**
	 * 清理老师上传数据
	 */
	public function actionClearTeacherUpload()
	{
		$clearResult = UploadModel::model()->clearTeacherUpload();
		CUtilConsole::outputStr( "Clear Result:" );
		CUtilConsole::outputArray( $clearResult );
	}
	
	/**
	 * 归档审核数据
	 */
	public function actionClearSourceFiles()
	{
		$clearResult = UploadModel::model()->clearSourceFiles();
		CUtilConsole::outputStr( "Archive Result:" );
		CUtilConsole::outputArray( $clearResult );
	}
	
//end class	
}
