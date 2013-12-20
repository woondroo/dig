<?php
/**
 * 通用的功能
 *
 */
class CUtil
{
	/**性别-男*/
	const SEX_MAN = 1;
	/**性别-女*/
	const SEX_WOMEN = 2;
	
	/**老师帐户状态-禁用-*/
	const TEACHER_ACCOUNT_STATUS_DISABLED = 0;
	/**老师帐户状态-启用-*/
	const TEACHER_ACCOUNT_STATUS_ENABLED = 1;

	/**老师帐户状态-未删除-*/
	const TEACHER_DELETE_STATUS_USEABLE = 0;
	/**老师帐户状态-已删除-*/
	const TEACHER_DELETE_STATUS_DELETED = 1;
	
	/**教程状态-录制中*/
	const TUTORIALS_STATUS_RECORDING = 0;
	/**教程状态-已完成*/
	const TUTORIALS_STATUS_COMPLETED = 1;

	/**教程发布状态-未发布*/
	const TUTORIALS_STATUS_UNPUBLISH = 0;
	/**教程发布状态-已发布*/
	const TUTORIALS_STATUS_PUBLISHED = 1;

	/**章节同步状态-未同步*/
	const TUTORIALS_CHAPTER_SYNC_NO = 0;
	/**章节同步状态-已同步*/
	const TUTORIALS_CHAPTER_SYNC_YES = 1;
	
	/**教程章节状态-不通过**/
	const TUTORIALS_CHAPTER_STATUS_NO = -1;
	/**教程章节状态-未提交审核**/
	const TUTORIALS_CHAPTER_STATUS_INIT = 0;
	/**教程章节状态-待审核**/
	const TUTORIALS_CHAPTER_STATUS_SUBMIT = 1;
	/**教程章节状态-审核通过**/
	const TUTORIALS_CHAPTER_STATUS_YES = 2;
	
	/**上传方式-WEB上传*/
	const UPLOAD_WAY_WEB = 0;
	/**上传方式-FTP上传*/
	const UPLOAD_WAY_FTP = 1;

	/**教程类别-初级*/
	const TUTORIALS_LEVL_ONE = 1;
	/**教程类别-中级*/
	const TUTORIALS_LEVL_TWO = 2;	
	/**教程类别-高级*/
	const TUTORIALS_LEVL_THREE = 3;	
	/**教程类别-超高级*/
	const TUTORIALS_LEVL_FOUR = 4;
	
	/**网站会员状态-冻结*/
	const USER_ACCOUNT_STATUS_DISABLED = 0;
	/**网站会员状态-正常*/
	const USER_ACCOUNT_STATUS_ENABLED = 1;
	
	
	
