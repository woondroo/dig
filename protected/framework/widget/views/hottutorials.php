<?php
if ( count( $this->aryData ) )
{
	foreach ( $this->aryData as $tutorial )
	{
		$teacherUrlParams = array( 'teid'=>$tutorial['tu_teid'] );
		$teacherInfoUrl = Nbt::app()->createUrl( 'teacher/info' , $teacherUrlParams );

		$tutorialUrlParams = array( 'tuid'=>$tutorial['tu_id'] );
		$tutorialInfoUrl = Nbt::app()->createUrl( 'tutorial/chapters' , $tutorialUrlParams );
?>
	<div class="tutorial_item">
		<a class="tutorial_head_img" href="<?php echo $tutorialInfoUrl; ?>">
			<img onerror="javascript:this.src='../images/touxiang.jpg'" src="<?php echo CImage::tutorialsUrl( $tutorial['tu_id'] ); ?>" />
		</a>
		<div class="tutorial_item_mess">
			<div class="list_tutorial_title">
				<a href="<?php echo $tutorialInfoUrl; ?>"><?php echo $tutorial['tu_title'] ?></a>
			</div>
			<div class="list_tutorial_level">
				<?php echo CUtil::getTurorialsLevel($tutorial['tu_level']) ?>
				&nbsp;
				<a href="<?php echo $teacherInfoUrl; ?>"><?php echo $tutorial['te_truename'] ?></a>
			</div>
			<div class="list_tutorial_statistics">
				点播数：<?php echo $tutorial['ts_num_view'] ?>
				&nbsp;
				评论数：<?php echo $tutorial['ts_num_comment'] ?>
				&nbsp;
				收藏：<?php echo $tutorial['ts_num_favorites'] ?>
			</div>
			<div class="list_tutorial_summary">
				<?php echo $tutorial['tu_summary'] ?>
			</div>
			<div>&nbsp;</div>
		</div>
	</div>
<?php
	}
}
?>
