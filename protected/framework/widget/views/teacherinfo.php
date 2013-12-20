<img onerror="javascript:this.src='../images/touxiang.jpg'" src="<?php echo CImage::teacherUrl( $this->_aryData['te_id'] ); ?>" />
<div class="teacher_info_more">
	<div>
		<?php echo $this->_aryData['te_truename'] ?>，<?php echo CUtil::getSex($this->_aryData['te_sex']) ?>，收藏<?php echo $this->_aryData['tes_num_favorites'] ?>
	</div>
	<div>
		职业：<?php echo $this->_aryData['te_job'] ?>
	</div>
	<div>
		擅长领域：<?php echo $this->_aryData['te_scopes'] ?>
	</div>
	<div>
		个人简历：<?php echo $this->_aryData['te_resume'] ?>
	</div>
</div>
