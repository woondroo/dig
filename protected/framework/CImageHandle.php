<?php
/**
 * 图片缩略图处理
 * 
 * 
 * @author zhouyang
 * @date 2013-11-15
 */
class CImageHandle
{
    public $_strbgColor     = '';
    public $_aryTypeMaping = array(1 => 'image/gif', 2 => 'image/jpeg', 3 => 'image/png');
    public $_strMakeThumbLibType = "gd";

	/**
	 * 构造函数
	 *
	 * @param string $_strMakeThumbLibType	生成缩略图的库(gd,magickwand)
	 * @param string $bgcolor	背景颜色
	 * @return bool
	 */
    public function __construct( $_strMakeThumbLibType = "gd" , $_strBgColor = '' )
    {
       $this->setMakeThumbLibType( $_strMakeThumbLibType );
       $this->setBgColor( $_strBgColor );
    }
    
    /**
     * 设置生成缩略图使用的库
     *
     * @param string $_strMakeThumbLibType	生成缩略图的库(gd,magickwand)
     */
    public function setMakeThumbLibType( $_strMakeThumbLibType = "" )
    {
    	if( !in_array( $_strMakeThumbLibType , array('gd','magickwand') ) )
    		throw new CException( "未正确配置生成缩略图的lib库" );
    	$this->_strMakeThumbLibType = $_strMakeThumbLibType;
    }
    
    /**
     * 设置背景图片
     *
     * @param string $_$this->_strbgColor
     */
    public function setBgColor( $_strbgColor = "" )
    {
		if ( !empty( $_strbgColor ) )
			$this->_strbgColor = $_strbgColor;
		else
			$this->_strbgColor = "#FFFFFF";
    }
    
    /**
     * 生成缩略图
     *
     * @param string $_pathSourceImg		原始图片路径及文件名
     * @param string $_pathThumbImg			缩略图路径及文件名
     * @param int $_intThumbWidth			缩略图宽度
     * @param int $_intThumbHeight			缩略图高度
     * @return 成功返回路径，失败返回文件名
     */
    public function makeThumb( $_pathSourceImg , $_pathThumbImg , $_intThumbWidth = 0 , $_intThumbHeight = 0 )
    {
    	switch ( $this->_strMakeThumbLibType )
    	{
    		case 'gd':
    			return $this->makeThumbByGd( $_pathSourceImg , $_pathThumbImg , $_intThumbWidth , $_intThumbHeight );
    			break;
    		case 'magickwand':
    			return $this->makeThumbByMagickWand( $_pathSourceImg , $_pathThumbImg , $_intThumbWidth , $_intThumbHeight );
    			break;
    		default:
    			throw new CException( "未正确配置生成缩略图的lib库" );
    	}
    }
    
    
    /**
     * 创建图片的缩略图
     *
      * @param string $_pathSourceImg		原始图片路径及文件名
     * @param string $_pathThumbImg			缩略图路径及文件名
     * @param int $_intThumbWidth			缩略图宽度
     * @param int $_intThumbHeight			缩略图高度
     * @return 创建成功 返回true
     */
    public function makeThumbByGd( $_pathSourceImg = "" , $_pathThumbImg = '' , $_intThumbWidth = 0, $_intThumbHeight = 0 )
    {
    	//获取 GD 版本。0 表示没有 GD 库，1 表示 GD 1.x，2 表示 GD 2.x
         $gd = $this->getGdVersion();
         if ($gd == 0)
         	throw new CExecption( "没有安装GD库!" );

        //检查缩略图宽度和高度是否合法
        if ($_intThumbWidth == 0 && $_intThumbHeight == 0)
        	throw new CExecption( "缩略图宽度和高度不合法，不能都为0!" );

        //检查原始文件是否存在及获得原始文件的信息
        $arySourceImgInfo = @getimagesize( $_pathSourceImg );
        if ( empty( $arySourceImgInfo ) )
        	throw new CException( "找不到原始图片!" );

        if ( !$this->checkImgFunction( $arySourceImgInfo[2] ) )
            throw new CException( "系统没有不能支持图片类型：{$this->_aryTypeMaping[$arySourceImgInfo[2]]}" );

        $sourceImgResource = $this->createImageSource($_pathSourceImg, $arySourceImgInfo[2]);        
        if(empty( $sourceImgResource ))
        	throw new CException( "创建原始图片源错误" );

        //原始图片以及缩略图的尺寸比例
        $floSourceImgPercentage  = $arySourceImgInfo[0] / $arySourceImgInfo[1];
        /* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
        if ($_intThumbWidth == 0)
        {
            $_intThumbWidth = $_intThumbHeight * $floSourceImgPercentage;
        }
        if ($_intThumbHeight == 0)
        {
            $_intThumbHeight = $_intThumbWidth / $floSourceImgPercentage;
        }

        /* 创建缩略图的标志符 */
        $thumbImgResource = null;
        if ($gd == 2)
            $thumbImgResource  = imagecreatetruecolor($_intThumbWidth, $_intThumbHeight);
        else
            $thumbImgResource  = imagecreate($_intThumbWidth, $_intThumbHeight);

        $bgcolor = trim( $this->_strbgColor );
        sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
        $clr = imagecolorallocate($thumbImgResource, $red, $green, $blue);
        imagefilledrectangle($thumbImgResource, 0, 0, $_intThumbWidth, $_intThumbHeight, $clr);

        if ($arySourceImgInfo[0] / $_intThumbWidth > $arySourceImgInfo[1] / $_intThumbHeight)
        {
            $lessen_width  = $_intThumbWidth;
            $lessen_height  = $_intThumbWidth / $floSourceImgPercentage;
        }
        else
        {
            /* 原始图片比较高，则以高度为准 */
            $lessen_width  = $_intThumbHeight * $floSourceImgPercentage;
            $lessen_height = $_intThumbHeight;
        }

        $dst_x = ($_intThumbWidth  - $lessen_width)  / 2;
        $dst_y = ($_intThumbHeight - $lessen_height) / 2;

        /* 将原始图片进行缩放处理 */
        if ($gd == 2)
            imagecopyresampled($thumbImgResource, $sourceImgResource, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $arySourceImgInfo[0], $arySourceImgInfo[1]);
        else
            imagecopyresized($thumbImgResource, $sourceImgResource, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $arySourceImgInfo[0], $arySourceImgInfo[1]);

        /* 生成文件 */
        if (function_exists('imagejpeg'))
            imagejpeg($thumbImgResource,$_pathThumbImg);
        elseif (function_exists('imagegif'))
            imagegif($thumbImgResource,$_pathThumbImg);
        elseif (function_exists('imagepng'))
            imagepng($thumbImgResource, $_pathThumbImg);
        else
        	throw new CException( "无法创建缩略图" );

        imagedestroy($thumbImgResource);
        imagedestroy($sourceImgResource);

        //确认文件是否生成
        if (!file_exists( $_pathThumbImg ))
           throw new CException( "生成缩略图失败" );
        
        return true;
    }
    
    
    /**
     * 用Magickwand生成品质高的图片,图片偏大
     *
     * @access  public
     * @param   string      $_pathSourceImg    原始图片的路径
     * @param   int         $_intThumbWidth  缩略图宽度
     * @param   int         $_intThumbHeight 缩略图高度
     * @param   strint      $_pathThumbImg         指定生成图片的目录名
     * 
     * @return  mix         如果成功返回缩略图的路径，失败则返回false
     */
     
