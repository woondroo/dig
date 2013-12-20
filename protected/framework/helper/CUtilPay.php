<?php
/**
 * 支付相关配置信息
 * 
 * @author zhouyang 55090127@qq.com
 * @date 2013-10-21
 */
class CUtilPay
{
	/**支付类型-支付宝*/
	const PAY_TYPE_ALIPAY = 1;
	/**支付类型-财富通*/
	const PAY_TYPE_TENPAY = 2;
	/**支付类型-财富通-网银*/
	const PAY_TYPE_TENPAY_BANK = 3;
	/**支付类型-用户余额*/
	const PAY_TYPE_USER_MONEY = 4;
	
	/**
	 * 获取财富通，网银直联样式配置
	 *
	 * @param string $_bankType		银行
	 * @return unknown
	 */
	public static function getTenpayBankList( $_intBankId = 999 )
	{
		$aryBankList = array(
			'1002'=>array('id'=>'1002','name'=>'中国工商银行','icon'=>'/images/payment/icon/10.gif'),
			'1081'=>array('id'=>'1081','name'=>'中国建设银行','icon'=>'/images/payment/icon/2.gif'),
			'1001'=>array('id'=>'1001','name'=>'招商银行','icon'=>'/images/payment/icon/17.gif'),
			'1005'=>array('id'=>'1005','name'=>'中国农业银行','icon'=>'/images/payment/icon/1.gif'),
			'1026'=>array('id'=>'1026','name'=>'中国银行','icon'=>'/images/payment/icon/19.gif'),
			'1004'=>array('id'=>'1004','name'=>'上海浦东发展银行','icon'=>'/images/payment/icon/9.gif'),
			'1027'=>array('id'=>'1027','name'=>'广东发展银行','icon'=>'/images/payment/icon/6.gif'),
			'1022'=>array('id'=>'1022','name'=>'中国光大银行','icon'=>'/images/payment/icon/3.gif'),
			'1006'=>array('id'=>'1006','name'=>'中国民生银行','icon'=>'/images/payment/icon/4.gif'),
			'1021'=>array('id'=>'1021','name'=>'中信银行','icon'=>'/images/payment/icon/7.gif'),
			'1009'=>array('id'=>'1009','name'=>'兴业银行','icon'=>'/images/payment/icon/27.gif'),
			'1010'=>array('id'=>'1010','name'=>'平安银行','icon'=>'/images/payment/icon/18.gif'),
			'1008'=>array('id'=>'1008','name'=>'深圳发展银行','icon'=>'/images/payment/icon/8.gif'),
			'1020'=>array('id'=>'1020','name'=>'交通银行','icon'=>'/images/payment/icon/5.gif'),
			'1032'=>array('id'=>'1032','name'=>'北京银行','icon'=>'/images/payment/icon/11.gif'),
		);
		
		if( is_null( $_intBankId ) ) 
			return $aryBankList;
		else
			return isset($aryBankList[$_intBankId]) ? $aryBankList[$_intBankId] : array();
	}
	
//end class	
}