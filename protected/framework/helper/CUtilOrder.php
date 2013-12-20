<?php
/**
 * 订单相关常用的静态方法
 *
 * @author zhouyang
 * @date 2013-10-12
 */
class CUtilOrder
{
	/**教程服务类型 - 终身*/
	const TUTORIALS_SERVICES_LIFELONG = 1;
	/**教程服务类型 - 包年*/
	const TUTORIALS_SERVICES_YEAR = 2;
	/**教程服务类型 - 包季度*/
	const TUTORIALS_SERVICES_QUARTERLY = 4;	
	/**教程服务类型 - 包月*/
	const TUTORIALS_SERVICES_MONTH = 5;
	
	/**订单类型-教程服务*/
	const ORDER_TYPE_TUTORIALS_SERVICE = 1;
	/**订单类型-教程*/
	const ORDER_TYPE_TUTORIALS = 2;
	/**订单类型-充值*/
	const ORDER_TYPE_RECHARGE = 3;
	
	/**虚拟订单状态-已关闭*/
	const VIRTURAL_ORDER_STATUS_CLOSED = 0;
	/**虚拟订单状态-待付款*/
	const VIRTURAL_ORDER_STATUS_PAYING = 1;
	/**虚拟订单状态-交易完毕*/
	const VIRTURAL_ORDER_STATUS_FINISH = 2;
	
	/**支付状态-未支付*/
	const PAY_STATUS_NO = 0;
	/**支付状态-已支付*/
	const PAY_STATUS_YES = 1;
	
	/**订单类型-终身订单*/
	const ORDER_TYPE_ALL = 1;
	/**订单类型-包年订单*/
	const ORDER_TYPE_YEAR = 2;
	/**订单类型-包季度订单*/
	const ORDER_TYPE_QUARTERLY = 4;
	/**订单类型-包月订单*/
	const ORDER_TYPE_MONTH = 5;
	
	/**支付方式-支付宝*/
	const ORDER_PAY_ZHIFUBAO = 1;
	/**支付方式-财付通*/
	const ORDER_PAY_CAIFUTONG = 2;
	/**支付方式-网银*/
	const ORDER_PAY_WANGYIN = 3;
	
	/**
	 *支付方式
	 *
	 * @param int $_intPayTypeId
	 * @return array|string
	 */
	public static function getPayType( $_intPayTypeId = 999){
		$aryData = array( 
							self::ORDER_PAY_ZHIFUBAO=>'支付宝' ,
							self::ORDER_PAY_CAIFUTONG =>'财付通' ,
							self::ORDER_PAY_WANGYIN =>'网银' ,
					);
		if( is_null( $_intPayTypeId ) )
			return $aryData;
		else
			return isset( $aryData[$_intPayTypeId] ) ? $aryData[$_intPayTypeId] : '-';
	}
	
	/**
	 * 根据订单类型ID生成类型
	 *
	 * @param int $_intOrderTypeId
	 * @return array|string
	 */
	public static function getOrderType( $_intOrderTypeId = 999){
		$aryData = array( 
							self::ORDER_TYPE_ALL=>'终身订单' ,
							self::ORDER_TYPE_YEAR =>'包年订单' ,
							self::ORDER_TYPE_QUARTERLY =>'包季度订单' ,
							self::ORDER_TYPE_MONTH =>'包月订单' ,
					);
		if( is_null( $_intOrderTypeId ) )
			return $aryData;
		else
			return isset( $aryData[$_intOrderTypeId] ) ? $aryData[$_intOrderTypeId] : '-';
	}
	
	/**
	 * 生成订单号
	 *
	 * @param int $_intUid			会员ID
	 * @param int $_intOrderType	订单类型
	 * @return string
	 */
	public static function generateSn( $_intUid = 0 , $_intOrderType = null )
	{
		mt_srand((double)microtime() * 1000000);
		$sn1 = 0;
		$sn2 = UtilWww::md5FindNum( $_intUid )%100;
		$sn3 = $_intOrderType;
		$sn4 = date('YmdHis');
		$sn5 = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
		
		if( in_array( $_intOrderType , array( self::ORDER_TYPE_TUTORIALS , self::ORDER_TYPE_RECHARGE  ) ) )
			$sn1 = $_intUid%10;
		return "{$sn1}_{$sn2}_{$sn3}_{$sn4}{$sn5}";
	}
	
