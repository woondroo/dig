<?php
/**
 * console�������
 * 
 * @author zhouyang
 * @date 2013-11-06
 */
class CUtilConsole
{
	/**
	 * ����shell�ļ�
	 *
	 * @param string $_strFile		shell�ļ���
	 * @param shell	$_strContent	shell����
	 */
	public static function genShShellFile( $_strFile = "" , $_strContent = "" )
	{
		//ǰ��Ҫ����shell��ǰ׺
		$strContent = "#!/bin/bash\n\n";
		$strContent .= $_strContent;
		$boolRes = @file_put_contents( $_strFile , $strContent );

		if( !$boolRes )
			return false;
		chmod( $_strFile , 0777 );
	}

	/**
	 * ִ��shell�ļ�
	 *
	 * @param string $_strFile
	 */
	public static function execShShellFile( $_strFile = "" )
	{
		exec( "{$_strFile}\n\n" );
	}
	
	/**
	 * ����ַ���
	 *
	 * @param string $_strV
	 */
	public static function outputStr( $_strV = "" )
	{
		echo "\n{$_strV}\n";
	}
	
	/**
	 * ���һά����
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
	 * �����ά����
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
	 * �������
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
	 * �������
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
