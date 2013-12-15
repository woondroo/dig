<?php
/**
 * 教师模块API静态方法
 * 
 * @author zhouyang<zhouyang@tonqu.com>
 * @date 2013-09-17
 */
class UtilApi
{
	/**
	 * 查找教程的视频和附件是否存在
	 *
	 * @param $_intTeid 教师ID
	 * @param $_intTuid 教程ID
	 * @param $_intTucid 章节ID
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callVideoAndAttachIsExist( $_intTeid = 0 , $_intTuid = 0 , $_intTucid = 0 )
	{
		$aryData = array();
		// 老师ID
		$aryData['teid'] = $_intTeid;
		// 教程ID
		$aryData['tuid'] = $_intTuid;
		// 章节ID
		$aryData['tucid'] = $_intTucid;

		return CApi::callApi( UPLOAD_DOMAIN."/index.php?r=teacher/videoAndAttachIsExist" , $aryData , UPLOAD_DOMAIN_KEY );
	}

	/**
	 * 移动教程的视频和附件，提供管理员审核
	 *
	 * @param $_intTeid 教师ID
	 * @param $_intTuid 教程ID
	 * @param $_intTucid 章节ID
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callVideoAndAttachMove( $_intTeid = 0 , $_intTuid = 0 , $_intTucid = 0 )
	{
		$aryData = array();
		// 老师ID
		$aryData['teid'] = $_intTeid;
		// 教程ID
		$aryData['tuid'] = $_intTuid;
		// 章节ID
		$aryData['tucid'] = $_intTucid;

		return CApi::callApi( UPLOAD_DOMAIN."/index.php?r=teacher/videoAndAttachMove" , $aryData , UPLOAD_DOMAIN_KEY );
	}

	/**
	 * 删除对应章节下的文件
	 *
	 * @param $_intTeid 教师ID
	 * @param $_intTuid 教程ID
	 * @param $_intTucid 章节ID
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callDeleteChapterSourceFile( $_intTeid = 0 , $_intTuid = 0 , $_intTucid = 0 )
	{
		$aryData = array();
		// 老师ID
		$aryData['teid'] = $_intTeid;
		// 教程ID
		$aryData['tuid'] = $_intTuid;
		// 章节ID
		$aryData['tucid'] = $_intTucid;

		return CApi::callApi( UPLOAD_DOMAIN."/index.php?r=tutorials/deleteChapterSourceFile" , $aryData , UPLOAD_DOMAIN_KEY );
	}

	/**
	 * 清除老师上传的过期文件
	 *
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callClearTeacherUpload()
	{
		$aryData = array();

		return CApi::callApi( UPLOAD_DOMAIN."/index.php?r=clear/clearTeacherUpload" , $aryData , UPLOAD_DOMAIN_KEY );
	}

	/**
	 * 归档审核的文件
	 *
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callClearSourceFiles()
	{
		$aryData = array();

		return CApi::callApi( UPLOAD_DOMAIN."/index.php?r=clear/clearSourceFiles" , $aryData , UPLOAD_DOMAIN_KEY );
	}
	
	//end class
}