	/**
	 * 解析订单号,返回相应的库ID，表ID
	 * [库ID]-[表ID]-[其它]
	 *
	 * @param string $_strOrderSn	订单号
	 * @return array
	 */
	public static function parseOrderSn( $_strOrderSn = "" )
	{
		$arySn = explode( '_' , $_strOrderSn );
		if( count( $arySn ) != 4 )
			throw new CModelException( "订单号不存在" );
		return $arySn;
	}
	
	/**
	 * 根据类型获取过期时间戳
	 *
	 * @param int $_intTsType 类型
	 */
	public static function getPeriodTimestamp( $_intTsType = null )
	{
		/*if( !in_array( $_intTsType , array_keys( CUtil::getTutorialServices(null) ) ) )
			throw new CModelException( "参数错误." );
		switch ( $_intTsType )
		{
			case CUtil::TUTORIALS_SERVICES_LIFELONG:
				return 0;
				break;
			case CUtil::TUTORIALS_SERVICES_YEAR:
				return 1;
		}*/
	}
	
	/**
	 * 获取订单状态
	 *
	 * @param int $_intOrderStatus	订单状态
	 * @return array|string
	 */
	public function getVitrualOrderStatus( $_intOrderStatus = 999 )
	{
		$aryData = array( 
							self::VIRTURAL_ORDER_STATUS_PAYING=>'待付款' ,
							self::VIRTURAL_ORDER_STATUS_FINISH =>'交易完毕' ,
							self::VIRTURAL_ORDER_STATUS_CLOSED =>'已关闭' ,
					);
		if( is_null( $_intOrderStatus ) )
			return $aryData;
		else
			return isset( $aryData[$_intOrderStatus] ) ? $aryData[$_intOrderStatus] : '-';
	}
	
	/**
	 * 获取支付状态
	 *
	 * @param int $_intPsStatus		支付状态
	 * @return array|string
	 */
	public function getPayStatus( $_intPsStatus = 999 )
	{
		$aryData = array( 
							self::PAY_STATUS_NO=>'未支付' ,
							self::PAY_STATUS_YES=>'已支付' ,
					);
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '-';
	}
	
	/** 获取订单类型
	 *
	 * @param int $_intOrderTypeId
	 * @return array|string
	 */
	public static function getVirtualOrderType( $_intOrderTypeId = 999)
	{
		$aryData = array( 
							self::TUTORIALS_SERVICES_LIFELONG=>'终身订单' ,
							self::TUTORIALS_SERVICES_YEAR =>'包年订单' ,
							self::TUTORIALS_SERVICES_QUARTERLY =>'包季度订单' ,
							self::TUTORIALS_SERVICES_MONTH =>'包月订单' ,
					);
		if( is_null( $_intOrderTypeId ) )
			return $aryData;
		else
			return isset( $aryData[$_intOrderTypeId] ) ? $aryData[$_intOrderTypeId] : '-';
	}
	
	/**
	 * 获取有效天数
	 *
	 * @param int $_intVal 
	 * @return array|string
	 */
	public static function getVirtualOrderPeriodDay( $_intVal = 999 )
	{
		$aryData = array( 
							'0'=>'无限制' ,
							'365'=>'365天' ,
							'90'=>'90天' ,
							'30'=>'30天' ,
					);
		if( is_null( $_intOrderTypeId ) )
			return $aryData;
		else
			return isset( $aryData[$_intOrderTypeId] ) ? $aryData[$_intOrderTypeId] : '-';
	}
	
	/**
	 * 获取充值金额
	 * 
	 * @return array
	 *
	 */
	public static function getRechargeMoney()
	{
		return array(
						'50'=>'￥50元',
						'100'=>'￥100元',
						'200'=>'￥200元',
						'500'=>'￥500元',
					);
		
	}
	
	/**
	 * 获取教程服务相关的图片
	 * 
	 * @param int $_intV 
	 * @return array|string
	 */
	public static function getTutorialsServiceImg( $_intV = 999 )
	{
		$aryData = array(
							self::TUTORIALS_SERVICES_LIFELONG => '/images/baolifelong.jpg',//终身
							self::TUTORIALS_SERVICES_YEAR => '/images/baonian.jpg',//包年
							self::TUTORIALS_SERVICES_QUARTERLY => '/images/baoji.jpg',//包季
							self::TUTORIALS_SERVICES_MONTH => '/images/baoyue.jpg',//包月
						);
		if( is_null( $_intV ) )
			return $aryData;
		else
			return isset( $aryData[$_intV] ) ? $aryData[$_intV] : '';
	}
	
// end class
}