<?php
class UtilMsg
{
	
	/**
	 * 将提示信息保存到session中
	 *
	 * @param string $_strMsg
	 */
	public function saveTipToSession( $_strMsg )
	{
		$_strMsg = "<img src='../images/arr_wc.png' width='13' height='13' align='absmiddle'>&nbsp;&nbsp;".$_strMsg;
		Nbt::app()->session->set( '_atip' , $_strMsg );
	}
	
	public function getTipFromSession()
	{
		//删除并返当获取到的session值
		return Nbt::app()->session->remove( '_atip' );
	}
	
	/**
	 * 将错误提示信息保存到session中
	 *
	 * @param string $_strMsg
	 */
	public function saveErrorTipToSession( $_strMsg )
	{
		$_strMsg = "<img src='../images/arr_wtg.png' width='13' height='13' align='absmiddle'>&nbsp;&nbsp;".$_strMsg;
		Nbt::app()->session->set( '_atip_error' , $_strMsg );
	}
	
	/**
	 * 获取错误的提示信息
	 *
	 * @return string $_strMsg
	 */
	public function getErrorTipFromSession()
	{
		//删除并返当获取到的session值
		return Nbt::app()->session->remove( '_atip_error' );
	}
//end class	
}
