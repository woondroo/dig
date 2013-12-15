<?php
/**
 * Base Controller
 * 
 * 
 * @author samson.zhou
 * @date 2013-08-28
 */
class BaseController extends CController
{
	/**
	 * init
	 * 
	 */
	public function init()
	{
		parent::init();
		CUtilConsole::outputStr( '###########################################################################################################################' );
	}
		
//end class	
}
