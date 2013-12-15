<?php
/**
 * 章节
 * 
 * @author zhouyang
 * @date 2013-11-22
 */
class TutorialsChapterModel extends CModel
{
	private $_strTableName  = "";
	
	/**
	 * 初始化
	 */
	public function init()
	{
		parent::init();
		$this->setDb( CDbConnection::getWebDbConnection() );
	}
	
	/**
	 * 返回惟一实例
	 *
	 * @return TutorialsChapterModel
	 */
	public static function model()
	{
		return parent::model( __CLASS__ );
	}
	
	/**
	 * 表名
	 *
	 * @return string
	 */
	public function tableName()
	{
		return $this->_strTableName;
	}
	
	/**
	 * 设置表名
	 *
	 * @param int $_intSn	表序号
	 * @return TutorialsChapterModel
	 */
	public function setTableNameBySn( $_intSn = 0 , $_isReturnTableName = false )
	{
		if( $_intSn < 0 || $_intSn > 9 )
			throw new CModelException( "参数错误，表ID范围在0~9之间" );
		$this->_strTableName = "qx_tutorials_chapter_{$_intSn}";
		
		if( $_isReturnTableName )
			return $this->_strTableName;
		return $this;
	}
	
	/**
	 * 同步1280*720的视频数据到章节视频表，并新增加视频规格1024*576
	 *
	 * 
	 * @throws Exception
	 */
	public function syncDataToTableChapterVideo()
	{
		for( $i = 0 ; $i < 10 ; $i++ )
		{
			$this->doSyncDataToTableChapterVideo( $i );
		}
	}
	
	/**
	 * 真正的视频数据同步
	 *
	 * @param int $_intSnTableId 表ID
	 */
	public function doSyncDataToTableChapterVideo( $_intSnTableId = null )
	{
		CUtilConsole::outputStr( "loading data from qx_tutorials_chapter_{$_intSnTableId}" );		
		//获取表数据
		$this->setTableNameBySn( $_intSnTableId );
		$criteria = new CDbCriteria();
		$criteria->select = "tuc_id,tuc_tuid,tuc_video_path";
		$criteria->condition = "tuc_status = ".CUtil::TUTORIALS_CHAPTER_STATUS_YES." AND tuc_video_upload_sync_status = 0";
		$aryRow = $this->findAll( $criteria );		
		if( empty( $aryRow ) )
		{
			CUtilConsole::outputStr( "nodata." );
			return ;
		}
		
		CUtilConsole::outputStr( "sync ".count($aryRow)." data to qx_tutorials_chapter_video_{$_intSnTableId}" );
		$modelTcv = TutorialsChapterVideoModel::model();
		$modelTcv->setTableNameBySn( $_intSnTableId );
		foreach ( $aryRow as $v )
		{
			try
			{
				CDbConnection::getWebDbConnection()->beginTransaction();
				//新增1280规格数据
				$aryData = array();
				$aryData['tcv_pk'] = CUtilConsole::genTcvKey( $v['tuc_tuid'] , $v['tuc_id'] , '1280*720' );
				$aryData['tcv_tuid'] = $v['tuc_tuid'];
				$aryData['tcv_tucid'] = $v['tuc_id'];
				$aryData['tcv_spec'] = "1280*720";				
				$aryData['tcv_path'] = $v['tuc_video_path'];
				$boolIsConvert = file_exists( DIR_VIDEOS.$aryData['tcv_path'] ) ? true : false;
				$aryData['tcv_status'] = $boolIsConvert ? 1 : 0;
				$aryData['tcv_yamdi_path'] = CUtilConsole::genUnCutYamdiFilePath( $aryData['tcv_path'] , true );
				$modelTcv->setData( $aryData );
				if( false === $modelTcv->insert() )
					throw new CModelException( "sync data failed!" );
				
				//新增1024*576的数据
				/*$aryData = array();
				$aryData['tcv_pk'] = CUtilConsole::genTcvKey( $v['tuc_tuid'] , $v['tuc_id'] , '1204*576' );
				$aryData['tcv_tuid'] = $v['tuc_tuid'];
				$aryData['tcv_tucid'] = $v['tuc_id'];
				$aryData['tcv_spec'] = "1024*576";
				$aryData['tcv_status'] = 0;
				$aryData['tcv_path'] = CUtilConsole::genUnCutSpecFilePath( $v['tuc_video_path'] , "1024576" , true );
				$boolIsConvert = file_exists( DIR_VIDEOS.$aryData['tcv_path'] ) ? true : false;
				$aryData['tcv_status'] = $boolIsConvert ? 1 : 0;
				$aryData['tcv_yamdi_path'] = CUtilConsole::genUnCutYamdiFilePath( $aryData['tcv_path'] , true );
				$modelTcv->setData( $aryData );
				if( false === $modelTcv->insert( $aryData ) )
					throw new CModelException( "sync data failed" );*/
					
				//更新章节的同步状态为已同步
				$this->updateByCondition( array('tuc_video_upload_sync_status'=>1) , "tuc_id='{$v['tuc_id']}' AND tuc_tuid = '{$v['tuc_tuid']}'" );
				
				CDbConnection::getWebDbConnection()->commit();
				
				CUtilConsole::outputStr( "sync success" );
				
			}
			catch ( CModelException $e )
			{
				CUtilConsole::outputStr( $e->getMessage() );
				CDbConnection::getWebDbConnection()->rollBack();
			}
			catch ( CException $e )
			{
				CUtilConsole::outputStr( $e->getMessage() );
				CDbConnection::getWebDbConnection()->rollBack();
			}
		}
	}
//end class
}