<?php
/**
 * 通用的功能
 *
 */
class CMemberUtil
{
	/**用户-昵称*/
	//const MEMBER_NICK_NAME = "";
	/**用户-用户名*/
	//const MEMBER_USERNAME = "";
	
	/**性别-保密*/
	const SEX_SECRET = 0;
	/**性别-男*/
	const SEX_MAN = 1;
	/**性别-女*/
	const SEX_WOMEN = 2;
	
	/**
	 * 从事行业
	 *
	 */
	public static function getTrade( $_intV = 9999 )
	{
		$aryData = array( 1=>'在校学生' ,2=>'计算机·网络·技术' , 3=>'经营管理',4=>'娱乐业' ,5=>'文体工作',6=>'销售' , 7=>'医疗卫生',8=>'农林牧渔劳动者' , 9=>'酒店·餐饮·旅游·其他服务',10=>'美术·设计·创意' , 11=>'电子·电器·通信技术',12=>'外出务工人员' , 13=>'贸易·物流·采购·运输',14=>'建筑·房地产·装饰装修·物业管理' , 15=>'财务·审计·统计',16=>'电气·能源·动力' , 17=>'个体经营·商业零售',18=>'军人警察' , 19=>'美容保健',20=>'行政·后勤' , 21=>'教育·培训',22=>'党政机关事业单位工作者·公务员类' , 23=>'市场·公关·咨询·媒介',24=>'技工' , 25=>'工厂生产',26=>'宗教、神职人员' , 27=>'工程师',28=>'新闻出版·文化工作' , 29=>'金融',30=>'人力资源' , 31=>'保险',32=>'法律' , 33=>'翻译',34=>'自由职业者' , 35=>'待业/无业/失业', 36=>'其他' );
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
		
	/**
	 * 用户昵称查找，没有返回用户名
	 *
	 */
	public static function getMemberName( $_intV = 9999 )
	{
		$modelUserinfo =new UserInfoModel();
		$table = $modelUserinfo->setTableNameByUid($_intV);
		$aryResult = array();
		$aryResult = $modelUserinfo->findByPk($_intV);
		if(empty($aryResult['ui_nickname']))
			return $aryResult['ui_username'];
		else 
			return $aryResult['ui_nickname'];
	}

	/**
	 * 性别
	 *
	 */
	public static function getSex( $_intV = 9999 )
	{
		$aryData = array( self::SEX_SECRET=>'保密' ,self::SEX_MAN=>'男' , self::SEX_WOMEN=>'女' );
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/**
	 * 头像临时文件存储的位置
	 * 
	 * @param int $_intUid 		用户ID
	 * @param string $_strExt	扩展名
	 * @return string 头像规范存放目录格式
	 */
	public static function headIconTmpSavePath( $_intUid = 0 , $_strExt = "" )
	{		
		$_intUid = intval( $_intUid );
		$dirId = ceil( $_intUid/1000);
		$filename = "u{$_intUid}{$_strExt}";

		return "/tmpupfile/member/$dirId/{$filename}";
	}

	/**
	 * 上传后裁减后保存的同步位置
	 *
	 * @param int $_intUid	用户ID
	 * @param string $_strSpec	规格名big|middle|small
	 */
	public static function headIconSavePath( $_intUid = 0 , $_strSpec = "" )
	{
		$_intUid = intval( $_intUid );
		$dirId = ceil( $_intUid/1000);
		$filename = "u{$_intUid}-{$_strSpec}.gif";

		return "/member/$dirId/{$filename}";
	} 

	/**
	 * 头像规格
	 *
	 */
	public static function headIconSpec()
	{
		return array( 1=>'big',2=>'middle',3=>'small' );
	}

	/**
	 * 获得密保问题列表
	 *
	 * @params int $_intQuestionID 问题编号
	 * @return string
	 */
	public static function getPasswordQuestions( $_intQuestionID = 9999 )
	{
		$aryData = array(
				0=>'--请选择密保问题--',
				1=>'您母亲的姓名是？',
				2=>'您母亲的生日是？',
				3=>'您父亲的姓名是？',
				4=>'您父亲的生日是？',
				5=>'您配偶的姓名是？',
				6=>'您配偶的生日是？',
				7=>'您的学号（或工号）是？',
				8=>'您高中班主任的名字是？',
				9=>'您初中班主任的名字是？',
				10=>'您小学班主任的名字是？',
				11=>'您最熟悉的童年好友名字是？',
				12=>'您最熟悉的学校宿舍室友名字是？',
				13=>'对您影响对大的人名字是？'
			);

		if( is_null( $_intQuestionID ) )
			return $aryData;
		else
			return isset( $aryData[$_intQuestionID] ) ? $aryData[$_intQuestionID] : '-';
	}

	/**
	 * 加密密保问题验证TOKEN
	 *
	 * @param string $_strA1 答案1
	 * @param string $_strA2 答案2
	 * @param int $_intUid 用户ID
	 * @return string
	 */
	public static function getValidateToken( $_strA1 = '' , $_strA2 = '' , $_intUid = 0 )
	{
		$now = time();
		$token = md5( "{$_strA1}-{$_strA2}-{$_intUid}-{$now}" );
		return $token.'-'.$now;
	}

	/**
	 * 验证密保TOKEN
	 *
	 * @param string $_strA1 答案1
	 * @param string $_strA2 答案2
	 * @param int $_intUid 用户ID
	 * @param string $_strToken TOKEN
	 * @return boolean
	 */
	public static function validateToken( $_strA1 = '' , $_strA2 = '' , $_intUid = 0 , $_strToken = '' )
	{
		$split = explode( '-' , $_strToken );
		$timestamp = intval( $split[1] );

		$now = time();
		// TOKEN 过期检查
		if ( $now - $timestamp > 300 )
			return false;

		// TOKEN 对比
		$token = md5( "{$_strA1}-{$_strA2}-{$_intUid}-{$timestamp}" ).'-'.$timestamp;
		return $token == $_strToken;
	}

//end class
}
