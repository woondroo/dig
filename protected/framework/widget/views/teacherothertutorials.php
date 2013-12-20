<?php
if ( count( $this->_aryData ) > 0 ) 
{
	foreach ( $this->_aryData as $tutorial )
	{
		$tutorialUrlParams = array( 'tuid'=>$tutorial['tu_id'] );
		$tutorialInfoUrl = Nbt::app()->createUrl( 'tutorial/chapters' , $tutorialUrlParams );
?>
<a href="<?php echo $tutorialInfoUrl ?>"><?php echo $tutorial['tu_title'] ?></a><br/>
<?php
	}
}
?>
