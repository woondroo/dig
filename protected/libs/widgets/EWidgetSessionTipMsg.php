<?php
class EWidgetSessionTipMsg extends CWidget 
{
	public function run()
	{
		//success msg
		$msg = UtilMsg::getTipFromSession();
		if( !empty( $msg ) )
		{
			echo "<div class='error errorSuccess'>{$msg}</div>";
		}
		//error msg
		$msg = UtilMsg::getErrorTipFromSession();
		if( !empty( $msg ) )
		{
			echo "<div class='error errorError'>{$msg}</div>";
		}
		return;
	}
	
}
