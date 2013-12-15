<?php
/**
 * 章节视频
 * 
 * @author zhouyang
 * @date 2013-11-22
 */
class TutorialsChapterVideoModel extends CModel
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
	 * @return TutorialsChapterVideoModel
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
		$this->_strTableName = "qx_tutorials_chapter_video_{$_intSn}";
		
		if( $_isReturnTableName )
			return $this->_strTableName;
		return $this;
	}
	
	/**
	 * 给未切割之前的FLV视频文件增加关键帧，并生成结果
	 *
	 * @param int $_intSn	表ID
	 * 
	 */
	public function genShellYamdi( $_intSn = null )
	{
		//先清空shell里面的内容
		CUtilConsole::genShShellFile( SHELL_UNCUTVIDEO_YAMDI , "" );
		
		$strCmd = "";
		if( is_null( $_intSn ) )
		{
			for( $i = 0 ; $i< 10 ; $i++ )
				$strCmd .= $this->doGenShellYamdi( $i );
		}
		else
		{
			$strCmd .= $this->doGenShellYamdi( $_intSn );
		}
		
		//生成cmd shell
		$boolRes = CUtilConsole::genShShellFile( SHELL_UNCUTVIDEO_YAMDI , $strCmd );
		
		if( $boolRes === false )
			CUtilConsole::outputStr( "****generate ".SHELL_UNCUTVIDEO_YAMDI." failed.****" );
		else
			CUtilConsole::outputStr( "****generate ".SHELL_UNCUTVIDEO_YAMDI." success.****" );
	}
	
	/**
	 * 给切割之前的FLV视频增加关键帧
	 *
	 * @param int $_intSn
	 */
	public function doGenShellYamdi( $_intSn = null )
	{
		CUtilConsole::outputStr( "loading data from qx_tutorials_chapter_video_{$_intSn}" );	
		
		$this->setTableNameBySn( $_intSn );
		//获取出已生成的文件，并且未加关键帧的数据
		$aryData = $this->findAll( "tcv_status = 1 AND tcv_kf_status = 0" );
		$strCmd = "";
		
		if( empty( $aryData ) )
			CUtilConsole::outputStr( "nodata." );
		
		//遍历每一行数据，调用linux命令:yamdi对切割前的视频文件进行关键帧的注入
		foreach ( $aryData as $k=>$v )
		{
			$strInputFile = DIR_VIDEOS.$v['tcv_path'];
			$strOutputFile = DIR_VIDEOS.$v['tcv_yamdi_path'];
			$strXmlFile = CUtilConsole::genYamdiUnCutFileName( $_intSn , $v['tcv_tuid'] , $v['tcv_pk'] , true );
			
			$strCmd .= "echo 'begin {$strInputFile}'\n";
			$strCmd .= "rm -rf {$strOutputFile}\n"; 
			$strCmd .= "yamdi -i {$strInputFile} -o {$strOutputFile} -x {$strXmlFile}\n\n"; 
			$strCmd .= "echo 'end.'\n\n\n";
		}
		return $strCmd;
	}
	
	/**
	 * 获取yamdi处理后的xml结果文件，并将文件信息写入到数据库中
	 * 
	 * 
	 * 
	 */
	public function yamdiResult( $_intSn = null )
	{
		if( is_null( $_intSn ) )
		{
			for( $i = 0 ; $i< 10 ; $i++ )
				$this->doYamdiResult( $i );
		}
		else
		{
			$this->doYamdiResult( $_intSn );
		}
	}
	
	/**
	 * 真正的处理 yamdi 加入关键帧的xml结果
	 *
	 * @param int $_intSn	表序号
	 */
	public function doYamdiResult( $_intSn )
	{
		//获取需要处理的数据
		$this->setTableNameBySn( $_intSn );		
		$aryRow = $this->findAll( "tcv_status = 1 AND tcv_kf_status = 0" );
		if( empty( $aryRow ) )
		{
			CUtilConsole::outputStr( "table {$_intSn} no data." );
			return true;
		}
		foreach ( $aryRow as $itemTcv )
		{
			//加载xml文件
			$xmlFileName = CUtilConsole::genYamdiUnCutFileName( $_intSn , $itemTcv['tcv_tuid'] , $itemTcv['tcv_pk'] );
			if( !file_exists( $xmlFileName ) )
			{
				CUtilConsole::outputStr( "{$xmlFileName} is not exists." );
				continue;
			}
			
			CUtilConsole::outputStr( "begin process {$xmlFileName}." );
			
			$strXmlFileContent = file_get_contents( $xmlFileName );
			$xml = simplexml_load_string( $strXmlFileContent );
			$xml = $xml->flv;
			if( !$xml->hasKeyframes )
			{
				CUtilConsole::outputStr( "{$xmlFileName},has no keyframes." );
				continue;
			}
			//print_r($xml);
			
			//将xml文件里的结果写入到数据库
			$aryData = array();
			$aryData['tcv_kf_status'] = 1;
			$aryData['tcv_time'] = floatval($xml->duration);
			$aryData['tcv_size'] = floatval($xml->datasize);
			$aryData['tcv_kf_time'] = time();
			$aryKfValues = (array)$xml->keyframes->times;
			$aryKfValues = $aryKfValues['value'];
			$aryData['tcv_fames'] = implode( ',' , $aryKfValues );
			$aryData['tcv_cut_status'] = 1;
			if( empty( $aryData['tcv_fames'] ) )
			{
				CUtilConsole::outputStr( "{$xmlFileName},has no keyframes-." );
				return false;
			}
			
			try
			{
				CDbConnection::getWebDbConnection()->beginTransaction();
				
				//关键帧状态更新为已加入关键帧
				$this->updateByCondition( $aryData , "tcv_pk = '{$itemTcv['tcv_pk']}'" );
				//按照最优算法生成切割视频数据(差不多在每2分钟切割成一个视频)
				$aryCutData = array();
				$intItemCutDataKey = 0;
				$intKfKeyBegin = 0;
				foreach ( $aryKfValues as $itemKfKey=>$itemKfValue )
				{
					$aryCutData[$intItemCutDataKey] = array();
					$aryCutData[$intItemCutDataKey]['begin'] = $aryKfValues[$intKfKeyBegin];
					$aryCutData[$intItemCutDataKey]['end'] = $itemKfValue;
					if( floor($itemKfValue/120) > $intItemCutDataKey )
					{
						$intKfKeyBegin = $itemKfKey;
						$intItemCutDataKey++;
					}
				}
				
				$modelTcvc = TutorialsChapterVideoCutModel::model();
				$modelTcvc->setTableNameBySn( $_intSn );
				//删除原数据及视频文件
				$aryOldTcvcDatalist = $modelTcvc->findAll( "tcvc_pid='{$itemTcv['tcv_pk']}'" );
				foreach ( $aryOldTcvcDatalist as $k=>$v )
				{
					@unlink( DIR_VIDEOS.$v['tcvc_path'] );
					@unlink( DIR_VIDEOS.$v['tcvc_yamdi_path'] );
				}
				$modelTcvc->deleteAll( "tcvc_pid='{$itemTcv['tcv_pk']}'" );
				unset( $aryOldTcvcDatalist );
				//新增切割视频的数据
				foreach ( $aryCutData as $keyCutData=>$itemCutData )
				{
					$aryData = array();
					$aryData['tcvc_pid'] = $itemTcv['tcv_pk'];
					$aryData['tcvc_tuid'] = $itemTcv['tcv_tuid'];
					$aryData['tcvc_tucid'] = $itemTcv['tcv_tucid'];
					$aryData['tcvc_path'] = CUtilConsole::genVideoCutFileName( $itemTcv['tcv_path'] , $keyCutData , true );
					$aryData['tcvc_yamdi_path'] = CUtilConsole::genCutedYamdiFilePath( $itemTcv['tcv_path'] , $keyCutData , true );
					$aryData['tcvc_sv_begin'] = $itemCutData['begin'];
					$aryData['tcvc_sv_end'] = $itemCutData['end'];
					$modelTcvc->setData( $aryData );
					$modelTcvc->insert();
				}
				
				CDbConnection::getWebDbConnection()->commit();
				
				CUtilConsole::outputStr( "{$xmlFileName} save in database." );
			}
			catch ( CModelException $e )
			{
				CDbConnection::getWebDbConnection()->rollBack();
				CUtilConsole::outputStr( "{$xmlFileName} {$e->getMessage()}" );
			}
			catch ( Exception $e )
			{
				CDbConnection::getWebDbConnection()->rollBack();
				CUtilConsole::outputStr( "{$xmlFileName} {$e->getMessage()}" );
			}			
			
		}
	}
	
	/**
	 * 遍历表切割视频
	 *
	 * @param int $_intSn	表序号
	 */
	public function genShellCutVideo( $_intSn = null )
	{
		//先清空shell里面的内容
		CUtilConsole::genShShellFile( SHELL_CUTVIDEO , "" );
		
		$strCmd = "";
		if( is_null( $_intSn ) )
		{
			for( $i = 0 ; $i< 10 ; $i++ )
			{
				$strCmd .= $this->doGenShellCutVideo( $i );
			}
		}
		else
		{
			$strCmd .=$this->doGenShellCutVideo( $_intSn );
		}
		//生成cmd shell
		$boolRes = CUtilConsole::genShShellFile( SHELL_CUTVIDEO , $strCmd );
		
		if( $boolRes === false )
			CUtilConsole::outputStr( "****generate ".SHELL_CUTVIDEO." failed.****" );
		else
			CUtilConsole::outputStr( "****generate ".SHELL_CUTVIDEO." success.****" );
	}
	
	/**
	 * 按表切割视频-生成shell文件
	 *
	 * @param int $_intSn
	 */
	public function doGenShellCutVideo( $_intSn )
	{
		$this->setTableNameBySn( $_intSn );
		//获取待切割的视频数据
		$aryUnCutDataList = $this->findAll( "tcv_cut_status = 1" );
		//遍历待切割的数据，生成ffpgeg命令集合的shell命令
		$strCmd = "";
		
		if( empty( $aryUnCutDataList ) )
			CUtilConsole::outputStr( "table {$_intSn} nodata" );
		else
			CUtilConsole::outputStr( "table {$_intSn} ".count($aryUnCutDataList)." rows" );
		
		foreach ( $aryUnCutDataList as $itemUnCutDataList )
		{
			//获取分片数据
			$aryCutVideo = TutorialsChapterVideoCutModel::model()->setTableNameBySn( $_intSn )->findAll( "tcvc_pid='{$itemUnCutDataList['tcv_pk']}' AND tcvc_status = 0 AND tcvc_delflg='0' " );
			foreach ( $aryCutVideo as $itemCutVideo )
			{
				$strInputFile = DIR_VIDEOS.$itemUnCutDataList['tcv_yamdi_path'];
				$strOutFile = DIR_VIDEOS.$itemCutVideo['tcvc_path'];
				
				//截取时长
				$tmpFloLength = $itemCutVideo['tcvc_sv_end']-$itemCutVideo['tcvc_sv_begin'];
				//开始时间
				$tmpStrBeginPosition = CUtilConsole::convertToVideoBeginPosition( $itemCutVideo['tcvc_sv_begin'] );
				
				//拼接shell命令
				$strCmd .= "echo 'begin cuting {$strInputFile}'\n";
				$strCmd .= "rm -rf {$strOutFile}\n";				
				$strCmd .= "ffmpeg -ss {$tmpStrBeginPosition} -i {$strInputFile} -vcodec copy -acodec copy -t {$tmpFloLength} {$strOutFile}\n\n";
				$strCmd .= "echo 'end.'\n\n\n";
			}
		}
		
		return $strCmd;			
	}
	
	/**
	 * 校验切割视频是否已经生成
	 *
	 */
	public function cutVideoResult()
	{
		for( $i = 0 ; $i < 9 ; $i++ )
		{
			$this->doCutVideoResult( $i );
		}		
	}
	
	/**
	 * 按表校验切割视频是否已生成
	 *
	 * @param int $_intSnId	表ID
	 */
	public function doCutVideoResult( $_intSnId = 0 )
	{
		CUtilConsole::outputStr( "process table {$_intSnId}" );
		$this->setTableNameBySn( $_intSnId );
		//查询待切割的视频
		$aryCutingData = $this->findAll( "tcv_cut_status = 1" );		
		if( empty( $aryCutingData ) )
		{
			CUtilConsole::outputStr( "table {$_intSnId} no data..." );
			return true;
		}
		
		$modelTcvc = TutorialsChapterVideoCutModel::model();
		$modelTcvc->setTableNameBySn( $_intSnId );
		
		//遍历待切割的视频，查询切割的视频文件是否已经生成
		foreach ( $aryCutingData as $itemCutingData )
		{
			//是否该视频下的切割视频是否已经都生成
			$isAllGen = true;
			$aryCutedData = $modelTcvc->findAll( "tcvc_pid = '{$itemCutingData['tcv_pk']}' AND tcvc_status = 0 AND tcvc_delflg = 0" );
			if( empty( $aryCutedData ) )
				CUtilConsole::outputStr( "cuted table {$_intSnId} no data..." );
			
			try
			{
				CDbConnection::getWebDbConnection()->beginTransaction();
				
				
				//遍历是否该文件已经生成，如果已经生成，则将状态改为已切割.
				foreach ( $aryCutedData as $itemCutedData )
				{
					$strCutedFilePath = DIR_VIDEOS.$itemCutedData['tcvc_path'];
					//文件尚未生成
					if( !file_exists( $strCutedFilePath ) )
					{				
						$isAllGen = false;
						CUtilConsole::outputStr( "{$strCutedFilePath} is not existed." );
						continue;
					}
					//文件已生成
					CUtilConsole::outputStr( "{$strCutedFilePath} is existed." );
					$modelTcvc->updateByCondition( array('tcvc_status'=>1,'tcvc_cut_time'=>time()) , "tcvc_id='{$itemCutedData['tcvc_id']}'" );
				}
				
				if( $isAllGen )
					//更新原视频的状态为已切割
					$this->updateByCondition( array('tcv_cut_status'=>2) , "tcv_pk='{$itemCutingData['tcv_pk']}'" );
					
				
				CDbConnection::getWebDbConnection()->commit();
			}
			catch ( Exception $e )
			{
				CUtilConsole::outputStr( $e->getMessage() );
				CDbConnection::getWebDbConnection()->rollBack();
			}
		}
		
	}
	
//end class
}