<ul>
	<li><a class="<?php if($this->actionId=='profile'):?>currentSubMenu<?php endif;?>" href="<?php echo $this->createUrl('index/profile');?>">我的资料</a></li>
	<li><a class="<?php if($this->actionId=='pwd'):?>currentSubMenu<?php endif;?>" href="<?php echo $this->createUrl('index/pwd');?>">修改密码</a></li>
</ul>