    public function makeThumbByMagickWand( $_pathSourceImg, $_pathThumbImg = '', $_intThumbWidth = 0, $_intThumbHeight = 0 )
    {

        //允许图片的格式数组
        $allowed_img=array('gif', 'jpg', 'jpeg', 'pjpeg', 'png', 'x-png');
        /* 检查缩略图宽度和高度是否合法 */
        if ($_intThumbWidth == 0 && $_intThumbHeight == 0)
      		 throw new CExecption( "缩略图宽度和高度不合法，不能都为0!" );

        /* 检查原始文件是否存在及获得原始文件的信息 */
		$src_nmw   = NewMagickWand(); //建立MagickWand资源
		$is_img=MagickReadImage($src_nmw,$_pathSourceImg);  //读取
	    $format =strtolower(MagickGetImageFormat($src_nmw)); //原始格式
		$src_width =MagickGetImageWidth($src_nmw); //原始宽度
		$src_height =MagickGetImageHeight($src_nmw); //原始高度	
		$ratio_w=1.0 * $_intThumbWidth / $src_width;   //宽缩放比例
		$ratio_h=1.0 * $_intThumbHeight / $src_height; //高缩放比例 
		$ratio=1.0;
		
		//背景颜色
		$bgcolor = $this->_strbgColor;
		
        if (!$is_img)
        	throw new CException( "无法创建原始图片源" );
        
       /*检查原始图片类型，允许JPG,PNG,GIF格式生成*/
       if (!in_array($format,$allowed_img))
       		throw new CException( "{$format}为不允许处理的图片类型" );

       //生成图片更大，生成固定高宽，保留全部信息，只缩放，不裁剪, 不足填充空白
		if($ratio_w > 1 && $ratio_h > 1)
		{

			$dst_mkwd=NewMagickWand();
			MagickNewImage($dst_mkwd, $_intThumbWidth, $_intThumbHeight, $bgcolor);
			$dst_x = (int) abs($_intThumbWidth - $src_width) / 2 ;
			$dst_y = (int) abs($_intThumbHeight -$src_height) / 2;
			MagickCompositeImage($dst_mkwd, $src_nmw, MW_OverCompositeOp, $dst_x, $dst_y ) ;	
		}
		else
		{
			$ratio = $ratio_w > $ratio_h ? $ratio_h : $ratio_w;
			$tmp_w = (int)($src_width * $ratio);
			$tmp_h = (int)($src_height * $ratio);
			$dst_mkwd=NewMagickWand();
			MagickResizeImage($src_nmw, $tmp_w, $tmp_h, MW_LanczosFilter, 1.0);
			MagickNewImage($dst_mkwd, $_intThumbWidth, $_intThumbHeight, $bgcolor);
			$dst_x = (int) abs($tmp_w - $_intThumbWidth) / 2 ;
			$dst_y = (int) abs($tmp_h - $_intThumbHeight) / 2;
			MagickCompositeImage($dst_mkwd ,$src_nmw, MW_OverCompositeOp, $dst_x, $dst_y ) ;

		}

		//保存
		MagickSetFormat($dst_mkwd, $format);
		MagickWriteImages($dst_mkwd, $_pathThumbImg, MagickTrue);
		DestroyMagickWand($dst_mkwd);
		DestroyMagickWand($src_nmw);
			
		//确认文件是否生成,返回图片路径,或返回覆盖生成的图片路径
		if (!file_exists($_pathThumbImg))
			throw new CException( "生成缩略图失败" );
		return true;
    }
    
