<?php
/**
 * 章节切割视频
 * 
 * @author zhouyang
 * @date 2013-11-22
 */
class TutorialsChapterVideoCutModel extends CModel
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
	 * @return TutorialsChapterVideoCutModel
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
		$this->_strTableName = "qx_tutorials_chapter_video_cut_{$_intSn}";
		
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
		CUtilConsole::genShShellFile( SHELL_CUTVIDEO_YAMDI , "" );
		
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
		$boolRes = CUtilConsole::genShShellFile( SHELL_CUTVIDEO_YAMDI , $strCmd );
		
		if( $boolRes === false )
			CUtilConsole::outputStr( "****generate ".SHELL_CUTVIDEO_YAMDI." failed.****" );
		else
			CUtilConsole::outputStr( "****generate ".SHELL_CUTVIDEO_YAMDI." success.****" );
	}
	
	/**
	 * 给切割之前的FLV视频增加关键帧
	 *
	 * @param int $_intSn
	 */
	public function doGenShellYamdi( $_intSn = null )
	{
		CUtilConsole::outputStr( "loading data from qx_tutorials_chapter_video_cut_{$_intSn}" );	
		
		$this->setTableNameBySn( $_intSn );
		//获取出已生成的文件，并且未加关键帧的数据
		$aryData = $this->findAll( "tcvc_status = 1 AND tcvc_kf_status = 0" );
		$strCmd = "";
		
		if( empty( $aryData ) )
			CUtilConsole::outputStr( "nodata." );
		
		//遍历每一行数据，调用linux命令:yamdi对切割前的视频文件进行关键帧的注入
		foreach ( $aryData as $k=>$v )
		{
			$strInputFile = DIR_VIDEOS.$v['tcvc_path'];
			$strOutputFile = DIR_VIDEOS.$v['tcvc_yamdi_path'];
			$strXmlFile = CUtilConsole::genYamdiCutFileName( $_intSn , $v['tcvc_tuid'] , $v['tcvc_id'] , true );
			
			$strCmd .= "echo 'begin {$strInputFile}'\n";
			$strCmd .= "rm -rf {$strOutputFile}\n"; 
			$strCmd .= "yamdi -i $strInputFile -o {$strOutputFile} -x $strXmlFile\n"; 
			$strCmd .= "echo 'end.'\n\n";
			
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
		$aryDataList = $this->findAll( "tcvc_status = 1 AND tcvc_kf_status = 0" );
		if( empty( $aryDataList ) )
		{
			CUtilConsole::outputStr( "table {$_intSn} no data." );
			return true;
		}
		foreach ( $aryDataList as $itemTcvc )
		{
			//加载xml文件
			$xmlFileName = CUtilConsole::genyamdiCutFileName( $_intSn , $itemTcvc['tcvc_tuid'] , $itemTcvc['tcvc_id'] );
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
				return false;
			}
			//print_r($xml);
			
			//将xml文件里的结果写入到数据库
			$aryData = array();
			$aryData['tcvc_kf_status'] = 1;
			$aryData['tcvc_time'] = floatval($xml->duration);
			$aryData['tcvc_size'] = floatval($xml->datasize);
			$aryData['tcvc_kf_time'] = time();
			$aryValues = (array)$xml->keyframes->times;
			$aryData['tcvc_frames'] = implode( ',' , $aryValues['value'] );
			if( empty( $aryData['tcvc_frames'] ) )
			{
				CUtilConsole::outputStr( "{$xmlFileName},has no keyframes." );
				continue;
			}
			
			//关键帧状态更新为已加入关键帧
			$this->updateByCondition( $aryData , "tcvc_id = '{$itemTcvc['tcvc_id']}'" );
		}
	}	
	
//end class
}