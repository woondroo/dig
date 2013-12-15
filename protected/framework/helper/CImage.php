<?php
/**
 * 图片路径
 *
 */
class CImage
{
	/**
	 * 教师图片路径
	 *
	 */
	public function teacherUrl( $_intTeacherId = 0 )
	{
		//return IMAGE_DOMAIN.'/index.php?teid={$_intTeacherId}';
		return IMAGE_DOMAIN."/teacher/{$_intTeacherId}/head-{$_intTeacherId}.jpg";
	}
	
	/**
	 * 身份证图片路径
	 */
	public function teacherIdCardUrl( $_intTeacherId = 0 , $_intIdCardType = 0 )
	{
		//return IMAGE_DOMAIN.'/index.php?teid={$_intTeacherId}';
		return IMAGE_DOMAIN."/teacher/{$_intTeacherId}/idcard-{$_intTeacherId}-{$_intIdCardType}.jpg";
	}
	
//end class
}