    /**
     * 为图片增加水印
     *
     * @access      public
     * @param       string      filename            原始图片文件名，包含完整路径
     * @param       string      target_file         需要加水印的图片文件名，包含完整路径。如果为空则覆盖源文件
     * @param       string      $watermark          水印完整路径
     * @param       int         $watermark_place    水印位置代码
     * @return      mix         如果成功则返回文件路径，否则返回false
     */
    function addWatermark( $filename , $target_file='', $watermark='', $watermark_place='', $watermark_alpha = 0.65)
    {
        // 是否安装了GD
        $gd = $this->getGdVersion();
       	if ($gd == 0)
         	throw new CExecption( "没有安装GD库!" );

        // 文件是否存在
        if ((!file_exists($filename)) || (!is_file($filename)))
        	throw new CException( "原始文件不存在" );

        /* 如果水印的位置为0，则返回原图 */
        if ($watermark_place == 0 || empty($watermark))
        	return true;

       	$this->validateWaterImage($watermark);
       

        // 获得水印文件以及源文件的信息
        $watermark_info     = @getimagesize($watermark);
        $watermark_handle   = $this->createImageSource($watermark, $watermark_info[2]);

        if (!$watermark_handle)
			throw new CException( "无法创建水印图片源" );
        // 根据文件类型获得原始图片的操作句柄
        $source_info    = @getimagesize($filename);
        $source_handle  = $this->createImageSource($filename, $source_info[2]);
        if (!$source_handle)
         	throw new CException( "无法创建原始图片源" );

        // 根据系统设置获得水印的位置
        switch ($watermark_place)
        {
            case '1':
                $x = 0;
                $y = 0;
                break;
            case '2':
                $x = $source_info[0] - $watermark_info[0];
                $y = 0;
                break;
            case '4':
                $x = 0;
                $y = $source_info[1] - $watermark_info[1];
                break;
            case '5':
                $x = $source_info[0] - $watermark_info[0];
                $y = $source_info[1] - $watermark_info[1];
                break;
            default:
                $x = $source_info[0]/2 - $watermark_info[0]/2;
                $y = $source_info[1]/2 - $watermark_info[1]/2;
        }

        if (strpos(strtolower($watermark_info['mime']), 'png') !== false)
        {
            imageAlphaBlending($watermark_handle, true);
            imagecopy($source_handle, $watermark_handle, $x, $y, 0, 0,$watermark_info[0], $watermark_info[1]);
        }
        else
        {
            imagecopymerge($source_handle, $watermark_handle, $x, $y, 0, 0,$watermark_info[0], $watermark_info[1], $watermark_alpha);
        }
        $target = empty($target_file) ? $filename : $target_file;

        switch ($source_info[2] )
        {
            case 'image/gif':
            case 1:
                imagegif($source_handle,  $target);
                break;

            case 'image/pjpeg':
            case 'image/jpeg':
            case 2:
                imagejpeg($source_handle, $target);
                break;

            case 'image/x-png':
            case 'image/png':
            case 3:
                imagepng($source_handle,  $target);
                break;

            default:
            	throw new CException( "无法创建加了水印后的图片" );
        }

        imagedestroy($source_handle);
		
        //确认文件是否生成,返回图片路径,或返回覆盖生成的图片路径
		if (!file_exists($target_file))
			throw new CException( "生成缩略图失败" );
		return true;
      	
    }

