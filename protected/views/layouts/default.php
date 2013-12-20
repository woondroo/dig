<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="Keywords" content="<?php echo $this->getSeoKeyword();?>" />
<meta name="Description" content="<?php echo $this->getSeoDesc();?>" />
<title><?php echo $this->getSeoTitle();?></title>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl;?>/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl;?>/css/bootstrap-theme.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl;?>/css/index.css"/>
<script language="javascript" type="text/javascript" src="<?php echo $this->baseUrl;?>/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->baseUrl;?>/js/bootstrap.min.js"></script>
<?php if( NBT_DEBUG ):?>
<script language="javascript" type="text/javascript" src="<?php echo $this->baseUrl;?>/js/dump.js"></script>
<?php endif;?>
<script language="javascript" type="text/javascript">
	var NBT_DEBUG = <?php echo NBT_DEBUG ? 1 : 0;?>;
	var WEB_PATH = "<?php echo $this->baseUrl;?>";
</script>
</head>

<body>
<?php include NBT_VIEW_PATH.'/layouts/_header.php';?>
<?php $this->widget('EWidgetSessionTipMsg'); ?>
<?php echo $content;?>	    	
<?php include NBT_VIEW_PATH.'/layouts/_footer.php';?>
<?php include NBT_VIEW_PATH.'/systems/debug.php';?>
</body>
</html>
