<?php echo "\n";?>
*****************************500 ERROR*************************************************
<?php if( NBT_DEBUG ):?>
	<?php echo "\n".$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine().')'."\n";?>
	<?php echo "\n".$exception->getTraceAsString()."\n";?>
<?php else:?>	
	<?php echo "\nServer Error.";?>
<?php endif;?>
****************************************************************************************