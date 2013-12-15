<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php echo implode( ' - ' , $this->getBreadCrumbs() );?> － 课程管理中心 － 勤学网</title>
<script src="js/iepng.js" type="text/javascript"></script>
<script type="text/javascript">
EvPNG.fix('div,ul,img,li,input,a,img,p,span');
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl;?>/css/index.css"/>
<script language="javascript" type="text/javascript" src="<?php echo $this->baseUrl;?>/js/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->baseUrl;?>/js/common.js"></script>
<?php if( NBT_DEBUG ):?>
<script language="javascript" type="text/javascript" src="<?php echo $this->baseUrl;?>/js/dump.js"></script>
<?php endif;?>
<script language="javascript" type="text/javascript">
	var NBT_DEBUG = <?php echo NBT_DEBUG ? 1 : 0;?>;
	var WEB_PATH = "<?php echo $this->baseUrl;?>";
</script>
</head>

<body>

	<div class="top">
	  <div class="logo"><div class="logo_user">您好，<span><?php echo Nbt::app()->user->getState('te_truename');?></span> 老师! </div></div>
	  <div class="logo_right"><a href="#">勤学网首页</a> | <a href="javascript:if(confirm('退出系统吗?')){ window.location.href='<?php echo $this->createUrl('login/logout');?>'; }">退出系统</a>&nbsp;&nbsp;&nbsp;</div>
	</div>
	<div class="mune">
		<ul>
			<li><a href="<?php echo $this->createUrl('index/profile');?>" class="<?php if( $this->id == 'index' ):?>currentMenu<?php endif;?>">我的资料</a></li>
			<li><a href="<?php echo $this->createUrl('tutorial/index');?>" class="<?php if( $this->id == 'tutorial' ):?>currentMenu<?php endif;?>">我的教程</a></li>
		</ul>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="202" valign="top" class="index_left">
			<?php @include( NBT_VIEW_PATH."/layouts/_menu_".strtolower($this->id).".php" ) ?>
	    </td>
	    <td valign="top" style="padding:0px 10px;">
	    	<div class="Location">当前位置：<?php echo implode( '&nbsp;&gt;&nbsp;' , $this->getBreadCrumbs() );?></div>
		<?php $this->widget('EWidgetSessionTipMsg'); ?>
	    	<?php echo $content;?>	    	
	    </td>
	  </tr>
	</table>
	<div><?php include NBT_VIEW_PATH.'/systems/debug.php';?></div>
</body>
</html>
