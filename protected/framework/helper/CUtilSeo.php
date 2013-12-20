<?php
/**
 * seo常量定义及相关函数
 *
 * @author zhouyang<55090127@qq.comj>
 */
class CUtilSeo
{
	/**页面类型-网站首页*/
	const PAGE_TYPE_WWW_INDEX = "www_index";	
	/**页面类型-教程频道首页*/
	const PAGE_TYPE_WWW_TUTORIALS_INDEX = "www_tutorials_index";
	/**页面类型-教程频道列表页*/
	const PAGE_TYPE_WWW_TUTORIALS_LIST = "www_tutorials_list";
	/**页面类型-教程页*/
	const PAGE_TYPE_WWW_TUTORIALS_DETAIL = "www_tutorials_detail";
	/**页面类型-章节页*/
	const PAGE_TYPE_WWW_TUTORIALS_CHAPTER = "www_tutorials_chapters";
	/**页面类型-教师列表页*/
	const PAGE_TYPE_WWW_TEACHER_LIST = "www_teacher_list";
	/**页面类型-教师详情页*/
	const PAGE_TYPE_WWW_TEACHER_DETAIL = "www_teacher_detail";
	/**页面类型-独立页面*/
	const PAGE_TYPE_WWW_PAGE = "www_page";
	
	/**
	 * 获取页面类型
	 *
	 * @param string $_strPageType	页面类型
	 * @return array|string
	 */
	public function getPageType( $_strPageType = "999" )
	{
		$aryData = array( 
							self::PAGE_TYPE_WWW_INDEX=>'网站首页' ,
							self::PAGE_TYPE_WWW_TUTORIALS_INDEX =>'教程频道首页' ,
							self::PAGE_TYPE_WWW_TUTORIALS_LIST =>'教程频道列表' ,
							self::PAGE_TYPE_WWW_TUTORIALS_DETAIL =>'教程页' ,
							self::PAGE_TYPE_WWW_TUTORIALS_CHAPTER =>'章节播放页' ,
							self::PAGE_TYPE_WWW_TEACHER_LIST =>'教师列表页' ,
							self::PAGE_TYPE_WWW_TEACHER_DETAIL =>'教师详情页' ,
							self::PAGE_TYPE_WWW_PAGE =>'独立页面' ,
					);
		if( is_null( $_strPageType ) )
			return $aryData;
		else
			return isset( $aryData[$_strPageType] ) ? $aryData[$_strPageType] : '-';
	}
	
//endclass	
}