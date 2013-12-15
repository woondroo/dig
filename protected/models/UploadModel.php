<?php
/**
 * 上传相关
 * 
 * @author wengebin
 * @date 2013-11-08
 */
class UploadModel extends CModel
{
	/**
	 * 初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 * 返回惟一实例
	 *
	 * @return TutorialsBuyRecordModel
	 */
	public static function model()
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 清除老师上传过期的文件
	 *
	 * @return bool
	 */
	public function clearTeacherUpload()
	{
		$resultData = UtilApi::callClearTeacherUpload();
		return $resultData;
	}

	/**
	 * 归档审核的文件
	 *
	 * @return bool
	 */
	public function clearSourceFiles()
	{
		$resultData = UtilApi::callClearSourceFiles();
		return $resultData;
	}
	
//end class
}
