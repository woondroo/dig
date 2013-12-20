<?php
if ( count( $this->_aryCategories ) )
{
	// 获得当前选中的分类
	$intActiveCat = empty( $this->_aryParams['tcid'] ) ? 0 : intval( $this->_aryParams['tcid'] );

	foreach ( $this->_aryCategories as $val=>$catName )
	{
		// 合并参数
		$aryParams = array_merge( $this->_aryParams , array( 'tcid'=>$val ) );
		$strUrl = Nbt::app()->createUrl( $this->_strTargetPage , $aryParams );
?>
	<a class="cat_menu<?php echo $intActiveCat === intval( $val ) ? ' activeCat' : '' ; ?>" href="<?php echo $strUrl; ?>">
		<?php echo $catName; ?>
	</a>
<?php
	}
}
?>
