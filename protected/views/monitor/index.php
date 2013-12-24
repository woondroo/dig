<div class="container">
  <div class="page-header">
	<h1>新设备检测</h1>
  </div>
  <div id="new-machine-container" class="row"></div>
  <div class="page-header">
	<h1>BTC 运行状态</h1>
  </div>
  <div id="btc-machine-container" class="row">

    <div class="col-sm-4">
	  
	  <div class="panel panel-default">
		<div class="panel-heading">
		  <h3 class="panel-title">设备: /dev/ttyUSB0</h3>
		</div>
		<div class="panel-body">
		  正在运行: BTC [正常]<br><br>
          <button type="button" class="btn btn-sm btn-danger">重启</button>
          <button type="button" class="btn btn-sm btn-default">运行LTC</button>
		</div>
	  </div>

	</div>

  </div>

  <div class="page-header">
	<h1>LTC 运行状态</h1>
  </div>
  <div id="btc-machine-container" class="row"></div>
<script type="text/javascript">
function refreshState()
{
	if ( actions.setting.runstate === false ) actions.usbstate();
	//actions.check();
	setTimeout(function(){
		refreshState();
	},5000);
}
$(document).ready(function(){
	refreshState();
});
</script>