	/**
	 * 性别
	 *
	 */
	public static function getSex( $_intV = 9999 )
	{
		$aryData = array( self::SEX_MAN=>'男' , self::SEX_WOMEN=>'女' );
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/**
	 * 获取教师等级
	 *
	 * @param int $_intV
	 */
	public static function getTeacherLevel( $_intV = 999 )
	{
		$aryData = array( 1=>'A' , 2=>'B' , 3=>'C' ,4=>'D');
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}

	/**
	 * 教程状态图标
	 */
	public static function getTutorialStateClass( $_intV = 9999 )
	{
		$aryData = array( self::TUTORIALS_STATUS_RECORDING=>'table_arr05' , 
				self::TEACHER_ACCOUNT_STATUS_ENABLED=>'table_arr03' );

		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}

	/**
	 * 章节状态图标
	 */
	public static function getTutorialChapterStateClass( $_intV = 9999 )
	{
		$aryData = array( self::TUTORIALS_CHAPTER_STATUS_INIT=>'table_arr06' , 
				self::TUTORIALS_CHAPTER_STATUS_SUBMIT=>'table_arr07' , 
				self::TUTORIALS_CHAPTER_STATUS_YES=>'table_arr08' , 
				self::TUTORIALS_CHAPTER_STATUS_NO=>'table_arr09' );

		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/**
	 * 老师帐户状态
	 *
	 */
	public static function getTeacherAccountStatus( $_intV = 999 )
	{
		$aryData = array( self::TEACHER_ACCOUNT_STATUS_DISABLED=>'禁用' , self::TUTORIALS_STATUS_COMPLETED =>'启用' );
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/**
	 * 教程状态
	 *
	 */
	public static function getTutorialsStatus( $_intV = 9999 )
	{
		$aryData = array( self::TUTORIALS_STATUS_RECORDING=>'录制中' , self::TUTORIALS_STATUS_COMPLETED =>'已完成' );
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	
	/**
	 * 教程章节状态
	 *
	 */
	public static function getTutorialsChapterStatus( $_intV = 9999 )
	{
		$aryData = array( 
							self::TUTORIALS_CHAPTER_STATUS_NO=>'不通过' ,
							self::TUTORIALS_CHAPTER_STATUS_INIT =>'未提交审核' ,
							self::TUTORIALS_CHAPTER_STATUS_SUBMIT =>'待审核' ,
							self::TUTORIALS_CHAPTER_STATUS_YES =>'通过' 
					);
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	/**
	 * @author  zhaojingyun
	 * 查询的教程级别
	 * @return array()
	 */
	public static function getTurorialsLevel( $_intV = 9999 )
	{
		$aryData = array( 
							self::TUTORIALS_LEVL_ONE =>'初级' ,
							self::TUTORIALS_LEVL_TWO =>'中级' ,
							self::TUTORIALS_LEVL_THREE =>'高级' ,
							self::TUTORIALS_LEVL_FOUR =>'超高级' 
					);
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/**
	 * 获取教程服务类型
	 *
	 * @param int $_intV	教程ID
	 * @return array|string
	 */
	public function getTutorialServices( $_intV = 9999 )
	{
		$aryData = array( 
							self::TUTORIALS_SERVICES_LIFELONG =>'终身有效订单' ,
							self::TUTORIALS_SERVICES_YEAR =>'包年订单' ,
							self::TUTORIALS_SERVICES_MONTH =>'包月订单' ,
							self::TUTORIALS_SERVICES_QUARTERLY =>'包季度订单' ,
					);
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/**
	 * 循环并创建目录
	 */
	public static function createDir( $path )
	{
		if (!file_exists($path)) {
			self::createDir( dirname($path) );
			@mkdir($path, 0755);
		}
	}

	/**
	 * 时间戳过期检查
	 *
	 * @params int $_intTimestamp	客户端传递的时间戳
	 * @params int $_intOverdueTime	过期时间间隔
	 */
	public static function checkTimpStampOverdue( $_intTimestamp = 0 , $_intOverdueTime = 0 )
	{
		if ( empty($_intTimestamp) )
			return false;

		if ( empty($_intOverdueTime) )
			return false;

		if ( time() - $_intTimestamp > $_intOverdueTime )
			return false;

		return true;
	}

	/**
	 * 判断字符串中是否有中文
	 *
	 * @params string $_str 字符串
	 * @return bool
	 */
	public static function isContainChinese( $_str )
	{
		if( preg_match( '/([\x80-\xFE][\x40-\x7E\x80-\xFE])+/' , $_str ) )
			return true;
		else
			return false;
	}

	/**
	 * 判断文件是否是RAR文件
	 *
	 * @params string $_strFileName 文件名
	 * @return bool
	 */
	public static function isRar( $_strFileName )
	{
		if ( empty( $_strFileName ) )
			return false;

		// 分割文件名，并获得后缀名
		$splits = explode('.', $_strFileName);
		$suffix = $splits[ count($splits) - 1 ];

		// 判断是否是 rar 文件
		if( strtoupper($suffix) === 'RAR' )
			return true;
		else
			return false;
	}
	
	/**
	 * 根据随机数判断是否需要执行某一操作。
	 *
	 * @return false;
	 */
	public static function isExecuteByRandom()
	{
		mt_srand((double)microtime() * 1000000);
		$intNumber = mt_rand(1, 9999);
		if( $intNumber%100 == 99 )
			return true;
		return false;
	}
	
	/**
	 * 获得用户的真实IP地址
	 *
	 * @access  public
	 * @return  string
	 */
	public static function realIp()
	{
	    if (isset($_SERVER))
	    {
	        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        {
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	
	            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
	            foreach ($arr AS $ip)
	            {
	                $ip = trim($ip);
	
	                if ($ip != 'unknown')
	                {
	                    $realip = $ip;
	
	                    break;
	                }
	            }
	        }
	        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
	        {
	            $realip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	        else
	        {
	            if (isset($_SERVER['REMOTE_ADDR']))
	            {
	                $realip = $_SERVER['REMOTE_ADDR'];
	            }
	            else
	            {
	                $realip = '0.0.0.0';
	            }
	        }
	    }
	    else
	    {
	        if (getenv('HTTP_X_FORWARDED_FOR'))
	        {
	            $realip = getenv('HTTP_X_FORWARDED_FOR');
	        }
	        elseif (getenv('HTTP_CLIENT_IP'))
	        {
	            $realip = getenv('HTTP_CLIENT_IP');
	        }
	        else
	        {
	            $realip = getenv('REMOTE_ADDR');
	        }
	    }
	
	    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
	
	    return $realip;
	}

	/**
	 * 计算时间距离现在多久
	 *
	 * @param int $_timestamp 时间戳
	 * @return string
	 */
	public function tranTime( $_timestamp ){
		$rtime = date("Y年m月d日 H:i:s", $_timestamp);
		$rtime = str_replace( date('Y年') , '' , $rtime );
		$htime = date("H:i:s", $_timestamp);

		$now = time();
		// 时间戳距离现在的时间
		$time = $now - $_timestamp;
		// 昨天凌晨时间
		$timey = strtotime(date('Y-m-d')) - 24*3600;
		// 前天凌晨时间
		$timeby = $timey - 24*3600;

		if ($time < 60)
		{
			$str = '刚刚';
		}
		elseif ($time < 60 * 60)
		{
			$min = floor($time/60);
			$str = $min.'分钟前';
		}
		elseif ($time < 60 * 60 * 24)
		{
			$h = floor($time/(60*60));
			$str = $h.'小时前';
		}
		elseif ($timey < $_timestamp)
		{
			$str = '昨天 '.$htime;
		}
		elseif ($timeby < $_timestamp)
		{
			$str = '前天 '.$htime;
		}
		else
		{
			$str = $rtime;
		}

		return $str;
	}
//end class
}
