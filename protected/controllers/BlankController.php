<?php
/**
 * Blank Controller
 * 
 * @author wengebin
 * @date 2013-12-15
 */
class BlankController extends BaseController
{
	/**
	 * init
	 */
	public function init()
	{
		parent::init();		
	}
	
	/**
	 * 测试
	 */
	public function actionTest()
	{
		//输出字符
		CUtilConsole::outputStr( '00000' );

		//输出一维数组
		$aryRow = array(
							'name'=>'wengebin',
							'sex'=>'男',
							'age'=>23
						);
		CUtilConsole::outputRow( $aryRow );

		//输出二维数组
		$aryRowSets = array();
		$aryRowSets[0] = array( 'name'=>'wengebin1',	'sex'=>'男',	'age'=>23);
		$aryRowSets[1] = array( 'name'=>'wengebin2',	'sex'=>'女',	'age'=>23);
		$aryRowSets[2] = array( 'name'=>'wengebin3',	'sex'=>'女',	'age'=>23);
		
		CUtilConsole::outputRowsets( $aryRowSets );
		
		CUtilConsole::outputArray( $aryRowSets );
		
		CUtilConsole::vardumpVar( $aryRowSets );
		
		throw new CModelException( '404 error!' );
	}

//end class	
}
