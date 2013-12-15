<?php
/**
 * CString class file.
 * 
 * 
 * 
 * @author samson.zhou <samson.zhou@newbiiz.com>
 * @package framework
 * @date 2010-09-16
 */
class CString
{
	/**
	 * 密码加密
	 *
	 * @param string $_strPwd 用户密码
	 * @return string
	 */
	public static function encodeMemberPassword( $_strPwd = '' )
	{
		return md5( "member-admin-".$_strPwd );
	}
	
	/**
	 * 加密方法
	 *
	 * @param String $_v 要加密的内容
	 * @param String $_key 密钥
	 * @return string
	 */
	public static function encode( $_v, $_key = '' )
	{
		if( empty( $_key ) )
			throw new CException( NBT_DEBUG ? 'CString->encode() 未传递参数$_key' : '系统错误' );
		$tempEncryptKey = md5(rand(0, 32000));
		$tempEncryptKeyLen = strlen($tempEncryptKey);
		$tempTxtLen = strlen($_v);
		$tempCtl = 0;
		$tempStr = '';
		for ($i=0; $i<$tempTxtLen; $i++)
		{
			if ($tempCtl == $tempEncryptKeyLen)
			{
				$tempCtl = 0;
			}
			$tempKey = substr($tempEncryptKey, $tempCtl, 1);
			$tempStr .= $tempKey.(substr($_v, $i, 1) ^ $tempKey);
			$tempCtl++;
		}
		return urlencode(base64_encode(self::DoOp($tempStr, $_key)));
	}

	/**
	 * 解密
	 *
	 * @param String $_v 要解密的内容
	 * @param String $_key 密钥
	 * @return string
	 */
	public static function decode( $_v, $_key = '' )
	{
		if( empty( $_key ) )
			throw new CException( NBT_DEBUG ? 'CString->encode() 未传递参数$_key' : '系统错误' );
		$_v = urldecode(self::doOp(base64_decode($_v), $_key));
		$tempTxtLen = strlen($_v);
		$tempStr = '';
		for ($i=0; $i<$tempTxtLen; $i++)
		{
			$tempKey = substr($_v, $i, 1);
			$i++;
			$tempStr .= (substr($_v, $i, 1) ^ $tempKey);
		}
		return $tempStr;
	}

	/**
	 * 密码运算
	 * @param $strTxt 要运算的内容
	 * @param $strKey 运算密钥
	 * @return string 运算后的结果
	 */
	public static function doOp( $strTxt, $strKey )
	{
		$strKey = md5( $strKey );
		$tempTxtLen = strlen( $strTxt );
		$tempKeyLen = strlen( $strKey );
		$tempCtl = 0;
		$tempStr = '';
		for( $i=0; $i<$tempTxtLen; $i++ )
		{
			if( $tempCtl == $tempKeyLen )
			{
				$tempCtl = 0;
			}
			$tempStr .= substr( $strTxt , $i , 1 ) ^ substr( $strKey , $tempCtl , 1 );
			$tempCtl++;
		}
		return $tempStr;
	}
	
//end class
}
