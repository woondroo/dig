<?php
/**
 * 教师模块API静态方法
 * 
 * @author wengebin
 * @date 2013-09-17
 */
class UtilApi
{
	/**
	 * 检查性版本
	 *
	 * @param string $_strVersion 当前版本号
	 * @return array
	 * 			<pre>
	 * 					return array( 'ISOK'=>bool,'DATA'=>array(),'ERROR'=>'错误号' );
	 * 			</pre>
	 */
	public static function callCheckNewVersion( $_strVersion = '' )
	{
		$aryData = array();

		// cur version
		$aryData['version'] = $_strVersion;

		return CApi::callApi( MAIN_DOMAIN."/checkversion" , $aryData , MAIN_DOMAIN_KEY , true );
	}
	
	//end class
}
