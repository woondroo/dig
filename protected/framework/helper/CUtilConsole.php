<?php
/**
 * console公共类库
 * 
 * @author zhouyang
 * @date 2013-11-06
 */
class CUtilConsole
{
	/**
	 * 生成shell文件
	 *
	 * @param string $_strFile		shell文件名
	 * @param shell	$_strContent	shell内容
	 */
	public static function genShShellFile( $_strFile = "" , $_strContent = "" )
	{
		//前面要增加shell的前缀
		$strContent = "#!/bin/bash\n\n";
		$strContent .= $_strContent;
		$boolRes = @file_put_contents( $_strFile , $strContent );

		if( !$boolRes )
			return false;
		chmod( $_strFile , 0777 );
	}

	/**
	 * 执行shell文件
	 *
	 * @param string $_strFile
	 */
	public static function execShShellFile( $_strFile = "" )
	{
		exec( "{$_strFile}\n\n" );
	}
	
	/**
	 * 输出字符串
	 *
	 * @param string $_strV
	 */
	public static function outputStr( $_strV = "" )
	{
		echo "\n{$_strV}\n";
	}
	
	/**
	 * 输出一维数组
	 *
	 * @param array $_aryV
	 */
	public static function outputRow( $_aryV = array() )
	{
		echo "\n------------------------------------------------------\n";
		//echo "\nArray(";
		foreach ( $_aryV as $k=>$v )
		{
			echo "\n    {$k}=>{$v}";
		}
		//echo "\n)";
		echo "\n-------------------------------------------------------\n";
	}
	
	/**
	 * 输出二维数组
	 *
	 * @param array $_aryV
	 */
	public static function outputRowsets( $_aryV = array() )
	{
		echo "\n------------------------------------------------------\n";
		//echo "\nArray(";
		foreach ( $_aryV as $k=>$v )
		{
			echo "\n    {$k}:";
			foreach ( $v as $kk=>$vv )
			{
				echo "\n          {$kk}=>{$vv}";
			}
		}
		//echo "\n)";
		echo "\n-------------------------------------------------------\n";
	}
	
	/**
	 * 输出数组
	 *
	 * @param array $_aryV
	 */
	public static function outputArray( $_aryV = array() )
	{
		echo "\n------------------------------------------------------\n";
		print_r( $_aryV );
		echo "\n-------------------------------------------------------\n";
	}
	
	/**
	 * 输出数组
	 *
	 * @param array $_aryV
	 */
	public static function vardumpVar( $_mixVar = null )
	{
		echo "\n------------------------------------------------------\n";
		var_dump( $_mixVar );
		echo "\n-------------------------------------------------------\n";
	}
		
//end class
}
