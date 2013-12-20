<?php
$aryStatus = array( 'success'=>'alert-success' , 'warning'=>'alert-warning' , 'error'=>'alert-danger' );
?>
<div class="container">
  <div class="page-header">
    <h1>Setting!</h1>
  </div>
  <div class="alert alert-warning">
    <strong>注意!</strong> 请仔细核对您的矿池地址和矿工号，否则无法正常运行！
  </div>
  <div class="jumbotron">
    <form class="form-signin" role="form" method="POST" action="<?php echo $this->createUrl( 'index/index' ); ?>">
      <?php if ( !empty( $tip['status'] ) ) : ?>
      <div id="action-tip" class="alert <?php echo $aryStatus[$tip['status']]; ?> important-tip"><?php echo $tip['text']; ?></div>
      <script type="text/javascript">
      	setTimeout(function(){
		$('#action-tip').hide();
	}, 5000);
      </script>
      <?php endif; ?>
      <div class="input-area">
	<div>BTC设置 (不填写则不启动)</div>
        <input class="form-control" placeholder="BTC矿池地址" name="address_btc" value="<?php echo $btc['ad']; ?>" type="text" <?php echo empty($btc['ad']) ? 'autofocus' : ''; ?>/>
        <input class="form-control" placeholder="BTC矿工号" name="account_btc" value="<?php echo $btc['ac']; ?>" type="text" />
        <input class="form-control" placeholder="BTC矿工密码" name="password_btc" value="<?php echo $btc['pw']; ?>" type="text" />

	<div>LTC设置 (不填写则不启动)</div>
        <input class="form-control" placeholder="LTC矿池地址" name="address_ltc" value="<?php echo $ltc['ad']; ?>" type="text" />
        <input class="form-control" placeholder="LTC矿工号" name="account_ltc" value="<?php echo $ltc['ac']; ?>" type="text" />
        <input class="form-control" placeholder="LTC矿工密码" name="password_ltc" value="<?php echo $ltc['pw']; ?>" type="text" />
      </div>
      <p>
        <button class="btn btn-lg btn-primary btn-block" type="submit">保存设置</button>
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>
	<div id="action-restart-tip" class="alert alert-info important-tip"><strong>重要操作!</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保存后请重启程序!</div>
	<button class="btn btn-lg btn-danger btn-block" onclick="actions.restart_home()" type="button" >重启程序</button>
      </p>
    </form>
  </div>

