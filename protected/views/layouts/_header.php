<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $this->createUrl( 'index/index' ); ?>">BTC&amp;LTC设置</a>
    </div>
    
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?php echo $this->createUrl( 'index/index' ); ?>">设置中心</a></li>
        <!--<li><a href="#">历史记录</a></li>-->
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="<?php echo $this->createUrl( 'action/start' ); ?>">运行</a></li>
	<li><a href="<?php echo $this->createUrl( 'action/super' ); ?>">超频</a></li>
        <li><a href="<?php echo $this->createUrl( 'action/stop' ); ?>">停止</a></li>
      </ul>
    </div>
  </div>
</div>
