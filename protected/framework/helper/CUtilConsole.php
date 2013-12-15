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