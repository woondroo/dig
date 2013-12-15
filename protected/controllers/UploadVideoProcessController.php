<?php
/**
 * Upload Controller
 * 
 * @author zhouyang
 * @date 2013-11-22
 */
class UploadVideoProcessController extends BaseController
{
	/**
	 * init
	 */
	public function init()
	{
		parent::init();		
	}
	
	/**
	 * 同步需要转换的数据
	 * 将已经审核通过的视频数据转移到章节视频表中
	 * 
	 */
	public function actionStep1()
	{
		TutorialsChapterModel::model()->syncDataToTableChapterVideo();
	}
	
	/**
	 * 生成1024*576规格的视频
	 *
	 */
	public function actionConvertFlv1024_576()
	{
		
	}
	
	/**
	 * 调用yamdi给切割前的视频文件加入关键帧
	 *
	 */
	public function actionUnCutVideoYamdi()
	{
		TutorialsChapterVideoModel::model()->genShellYamdi( $intSnId );		
	}
	
	/**
	 * 处理yamdi的xml结果文件，并将结果填充到与之对应的视频信息。
	 *
	 * 
	 */
	public function actionUnCutVideoYamdiResult()
	{
		TutorialsChapterVideoModel::model()->yamdiResult();
	}
	
	/**
	 * 切割视频
	 *
	 */
	public function actionCutVideo()
	{
		TutorialsChapterVideoModel::model()->genShellCutVideo();
	}
	
	/**
	 * 校验是否已经切割，对已切割的视频的更新状态
	 */
	public function actionCutVideoResult()
	{
		$intSnId = isset( $_REQUEST['sn'] ) ? intval( $_REQUEST['sn'] ) : null;
		TutorialsChapterVideoModel::model()->cutVideoResult( $intSnId );
	}
	
	/**
	 * 对已切割视频yamdi注入关键帧
	 *
	 */
	public function actionCutVideoYamdi()
	{
		$intSnId = isset( $_REQUEST['sn'] ) ? intval( $_REQUEST['sn'] ) : null;
		TutorialsChapterVideoCutModel::model()->genShellYamdi( $intSnId );
	}
	
	/**
	 * 处理yamdi的xml结果文件，并将结果填充到与之对应的视频信息。
	 *
	 */
	public function actionCutVideoYamdiResult()
	{
		TutorialsChapterVideoCutModel::model()->yamdiResult();
	}
	
//end class	
}