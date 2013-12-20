<?php
if ( count( $this->_aryData ) )
{
	foreach ( $this->_aryData as $u )
	{
?>
	<div class="user_item">
		<a class="user_item_head_img" href="#">
			<img onerror="javascript:this.src='../images/touxiang.jpg'" src="<?php echo CImage::teacherUrl( $u['ua_id'] ); ?>" />
		</a>
		<div class="user_item_name">
			<div><?php echo $u['data']['ui_nickname'];?></div>
		</div>
	</div>
<?php
	}
}
?>
