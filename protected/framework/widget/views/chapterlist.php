<?php
if ( count( $this->_aryData ) > 0 ) 
{
	foreach ( $this->_aryData as $chapter )
	{
		$chapterUrlParams = array( 'tuid'=>$chapter['tuc_tuid'] , 'tucid'=>$chapter['tuc_id'] );
		$chapterUrl = Nbt::app()->createUrl( 'chapter/play' , $chapterUrlParams );
?>
<div><a href="<?php echo $chapterUrl ?>"><?php echo $chapter['tuc_title'] ?></a></div>
<?php
	}
}
?>