    /**
     *  检查水印图片是否合法
     *
     * @access  public
     * @param   string      $_pathThumbImg       图片路径
     *
     * @return boolen
     */
    public function validateWaterImage( $_pathWaterImg )
    {
        if (empty($_pathWaterImg))
       		throw new CException( "水印图片路径不能为空" );

        /* 文件是否存在 */
        if (!file_exists($_pathWaterImg))
       		 throw new CException( "水印图片不存在" );

        // 获得文件以及源文件的信息
        $aryWaterImgInfo = @getimagesize($_pathWaterImg);
        if ( empty( $aryWaterImgInfo ) )
        	throw new CException( "错误的水印图片类型" );

        /* 检查处理函数是否存在 */
        if (!$this->checkImgFunction($aryWaterImgInfo[2]))
        	throw new CException( "系统没有不能支持图片类型：{$this->_aryTypeMaping[$aryWaterImgInfo[2]]}" );

        return true;
    }

    
    /**
     * 检查图片类型
     * @param   string  $_pathSourceImg_type   图片类型
     * @return  bool
     */
    function checkImgType($_pathSourceImg_type)
    {
        return $_pathSourceImg_type == 'image/pjpeg' ||
               $_pathSourceImg_type == 'image/x-png' ||
               $_pathSourceImg_type == 'image/png'   ||
               $_pathSourceImg_type == 'image/gif'   ||
               $_pathSourceImg_type == 'image/jpeg';
    }

    /**
     * 检查图片处理能力
     *
     * @access  public
     * @param   string  $_pathSourceImg_type   图片类型
     * @return  void
     */
    public function checkImgFunction($_pathSourceImg_type)
    {
        switch ($_pathSourceImg_type)
        {
            case 'image/gif':
            case 1:

                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromgif');
                }
                else
                {
                    return (imagetypes() & IMG_GIF) > 0;
                }
            break;

            case 'image/pjpeg':
            case 'image/jpeg':
            case 2:
                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromjpeg');
                }
                else
                {
                    return (imagetypes() & IMG_JPG) > 0;
                }
            break;

            case 'image/x-png':
            case 'image/png':
            case 3:
                if (PHP_VERSION >= '4.3')
                {
                     return function_exists('imagecreatefrompng');
                }
                else
                {
                    return (imagetypes() & IMG_PNG) > 0;
                }
            break;

            default:
                return false;
        }
    }

    /**
     * 根据来源文件的文件类型创建一个图像操作的标识符
     *
     * @access  public
     * @param   string      $_pathSourceImg_file   图片文件的路径
     * @param   string      $_mixMineType  图片文件的文件类型
     * @return  resource    如果成功则返回图像操作标志符，反之则返回错误代码
     */
    public function createImageSource( $_pathSourceImg, $_mixMineType)
    {
        switch ($_mixMineType)
        {
            case 1:
            case 'image/gif':
                $res = imagecreatefromgif($_pathSourceImg);
                break;

            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
                $res = imagecreatefromjpeg($_pathSourceImg);
                break;

            case 3:
            case 'image/x-png':
            case 'image/png':
                $res = imagecreatefrompng($_pathSourceImg);
                break;

            default:
                return false;
        }

        return $res;
    }

    /**
     * 获得服务器上的 GD 版本
     *
     * @access      public
     * @return      int         可能的值为0，1，2
     */
    public function getGdVersion()
    {
        static $version = -1;

        if ($version >= 0)
        {
            return $version;
        }

        if (!extension_loaded('gd'))
        {
            $version = 0;
        }
        else
        {
            // 尝试使用gd_info函数
            if (PHP_VERSION >= '4.3')
            {
                if (function_exists('gd_info'))
                {
                    $ver_info = gd_info();
                    preg_match('/\d/', $ver_info['GD Version'], $match);
                    $version = $match[0];
                }
                else
                {
                    if (function_exists('imagecreatetruecolor'))
                    {
                        $version = 2;
                    }
                    elseif (function_exists('imagecreate'))
                    {
                        $version = 1;
                    }
                }
            }
            else
            {
                if (preg_match('/phpinfo/', ini_get('disable_functions')))
                {
                    /* 如果phpinfo被禁用，无法确定gd版本 */
                    $version = 1;
                }
                else
                {
                  // 使用phpinfo函数
                   ob_start();
                   phpinfo(8);
                   $info = ob_get_contents();
                   ob_end_clean();
                   $info = stristr($info, 'gd version');
                   preg_match('/\d/', $info, $match);
                   $version = $match[0];
                }
             }
        }
        return $version;
     }

//end class   
}
