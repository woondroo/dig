<?php echo "\n";?>
*****************************403 ERROR*************************************************
<?php if( NBT_DEBUG ):?>
	<?php echo "\n".$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine().')'."\n";?>
	<?php echo "\n".$exception->getTraceAsString()."\n";?>
<?php else:?>	
	<?php echo "\nYou have no permission to access this page.";?>
<?php endif;?>
****************************************************************************************