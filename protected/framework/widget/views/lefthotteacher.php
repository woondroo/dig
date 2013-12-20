<?php
if ( count( $this->aryData ) )
{
	foreach ( $this->aryData as $teacher )
	{
		$aryParams = array( 'teid'=>$teacher['te_id'] );
		$strUrl = Nbt::app()->createUrl( 'teacher/info' , $aryParams );
?>
	<div class="left_teacher_item">
		<a class="left_teacher_head_img" href="<?php echo $strUrl; ?>">
			<img onerror="javascript:this.src='../images/touxiang.jpg'" src="<?php echo CImage::teacherUrl( $teacher['te_id'] ); ?>" />
		</a>
		<div class="left_teacher_mess">
			<div><a href="<?php echo $strUrl; ?>"><?php echo $teacher['te_truename'];?></a></div>
			<div><?php echo $teacher['te_scopes'] ?></div>
		</div>
	</div>
<?php
	}
}
?>
