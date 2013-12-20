<?php
if ( count( $this->_aryLevels ) )
{
	// 获得当前选中的分类
	$intActiveLevel = empty( $this->_aryParams['tcid'] ) ? 0 : intval( $this->_aryParams['tcid'] );

	foreach ( $this->_aryLevels as $val=>$levelName )
	{
		// 合并参数
		$aryParams = array_merge( $this->_aryParams , array( 'lid'=>$val ) );
		$strUrl = Nbt::app()->createUrl( $this->_strTargetPage , $aryParams );
?>
	<a class="level_menu<?php echo $intActiveLevel === intval( $val ) ? ' activeLevel' : '' ; ?>" href="<?php echo $strUrl; ?>">
		<?php echo $levelName; ?>
	</a>
<?php
	}
}
?>
