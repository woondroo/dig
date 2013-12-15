<?php
/**
 * Base Controller
 * 
 * @author wengebin
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
